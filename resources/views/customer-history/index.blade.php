@extends('layouts.app')
@section('title', 'Customer History')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Customer History</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Customer History</li>
            </ol>
        </nav>
    </div>
</div>

@include('partials.alerts')

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('customer-history.index') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="contact" class="form-label">Search by Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="contact" id="contact" class="form-control" value="{{ request('contact') }}" placeholder="Enter exact or partial phone number..." required>
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search History
                </button>
            </div>
        </form>
    </div>
</div>

@if(request('contact'))
    <div class="page-header mt-5">
        <div class="page-header-left">
            <h3>Results for "{{ request('contact') }}"</h3>
        </div>
        <div>
            <a href="{{ route('customer-history.index') }}" class="btn btn-secondary btn-sm">Clear Search</a>
        </div>
    </div>

    @if(empty($history))
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-clock-history"></i></div>
                <div class="empty-state-text">No history found for this contact number.</div>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($history as $record)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100" style="border-top:4px solid var(--{{ 
                    $record['module'] == 'Solar Client' ? 'warning' : (
                    $record['module'] == 'Solar Site' ? 'success' : (
                    $record['module'] == 'Lead' ? 'orange' : (
                    $record['module'] == 'Sofa Cleaning' ? 'purple' : 'info'
                    ))) }})">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold">{{ $record['module'] }}</h6>
                        <span class="id-badge bg-light text-dark border">{{ $record['id'] }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-2"><strong class="text-muted">Name:</strong> {{ $record['name'] }}</div>
                        <div class="mb-2"><strong class="text-muted">Date:</strong> {{ $record['date'] }}</div>
                        @if(isset($record['amount']))
                        <div class="mb-2"><strong class="text-muted">Amount:</strong> <span class="text-success fw-bold">{{ $record['amount'] }}</span></div>
                        @endif
                        @if(isset($record['status']))
                        <div class="mb-2"><strong class="text-muted">Status:</strong> <span class="badge bg-secondary">{{ $record['status'] }}</span></div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ $record['route'] }}" class="btn btn-sm btn-outline-primary w-100">View Full Record</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon" style="color:var(--text-muted);opacity:0.5;"><i class="bi bi-search"></i></div>
            <div class="empty-state-text" style="color:var(--text-muted);">Enter a phone number above to view all past records associated with that customer across all modules.</div>
        </div>
    </div>
@endif

@endsection
