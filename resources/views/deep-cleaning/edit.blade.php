@extends('layouts.app')
@section('title', 'Edit Deep Cleaning Record')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Edit Deep Cleaning Record</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('deep-cleaning.index') }}">Deep Cleaning</a></li>
                <li class="breadcrumb-item active">{{ $deepCleaning->record_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('deep-cleaning.show', $deepCleaning) }}" class="btn btn-secondary">
            <i class="bi bi-eye"></i> View
        </a>
        <a href="{{ route('deep-cleaning.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

@include('partials.alerts')

<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-pencil-fill me-2" style="color:var(--primary)"></i>
            Editing: <span class="id-badge deep">{{ $deepCleaning->record_id }}</span>
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('deep-cleaning.update', $deepCleaning) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="form-label">Record ID</label>
                <div class="auto-id-display">{{ $deepCleaning->record_id }}</div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Customer Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $deepCleaning->customer_name) }}">
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $deepCleaning->contact) }}">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $deepCleaning->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Service Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="house_size" class="form-label">House Size</label>
                        <input type="text" id="house_size" name="house_size" class="form-control @error('house_size') is-invalid @enderror" value="{{ old('house_size', $deepCleaning->house_size) }}">
                        @error('house_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="num_rooms" class="form-label">Number of Rooms</label>
                        <input type="number" id="num_rooms" name="num_rooms" class="form-control @error('num_rooms') is-invalid @enderror" value="{{ old('num_rooms', $deepCleaning->num_rooms) }}" min="0">
                        @error('num_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="num_bathrooms" class="form-label">Number of Bathrooms</label>
                        <input type="number" id="num_bathrooms" name="num_bathrooms" class="form-control @error('num_bathrooms') is-invalid @enderror" value="{{ old('num_bathrooms', $deepCleaning->num_bathrooms) }}" min="0">
                        @error('num_bathrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="booking_date" class="form-label">Booking Date</label>
                        <input type="date" id="booking_date" name="booking_date" class="form-control @error('booking_date') is-invalid @enderror" value="{{ old('booking_date', $deepCleaning->booking_date?->format('Y-m-d')) }}">
                        @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="service_date" class="form-label">Service Date</label>
                        <input type="date" id="service_date" name="service_date" class="form-control @error('service_date') is-invalid @enderror" value="{{ old('service_date', $deepCleaning->service_date?->format('Y-m-d')) }}">
                        @error('service_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Pending" {{ old('status', $deepCleaning->status) == 'Pending' ? 'selected':'' }}>Pending</option>
                            <option value="In Progress" {{ old('status', $deepCleaning->status) == 'In Progress' ? 'selected':'' }}>In Progress</option>
                            <option value="Completed" {{ old('status', $deepCleaning->status) == 'Completed' ? 'selected':'' }}>Completed</option>
                            <option value="Cancelled" {{ old('status', $deepCleaning->status) == 'Cancelled' ? 'selected':'' }}>Cancelled</option>
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
                        <input type="number" step="0.01" id="service_charges" name="service_charges" class="form-control @error('service_charges') is-invalid @enderror" value="{{ old('service_charges', $deepCleaning->service_charges) }}" min="0">
                        @error('service_charges')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="amount_received" class="form-label">Amount Received (₨)</label>
                        <input type="number" step="0.01" id="amount_received" name="amount_received" class="form-control @error('amount_received') is-invalid @enderror" value="{{ old('amount_received', $deepCleaning->amount_received) }}" min="0">
                        @error('amount_received')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Notes</div>
                <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $deepCleaning->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update Record
                </button>
                <a href="{{ route('deep-cleaning.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
