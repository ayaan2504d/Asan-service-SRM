<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Http\Requests\StoreLeadRequest;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lead_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact', 'LIKE', "%{$search}%");
            });
        }

        if ($platform = $request->get('platform')) {
            $query->where('platform', $platform);
        }

        if ($response = $request->get('response')) {
            $query->where('response', $response);
        }

        if ($from = $request->get('from')) {
            $query->whereDate('date', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->whereDate('date', '<=', $to);
        }

        $totalRevenue = (clone $query)->sum('amount_received');
        $leads = $query->latest()->paginate(15)->withQueryString();

        return view('leads.index', compact('leads', 'totalRevenue'));
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
        Lead::create($data);

        return redirect()->route('leads.index')
            ->with('success', 'Lead added successfully.');
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
        $lead->update($request->validated());
        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
