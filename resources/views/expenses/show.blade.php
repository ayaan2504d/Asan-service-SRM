@extends('layouts.app')
@section('title', 'Expense Details — '.$expense->expense_id)
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Expense Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
                <li class="breadcrumb-item active">{{ $expense->expense_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
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
                    <span class="id-badge lead me-2">{{ $expense->expense_id }}</span>
                    {{ $expense->title ?? 'Expense Record' }}
                </h5>
                @if($expense->status)
                    <span class="status-badge {{ $expense->status == 'Approved' ? 'status-completed' : ($expense->status == 'Rejected' ? 'status-cancelled' : 'status-pending') }}">
                        {{ $expense->status }}
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="form-section-title">Expense Info</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Title</td><td>{{ $expense->title ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Category</td><td>{{ $expense->category ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Date</td><td>{{ $expense->date?->format('d M Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Amount</td><td class="fw-bold text-danger">{{ $expense->amount ? '₨ '.number_format($expense->amount, 0) : '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="form-section-title">Personnel</h6>
                        <table class="table table-borderless table-sm" style="font-size:.875rem;">
                            <tr><td class="text-muted fw-semibold" style="width:140px;">Requested By</td><td>{{ $expense->requested_by ?? '—' }}</td></tr>
                            <tr><td class="text-muted fw-semibold">Approved By</td><td>{{ $expense->approved_by ?? '—' }}</td></tr>
                        </table>
                    </div>
                    @if($expense->notes)
                    <div class="col-12">
                        <h6 class="form-section-title">Notes</h6>
                        <p style="font-size:.875rem;color:var(--text-secondary);">{{ $expense->notes }}</p>
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
                <div class="d-flex justify-content-between mb-2"><span>Created:</span><strong>{{ $expense->created_at->format('d M Y H:i') }}</strong></div>
                <div class="d-flex justify-content-between"><span>Updated:</span><strong>{{ $expense->updated_at->format('d M Y H:i') }}</strong></div>
            </div>
        </div>
        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this record?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-trash"></i> Delete Record
            </button>
        </form>
    </div>
</div>

@endsection
