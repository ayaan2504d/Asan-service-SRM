@extends('layouts.app')
@section('title', 'Marketing Leads')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Marketing Leads</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('leads.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Lead
    </a>
</div>

@include('partials.alerts')

{{-- Filter Bar --}}
<form method="GET" class="filter-bar">
    <div class="global-search-wrap" style="max-width:320px;">
        <i class="bi bi-search global-search-icon"></i>
        <input type="text" name="search" class="filter-input global-search-input" placeholder="Search ID, name, contact..." value="{{ request('search') }}">
    </div>
    
    <select name="platform" class="filter-input">
        <option value="">All Platforms</option>
        <option value="Facebook" {{ request('platform') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
        <option value="Instagram" {{ request('platform') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
        <option value="Google" {{ request('platform') == 'Google' ? 'selected' : '' }}>Google</option>
        <option value="Referral" {{ request('platform') == 'Referral' ? 'selected' : '' }}>Referral</option>
        <option value="Direct" {{ request('platform') == 'Direct' ? 'selected' : '' }}>Direct</option>
        <option value="Other" {{ request('platform') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>
    
    <select name="response" class="filter-input">
        <option value="">All Responses</option>
        <option value="Positive" {{ request('response') == 'Positive' ? 'selected' : '' }}>Positive</option>
        <option value="Neutral" {{ request('response') == 'Neutral' ? 'selected' : '' }}>Neutral</option>
        <option value="Negative" {{ request('response') == 'Negative' ? 'selected' : '' }}>Negative</option>
        <option value="No Answer" {{ request('response') == 'No Answer' ? 'selected' : '' }}>No Answer</option>
        <option value="Follow Up" {{ request('response') == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
    </select>

    <div class="d-flex align-items-center gap-2">
        <input type="date" name="from" class="filter-input" value="{{ request('from') }}" placeholder="From">
        <span class="text-muted" style="font-size:12px;">to</span>
        <input type="date" name="to" class="filter-input" value="{{ request('to') }}" placeholder="To">
    </div>

    <button type="submit" class="btn btn-primary btn-sm">
        <i class="bi bi-search"></i> Filter
    </button>
    
    @if(request('search') || request('platform') || request('response') || request('from') || request('to'))
        <a href="{{ route('leads.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-x-lg"></i> Clear
        </a>
    @endif
    
    <div class="ms-auto" style="font-size:.78rem;color:var(--text-muted);">
        Total Received: <strong style="color:var(--success);">₨ {{ number_format($totalRevenue, 0) }}</strong>
    </div>
</form>

<div class="card">
    <div class="table-wrapper">
        @if($leads->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-megaphone"></i></div>
                <div class="empty-state-text">No leads found.</div>
                <a href="{{ route('leads.create') }}" class="btn btn-primary">Add First Lead</a>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Lead ID</th>
                    <th>Date</th>
                    <th>Name / Contact</th>
                    <th>Platform</th>
                    <th>Response</th>
                    <th>Service Type</th>
                    <th>Amount Rcvd</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td><span class="id-badge lead">{{ $lead->lead_id }}</span></td>
                    <td>{{ $lead->date ? $lead->date->format('d M Y') : '—' }}</td>
                    <td>
                        <div class="fw-semibold">{{ $lead->name ?? '—' }}</div>
                        <div style="font-size:12px;color:var(--text-secondary);">{{ $lead->contact ?? '—' }}</div>
                    </td>
                    <td>{{ $lead->platform ?? '—' }}</td>
                    <td>
                        @if($lead->response == 'Positive') <span class="status-badge status-completed">{{ $lead->response }}</span>
                        @elseif($lead->response == 'Negative') <span class="status-badge status-cancelled">{{ $lead->response }}</span>
                        @elseif($lead->response == 'Follow Up') <span class="status-badge status-pending">{{ $lead->response }}</span>
                        @else <span class="badge bg-secondary">{{ $lead->response ?? '—' }}</span>
                        @endif
                    </td>
                    <td>{{ $lead->type_of_service ?? '—' }}</td>
                    <td class="fw-semibold" style="color:var(--success);">
                        {{ $lead->amount_received ? '₨ '.number_format($lead->amount_received, 0) : '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-secondary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Delete">
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
    @if($leads->hasPages())
    <div class="pagination-wrap">
        <div>Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</div>
        {{ $leads->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
