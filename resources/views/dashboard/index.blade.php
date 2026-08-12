@extends('layouts.app')

@section('title', 'Business Analytics Dashboard')

@section('content')

{{-- ════ TOP HEADER & QUICK ACTION BAR ════ --}}
<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="page-header-left">
        <h1 class="h3 fw-bold mb-1 text-dark"><i class="bi bi-speedometer2 me-2 text-primary"></i>Business Analytics Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Live Executive Performance & Operations</li>
            </ol>
        </nav>
    </div>
    <div class="mt-2 mt-sm-0 d-flex flex-wrap gap-2">
        <a href="{{ route('dashboard.export-pdf') }}" target="_blank" class="btn btn-danger shadow-sm" id="btn-export-business-pdf">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF Report
        </a>
    </div>
</div>

{{-- ════ QUICK ACTION BUTTONS BAR ════ --}}
<div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="fw-semibold text-muted small"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> QUICK ACTIONS:</div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('solar-clients.create') }}" class="btn btn-sm btn-warning text-dark font-semibold shadow-sm">
                    <i class="bi bi-sun-fill me-1"></i> Add Solar Client
                </a>
                <a href="{{ route('sofa-cleaning.create') }}" class="btn btn-sm btn-info text-white font-semibold shadow-sm">
                    <i class="bi bi-diamond-fill me-1"></i> Add Sofa Cleaning
                </a>
                <a href="{{ route('deep-cleaning.create') }}" class="btn btn-sm btn-primary font-semibold shadow-sm">
                    <i class="bi bi-brush-fill me-1"></i> Add Deep Cleaning
                </a>
                <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-danger text-white font-semibold shadow-sm">
                    <i class="bi bi-cash-stack me-1"></i> Add Expense
                </a>
                <a href="{{ route('leads.create') }}" class="btn btn-sm btn-dark text-white font-semibold shadow-sm">
                    <i class="bi bi-megaphone-fill me-1"></i> Add Marketing Lead
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Session Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" data-auto-dismiss>
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ════ LEAD KPI CARDS ════ --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-2">
        <a href="{{ route('leads.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background:linear-gradient(135deg,#4e73df,#224abe);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-semibold">Total Leads</div>
                            <div class="fs-3 fw-bold mt-1">{{ number_format($totalLeads) }}</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-megaphone-fill fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="small mt-2 text-white-50"><i class="bi bi-people me-1"></i>All Marketing Leads</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-2">
        <a href="{{ route('leads.index', ['status'=>'Pending']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background:linear-gradient(135deg,#f6c23e,#dda20a);">
                <div class="card-body p-3 text-dark">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold small text-uppercase" style="opacity:.7">Pending</div>
                            <div class="fs-3 fw-bold mt-1">{{ number_format($pendingLeads) }}</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                    <div class="small mt-2" style="opacity:.6"><i class="bi bi-clock me-1"></i>Awaiting Action</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-2">
        <a href="{{ route('leads.index', ['status'=>'Follow Up']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background:linear-gradient(135deg,#36b9cc,#1a8a9c);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-semibold">Follow Up</div>
                            <div class="fs-3 fw-bold mt-1">{{ number_format($followUpLeads) }}</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-telephone-outbound-fill fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="small mt-2 text-white-50"><i class="bi bi-arrow-return-right me-1"></i>In Follow Up</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-2">
        <a href="{{ route('leads.index', ['status'=>'Done']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background:linear-gradient(135deg,#1cc88a,#13855c);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-semibold">Done</div>
                            <div class="fs-3 fw-bold mt-1">{{ number_format($doneLeads) }}</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-check-circle-fill fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="small mt-2 text-white-50"><i class="bi bi-check2-all me-1"></i>Converted / Closed</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-2">
        <a href="{{ route('leads.index', ['status'=>'Cancel']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background:linear-gradient(135deg,#e74a3b,#be2617);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-semibold">Cancelled</div>
                            <div class="fs-3 fw-bold mt-1">{{ number_format($cancelLeads) }}</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-x-circle-fill fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="small mt-2 text-white-50"><i class="bi bi-slash-circle me-1"></i>Not Converted</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-2">
        <a href="{{ route('customer-history.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="background:linear-gradient(135deg,#5a5c69,#373840);">
                <div class="card-body p-3 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-semibold">Customers</div>
                            <div class="fs-3 fw-bold mt-1">{{ number_format($totalCustomers) }}</div>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-people-fill fs-4 text-white"></i>
                        </div>
                    </div>
                    <div class="small mt-2 text-white-50"><i class="bi bi-person-check me-1"></i>Unique Contacts</div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ════ LEAD ANALYTICS CHARTS ════ --}}
