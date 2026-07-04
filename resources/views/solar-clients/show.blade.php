@extends('layouts.app')
@section('title', 'Solar Client - '.$solarClient->client_id)
@section('content')

@php
    $wa = new \App\Services\WhatsAppService();
    $solarMessage = $wa->render('solar_follow_up_template', $solarClient);
@endphp

<div class="page-header">
    <div class="page-header-left">
        <h1>Solar Client Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('solar-clients.index') }}">Solar Clients</a></li>
                <li class="breadcrumb-item active">{{ $solarClient->client_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @include('partials.whatsapp-button', [
            'wa' => $wa,
            'number' => $solarClient->whatsapp_number ?: $solarClient->contact,
            'message' => $solarMessage,
            'label' => 'WhatsApp',
            'class' => 'btn btn-success',
        ])
        <a href="{{ route('solar-clients.edit', $solarClient) }}" class="btn btn-warning" id="btn-edit-sc">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('solar-clients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

@include('partials.alerts')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="id-badge solar me-2">{{ $solarClient->client_id }}</span>
                    {{ $solarClient->name ?? 'Unknown Client' }}
                </h5>
                @if($solarClient->reminder_date && !$solarClient->reminder_sent)
                    <span class="status-badge status-pending"><i class="bi bi-bell-fill"></i> Follow-up Pending</span>
                @elseif($solarClient->reminder_sent)
                    <span class="status-badge status-completed">Follow-up Done</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="form-section-title">Customer Information</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Name</td><td>{{ $solarClient->name ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Contact</td><td>{{ $solarClient->contact ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">WhatsApp</td><td>{{ $solarClient->whatsapp_number ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Address</td><td>{{ $solarClient->address ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Area</td><td>{{ $solarClient->area ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Package Details</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Package</td><td>{{ $solarClient->package ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">No. of Plates</td><td>{{ $solarClient->num_plates ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Latest Visit</td><td>{{ $solarClient->latestVisitDate()?->format('d M Y') ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title"><i class="bi bi-calendar-check me-2 text-primary"></i>Visit History</h5></div>
            <div class="table-wrapper">
                @if(empty($solarClient->visitHistory()))
                    <div class="empty-state">
                        <div class="empty-state-text">No visits recorded yet.</div>
                    </div>
                @else
                <table class="data-table">
                    <thead><tr><th>Visit</th><th>Date</th><th>Notes</th></tr></thead>
                    <tbody>
                        @foreach($solarClient->visitHistory() as $visit)
                        <tr>
                            <td class="fw-semibold">{{ $visit['label'] }}</td>
                            <td>{{ $visit['date']?->format('d M Y') }}</td>
                            <td>{{ $visit['notes'] ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title"><i class="bi bi-clock-history me-2 text-primary"></i>Full Customer History</h5></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="form-section-title">Payments</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">First Amount</td><td>{{ $solarClient->amount ? 'Rs '.number_format($solarClient->amount, 0) : '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Payment Mode</td><td>{{ $solarClient->payment_mode ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Received By</td><td>{{ $solarClient->received_by ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Second Payment</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Amount</td><td>{{ $solarClient->second_amount ? 'Rs '.number_format($solarClient->second_amount, 0) : '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Payment Mode</td><td>{{ $solarClient->second_payment_mode ?? '-' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Received By</td><td>{{ $solarClient->second_received_by ?? '-' }}</td></tr>
                        </table>
                    </div>
                    @if($solarClient->notes)
                    <div class="col-12">
                        <h6 class="form-section-title">Notes</h6>
                        <p style="font-size:.875rem;color:var(--text-secondary);">{{ $solarClient->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stat-card green mb-4">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">Rs {{ number_format($solarClient->total_amount, 0) }}</div>
                    <div class="stat-sub">First + second payment</div>
                </div>
                <div class="stat-icon"><i class="bi bi-currency-exchange"></i></div>
            </div>
        </div>

        @if($solarClient->reminder_date)
        <div class="reminder-card mb-4">
            <div class="reminder-title"><i class="bi bi-bell-fill"></i> Follow-up Reminder</div>
            <div style="font-size:.855rem;">
                <div><strong>Due Date:</strong> {{ $solarClient->reminder_date->format('d M Y') }}</div>
                <div><strong>Latest Visit:</strong> {{ $solarClient->latestVisitDate()?->format('d M Y') ?? '-' }}</div>
                <div><strong>Status:</strong>
                    @if($solarClient->reminder_sent)
                        <span style="color:var(--success);">Completed</span>
                    @elseif($solarClient->reminder_date->isPast())
                        <span style="color:#e53e3e;">Overdue</span>
                    @else
                        <span style="color:#c05621;">Pending</span>
                    @endif
                </div>
            </div>
            @if(!$solarClient->reminder_sent)
            <div class="d-flex gap-2 mt-3 flex-wrap">
                @include('partials.whatsapp-button', [
                    'wa' => $wa,
                    'number' => $solarClient->whatsapp_number ?: $solarClient->contact,
                    'message' => $solarMessage,
                    'label' => 'Follow-up',
                    'class' => 'btn btn-success btn-sm',
                ])
                <form method="POST" action="{{ route('reminders.dismiss', $solarClient) }}">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm" id="btn-dismiss-from-show">
                        <i class="bi bi-check-lg"></i> Mark as Done
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif

        <div class="card">
            <div class="card-header"><h6 class="card-title">Record Info</h6></div>
            <div class="card-body" style="font-size:.82rem;color:var(--text-secondary);">
                <div class="d-flex justify-content-between mb-2"><span>Created:</span><strong>{{ $solarClient->created_at->format('d M Y H:i') }}</strong></div>
                <div class="d-flex justify-content-between"><span>Updated:</span><strong>{{ $solarClient->updated_at->format('d M Y H:i') }}</strong></div>
            </div>
        </div>
    </div>
</div>

@endsection
