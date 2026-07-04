@extends('layouts.app')
@section('title', 'Add Lead')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Add Marketing Lead</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Leads</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('leads.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@include('partials.alerts')

<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-megaphone-fill me-2" style="color:var(--primary)"></i>New Lead Record</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('leads.store') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Lead ID (Auto-generated)</label>
                <div class="auto-id-display">{{ $nextId }}</div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Lead Information</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}">
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror" value="{{ old('whatsapp_number') }}">
                        @error('whatsapp_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="platform" class="form-label">Platform</label>
                        <select id="platform" name="platform" class="form-select @error('platform') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Facebook" {{ old('platform') == 'Facebook' ? 'selected':'' }}>Facebook</option>
                            <option value="Instagram" {{ old('platform') == 'Instagram' ? 'selected':'' }}>Instagram</option>
                            <option value="Google" {{ old('platform') == 'Google' ? 'selected':'' }}>Google</option>
                            <option value="Referral" {{ old('platform') == 'Referral' ? 'selected':'' }}>Referral</option>
                            <option value="Direct" {{ old('platform') == 'Direct' ? 'selected':'' }}>Direct</option>
                            <option value="Other" {{ old('platform') == 'Other' ? 'selected':'' }}>Other</option>
                        </select>
                        @error('platform')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="campaign_name" class="form-label">Campaign Name</label>
                        <input type="text" id="campaign_name" name="campaign_name" class="form-control @error('campaign_name') is-invalid @enderror" value="{{ old('campaign_name') }}">
                        @error('campaign_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="response" class="form-label">Response</label>
                        <select id="response" name="response" class="form-select @error('response') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Positive" {{ old('response') == 'Positive' ? 'selected':'' }}>Positive</option>
                            <option value="Neutral" {{ old('response') == 'Neutral' ? 'selected':'' }}>Neutral</option>
                            <option value="Negative" {{ old('response') == 'Negative' ? 'selected':'' }}>Negative</option>
                            <option value="No Answer" {{ old('response') == 'No Answer' ? 'selected':'' }}>No Answer</option>
                            <option value="Follow Up" {{ old('response') == 'Follow Up' ? 'selected':'' }}>Follow Up</option>
                        </select>
                        @error('response')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Service Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="type_of_site" class="form-label">Type of Site</label>
                        <input type="text" id="type_of_site" name="type_of_site" class="form-control @error('type_of_site') is-invalid @enderror" value="{{ old('type_of_site') }}">
                        @error('type_of_site')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="type_of_service" class="form-label">Type of Service</label>
                        <input type="text" id="type_of_service" name="type_of_service" class="form-control @error('type_of_service') is-invalid @enderror" value="{{ old('type_of_service') }}">
                        @error('type_of_service')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="size_of_area" class="form-label">Size of Area</label>
                        <input type="text" id="size_of_area" name="size_of_area" class="form-control @error('size_of_area') is-invalid @enderror" value="{{ old('size_of_area') }}">
                        @error('size_of_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="num_items" class="form-label">Number of Items</label>
                        <input type="number" id="num_items" name="num_items" class="form-control @error('num_items') is-invalid @enderror" value="{{ old('num_items') }}" min="0">
                        @error('num_items')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Booking & Payment</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="booking_date" class="form-label">Date of Booking</label>
                        <input type="date" id="booking_date" name="booking_date" class="form-control @error('booking_date') is-invalid @enderror" value="{{ old('booking_date') }}">
                        @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="service_date" class="form-label">Date of Service</label>
                        <input type="date" id="service_date" name="service_date" class="form-control @error('service_date') is-invalid @enderror" value="{{ old('service_date') }}">
                        @error('service_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="appointment_time" class="form-label">Appointment Time</label>
                        <input type="time" id="appointment_time" name="appointment_time" class="form-control @error('appointment_time') is-invalid @enderror" value="{{ old('appointment_time') }}">
                        @error('appointment_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="estimated_amount" class="form-label">Estimated Amount (₨)</label>
                        <input type="number" step="0.01" id="estimated_amount" name="estimated_amount" class="form-control @error('estimated_amount') is-invalid @enderror" value="{{ old('estimated_amount') }}" min="0">
                        @error('estimated_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="amount_received" class="form-label">Amount Received (₨)</label>
                        <input type="number" step="0.01" id="amount_received" name="amount_received" class="form-control @error('amount_received') is-invalid @enderror" value="{{ old('amount_received') }}" min="0">
                        @error('amount_received')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save Lead
                </button>
                <a href="{{ route('leads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
