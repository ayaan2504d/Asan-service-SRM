@extends('layouts.app')
@section('title', 'Lead Details — '.$lead->lead_id)
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Lead Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Leads</a></li>
                <li class="breadcrumb-item active">{{ $lead->lead_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('leads.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

@include('partials.alerts')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">
                    <span class="id-badge lead me-2">{{ $lead->lead_id }}</span>
                    {{ $lead->name ?? 'Unknown Lead' }}
                </h5>
                @if($lead->response)
                    <span class="badge {{ $lead->response == 'Positive' ? 'bg-success' : ($lead->response == 'Negative' ? 'bg-danger' : ($lead->response == 'Follow Up' ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                        {{ $lead->response }}
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="form-section-title">Lead Information</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Date</td><td>{{ $lead->date?->format('d M Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Name</td><td>{{ $lead->name ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Contact</td><td>{{ $lead->contact ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Location</td><td>{{ $lead->location ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Campaign Info</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Platform</td><td>{{ $lead->platform ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Campaign</td><td>{{ $lead->campaign_name ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Response</td><td>{{ $lead->response ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Service Requirements</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Site Type</td><td>{{ $lead->type_of_site ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Service Type</td><td>{{ $lead->type_of_service ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Area Size</td><td>{{ $lead->size_of_area ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">No. of Items</td><td>{{ $lead->num_items ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Booking Details</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Booking Date</td><td>{{ $lead->booking_date?->format('d M Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Service Date</td><td>{{ $lead->service_date?->format('d M Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Appt Time</td><td>{{ $lead->appointment_time ? \Carbon\Carbon::parse($lead->appointment_time)->format('h:i A') : '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <h6 class="form-section-title">Financials</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Estimated</td><td>{{ $lead->estimated_amount ? '₨ '.number_format($lead->estimated_amount, 0) : '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Received</td><td class="fw-bold text-success">{{ $lead->amount_received ? '₨ '.number_format($lead->amount_received, 0) : '—' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title">Record Info</h6></div>
            <div class="card-body" style="font-size:.82rem;color:var(--text-secondary);">
                <div class="d-flex justify-content-between mb-2"><span>Created:</span><strong>{{ $lead->created_at->format('d M Y H:i') }}</strong></div>
                <div class="d-flex justify-content-between"><span>Updated:</span><strong>{{ $lead->updated_at->format('d M Y H:i') }}</strong></div>
            </div>
        </div>
        <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-trash"></i> Delete Record
            </button>
        </form>
    </div>
</div>

@endsection
