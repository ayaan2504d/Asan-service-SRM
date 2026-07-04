@extends('layouts.app')
@section('title', 'Sofa Cleaning')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Sofa Cleaning</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Sofa Cleaning</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('sofa-cleaning.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Record
    </a>
</div>

@include('partials.alerts')

@php $wa = new \App\Services\WhatsAppService(); @endphp

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

    <button type="submit" class="btn btn-primary btn-sm">
        <i class="bi bi-search"></i> Filter
    </button>
    
    @if(request('search') || request('status'))
        <a href="{{ route('sofa-cleaning.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-x-lg"></i> Clear
        </a>
    @endif
    
    <div class="ms-auto" style="font-size:.78rem;color:var(--text-muted);">
        Total Revenue: <strong style="color:var(--success);">₨ {{ number_format($totalRevenue, 0) }}</strong>
    </div>
</form>

<div class="card">
    <div class="table-wrapper">
        @if($records->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-diamond"></i></div>
                <div class="empty-state-text">No sofa cleaning records found.</div>
                <a href="{{ route('sofa-cleaning.create') }}" class="btn btn-primary">Add First Record</a>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Customer Name</th>
                    <th>Contact</th>
                    <th>Service Date</th>
                    <th>Status</th>
                    <th>Amount Rcvd</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                <tr>
                    <td><span class="id-badge sofa">{{ $record->record_id }}</span></td>
                    <td class="fw-semibold">{{ $record->customer_name ?? '—' }}</td>
                    <td>{{ $record->contact ?? '—' }}</td>
                    <td>{{ $record->service_date ? $record->service_date->format('d M Y') : '—' }}</td>
                    <td>
                        @if($record->status == 'Completed')
                            <span class="status-badge status-completed">{{ $record->status }}</span>
                        @elseif($record->status == 'Pending')
                            <span class="status-badge status-pending">{{ $record->status }}</span>
                        @elseif($record->status == 'In Progress')
                            <span class="status-badge status-inprogress">{{ $record->status }}</span>
                        @elseif($record->status == 'Cancelled')
                            <span class="status-badge status-cancelled">{{ $record->status }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="fw-semibold" style="color:var(--success);">
                        {{ $record->amount_received ? '₨ '.number_format($record->amount_received, 0) : '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('sofa-cleaning.show', $record) }}" class="btn btn-sm btn-secondary btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @include('partials.whatsapp-button', [
                                'wa' => $wa,
                                'number' => $record->whatsapp_number ?: $record->contact,
                                'message' => $wa->render('sofa_cleaning_reminder_template', $record),
                            ])
                            <a href="{{ route('sofa-cleaning.edit', $record) }}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('sofa-cleaning.destroy', $record) }}" onsubmit="return confirm('Delete this record?')">
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
    @if($records->hasPages())
    <div class="pagination-wrap">
        <div>Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }}</div>
        {{ $records->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
