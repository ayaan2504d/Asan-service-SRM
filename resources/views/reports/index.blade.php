@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')

<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="page-header-left">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Marketing Leads Reports</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div>
    <div class="mt-2 mt-sm-0">
        <a href="{{ route('reports.export-pdf', request()->query()) }}" target="_blank" class="btn btn-danger shadow-sm" id="btn-export-pdf">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF Report
        </a>
    </div>
</div>

{{-- Filters Form --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold">Service Type</label>
                <select name="service_type" class="form-select">
                    <option value="">All Services</option>
                    <option value="Solar Cleaning" {{ $serviceType == 'Solar Cleaning' ? 'selected' : '' }}>Solar Cleaning</option>
                    <option value="Sofa Cleaning" {{ $serviceType == 'Sofa Cleaning' ? 'selected' : '' }}>Sofa Cleaning</option>
                    <option value="Deep Cleaning" {{ $serviceType == 'Deep Cleaning' ? 'selected' : '' }}>Deep Cleaning</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Follow Up" {{ $status == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
                    <option value="Done" {{ $status == 'Done' ? 'selected' : '' }}>Done</option>
                    <option value="Cancel" {{ $status == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-semibold">Campaign Name</label>
                <select name="campaign_name" class="form-select">
                    <option value="">All Campaigns</option>
                    @foreach($allCampaigns as $c)
                        <option value="{{ $c }}" {{ $campaign == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-filter me-1"></i> Generate Report</button>
                @if(request()->anyFilled(['service_type', 'status', 'campaign_name', 'from_date', 'to_date']))
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-x-lg me-1"></i> Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Overall Lead Status Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3" style="background:linear-gradient(135deg,#4e73df,#224abe);">
            <div class="small text-white-50 text-uppercase fw-semibold">Total Leads</div>
            <div class="fs-3 fw-bold text-white mt-1">{{ number_format($metrics['total']) }}</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3" style="background:linear-gradient(135deg,#f6c23e,#dda20a);">
            <div class="small fw-semibold text-uppercase" style="opacity:.7">Pending</div>
            <div class="fs-3 fw-bold mt-1" style="color:#5a3e00">{{ number_format($metrics['pending']) }}</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3" style="background:linear-gradient(135deg,#36b9cc,#1a8a9c);">
            <div class="small text-white-50 fw-semibold text-uppercase">Follow Up</div>
            <div class="fs-3 fw-bold text-white mt-1">{{ number_format($metrics['follow_up']) }}</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3" style="background:linear-gradient(135deg,#1cc88a,#13855c);">
            <div class="small text-white-50 fw-semibold text-uppercase">Done</div>
            <div class="fs-3 fw-bold text-white mt-1">{{ number_format($metrics['done']) }}</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3" style="background:linear-gradient(135deg,#e74a3b,#be2617);">
            <div class="small text-white-50 fw-semibold text-uppercase">Cancel</div>
            <div class="fs-3 fw-bold text-white mt-1">{{ number_format($metrics['cancel']) }}</div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
@if($metrics['total'] > 0)
<div class="row g-4 mb-4">
    {{-- Service Breakdown Doughnut --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Service Breakdown (Chart)</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:260px;">
                <canvas id="reportServiceChart" style="max-height:250px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Lead Status Breakdown Bar --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-bar-chart-fill text-success me-2"></i>Status Distribution (Chart)</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:260px;">
                <canvas id="reportStatusChart" style="max-height:250px;width:100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Service Breakdown & Campaign Performance --}}
<div class="row g-4 mb-4">
    {{-- Service Breakdown --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold m-0 text-primary"><i class="bi bi-pie-chart me-2"></i>Service Breakdown</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Service Type</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Follow Up</th>
                            <th class="text-center">Done</th>
                            <th class="text-center">Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serviceBreakdown as $stName => $stStats)
                        <tr>
                            <td class="fw-semibold">{{ $stName }}</td>
                            <td class="text-center fw-bold">{{ $stStats['total'] }}</td>
                            <td class="text-center text-warning fw-bold">{{ $stStats['pending'] }}</td>
                            <td class="text-center text-info fw-bold">{{ $stStats['follow_up'] }}</td>
                            <td class="text-center text-success fw-bold">{{ $stStats['done'] }}</td>
                            <td class="text-center text-danger fw-bold">{{ $stStats['cancel'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Campaign Breakdown --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold m-0 text-primary"><i class="bi bi-graph-up me-2"></i>Campaign Performance</h5>
            </div>
            <div class="table-responsive">
                @if(empty($campaignBreakdown))
                    <div class="text-center py-4 text-muted">No campaign data for selected filters.</div>
                @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Campaign Name</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Done</th>
                            <th class="text-center">Cancel</th>
                            <th class="text-center">Conversion %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaignBreakdown as $cName => $cStats)
                        @php
                            $rate = $cStats['total'] > 0 ? round(($cStats['done'] / $cStats['total']) * 100, 1) : 0;
                            $rateBadge = $rate >= 50 ? 'bg-success' : ($rate >= 25 ? 'bg-warning text-dark' : 'bg-danger');
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $cName }}</td>
                            <td class="text-center fw-bold">{{ $cStats['total'] }}</td>
                            <td class="text-center text-success fw-bold">{{ $cStats['done'] }}</td>
                            <td class="text-center text-danger fw-bold">{{ $cStats['cancel'] }}</td>
                            <td class="text-center"><span class="badge {{ $rateBadge }}">{{ $rate }}%</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Service Breakdown Doughnut
    const svcEl = document.getElementById('reportServiceChart');
    if (svcEl) {
        const svcData = @json($serviceBreakdown);
        const svcLabels = Object.keys(svcData);
        const svcTotals = svcLabels.map(k => svcData[k].total);
        new Chart(svcEl, {
            type: 'doughnut',
            data: {
                labels: svcLabels,
                datasets: [{
                    data: svcTotals,
                    backgroundColor: ['#f6c23e', '#36b9cc', '#4e73df'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 14, font: { size: 12 } } }
                }
            }
        });
    }

    // Status Breakdown Bar
    const stEl = document.getElementById('reportStatusChart');
    if (stEl) {
        new Chart(stEl, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Follow Up', 'Done', 'Cancel'],
                datasets: [{
                    label: 'Leads',
                    data: [{{ $metrics['pending'] }}, {{ $metrics['follow_up'] }}, {{ $metrics['done'] }}, {{ $metrics['cancel'] }}],
                    backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b'],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f5f5f5' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush

