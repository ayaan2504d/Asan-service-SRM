@extends('layouts.app')
@section('title', 'Reports & Financials')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Reports & Financials</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card green">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">₨ {{ number_format($totalRevenue, 0) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card red">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Total Expenses</div>
                    <div class="stat-value">₨ {{ number_format($totalExpenses, 0) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-down-arrow"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="stat-card {{ $netProfit >= 0 ? 'blue' : 'orange' }}">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Net Profit / Loss</div>
                    <div class="stat-value">₨ {{ number_format($netProfit, 0) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title">Revenue Breakdown</h5></div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td><i class="bi bi-sun-fill text-warning me-2"></i> Solar Clients</td>
                            <td class="text-end fw-bold">₨ {{ number_format($revenueBreakdown['solar_clients'], 0) }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-megaphone-fill text-orange me-2"></i> Leads</td>
                            <td class="text-end fw-bold">₨ {{ number_format($revenueBreakdown['leads'], 0) }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-diamond-fill text-purple me-2"></i> Sofa Cleaning</td>
                            <td class="text-end fw-bold">₨ {{ number_format($revenueBreakdown['sofa_cleaning'], 0) }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-brush-fill text-info me-2"></i> Deep Cleaning</td>
                            <td class="text-end fw-bold">₨ {{ number_format($revenueBreakdown['deep_cleaning'], 0) }}</td>
                        </tr>
                        <tr class="table-light">
                            <td class="fw-bold">Total Revenue</td>
                            <td class="text-end fw-bold text-success">₨ {{ number_format($totalRevenue, 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title">Expense Summary</h5></div>
            <div class="card-body">
                <div class="alert alert-secondary">
                    Total tracked expenses for this period. 
                    <a href="{{ route('expenses.index') }}" class="alert-link">View all expenses</a>
                </div>
                <div class="d-flex align-items-center justify-content-center h-50">
                    <div class="text-center">
                        <div class="display-6 fw-bold text-danger mb-2">₨ {{ number_format($totalExpenses, 0) }}</div>
                        <div class="text-muted">Total outflows</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
