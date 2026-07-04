@extends('layouts.app')
@section('title', 'Add Solar Site')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Add Solar Site</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('solar-sites.index') }}">Solar Sites</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('solar-sites.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@include('partials.alerts')

<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-geo-alt-fill me-2" style="color:var(--primary)"></i>New Solar Site Record</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('solar-sites.store') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Site ID (Auto-generated)</label>
                <div class="auto-id-display">{{ $nextId }}</div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Client Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="client_name" class="form-label">Client Name</label>
                        <input type="text" id="client_name" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name') }}" placeholder="Full name">
                        @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" placeholder="e.g. 03001234567">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Full address">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="area" class="form-label">Area</label>
                        <input type="text" id="area" name="area" class="form-control @error('area') is-invalid @enderror" value="{{ old('area') }}" placeholder="City / Area">
                        @error('area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Visit Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="num_plates" class="form-label">Number of Plates</label>
                        <input type="number" id="num_plates" name="num_plates" class="form-control @error('num_plates') is-invalid @enderror" value="{{ old('num_plates') }}" min="0">
                        @error('num_plates')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="visit_date" class="form-label">Visit Date</label>
                        <input type="date" id="visit_date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" value="{{ old('visit_date') }}">
                        @error('visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="visit_time" class="form-label">Visit Time</label>
                        <input type="time" id="visit_time" name="visit_time" class="form-control @error('visit_time') is-invalid @enderror" value="{{ old('visit_time') }}">
                        @error('visit_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="team" class="form-label">Team Assigned</label>
                        <input type="text" id="team" name="team" class="form-control @error('team') is-invalid @enderror" value="{{ old('team') }}">
                        @error('team')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    <div class="col-md-4">
                        <label for="charges" class="form-label">Charges (₨)</label>
                        <input type="number" step="0.01" id="charges" name="charges" class="form-control @error('charges') is-invalid @enderror" value="{{ old('charges') }}" min="0" placeholder="0.00">
                        @error('charges')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="payment_type" class="form-label">Payment Type</label>
                        <select id="payment_type" name="payment_type" class="form-select @error('payment_type') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Cash" {{ old('payment_type') == 'Cash' ? 'selected':'' }}>Cash</option>
                            <option value="Bank Transfer" {{ old('payment_type') == 'Bank Transfer' ? 'selected':'' }}>Bank Transfer</option>
                            <option value="Cheque" {{ old('payment_type') == 'Cheque' ? 'selected':'' }}>Cheque</option>
                            <option value="Online" {{ old('payment_type') == 'Online' ? 'selected':'' }}>Online</option>
                        </select>
                        @error('payment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="received_by" class="form-label">Received By</label>
                        <input type="text" id="received_by" name="received_by" class="form-control @error('received_by') is-invalid @enderror" value="{{ old('received_by') }}">
                        @error('received_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Save Site Record
                </button>
                <a href="{{ route('solar-sites.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
