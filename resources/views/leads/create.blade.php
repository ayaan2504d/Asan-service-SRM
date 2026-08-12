@extends('layouts.app')

@section('title', 'Add Marketing Lead')

@section('content')

<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="page-header-left">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Add Marketing Lead</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Marketing Leads</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
    <div class="mt-2 mt-sm-0">
        <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Leads
        </a>
    </div>
</div>

{{-- Duplicate Customer Alert (Hidden by default, shown via JS or Blade error) --}}
<div id="duplicateCustomerAlert" class="alert alert-warning border-warning d-none mb-4" role="alert">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-warning"></i>
            <strong id="duplicateMessage">Customer Already Exists</strong>
        </div>
        <a id="btnOpenExistingCustomer" href="#" target="_blank" class="btn btn-warning text-dark font-semibold btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Open Existing Customer
        </a>
    </div>
</div>

@if($errors->has('contact') && str_contains($errors->first('contact'), 'Customer Already Exists'))
<div class="alert alert-danger border-danger mb-4" role="alert">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <i class="bi bi-x-circle-fill fs-5 me-2"></i>
            <strong>Customer Already Exists with this Phone Number!</strong>
        </div>
        <a href="{{ route('customer-history.index', ['q' => old('contact')]) }}" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-person-lines-fill me-1"></i> Open Existing Customer
        </a>
    </div>
</div>
@endif

