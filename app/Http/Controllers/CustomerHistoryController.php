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
        $query  = trim($request->get('q', ''));
        $results = null;

        if (!empty($query)) {
            $solarClients = SolarClient::where('client_id', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$query}%")
                ->orWhere('contact', 'LIKE', "%{$query}%")
                ->get();

            $leads = Lead::where('name', 'LIKE', "%{$query}%")
                ->orWhere('contact', 'LIKE', "%{$query}%")
                ->orWhere('lead_id', 'LIKE', "%{$query}%")
                ->get();

            $sofaCleanings = SofaCleaning::where('customer_name', 'LIKE', "%{$query}%")
                ->orWhere('contact', 'LIKE', "%{$query}%")
                ->orWhere('record_id', 'LIKE', "%{$query}%")
                ->get();

            $deepCleanings = DeepCleaning::where('customer_name', 'LIKE', "%{$query}%")
                ->orWhere('contact', 'LIKE', "%{$query}%")
                ->orWhere('record_id', 'LIKE', "%{$query}%")
                ->get();

            // Revenue totals for this customer
            $totalRevenue = 0;
            foreach ($solarClients as $c) { $totalRevenue += $c->total_amount; }
            foreach ($leads as $l) { $totalRevenue += (float)$l->amount_received; }
            foreach ($sofaCleanings as $s) { $totalRevenue += (float)$s->amount_received; }
            foreach ($deepCleanings as $d) { $totalRevenue += (float)$d->amount_received; }

            $results = compact(
                'solarClients', 'leads',
                'sofaCleanings', 'deepCleanings', 'totalRevenue'
            );
        }

        return view('customer-history.index', compact('query', 'results'));
    }
}