<div class="row g-3 mb-4">
    {{-- Chart 1: Leads By Status (Doughnut) --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Leads By Status</h6>
                <a href="{{ route('leads.index') }}" class="small text-primary text-decoration-none">View All →</a>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px;">
                @if($totalLeads > 0)
                    <canvas id="leadsStatusChart" height="200"></canvas>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-pie-chart display-4 d-block mb-2 opacity-25"></i>
                        <span class="small">No lead data yet</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Chart 2: Leads By Service Type (Bar) --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-bar-chart-fill text-warning me-2"></i>Leads By Service</h6>
                <a href="{{ route('leads.index') }}" class="small text-primary text-decoration-none">View All →</a>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px;">
                @if($totalLeads > 0)
                    <canvas id="leadsServiceChart" height="200"></canvas>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bar-chart display-4 d-block mb-2 opacity-25"></i>
                        <span class="small">No lead data yet</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Chart 3: Campaign Performance (Horizontal Bar) --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-graph-up-arrow text-success me-2"></i>Campaign Performance</h6>
                <a href="{{ route('reports.index') }}" class="small text-primary text-decoration-none">Reports →</a>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px;">
                @if(!empty($campaignPerformanceChart['labels']))
                    <canvas id="campaignPerformanceChart" height="200"></canvas>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-graph-up display-4 d-block mb-2 opacity-25"></i>
                        <span class="small">No campaign data yet</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ════ 1. MAIN FINANCIAL SUMMARY CARDS ════ --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100 bg-success text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-white-50 small text-uppercase fw-semibold">Total Earnings</div>
                        <div class="fs-3 fw-bold mt-1">₨ {{ number_format($totalEarnings, 0) }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                        <i class="bi bi-currency-exchange fs-3 text-white"></i>
                    </div>
                </div>
                <div class="small mt-2 text-white-50"><i class="bi bi-check-circle-fill me-1"></i>All modules combined</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100 bg-danger text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-white-50 small text-uppercase fw-semibold">Total Expenses</div>
                        <div class="fs-3 fw-bold mt-1">₨ {{ number_format($totalExpenses, 0) }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                        <i class="bi bi-cash-stack fs-3 text-white"></i>
                    </div>
                </div>
                <div class="small mt-2 text-white-50"><i class="bi bi-graph-down me-1"></i>This Month: ₨ {{ number_format($thisMonthExpense, 0) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100 bg-primary text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-white-50 small text-uppercase fw-semibold">Total Profit</div>
                        <div class="fs-3 fw-bold mt-1">₨ {{ number_format($totalProfit, 0) }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                        <i class="bi bi-trophy-fill fs-3 text-white"></i>
                    </div>
                </div>
                <div class="small mt-2 text-white-50"><i class="bi bi-calculator me-1"></i>Earnings - Expenses</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100 bg-dark text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-white-50 small text-uppercase fw-semibold">Total Customers</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($totalCustomers) }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                        <i class="bi bi-people-fill fs-3 text-white"></i>
                    </div>
                </div>
                <div class="small mt-2 text-white-50"><i class="bi bi-check2-all me-1"></i>{{ $totalCompletedServices }} Jobs Completed</div>
            </div>
        </div>
    </div>
</div>

{{-- ════ 2. DAILY BUSINESS SNAPSHOT (PRO FEATURE) ════ --}}
<div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold m-0 text-dark"><i class="bi bi-sun-fill text-warning me-2"></i>Daily Business Snapshot (Today's Activity)</h6>
        <span class="small text-muted">{{ now()->format('l, d F Y') }}</span>
    </div>
    <div class="card-body pt-0 pb-3">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="small text-muted fw-semibold">Leads & Calls Today</div>
                    <div class="fs-4 fw-bold text-primary mt-1">{{ $dailySnapshot['leads_today'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="small text-muted fw-semibold">New Bookings Today</div>
                    <div class="fs-4 fw-bold text-info mt-1">{{ $dailySnapshot['bookings_today'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="small text-muted fw-semibold">Earnings Today</div>
                    <div class="fs-4 fw-bold text-success mt-1">₨ {{ number_format($dailySnapshot['earnings_today'], 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="small text-muted fw-semibold">Reminders Pending</div>
                    <div class="fs-4 fw-bold text-warning mt-1">{{ $dailySnapshot['reminders_today'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════ 3. SERVICE WISE EARNING ANALYTICS ════ --}}
<div class="row g-3 mb-4">
    {{-- Solar Cleaning Analytics --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-warning border-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold m-0 text-dark"><i class="bi bi-sun-fill text-warning me-2"></i>Solar Cleaning</h6>
                    <span class="badge bg-warning text-dark font-monospace">SOLAR</span>
                </div>
                <div class="fs-4 fw-bold text-success mb-2">₨ {{ number_format($serviceAnalytics['solar']['total_earnings'], 0) }}</div>
                <div class="d-flex justify-content-between small text-muted pt-2 border-top">
                    <span>Total Customers: <strong>{{ number_format($serviceAnalytics['solar']['total_customers']) }}</strong></span>
                    <span>Completed: <strong>{{ number_format($serviceAnalytics['solar']['completed_services']) }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sofa Cleaning Analytics --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-info border-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold m-0 text-dark"><i class="bi bi-diamond-fill text-info me-2"></i>Sofa Cleaning</h6>
                    <span class="badge bg-info text-white font-monospace">SOFA</span>
                </div>
                <div class="fs-4 fw-bold text-success mb-2">₨ {{ number_format($serviceAnalytics['sofa']['total_earnings'], 0) }}</div>
                <div class="d-flex justify-content-between small text-muted pt-2 border-top">
                    <span>Total Customers: <strong>{{ number_format($serviceAnalytics['sofa']['total_customers']) }}</strong></span>
                    <span>Completed: <strong>{{ number_format($serviceAnalytics['sofa']['completed_services']) }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Deep Cleaning Analytics --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-primary border-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold m-0 text-dark"><i class="bi bi-brush-fill text-primary me-2"></i>Deep Cleaning</h6>
                    <span class="badge bg-primary font-monospace">DEEP</span>
                </div>
                <div class="fs-4 fw-bold text-success mb-2">₨ {{ number_format($serviceAnalytics['deep']['total_earnings'], 0) }}</div>
                <div class="d-flex justify-content-between small text-muted pt-2 border-top">
                    <span>Total Customers: <strong>{{ number_format($serviceAnalytics['deep']['total_customers']) }}</strong></span>
                    <span>Completed: <strong>{{ number_format($serviceAnalytics['deep']['completed_services']) }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════ 4. MONTHLY BUSINESS KPI SECTION ════ --}}
<div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold m-0 text-primary"><i class="bi bi-calendar-check-fill me-2"></i>Monthly Business KPIs ({{ now()->format('F Y') }})</h6>
    </div>
    <div class="card-body pt-0 pb-3">
        <div class="row g-3 text-center">
            <div class="col-4 col-md-2">
                <div class="p-2 border rounded">
                    <div class="small text-muted">Total Jobs</div>
                    <div class="fw-bold text-dark fs-5">{{ $monthlyKpis['total_jobs'] }}</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="p-2 border rounded border-success bg-success bg-opacity-10">
                    <div class="small text-success fw-semibold">Completed</div>
                    <div class="fw-bold text-success fs-5">{{ $monthlyKpis['completed_jobs'] }}</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="p-2 border rounded border-danger bg-danger bg-opacity-10">
                    <div class="small text-danger fw-semibold">Cancelled</div>
                    <div class="fw-bold text-danger fs-5">{{ $monthlyKpis['cancelled_jobs'] }}</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="p-2 border rounded">
                    <div class="small text-muted">Total Income</div>
                    <div class="fw-bold text-success fs-5">₨ {{ number_format($monthlyKpis['total_income'], 0) }}</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="p-2 border rounded">
                    <div class="small text-muted">Total Expense</div>
                    <div class="fw-bold text-danger fs-5">₨ {{ number_format($monthlyKpis['total_expense'], 0) }}</div>
                </div>
            </div>
            <div class="col-4 col-md-2">
                <div class="p-2 border rounded bg-primary bg-opacity-10 border-primary">
                    <div class="small text-primary fw-semibold">Net Profit</div>
                    <div class="fw-bold text-primary fs-5">₨ {{ number_format($monthlyKpis['net_profit'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════ 5. SOLAR CLEANING 14-DAY REMINDER & MONTHLY VISITS ════ --}}
<div class="row g-4 mb-4">
    {{-- Solar 14-Day Reminder System --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-top border-warning border-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-bell-fill text-warning me-2"></i>14-Day Solar Cleaning Reminders</h6>
                <span class="badge bg-warning text-dark">{{ $due14DayReminders->count() }} Due</span>
            </div>
            <div class="card-body p-3">
                @if($due14DayReminders->isEmpty())
                    <div class="text-center py-4 text-muted">No 14-day service reminders due.</div>
                @else
                    @foreach($due14DayReminders as $rem)
                    <div class="alert alert-warning border-warning p-3 rounded-3 mb-3" role="alert">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <div class="fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i>14 Days Completed - Customer Service Reminder Due</div>
                                <div class="small text-dark mt-1"><strong>{{ $rem->customer_name }}</strong> ({{ $rem->contact }})</div>
                                <div class="small text-muted">Last Service: {{ $rem->last_service_date?->format('d M Y') }}</div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                @include('partials.whatsapp-button', [
                                    'wa' => $whatsapp,
                                    'number' => $rem->contact,
                                    'message' => "Hello {$rem->customer_name}, your 14-day Solar Cleaning service is due! Contact Asan Home Service to schedule.",
                                    'label' => 'Contact Customer',
                                    'class' => 'btn btn-sm btn-success',
                                ])
                                <form method="POST" action="{{ route('reminders.complete', $rem) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning text-dark font-semibold">
                                        <i class="bi bi-check-lg me-1"></i> Mark Complete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Monthly 2-Visit Reminders --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-top border-info border-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-calendar-range-fill text-info me-2"></i>Upcoming Monthly Visits (2 Visits/Month)</h6>
                <span class="badge bg-info text-white">{{ $upcomingMonthlyVisits->count() }} Scheduled</span>
            </div>
            <div class="table-responsive">
                @if($upcomingMonthlyVisits->isEmpty())
                    <div class="text-center py-4 text-muted">No upcoming monthly visits scheduled.</div>
                @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Visit #</th>
                            <th>Next Visit Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingMonthlyVisits as $visit)
                        <tr>
                            <td class="fw-semibold">{{ $visit->customer_name }}</td>
                            <td>{{ $visit->contact }}</td>
                            <td><span class="badge bg-light text-dark border">Visit {{ $visit->visit_number }}</span></td>
                            <td><span class="fw-bold text-primary">{{ $visit->visit_date?->format('d M Y') }}</span></td>
                            <td><span class="badge bg-info text-white">{{ $visit->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ════ 6. CUSTOMER FOLLOW UP PANEL ════ --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold m-0 text-primary"><i class="bi bi-telephone-outbound-fill me-2"></i>Customer Follow Ups Panel</h6>
    </div>
    <div class="table-responsive">
        @if($customerFollowUps->isEmpty())
            <div class="text-center py-4 text-muted">No customer follow ups pending.</div>
        @else
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Service Type</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Due Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customerFollowUps as $fu)
                <tr>
                    <td><span class="badge bg-secondary">{{ $fu['type'] }}</span></td>
                    <td class="fw-semibold">{{ $fu['name'] }}</td>
                    <td>{{ $fu['phone'] }}</td>
                    <td><span class="text-danger fw-bold">{{ $fu['due_date']?->format('d M Y') ?: 'Due Now' }}</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ $fu['url'] }}" class="btn btn-sm btn-outline-primary" title="View Customer">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="tel:{{ $fu['phone'] }}" class="btn btn-sm btn-outline-success" title="Call Customer">
                                <i class="bi bi-telephone"></i> Call
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ════ 7. DASHBOARD CHARTS (CHART.JS) ════ --}}
<div class="row g-4 mb-4">
    {{-- Chart 1: Monthly Earnings --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-bar-chart-line-fill me-2"></i>Monthly Earnings (Jan–Dec {{ now()->year }})</h6>
            </div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartMonthlyEarnings"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart 2: Service Performance --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-pie-chart-fill me-2"></i>Service Performance Comparison</h6>
            </div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartServicePerformance"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart 3: Income vs Expense --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-graph-up-arrow me-2"></i>Income vs Expense ({{ now()->year }})</h6>
            </div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartIncomeVsExpense"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart 4: Customer Growth --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-people-fill me-2"></i>Customer Growth Trend</h6>
            </div>
            <div class="card-body" style="min-height:280px;">
                <canvas id="chartCustomerGrowth"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ════ 8. PRO FEATURES: TOP CUSTOMERS (CLV) & RETURNING CUSTOMERS ════ --}}
<div class="row g-4 mb-4">
    {{-- Top Customers & Customer Lifetime Value (CLV) --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-success"><i class="bi bi-award-fill me-2"></i>Top Customers Panel (Customer Lifetime Value)</h6>
            </div>
            <div class="table-responsive">
                @if($topCustomers->isEmpty())
                    <div class="text-center py-4 text-muted">No customer spending recorded yet.</div>
                @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Rank</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th class="text-end">Total Spent (CLV)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $idx => $tc)
                        <tr>
                            <td><span class="badge bg-warning text-dark rounded-circle">{{ $idx + 1 }}</span></td>
                            <td class="fw-semibold">{{ $tc['name'] }}</td>
                            <td>{{ $tc['contact'] }}</td>
                            <td class="text-end fw-bold text-success">₨ {{ number_format($tc['total_spent'], 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- Returning Customers Feature --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-arrow-repeat me-2"></i>Returning Customers (Repeat Clients)</h6>
            </div>
            <div class="table-responsive">
                @if($returningCustomers->isEmpty())
                    <div class="text-center py-4 text-muted">No repeat customers recorded yet.</div>
                @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Customer Name</th>
                            <th>Visits</th>
                            <th>Last Service</th>
                            <th>Next Reminder</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returningCustomers as $rc)
                        <tr>
                            <td class="fw-semibold">{{ $rc['name'] }}</td>
                            <td><span class="badge bg-success">{{ $rc['visits'] }} Visits</span></td>
                            <td><span class="small text-muted">{{ $rc['last_date']?->format('d M Y') ?: '—' }}</span></td>
                            <td><span class="small text-warning fw-bold">{{ $rc['next_reminder']?->format('d M Y') ?: 'N/A' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ════ 9. RECENT ACTIVITY TIMELINE ════ --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="fw-bold m-0 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Activities Timeline</h6>
    </div>
    <div class="table-responsive">
        @if($recentActivities->isEmpty())
            <div class="text-center py-4 text-muted">No recent activities.</div>
        @else
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Event Type</th>
                    <th>Reference ID</th>
                    <th>Customer / Info</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivities as $activity)
                <tr>
                    <td><span class="badge {{ $activity['badge'] }}">{{ $activity['type'] }}</span></td>
                    <td><span class="font-monospace fw-bold">{{ $activity['id'] }}</span></td>
                    <td class="fw-semibold">{{ $activity['name'] }}</td>
                    <td class="text-muted small">{{ $activity['date']?->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const months = {!! json_encode($months) !!};

    // Chart 1: Monthly Earnings
    const ctxMonthly = document.getElementById('chartMonthlyEarnings');
    if (ctxMonthly) {
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Earnings (₨)',
                    data: {!! json_encode($monthlyEarningsData) !!},
                    backgroundColor: '#198754',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Chart 2: Service Performance
    const ctxService = document.getElementById('chartServicePerformance');
    if (ctxService) {
        new Chart(ctxService, {
            type: 'doughnut',
            data: {
                labels: ['Solar Cleaning', 'Sofa Cleaning', 'Deep Cleaning'],
                datasets: [{
                    data: [
                        {{ $servicePerformanceData['Solar Cleaning'] }},
                        {{ $servicePerformanceData['Sofa Cleaning'] }},
                        {{ $servicePerformanceData['Deep Cleaning'] }}
                    ],
                    backgroundColor: ['#ffc107', '#0dcaf0', '#0d6efd']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Chart 3: Income vs Expense
    const ctxIncomeVsExpense = document.getElementById('chartIncomeVsExpense');
    if (ctxIncomeVsExpense) {
        new Chart(ctxIncomeVsExpense, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Income (₨)',
                        data: {!! json_encode($incomeVsExpenseData['income']) !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Expense (₨)',
                        data: {!! json_encode($incomeVsExpenseData['expense']) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Chart 4: Customer Growth
    const ctxGrowth = document.getElementById('chartCustomerGrowth');
    if (ctxGrowth) {
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: {!! json_encode($growthLabels) !!},
                datasets: [{
                    label: 'Total Customers',
                    data: {!! json_encode($customerGrowthData) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.15)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ════ NEW: Lead Analytics Charts ════

    // Chart 1: Leads By Status (Doughnut)
    const leadsStatusEl = document.getElementById('leadsStatusChart');
    if (leadsStatusEl) {
        new Chart(leadsStatusEl, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($leadsStatusChart['labels']) !!},
                datasets: [{
                    data: {!! json_encode($leadsStatusChart['data']) !!},
                    backgroundColor: {!! json_encode($leadsStatusChart['colors']) !!},
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 12, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round(ctx.raw / total * 100) : 0;
                                return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Leads By Service Type (Vertical Bar)
    const leadsServiceEl = document.getElementById('leadsServiceChart');
    if (leadsServiceEl) {
        new Chart(leadsServiceEl, {
            type: 'bar',
            data: {
                labels: {!! json_encode($leadsServiceChart['labels']) !!},
                datasets: [{
                    label: 'Total Leads',
                    data: {!! json_encode($leadsServiceChart['data']) !!},
                    backgroundColor: {!! json_encode($leadsServiceChart['colors']) !!},
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (c) => ` ${c.raw} Leads` } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Chart 3: Campaign Performance (Horizontal Bar)
    const campaignPerfEl = document.getElementById('campaignPerformanceChart');
    if (campaignPerfEl) {
        new Chart(campaignPerfEl, {
            type: 'bar',
            data: {
                labels: {!! json_encode($campaignPerformanceChart['labels']) !!},
                datasets: [
                    {
                        label: 'Total',
                        data: {!! json_encode($campaignPerformanceChart['total']) !!},
                        backgroundColor: 'rgba(78,115,223,0.7)',
                        borderRadius: 4
                    },
                    {
                        label: 'Done',
                        data: {!! json_encode($campaignPerformanceChart['done']) !!},
                        backgroundColor: 'rgba(28,200,138,0.7)',
                        borderRadius: 4
                    },
                    {
                        label: 'Cancel',
                        data: {!! json_encode($campaignPerformanceChart['cancel']) !!},
                        backgroundColor: 'rgba(231,74,59,0.7)',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

});
</script>
@endpush

