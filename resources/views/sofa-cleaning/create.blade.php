@extends('layouts.app')
@section('title', 'Add Sofa Cleaning Record')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Add Sofa Cleaning Record</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sofa-cleaning.index') }}">Sofa Cleaning</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('sofa-cleaning.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@include('partials.alerts')

<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-diamond-fill me-2" style="color:var(--primary)"></i>New Sofa Cleaning Record</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('sofa-cleaning.store') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Record ID (Auto-generated)</label>
                <div class="auto-id-display">{{ $nextId }}</div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Customer Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}">
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror" value="{{ old('whatsapp_number') }}">
                        @error('whatsapp_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Service Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="sofa_type" class="form-label">Sofa Type</label>
                        <input type="text" id="sofa_type" name="sofa_type" class="form-control @error('sofa_type') is-invalid @enderror" value="{{ old('sofa_type') }}">
                        @error('sofa_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="num_seats" class="form-label">Number of Seats</label>
                        <input type="number" id="num_seats" name="num_seats" class="form-control @error('num_seats') is-invalid @enderror" value="{{ old('num_seats') }}" min="0">
                        @error('num_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="team" class="form-label">Team Assigned</label>
                        <input type="text" id="team" name="team" class="form-control @error('team') is-invalid @enderror" value="{{ old('team') }}">
                        @error('team')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="booking_date" class="form-label">Booking Date</label>
                        <input type="date" id="booking_date" name="booking_date" class="form-control @error('booking_date') is-invalid @enderror" value="{{ old('booking_date') }}">
                        @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="service_date" class="form-label">Service Date</label>
                        <input type="date" id="service_date" name="service_date" class="form-control @error('service_date') is-invalid @enderror" value="{{ old('service_date') }}">
                        @error('service_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected':'' }}>Pending</option>
                            <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected':'' }}>In Progress</option>
                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected':'' }}>Completed</option>
                            <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected':'' }}>Cancelled</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Payment Info</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="service_charges" class="form-label">Service Charges (₨)</label>
                        <input type="number" step="0.01" id="service_charges" name="service_charges" class="form-control @error('service_charges') is-invalid @enderror" value="{{ old('service_charges') }}" min="0">
                        @error('service_charges')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="amount_received" class="form-label">Amount Received (₨)</label>
                        <input type="number" step="0.01" id="amount_received" name="amount_received" class="form-control @error('amount_received') is-invalid @enderror" value="{{ old('amount_received') }}" min="0">
                        @error('amount_received')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    <i class="bi bi-check-lg"></i> Save Record
                </button>
                <a href="{{ route('sofa-cleaning.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
