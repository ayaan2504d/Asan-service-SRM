@extends('layouts.app')
@section('title', 'Deep Cleaning Details — '.$deepCleaning->record_id)
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Deep Cleaning Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('deep-cleaning.index') }}">Deep Cleaning</a></li>
                <li class="breadcrumb-item active">{{ $deepCleaning->record_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('deep-cleaning.edit', $deepCleaning) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('deep-cleaning.index') }}" class="btn btn-secondary">
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
                    <span class="id-badge deep me-2">{{ $deepCleaning->record_id }}</span>
                    {{ $deepCleaning->customer_name ?? 'Unknown Customer' }}
                </h5>
                @if($deepCleaning->status)
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '', $deepCleaning->status)) }}">
                        {{ $deepCleaning->status }}
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="form-section-title">Customer Information</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Name</td><td>{{ $deepCleaning->customer_name ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Contact</td><td>{{ $deepCleaning->contact ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Address</td><td>{{ $deepCleaning->address ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Service Details</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">House Size</td><td>{{ $deepCleaning->house_size ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Rooms</td><td>{{ $deepCleaning->num_rooms ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Bathrooms</td><td>{{ $deepCleaning->num_bathrooms ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Dates</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Booking Date</td><td>{{ $deepCleaning->booking_date?->format('d M Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Service Date</td><td>{{ $deepCleaning->service_date?->format('d M Y') ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Payment Info</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Service Charges</td><td>{{ $deepCleaning->service_charges ? '₨ '.number_format($deepCleaning->service_charges, 0) : '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Amount Rcvd</td><td class="fw-bold text-success">{{ $deepCleaning->amount_received ? '₨ '.number_format($deepCleaning->amount_received, 0) : '—' }}</td></tr>
                        </table>
                    </div>
                    @if($deepCleaning->notes)
                    <div class="col-12">
                        <h6 class="form-section-title">Notes</h6>
                        <p style="font-size:.875rem;color:var(--text-secondary);">{{ $deepCleaning->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="card-title">Record Info</h6></div>
            <div class="card-body" style="font-size:.82rem;color:var(--text-secondary);">
                <div class="d-flex justify-content-between mb-2"><span>Created:</span><strong>{{ $deepCleaning->created_at->format('d M Y H:i') }}</strong></div>
                <div class="d-flex justify-content-between"><span>Updated:</span><strong>{{ $deepCleaning->updated_at->format('d M Y H:i') }}</strong></div>
            </div>
        </div>
        <form method="POST" action="{{ route('deep-cleaning.destroy', $deepCleaning) }}" onsubmit="return confirm('Delete this record?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-trash"></i> Delete Record
            </button>
        </form>
    </div>
</div>

@endsection
