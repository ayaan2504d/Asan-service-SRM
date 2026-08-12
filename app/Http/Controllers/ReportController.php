<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportData = $this->buildReportData($request);

        return view('reports.index', $reportData);
    }

    public function exportPdf(Request $request)
    {
        $reportData = $this->buildReportData($request);
        $reportData['companyName'] = 'Asan Home Service';
        $reportData['generatedAt']  = now()->format('Y-m-d H:i:s');

        $pdf = Pdf::loadView('reports.pdf', $reportData);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'marketing_leads_report_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    private function buildReportData(Request $request): array
    {
        $fromDate    = $request->get('from_date', Carbon::today()->startOfMonth()->toDateString());
        $toDate      = $request->get('to_date', Carbon::today()->endOfMonth()->toDateString());
        $serviceType = $request->get('service_type');
        $status      = $request->get('status');
        $campaign    = $request->get('campaign_name');

        $query = Lead::query();

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if (!empty($serviceType)) {
            $query->where(function ($q) use ($serviceType) {
                $q->where('service_type', $serviceType)
                  ->orWhere('type_of_service', $serviceType);
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($campaign)) {
            $query->where('campaign_name', $campaign);
        }

        $filteredLeads = $query->get();

        $metrics = [
            'total'     => $filteredLeads->count(),
            'pending'   => $filteredLeads->where('status', 'Pending')->count(),
            'follow_up' => $filteredLeads->where('status', 'Follow Up')->count(),
            'done'      => $filteredLeads->where('status', 'Done')->count(),
            'cancel'    => $filteredLeads->where('status', 'Cancel')->count(),
        ];

        // Service Breakdown
        $serviceTypes = ['Solar Cleaning', 'Sofa Cleaning', 'Deep Cleaning'];
        $serviceBreakdown = [];
        foreach ($serviceTypes as $st) {
            $stLeads = $filteredLeads->filter(fn($l) => $l->effective_service_type === $st);
            $serviceBreakdown[$st] = [
                'total'     => $stLeads->count(),
                'pending'   => $stLeads->where('status', 'Pending')->count(),
                'follow_up' => $stLeads->where('status', 'Follow Up')->count(),
                'done'      => $stLeads->where('status', 'Done')->count(),
                'cancel'    => $stLeads->where('status', 'Cancel')->count(),
            ];
        }

        // Campaign Breakdown
        $campaigns = $filteredLeads->pluck('campaign_name')->filter()->unique()->values();
        $campaignBreakdown = [];
        foreach ($campaigns as $camp) {
            $cLeads = $filteredLeads->where('campaign_name', $camp);
            $campaignBreakdown[$camp] = [
                'total'     => $cLeads->count(),
                'pending'   => $cLeads->where('status', 'Pending')->count(),
                'follow_up' => $cLeads->where('status', 'Follow Up')->count(),
                'done'      => $cLeads->where('status', 'Done')->count(),
                'cancel'    => $cLeads->where('status', 'Cancel')->count(),
            ];
        }

        // Unique lists for filter dropdowns
        $allCampaigns = Lead::whereNotNull('campaign_name')->where('campaign_name', '!=', '')->distinct()->pluck('campaign_name');

        return compact(
            'fromDate',
            'toDate',
            'serviceType',
            'status',
            'campaign',
            'metrics',
            'serviceBreakdown',
            'campaignBreakdown',
            'allCampaigns'
        );
    }
}
