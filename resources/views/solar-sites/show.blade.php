@extends('layouts.app')
@section('title', 'Solar Site — '.$solarSite->site_id)
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Solar Site Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('solar-sites.index') }}">Solar Sites</a></li>
                <li class="breadcrumb-item active">{{ $solarSite->site_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('solar-sites.edit', $solarSite) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('solar-sites.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

@include('partials.alerts')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="id-badge site me-2">{{ $solarSite->site_id }}</span>
                    {{ $solarSite->client_name ?? 'Unknown Client' }}
                </h5>
                @if($solarSite->status)
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '', $solarSite->status)) }}">
                        {{ $solarSite->status }}
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="form-section-title">Client Information</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Name</td><td>{{ $solarSite->client_name ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Contact</td><td>{{ $solarSite->contact ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Address</td><td>{{ $solarSite->address ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Area</td><td>{{ $solarSite->area ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Visit Details</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Visit Date</td><td>{{ $solarSite->visit_date?->format('d M Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Visit Time</td><td>{{ $solarSite->visit_time ? \Carbon\Carbon::parse($solarSite->visit_time)->format('h:i A') : '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Team</td><td>{{ $solarSite->team ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">No. of Plates</td><td>{{ $solarSite->num_plates ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <h6 class="form-section-title">Payment Information</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Charges</td><td class="fw-bold text-success">{{ $solarSite->charges ? '₨ '.number_format($solarSite->charges, 0) : '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Payment Type</td><td>{{ $solarSite->payment_type ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Received By</td><td>{{ $solarSite->received_by ?? '—' }}</td></tr>
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
                <div class="d-flex justify-content-between mb-2"><span>Created:</span><strong>{{ $solarSite->created_at->format('d M Y H:i') }}</strong></div>
                <div class="d-flex justify-content-between"><span>Updated:</span><strong>{{ $solarSite->updated_at->format('d M Y H:i') }}</strong></div>
            </div>
        </div>
        <form method="POST" action="{{ route('solar-sites.destroy', $solarSite) }}" onsubmit="return confirm('Delete this record?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-trash"></i> Delete Record
            </button>
        </form>
    </div>
</div>

@endsection
