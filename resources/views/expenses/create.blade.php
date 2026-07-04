@extends('layouts.app')
@section('title', 'Add Expense')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Add Expense</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@include('partials.alerts')

<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-cash-stack me-2" style="color:var(--danger)"></i>New Expense Record</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Expense ID (Auto-generated)</label>
                <div class="auto-id-display">{{ $nextId }}</div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Expense Details</div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label">Title / Description</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">— Select —</option>
                            <option value="Fuel" {{ old('category') == 'Fuel' ? 'selected':'' }}>Fuel</option>
                            <option value="Material" {{ old('category') == 'Material' ? 'selected':'' }}>Material</option>
                            <option value="Food" {{ old('category') == 'Food' ? 'selected':'' }}>Food</option>
                            <option value="Maintenance" {{ old('category') == 'Maintenance' ? 'selected':'' }}>Maintenance</option>
                            <option value="Office" {{ old('category') == 'Office' ? 'selected':'' }}>Office</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected':'' }}>Other</option>
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="amount" class="form-label">Amount (₨)</label>
                        <input type="number" step="0.01" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="0" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected':'' }}>Pending</option>
                            <option value="Approved" {{ old('status') == 'Approved' ? 'selected':'' }}>Approved</option>
                            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected':'' }}>Rejected</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Personnel</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="requested_by" class="form-label">Requested By</label>
                        <input type="text" id="requested_by" name="requested_by" class="form-control @error('requested_by') is-invalid @enderror" value="{{ old('requested_by') }}">
                        @error('requested_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="approved_by" class="form-label">Approved By</label>
                        <input type="text" id="approved_by" name="approved_by" class="form-control @error('approved_by') is-invalid @enderror" value="{{ old('approved_by') }}">
                        @error('approved_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Notes</div>
                <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save Expense
                </button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