<form method="POST" action="{{ route('leads.store') }}" id="createLeadForm">
    @csrf

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Lead Information</h5>
        </div>

        <div class="card-body p-4">

            {{-- Row 1: Lead ID, Customer Name, Phone Number --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Lead ID <span class="text-muted">(Auto Generated)</span></label>
                    <input type="text" class="form-control bg-light font-monospace" value="{{ $nextId }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter customer full name" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="contact" id="contactInput" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" placeholder="e.g. 03001234567" required>
                    <div class="form-text">Phone number must be unique.</div>
                    @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Row 2: Address, Lead Source, Campaign Name --}}
            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Street address, city, area">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Lead Source <span class="text-danger">*</span></label>
                    <select name="lead_source" class="form-select @error('lead_source') is-invalid @enderror" required>
                        <option value="">Select Source</option>
                        <option value="Facebook" {{ old('lead_source') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="Instagram" {{ old('lead_source') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="WhatsApp" {{ old('lead_source') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Reference" {{ old('lead_source') == 'Reference' ? 'selected' : '' }}>Reference</option>
                        <option value="Other" {{ old('lead_source') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('lead_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Campaign Name</label>
                    <input type="text" name="campaign_name" class="form-control @error('campaign_name') is-invalid @enderror" value="{{ old('campaign_name') }}" placeholder="e.g. Summer Special 2026">
                    @error('campaign_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Row 3: Service Type, Status --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                    <select name="service_type" id="serviceTypeSelect" class="form-select @error('service_type') is-invalid @enderror" required>
                        <option value="">Select Service Type</option>
                        <option value="Solar Cleaning" {{ old('service_type') == 'Solar Cleaning' ? 'selected' : '' }}>Solar Cleaning</option>
                        <option value="Sofa Cleaning" {{ old('service_type') == 'Sofa Cleaning' ? 'selected' : '' }}>Sofa Cleaning</option>
                        <option value="Deep Cleaning" {{ old('service_type') == 'Deep Cleaning' ? 'selected' : '' }}>Deep Cleaning</option>
                    </select>
                    @error('service_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Pending" {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Follow Up" {{ old('status') == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
                        <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
                        <option value="Cancel" {{ old('status') == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ════ DYNAMIC FIELDS SECTION ════ --}}

            {{-- Solar Cleaning Fields --}}
            <div id="solarFields" class="card border border-warning bg-warning bg-opacity-10 rounded-3 mb-4 d-none">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-sun-fill text-warning me-2"></i>Solar Cleaning Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Number of Solar Plates</label>
                            <input type="number" name="num_plates" class="form-control" value="{{ old('num_plates') }}" placeholder="e.g. 16" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Quoted Amount (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">₨</span>
                                <input type="number" step="0.01" name="quoted_amount" class="form-control" value="{{ old('quoted_amount') }}" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sofa Cleaning Fields --}}
            <div id="sofaFields" class="card border border-info bg-info bg-opacity-10 rounded-3 mb-4 d-none">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-diamond-fill text-info me-2"></i>Sofa Cleaning Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Number of Sofas</label>
                            <input type="number" name="num_sofas" class="form-control" value="{{ old('num_sofas') }}" placeholder="e.g. 2" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sofa Type</label>
                            <select name="sofa_type" class="form-select">
                                <option value="">Select Sofa Type</option>
                                <option value="3 Seater" {{ old('sofa_type') == '3 Seater' ? 'selected' : '' }}>3 Seater</option>
                                <option value="5 Seater" {{ old('sofa_type') == '5 Seater' ? 'selected' : '' }}>5 Seater</option>
                                <option value="L Shape" {{ old('sofa_type') == 'L Shape' ? 'selected' : '' }}>L Shape</option>
                                <option value="Custom" {{ old('sofa_type') == 'Custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deep Cleaning Fields --}}
            <div id="deepFields" class="card border border-primary bg-primary bg-opacity-10 rounded-3 mb-4 d-none">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-brush-fill me-2"></i>Deep Cleaning Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">House Size</label>
                            <input type="text" name="house_size" class="form-control" value="{{ old('house_size') }}" placeholder="e.g. 10 Marla / 1 Kanal / 2000 sq ft">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Floors</label>
                            <select name="floors" class="form-select">
                                <option value="">Select Floors</option>
                                <option value="Ground" {{ old('floors') == 'Ground' ? 'selected' : '' }}>Ground</option>
                                <option value="G+1" {{ old('floors') == 'G+1' ? 'selected' : '' }}>G+1</option>
                                <option value="G+2" {{ old('floors') == 'G+2' ? 'selected' : '' }}>G+2</option>
                                <option value="G+3" {{ old('floors') == 'G+3' ? 'selected' : '' }}>G+3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Additional customer preferences or follow-up details...">{{ old('notes') }}</textarea>
            </div>

        </div>

        <div class="card-footer bg-light border-0 p-4 text-end">
            <button type="submit" id="btnSubmitLead" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-check-circle me-1"></i> Save Marketing Lead
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceTypeSelect = document.getElementById('serviceTypeSelect');
    const solarFields = document.getElementById('solarFields');
    const sofaFields = document.getElementById('sofaFields');
    const deepFields = document.getElementById('deepFields');

    function toggleServiceFields() {
        const selected = serviceTypeSelect.value;
        solarFields.classList.add('d-none');
        sofaFields.classList.add('d-none');
        deepFields.classList.add('d-none');

        if (selected === 'Solar Cleaning') {
            solarFields.classList.remove('d-none');
        } else if (selected === 'Sofa Cleaning') {
            sofaFields.classList.remove('d-none');
        } else if (selected === 'Deep Cleaning') {
            deepFields.classList.remove('d-none');
        }
    }

    serviceTypeSelect.addEventListener('change', toggleServiceFields);
    toggleServiceFields(); // Initial run

    // Duplicate Phone Check AJAX
    const contactInput = document.getElementById('contactInput');
    const duplicateAlert = document.getElementById('duplicateCustomerAlert');
    const duplicateMessage = document.getElementById('duplicateMessage');
    const btnOpenExistingCustomer = document.getElementById('btnOpenExistingCustomer');
    const btnSubmitLead = document.getElementById('btnSubmitLead');

    let debounceTimer;
    contactInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const contact = this.value.trim();

        if (contact.length < 5) {
            duplicateAlert.classList.add('d-none');
            btnSubmitLead.disabled = false;
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('leads.check-phone') }}?contact=${encodeURIComponent(contact)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        duplicateMessage.textContent = `Customer Already Exists (${data.customer_name})`;
                        btnOpenExistingCustomer.href = data.customer_url;
                        duplicateAlert.classList.remove('d-none');
                        btnSubmitLead.disabled = true;
                    } else {
                        duplicateAlert.classList.add('d-none');
                        btnSubmitLead.disabled = false;
                    }
                })
                .catch(() => {
                    duplicateAlert.classList.add('d-none');
                    btnSubmitLead.disabled = false;
                });
        }, 400);
    });
});
</script>
@endpush
