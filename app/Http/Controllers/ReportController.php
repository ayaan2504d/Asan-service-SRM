<?php

namespace App\Http\Controllers;

use App\Models\SolarClient;
use App\Models\Lead;
use App\Models\SofaCleaning;
use App\Models\DeepCleaning;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->get('from_date', Carbon::today()->startOfMonth()->toDateString());
        $toDate   = $request->get('to_date', Carbon::today()->endOfMonth()->toDateString());

        $solarClientsRev = SolarClient::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')
            ->value('total') ?? 0;

        $leadsRev      = Lead::whereBetween('date', [$fromDate, $toDate])->sum('amount_received') ?? 0;
        $sofaRev       = SofaCleaning::whereBetween('service_date', [$fromDate, $toDate])->sum('amount_received') ?? 0;
        $deepRev       = DeepCleaning::whereBetween('service_date', [$fromDate, $toDate])->sum('amount_received') ?? 0;

        $revenueBreakdown = [
            'solar_clients' => $solarClientsRev,
            'leads'         => $leadsRev,
            'sofa_cleaning' => $sofaRev,
            'deep_cleaning' => $deepRev,
        ];

        $totalRevenue = array_sum($revenueBreakdown);
        $totalExpenses = Expense::whereBetween('date', [$fromDate, $toDate])->sum('amount') ?? 0;
        $netProfit = $totalRevenue - $totalExpenses;

        return view('reports.index', compact(
            'fromDate',
            'toDate',
            'revenueBreakdown',
            'totalRevenue',
            'totalExpenses',
            'netProfit'
        ));
    }
}
