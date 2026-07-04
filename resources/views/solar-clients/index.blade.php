@extends('layouts.app')
@section('title', 'Solar Clients')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Solar Clients</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Solar Clients</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('solar-clients.create') }}" class="btn btn-primary" id="btn-add-solar-client">
        <i class="bi bi-plus-lg"></i> Add Client
    </a>
</div>

@include('partials.alerts')

@php $wa = new \App\Services\WhatsAppService(); @endphp

{{-- Filter Bar --}}
<form method="GET" class="filter-bar" id="solar-client-filter-form">
    <div class="global-search-wrap" style="max-width:320px;">
        <i class="bi bi-search global-search-icon"></i>
        <input type="text" name="search" class="filter-input global-search-input" placeholder="Search ID, name, contact..." value="{{ request('search') }}" id="solar-search-input">
    </div>
    <button type="submit" class="btn btn-primary btn-sm" id="btn-solar-filter">
        <i class="bi bi-search"></i> Search
    </button>
    @if(request('search'))
        <a href="{{ route('solar-clients.index') }}" class="btn btn-secondary btn-sm" id="btn-solar-clear">
            <i class="bi bi-x-lg"></i> Clear
        </a>
    @endif
    <div class="ms-auto" style="font-size:.78rem;color:var(--text-muted);">
        {{ $clients->total() }} record(s) found
    </div>
</form>

<div class="card">
    <div class="table-wrapper">
        @if($clients->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-sun"></i></div>
                <div class="empty-state-text">No solar clients found.</div>
                <a href="{{ route('solar-clients.create') }}" class="btn btn-primary">Add First Client</a>
            </div>
        @else
        <table class="data-table" id="solar-clients-table">
            <thead>
                <tr>
                    <th>Client ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Area</th>
                    <th>Plates</th>
                    <th>Total Amount</th>
                    <th>1st Visit</th>
                    <th>Latest Visit</th>
                    <th>Reminder</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td><span class="id-badge solar">{{ $client->client_id }}</span></td>
                    <td class="fw-semibold">{{ $client->name ?? '—' }}</td>
                    <td>{{ $client->contact ?? '—' }}</td>
                    <td>{{ $client->area ?? '—' }}</td>
                    <td>{{ $client->num_plates ?? '—' }}</td>
                    <td class="fw-semibold" style="color:var(--success);">
                        {{ $client->total_amount > 0 ? '₨ '.number_format($client->total_amount, 0) : '—' }}
                    </td>
                    <td>{{ $client->first_visit_date?->format('d M Y') ?? '—' }}</td>
                    <td>{{ $client->latestVisitDate()?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @if($client->reminder_date && !$client->reminder_sent)
                            <span class="status-badge status-pending">
                                {{ $client->reminder_date->format('d M') }}
                            </span>
                        @elseif($client->reminder_sent)
                            <span class="status-badge status-completed">Done</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('solar-clients.show', $client) }}" class="btn btn-sm btn-secondary btn-icon" title="View" id="view-sc-{{ $client->id }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            @include('partials.whatsapp-button', [
                                'wa' => $wa,
                                'number' => $client->whatsapp_number ?: $client->contact,
                                'message' => $wa->render('solar_follow_up_template', $client),
                            ])
                            <a href="{{ route('solar-clients.edit', $client) }}" class="btn btn-sm btn-warning btn-icon" title="Edit" id="edit-sc-{{ $client->id }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('solar-clients.destroy', $client) }}" onsubmit="return confirm('Delete this client? This action cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Delete" id="del-sc-{{ $client->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @if($clients->hasPages())
    <div class="pagination-wrap">
        <div>Showing {{ $clients->firstItem() }}–{{ $clients->lastItem() }} of {{ $clients->total() }}</div>
        {{ $clients->links() }}
    </div>
    @endif
</div>

@endsection
