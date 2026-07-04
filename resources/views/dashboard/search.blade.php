@extends('layouts.app')

@section('title', 'Search Results')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Search Results</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Search: "{{ $query }}"</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@php
    $totalResults = $solarClients->count() + $leads->count() + $sofaCleanings->count() + $deepCleanings->count();
@endphp

<div class="alert alert-info">
    <i class="bi bi-search"></i>
    Found <strong>{{ $totalResults }}</strong> result(s) across active modules for "<strong>{{ $query }}</strong>"
</div>

@if($solarClients->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-sun-fill me-2" style="color:#667eea"></i>Solar Clients ({{ $solarClients->count() }})</h5>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>Contact</th><th>Area</th><th>Amount</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($solarClients as $c)
                <tr>
                    <td><span class="id-badge solar">{{ $c->client_id }}</span></td>
                    <td class="fw-semibold">{{ $c->name ?? '-' }}</td>
                    <td>{{ $c->contact ?? '-' }}</td>
                    <td>{{ $c->area ?? '-' }}</td>
                    <td>Rs {{ number_format($c->total_amount, 0) }}</td>
                    <td><a href="{{ route('solar-clients.show', $c) }}" class="btn btn-sm btn-secondary btn-icon"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($leads->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-megaphone-fill me-2" style="color:#ed8936"></i>Marketing Leads ({{ $leads->count() }})</h5>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>Contact</th><th>Platform</th><th>Received</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($leads as $l)
                <tr>
                    <td><span class="id-badge lead">{{ $l->lead_id }}</span></td>
                    <td class="fw-semibold">{{ $l->name ?? '-' }}</td>
                    <td>{{ $l->contact ?? '-' }}</td>
                    <td>{{ $l->platform ?? '-' }}</td>
                    <td>{{ $l->amount_received ? 'Rs '.number_format($l->amount_received,0) : '-' }}</td>
                    <td><a href="{{ route('leads.show', $l) }}" class="btn btn-sm btn-secondary btn-icon"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($sofaCleanings->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-diamond-fill me-2" style="color:#b794f4"></i>Sofa Cleaning ({{ $sofaCleanings->count() }})</h5>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>Contact</th><th>Status</th><th>Amount</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($sofaCleanings as $sc)
                <tr>
                    <td><span class="id-badge sofa">{{ $sc->record_id }}</span></td>
                    <td class="fw-semibold">{{ $sc->customer_name ?? '-' }}</td>
                    <td>{{ $sc->contact ?? '-' }}</td>
                    <td>@if($sc->status)<span class="status-badge status-inprogress">{{ $sc->status }}</span>@else - @endif</td>
                    <td>{{ $sc->amount_received ? 'Rs '.number_format($sc->amount_received,0) : '-' }}</td>
                    <td><a href="{{ route('sofa-cleaning.show', $sc) }}" class="btn btn-sm btn-secondary btn-icon"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($deepCleanings->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-brush-fill me-2" style="color:#4fd1c7"></i>Deep Cleaning ({{ $deepCleanings->count() }})</h5>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>Contact</th><th>Status</th><th>Amount</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($deepCleanings as $dc)
                <tr>
                    <td><span class="id-badge deep">{{ $dc->record_id }}</span></td>
                    <td class="fw-semibold">{{ $dc->customer_name ?? '-' }}</td>
                    <td>{{ $dc->contact ?? '-' }}</td>
                    <td>@if($dc->status)<span class="status-badge status-inprogress">{{ $dc->status }}</span>@else - @endif</td>
                    <td>{{ $dc->amount_received ? 'Rs '.number_format($dc->amount_received,0) : '-' }}</td>
                    <td><a href="{{ route('deep-cleaning.show', $dc) }}" class="btn btn-sm btn-secondary btn-icon"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($totalResults === 0)
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-search"></i></div>
        <div class="empty-state-text">No results found for "{{ $query }}"</div>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>
@endif

@endsection
