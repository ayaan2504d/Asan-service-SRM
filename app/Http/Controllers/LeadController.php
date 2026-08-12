<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SolarClient;
use App\Models\SofaCleaning;
use App\Models\DeepCleaning;
use App\Http\Requests\StoreLeadRequest;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($search = trim($request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('lead_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact', 'LIKE', "%{$search}%")
                  ->orWhere('campaign_name', 'LIKE', "%{$search}%");
            });
        }

        if ($serviceType = $request->get('service_type')) {
            $query->where(function($q) use ($serviceType) {
                $q->where('service_type', $serviceType)
                  ->orWhere('type_of_service', $serviceType);
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($leadSource = $request->get('lead_source')) {
            $query->where(function($q) use ($leadSource) {
                $q->where('lead_source', $leadSource)
                  ->orWhere('platform', $leadSource);
            });
        }

        if ($campaign = $request->get('campaign_name')) {
            $query->where('campaign_name', $campaign);
        }

        if ($from = $request->get('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        // Summary counts for filter bar
        $counts = [
            'total'     => Lead::count(),
            'pending'   => Lead::where('status', 'Pending')->count(),
            'follow_up' => Lead::where('status', 'Follow Up')->count(),
            'done'      => Lead::where('status', 'Done')->count(),
            'cancel'    => Lead::where('status', 'Cancel')->count(),
        ];

        return view('leads.index', compact('leads', 'counts'));
    }

    public function create()
    {
        $nextId = Lead::generateLeadId();
        return view('leads.create', compact('nextId'));
    }

    public function store(StoreLeadRequest $request)
    {
        $data = $request->validated();
        $data['lead_id'] = Lead::generateLeadId();
        
        // Sync redundant fields for backwards compatibility
        if (isset($data['name'])) {
            $data['customer_name'] = $data['name'];
        }
        if (isset($data['contact'])) {
            $data['phone_number'] = $data['contact'];
        }
        if (isset($data['service_type'])) {
            $data['type_of_service'] = $data['service_type'];
        }
        if (isset($data['lead_source'])) {
            $data['platform'] = $data['lead_source'];
        }
        if (isset($data['address'])) {
            $data['location'] = $data['address'];
        }
        if (!isset($data['date'])) {
            $data['date'] = now()->toDateString();
        }

        Lead::create($data);

        return redirect()->route('leads.index')
            ->with('success', 'Marketing lead created successfully.');
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(StoreLeadRequest $request, Lead $lead)
    {
        $data = $request->validated();

        if (isset($data['name'])) {
            $data['customer_name'] = $data['name'];
        }
        if (isset($data['contact'])) {
            $data['phone_number'] = $data['contact'];
        }
        if (isset($data['service_type'])) {
            $data['type_of_service'] = $data['service_type'];
        }
        if (isset($data['lead_source'])) {
            $data['platform'] = $data['lead_source'];
        }
        if (isset($data['address'])) {
            $data['location'] = $data['address'];
        }

        $lead->update($data);

        return redirect()->route('leads.index')
            ->with('success', 'Marketing lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')
            ->with('success', 'Marketing lead deleted successfully.');
    }

    public function checkPhone(Request $request)
    {
        $phone = trim($request->get('contact', ''));
        $ignoreId = $request->get('ignore_id');

        if (empty($phone)) {
            return response()->json(['exists' => false]);
        }

        $queryLead = Lead::where('contact', $phone);
        if ($ignoreId) {
            $queryLead->where('id', '!=', $ignoreId);
        }
        $existingLead = $queryLead->first();

        $existingSolar = SolarClient::where('contact', $phone)->first();
        $existingSofa  = SofaCleaning::where('contact', $phone)->first();
        $existingDeep  = DeepCleaning::where('contact', $phone)->first();

        if ($existingLead || $existingSolar || $existingSofa || $existingDeep) {
            $name = $existingLead->name 
                ?? ($existingSolar->name 
                ?? ($existingSofa->customer_name 
                ?? $existingDeep->customer_name));

            return response()->json([
                'exists'        => true,
                'customer_name' => $name,
                'customer_url'  => route('customer-history.index', ['q' => $phone]),
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function convertToSolar(Lead $lead)
    {
        $solarClient = SolarClient::create([
            'client_id'       => SolarClient::generateClientId(),
            'name'            => $lead->name,
            'contact'         => $lead->contact,
            'whatsapp_number' => $lead->whatsapp_number,
            'address'         => $lead->effective_address,
            'num_plates'      => $lead->num_plates,
            'amount'          => $lead->quoted_amount ?: $lead->estimated_amount,
            'notes'           => "Converted from Lead {$lead->lead_id}. " . ($lead->notes ?? ''),
            'first_visit_date'=> now()->toDateString(),
        ]);

        $lead->update([
            'status'         => 'Done',
            'converted_type' => 'SolarClient',
            'converted_id'   => $solarClient->id,
        ]);

        return redirect()->route('solar-clients.index')
            ->with('success', "Lead {$lead->lead_id} successfully converted to Solar Client {$solarClient->client_id}!");
    }

    public function convertToSofa(Lead $lead)
    {
        $sofaCleaning = SofaCleaning::create([
            'record_id'      => SofaCleaning::generateRecordId(),
            'customer_name'  => $lead->name,
            'contact'        => $lead->contact,
            'whatsapp_number'=> $lead->whatsapp_number,
            'address'        => $lead->effective_address,
            'sofa_type'      => $lead->sofa_type,
            'num_seats'      => $lead->num_sofas,
            'service_charges'=> $lead->quoted_amount ?: $lead->estimated_amount,
            'booking_date'   => now()->toDateString(),
            'status'         => 'Pending',
            'notes'          => "Converted from Lead {$lead->lead_id}. " . ($lead->notes ?? ''),
        ]);

        $lead->update([
            'status'         => 'Done',
            'converted_type' => 'SofaCleaning',
            'converted_id'   => $sofaCleaning->id,
        ]);

        return redirect()->route('sofa-cleaning.index')
            ->with('success', "Lead {$lead->lead_id} successfully converted to Sofa Cleaning {$sofaCleaning->record_id}!");
    }

    public function convertToDeep(Lead $lead)
    {
        $deepCleaning = DeepCleaning::create([
            'record_id'      => DeepCleaning::generateRecordId(),
            'customer_name'  => $lead->name,
            'contact'        => $lead->contact,
            'whatsapp_number'=> $lead->whatsapp_number,
            'address'        => $lead->effective_address,
            'area_size'      => $lead->house_size,
            'property_type'  => $lead->floors,
            'service_charges'=> $lead->quoted_amount ?: $lead->estimated_amount,
            'status'         => 'Pending',
            'notes'          => "Converted from Lead {$lead->lead_id}. " . ($lead->notes ?? ''),
        ]);

        $lead->update([
            'status'         => 'Done',
            'converted_type' => 'DeepCleaning',
            'converted_id'   => $deepCleaning->id,
        ]);

        return redirect()->route('deep-cleaning.index')
            ->with('success', "Lead {$lead->lead_id} successfully converted to Deep Cleaning {$deepCleaning->record_id}!");
    }
}
