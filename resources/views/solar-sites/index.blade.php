@extends('layouts.app')
@section('title', 'Solar Sites')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Solar Sites</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Solar Sites</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('solar-sites.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Solar Site
    </a>
</div>

@include('partials.alerts')

{{-- Filter Bar --}}
<form method="GET" class="filter-bar">
    <div class="global-search-wrap" style="max-width:320px;">
        <i class="bi bi-search global-search-icon"></i>
        <input type="text" name="search" class="filter-input global-search-input" placeholder="Search ID, name, contact..." value="{{ request('search') }}">
    </div>
    
    <select name="status" class="filter-input">
        <option value="">All Statuses</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>

    <input type="date" name="date" class="filter-input" value="{{ request('date') }}">

    <button type="submit" class="btn btn-primary btn-sm">
        <i class="bi bi-search"></i> Filter
    </button>
    
    @if(request('search') || request('status') || request('date'))
        <a href="{{ route('solar-sites.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-x-lg"></i> Clear
        </a>
    @endif
    
    <div class="ms-auto" style="font-size:.78rem;color:var(--text-muted);">
        Total Charges: <strong style="color:var(--success);">₨ {{ number_format($totalCharges, 0) }}</strong>
    </div>
</form>

<div class="card">
    <div class="table-wrapper">
        @if($sites->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-geo-alt"></i></div>
                <div class="empty-state-text">No solar sites found.</div>
                <a href="{{ route('solar-sites.create') }}" class="btn btn-primary">Add First Site</a>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Site ID</th>
                    <th>Client Name</th>
                    <th>Contact</th>
                    <th>Visit Date/Time</th>
                    <th>Status</th>
                    <th>Charges</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sites as $site)
                <tr>
                    <td><span class="id-badge site">{{ $site->site_id }}</span></td>
                    <td class="fw-semibold">{{ $site->client_name ?? '—' }}</td>
                    <td>{{ $site->contact ?? '—' }}</td>
                    <td>
                        {{ $site->visit_date ? $site->visit_date->format('d M Y') : '—' }}
                        @if($site->visit_time) <br><small class="text-muted">{{ \Carbon\Carbon::parse($site->visit_time)->format('h:i A') }}</small> @endif
                    </td>
                    <td>
                        @if($site->status == 'Completed')
                            <span class="status-badge status-completed">{{ $site->status }}</span>
                        @elseif($site->status == 'Pending')
                            <span class="status-badge status-pending">{{ $site->status }}</span>
                        @elseif($site->status == 'In Progress')
                            <span class="status-badge status-inprogress">{{ $site->status }}</span>
                        @elseif($site->status == 'Cancelled')
                            <span class="status-badge status-cancelled">{{ $site->status }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="fw-semibold" style="color:var(--success);">
                        {{ $site->charges ? '₨ '.number_format($site->charges, 0) : '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('solar-sites.show', $site) }}" class="btn btn-sm btn-secondary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('solar-sites.edit', $site) }}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('solar-sites.destroy', $site) }}" onsubmit="return confirm('Delete this site record?')">
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
    @if($sites->hasPages())
    <div class="pagination-wrap">
        <div>Showing {{ $sites->firstItem() }}–{{ $sites->lastItem() }} of {{ $sites->total() }}</div>
        {{ $sites->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
