@extends('layouts.app')
@section('title', 'Expenses')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Expenses</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Expenses</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Expense
    </a>
</div>

@include('partials.alerts')

{{-- Filter Bar --}}
<form method="GET" class="filter-bar">
    <div class="global-search-wrap" style="max-width:320px;">
        <i class="bi bi-search global-search-icon"></i>
        <input type="text" name="search" class="filter-input global-search-input" placeholder="Search ID, title, requested by..." value="{{ request('search') }}">
    </div>
    
    <select name="category" class="filter-input">
        <option value="">All Categories</option>
        <option value="Fuel" {{ request('category') == 'Fuel' ? 'selected' : '' }}>Fuel</option>
        <option value="Material" {{ request('category') == 'Material' ? 'selected' : '' }}>Material</option>
        <option value="Food" {{ request('category') == 'Food' ? 'selected' : '' }}>Food</option>
        <option value="Maintenance" {{ request('category') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
        <option value="Office" {{ request('category') == 'Office' ? 'selected' : '' }}>Office</option>
        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>
    
    <select name="status" class="filter-input">
        <option value="">All Statuses</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
    </select>

    <div class="d-flex align-items-center gap-2">
        <input type="date" name="from" class="filter-input" value="{{ request('from') }}" placeholder="From">
        <span class="text-muted" style="font-size:12px;">to</span>
        <input type="date" name="to" class="filter-input" value="{{ request('to') }}" placeholder="To">
    </div>

    <button type="submit" class="btn btn-primary btn-sm">
        <i class="bi bi-search"></i> Filter
    </button>
    
    @if(request('search') || request('category') || request('status') || request('from') || request('to'))
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-x-lg"></i> Clear
        </a>
    @endif
    
    <div class="ms-auto" style="font-size:.78rem;color:var(--text-muted);">
        Total Filtered: <strong style="color:var(--danger);">₨ {{ number_format($totalExpenses, 0) }}</strong>
    </div>
</form>

<div class="card">
    <div class="table-wrapper">
        @if($expenses->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="empty-state-text">No expenses found.</div>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">Add First Expense</a>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Exp ID</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td><span class="id-badge lead">{{ $expense->expense_id }}</span></td>
                    <td>{{ $expense->date ? $expense->date->format('d M Y') : '—' }}</td>
                    <td class="fw-semibold">{{ $expense->title ?? '—' }}</td>
                    <td>{{ $expense->category ?? '—' }}</td>
                    <td>{{ $expense->requested_by ?? '—' }}</td>
                    <td>
                        @if($expense->status == 'Approved')
                            <span class="status-badge status-completed">{{ $expense->status }}</span>
                        @elseif($expense->status == 'Rejected')
                            <span class="status-badge status-cancelled">{{ $expense->status }}</span>
                        @else
                            <span class="status-badge status-pending">{{ $expense->status ?? 'Pending' }}</span>
                        @endif
                    </td>
                    <td class="fw-semibold" style="color:var(--danger);">
                        {{ $expense->amount ? '₨ '.number_format($expense->amount, 0) : '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-warning btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense record?')">
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
    @if($expenses->hasPages())
    <div class="pagination-wrap">
        <div>Showing {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} of {{ $expenses->total() }}</div>
        {{ $expenses->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
