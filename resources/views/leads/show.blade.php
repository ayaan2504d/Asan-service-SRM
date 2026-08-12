@extends('layouts.app')

@section('title', 'Lead Details — ' . $lead->lead_id)

@section('content')

<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="page-header-left">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-megaphone-fill me-2 text-primary"></i>Marketing Lead Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Marketing Leads</a></li>
                <li class="breadcrumb-item active">{{ $lead->lead_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="mt-2 mt-sm-0 d-flex gap-2">
        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning text-dark font-semibold">
            <i class="bi bi-pencil me-1"></i> Edit Lead
        </a>
        <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Leads
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" data-auto-dismiss>
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Main Lead Overview --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-dark font-monospace fs-6">{{ $lead->lead_id }}</span>
                    <h5 class="fw-bold m-0">{{ $lead->name }}</h5>
                </div>
                <div>
                    @if($lead->effective_status == 'Pending')
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                    @elseif($lead->effective_status == 'Follow Up')
                        <span class="badge bg-info text-white fs-6"><i class="bi bi-telephone-outbound me-1"></i>Follow Up</span>
                    @elseif($lead->effective_status == 'Done')
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Done</span>
                    @elseif($lead->effective_status == 'Cancel')
                        <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Cancel</span>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4 mb-3">
                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-person-badge me-2"></i>Customer Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted fw-semibold" style="width:130px;">Name:</td><td class="fw-bold">{{ $lead->name }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Phone:</td><td><a href="tel:{{ $lead->contact }}" class="text-decoration-none text-dark fw-semibold"><i class="bi bi-telephone me-1 text-primary"></i>{{ $lead->contact }}</a></td></tr>
                            <tr><td class="text-muted fw-semibold">Address:</td><td>{{ $lead->effective_address ?: '—' }}</td></tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-bar-chart-steps me-2"></i>Campaign & Source</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted fw-semibold" style="width:130px;">Lead Source:</td><td><span class="badge bg-light text-dark border">{{ $lead->effective_lead_source }}</span></td></tr>
                            <tr><td class="text-muted fw-semibold">Campaign Name:</td><td>{{ $lead->campaign_name ?: '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Service Type:</td><td><span class="badge bg-primary">{{ $lead->effective_service_type }}</span></td></tr>
                        </table>
                    </div>
                </div>

                {{-- Dynamic Service Specific Details --}}
                <div class="p-3 bg-light rounded-3 mb-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-gear-fill me-2 text-secondary"></i>Specific Requirements ({{ $lead->effective_service_type }})</h6>
                    <div class="row g-3">
                        @if($lead->effective_service_type == 'Solar Cleaning')
                            <div class="col-md-6">
                                <span class="text-muted">Solar Plates:</span> <strong>{{ $lead->num_plates ?: 'N/A' }} Plates</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Quoted Amount:</span> <strong class="text-success">₨ {{ number_format($lead->quoted_amount ?: $lead->estimated_amount, 2) }}</strong>
                            </div>
                        @elseif($lead->effective_service_type == 'Sofa Cleaning')
                            <div class="col-md-6">
                                <span class="text-muted">Number of Sofas:</span> <strong>{{ $lead->num_sofas ?: 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Sofa Type:</span> <strong>{{ $lead->sofa_type ?: 'N/A' }}</strong>
                            </div>
                        @elseif($lead->effective_service_type == 'Deep Cleaning')
                            <div class="col-md-6">
                                <span class="text-muted">House Size:</span> <strong>{{ $lead->house_size ?: 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Floors:</span> <strong>{{ $lead->floors ?: 'N/A' }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Notes --}}
                @if($lead->notes)
                <div>
                    <h6 class="text-muted fw-semibold mb-1">Notes:</h6>
                    <p class="mb-0 bg-white p-3 rounded border text-secondary" style="white-space: pre-wrap;">{{ $lead->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Conversion & Actions Side Column --}}
    <div class="col-lg-4">
        {{-- LEAD CONVERSION CARD --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0 text-primary"><i class="bi bi-arrow-repeat me-2"></i>Lead Conversion</h6>
            </div>
            <div class="card-body p-3">
                <p class="small text-muted mb-3">Convert customer data automatically into module records. Lead record will be preserved for history.</p>

                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('leads.convert.solar', $lead) }}" onsubmit="return confirm('Convert lead to Solar Client?')">
                        @csrf
                        <button type="submit" class="btn btn-warning text-dark fw-semibold w-100 text-start shadow-sm">
                            <i class="bi bi-sun-fill me-2"></i> Convert To Solar Client
                        </button>
                    </form>

                    <form method="POST" action="{{ route('leads.convert.sofa', $lead) }}" onsubmit="return confirm('Convert lead to Sofa Cleaning?')">
                        @csrf
                        <button type="submit" class="btn btn-info text-white fw-semibold w-100 text-start shadow-sm">
                            <i class="bi bi-diamond-fill me-2"></i> Convert To Sofa Cleaning
                        </button>
                    </form>

                    <form method="POST" action="{{ route('leads.convert.deep', $lead) }}" onsubmit="return confirm('Convert lead to Deep Cleaning?')">
                        @csrf
                        <button type="submit" class="btn btn-primary fw-semibold w-100 text-start shadow-sm">
                            <i class="bi bi-brush-fill me-2"></i> Convert To Deep Cleaning
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Meta details --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Created:</span> <strong class="text-dark">{{ $lead->created_at->format('d M Y, h:i A') }}</strong>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>Last Updated:</span> <strong class="text-dark">{{ $lead->updated_at->format('d M Y, h:i A') }}</strong>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Are you sure you want to delete this marketing lead?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="bi bi-trash me-1"></i> Delete Lead
            </button>
        </form>
    </div>
</div>

@endsection
