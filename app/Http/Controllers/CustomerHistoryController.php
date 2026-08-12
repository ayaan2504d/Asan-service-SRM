<?php

namespace App\Http\Controllers;

use App\Models\SolarClient;
use App\Models\Lead;
use App\Models\SofaCleaning;
use App\Models\DeepCleaning;
use Illuminate\Http\Request;

class CustomerHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->get('q', ''));
        $customerInfo = null;
        $summary = [
            'total_services'     => 0,
            'completed_services' => 0,
            'pending_services'   => 0,
            'cancelled_services' => 0,
        ];
        $timeline = collect();

        if (!empty($query)) {
            $solarClients = SolarClient::where('contact', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$query}%")
                ->orWhere('client_id', 'LIKE', "%{$query}%")
                ->get();

            $leads = Lead::where('contact', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$query}%")
                ->orWhere('lead_id', 'LIKE', "%{$query}%")
                ->get();

            $sofaCleanings = SofaCleaning::where('contact', 'LIKE', "%{$query}%")
                ->orWhere('customer_name', 'LIKE', "%{$query}%")
                ->orWhere('record_id', 'LIKE', "%{$query}%")
                ->get();

            $deepCleanings = DeepCleaning::where('contact', 'LIKE', "%{$query}%")
                ->orWhere('customer_name', 'LIKE', "%{$query}%")
                ->orWhere('record_id', 'LIKE', "%{$query}%")
                ->get();

            // Pick representative customer info
            $firstRecord = $solarClients->first() ?? $leads->first() ?? $sofaCleanings->first() ?? $deepCleanings->first();
            if ($firstRecord) {
                $customerInfo = [
                    'name'    => $firstRecord->name ?? $firstRecord->customer_name,
                    'phone'   => $firstRecord->contact,
                    'address' => $firstRecord->address ?? ($firstRecord->location ?? 'N/A'),
                ];
            }

            // Build Timeline items
            foreach ($solarClients as $sc) {
                $status = 'Completed';
                $summary['total_services']++;
                $summary['completed_services']++;

                $timeline->push([
                    'module'      => 'Solar Cleaning',
                    'module_badge'=> 'bg-warning text-dark',
                    'record_id'   => $sc->client_id,
                    'title'       => "Solar Client Registration ({$sc->num_plates} Plates)",
                    'date'        => $sc->created_at,
                    'status'      => $status,
                    'status_badge'=> 'bg-success',
                    'details'     => "Package: " . ($sc->package ?: 'Standard') . " | Amount: $" . number_format($sc->total_amount, 2),
                    'notes'       => $sc->notes,
                    'url'         => route('solar-clients.show', $sc->id),
                ]);
            }

            foreach ($sofaCleanings as $s) {
                $statusStr = strtolower($s->status ?: 'pending');
                $summary['total_services']++;
                if (in_array($statusStr, ['completed', 'done'])) {
                    $summary['completed_services']++;
                    $statusBadge = 'bg-success';
                } elseif (in_array($statusStr, ['cancelled', 'cancel'])) {
                    $summary['cancelled_services']++;
                    $statusBadge = 'bg-danger';
                } else {
                    $summary['pending_services']++;
                    $statusBadge = 'bg-info text-dark';
                }

                $timeline->push([
                    'module'      => 'Sofa Cleaning',
                    'module_badge'=> 'bg-info text-dark',
                    'record_id'   => $s->record_id,
                    'title'       => "Sofa Cleaning ({$s->sofa_type}, {$s->num_seats} Seats)",
                    'date'        => $s->created_at,
                    'status'      => ucfirst($s->status ?: 'Pending'),
                    'status_badge'=> $statusBadge,
                    'details'     => "Service Charges: $" . number_format($s->service_charges, 2) . " | Date: " . ($s->service_date ? $s->service_date->format('M d, Y') : 'N/A'),
                    'notes'       => $s->notes,
                    'url'         => route('sofa-cleaning.show', $s->id),
                ]);
            }

            foreach ($deepCleanings as $d) {
                $statusStr = strtolower($d->status ?: 'pending');
                $summary['total_services']++;
                if (in_array($statusStr, ['completed', 'done'])) {
                    $summary['completed_services']++;
                    $statusBadge = 'bg-success';
                } elseif (in_array($statusStr, ['cancelled', 'cancel'])) {
                    $summary['cancelled_services']++;
                    $statusBadge = 'bg-danger';
                } else {
                    $summary['pending_services']++;
                    $statusBadge = 'bg-info text-dark';
                }

                $timeline->push([
                    'module'      => 'Deep Cleaning',
                    'module_badge'=> 'bg-primary',
                    'record_id'   => $d->record_id,
                    'title'       => "Deep Cleaning ({$d->area_size}, {$d->property_type})",
                    'date'        => $d->created_at,
                    'status'      => ucfirst($d->status ?: 'Pending'),
                    'status_badge'=> $statusBadge,
                    'details'     => "Service Charges: $" . number_format($d->service_charges, 2) . " | Date: " . ($d->service_date ? $d->service_date->format('M d, Y') : 'N/A'),
                    'notes'       => $d->notes,
                    'url'         => route('deep-cleaning.show', $d->id),
                ]);
            }

            foreach ($leads as $l) {
                $statusStr = strtolower($l->status ?: 'pending');
                $summary['total_services']++;
                if (in_array($statusStr, ['done', 'completed'])) {
                    $summary['completed_services']++;
                    $statusBadge = 'bg-success';
                } elseif (in_array($statusStr, ['cancel', 'cancelled'])) {
                    $summary['cancelled_services']++;
                    $statusBadge = 'bg-danger';
                } elseif ($statusStr === 'follow up') {
                    $summary['pending_services']++;
                    $statusBadge = 'bg-warning text-dark';
                } else {
                    $summary['pending_services']++;
                    $statusBadge = 'bg-secondary';
                }

                $timeline->push([
                    'module'      => 'Marketing Lead',
                    'module_badge'=> 'bg-purple text-white',
                    'record_id'   => $l->lead_id,
                    'title'       => "Lead Source: {$l->effective_lead_source} ({$l->effective_service_type})",
                    'date'        => $l->created_at,
                    'status'      => $l->effective_status,
                    'status_badge'=> $statusBadge,
                    'details'     => "Campaign: " . ($l->campaign_name ?: 'N/A') . " | Quoted: $" . number_format($l->quoted_amount ?: $l->estimated_amount, 2),
                    'notes'       => $l->notes,
                    'url'         => route('leads.show', $l->id),
                ]);
            }

            // Sort timeline newest first
            $timeline = $timeline->sortByDesc('date')->values();
        }

        return view('customer-history.index', compact('query', 'customerInfo', 'summary', 'timeline'));
    }
}
