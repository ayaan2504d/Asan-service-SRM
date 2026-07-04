@extends('layouts.app')
@section('title', 'Add Solar Client')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Add Solar Client</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('solar-clients.index') }}">Solar Clients</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('solar-clients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@include('partials.alerts')

<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-sun-fill me-2" style="color:var(--primary)"></i>New Solar Client Record</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('solar-clients.store') }}" id="solar-client-create-form">
            @csrf

            {{-- Auto ID --}}
            <div class="mb-4">
                <label class="form-label">Client ID (Auto-generated)</label>
                <div class="auto-id-display" id="auto-id-display">{{ $nextId }}</div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Customer Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Customer Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Full name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" placeholder="e.g. 03001234567">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror" value="{{ old('whatsapp_number') }}" placeholder="e.g. 03001234567">
                        @error('whatsapp_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                <div class="form-section-title">Solar Package Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="num_plates" class="form-label">Number of Plates</label>
                        <input type="number" id="num_plates" name="num_plates" class="form-control @error('num_plates') is-invalid @enderror" value="{{ old('num_plates') }}" min="0">
                        @error('num_plates')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="package" class="form-label">Package</label>
                        <input type="text" id="package" name="package" class="form-control @error('package') is-invalid @enderror" value="{{ old('package') }}" placeholder="Package name/type">
                        @error('package')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">First Payment</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="amount" class="form-label">Amount (₨)</label>
                        <input type="number" step="0.01" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="0" placeholder="0.00" oninput="calcTotal()">
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="payment_mode" class="form-label">Payment Mode</label>
                        <select id="payment_mode" name="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected':'' }}>Cash</option>
                            <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected':'' }}>Bank Transfer</option>
                            <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected':'' }}>Cheque</option>
                            <option value="Online" {{ old('payment_mode') == 'Online' ? 'selected':'' }}>Online</option>
                        </select>
                        @error('payment_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="received_by" class="form-label">Received By</label>
                        <input type="text" id="received_by" name="received_by" class="form-control @error('received_by') is-invalid @enderror" value="{{ old('received_by') }}" placeholder="Staff name">
                        @error('received_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="first_visit_date" class="form-label">Date of First Visit</label>
                        <input type="date" id="first_visit_date" name="first_visit_date" class="form-control @error('first_visit_date') is-invalid @enderror" value="{{ old('first_visit_date') }}" oninput="updateReminderPreview()">
                        @error('first_visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4" id="reminder-preview-wrap" style="display:none;">
                        <label class="form-label">Auto Reminder Date</label>
                        <div class="auto-id-display" id="reminder-preview" style="color:#c05621;border-color:rgba(237,137,54,.3);background:rgba(237,137,54,.06);font-size:.82rem;"></div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Second Visit & Payment</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="second_visit_date" class="form-label">Date of Second Visit</label>
                        <input type="date" id="second_visit_date" name="second_visit_date" class="form-control @error('second_visit_date') is-invalid @enderror" value="{{ old('second_visit_date') }}" oninput="updateReminderPreview()">
                        @error('second_visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="second_amount" class="form-label">Second Amount (₨)</label>
                        <input type="number" step="0.01" id="second_amount" name="second_amount" class="form-control @error('second_amount') is-invalid @enderror" value="{{ old('second_amount') }}" min="0" placeholder="0.00" oninput="calcTotal()">
                        @error('second_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="second_payment_mode" class="form-label">Second Payment Mode</label>
                        <select id="second_payment_mode" name="second_payment_mode" class="form-select @error('second_payment_mode') is-invalid @enderror">
                            <option value="">— Select —</option>
                            <option value="Cash" {{ old('second_payment_mode') == 'Cash' ? 'selected':'' }}>Cash</option>
                            <option value="Bank Transfer" {{ old('second_payment_mode') == 'Bank Transfer' ? 'selected':'' }}>Bank Transfer</option>
                            <option value="Cheque" {{ old('second_payment_mode') == 'Cheque' ? 'selected':'' }}>Cheque</option>
                            <option value="Online" {{ old('second_payment_mode') == 'Online' ? 'selected':'' }}>Online</option>
                        </select>
                        @error('second_payment_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="second_received_by" class="form-label">Second Received By</label>
                        <input type="text" id="second_received_by" name="second_received_by" class="form-control @error('second_received_by') is-invalid @enderror" value="{{ old('second_received_by') }}" placeholder="Staff name">
                        @error('second_received_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Amount (Auto)</label>
                        <div class="auto-id-display" id="total-amount-display" style="color:var(--success);">₨ 0</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-section-title mb-0">Additional Visits</div>
                    <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#more-visits">
                        <i class="bi bi-plus-circle"></i> Add More Visits
                    </button>
                </div>
                <div class="collapse {{ old('third_visit_date') || old('third_visit_notes') || old('fourth_visit_date') || old('fourth_visit_notes') ? 'show' : '' }}" id="more-visits">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="third_visit_date" class="form-label">Date of Third Visit</label>
                            <input type="date" id="third_visit_date" name="third_visit_date" class="form-control @error('third_visit_date') is-invalid @enderror" value="{{ old('third_visit_date') }}" oninput="updateReminderPreview()">
                            @error('third_visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label for="third_visit_notes" class="form-label">Third Visit Notes</label>
                            <textarea id="third_visit_notes" name="third_visit_notes" class="form-control @error('third_visit_notes') is-invalid @enderror" rows="2">{{ old('third_visit_notes') }}</textarea>
                            @error('third_visit_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="fourth_visit_date" class="form-label">Date of Fourth Visit</label>
                            <input type="date" id="fourth_visit_date" name="fourth_visit_date" class="form-control @error('fourth_visit_date') is-invalid @enderror" value="{{ old('fourth_visit_date') }}" oninput="updateReminderPreview()">
                            @error('fourth_visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label for="fourth_visit_notes" class="form-label">Fourth Visit Notes</label>
                            <textarea id="fourth_visit_notes" name="fourth_visit_notes" class="form-control @error('fourth_visit_notes') is-invalid @enderror" rows="2">{{ old('fourth_visit_notes') }}</textarea>
                            @error('fourth_visit_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Notes</div>
                <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary" id="btn-create-solar-client">
                    <i class="bi bi-check-lg"></i> Save Client
                </button>
                <a href="{{ route('solar-clients.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function calcTotal() {
    const a = parseFloat(document.getElementById('amount').value) || 0;
    const b = parseFloat(document.getElementById('second_amount').value) || 0;
    document.getElementById('total-amount-display').textContent = '₨ ' + (a + b).toLocaleString('en-PK', {minimumFractionDigits: 0});
}

function updateReminderPreview() {
    const dates = ['first_visit_date', 'second_visit_date', 'third_visit_date', 'fourth_visit_date']
        .map(id => document.getElementById(id)?.value)
        .filter(Boolean)
        .sort();
    const val = dates[dates.length - 1];
    const wrap = document.getElementById('reminder-preview-wrap');
    const preview = document.getElementById('reminder-preview');
    if (val) {
        const d = new Date(val);
        d.setDate(d.getDate() + 13);
        const opts = { day: '2-digit', month: 'short', year: 'numeric' };
        preview.textContent = '📅 ' + d.toLocaleDateString('en-GB', opts) + ' (13 days after visit)';
        wrap.style.display = 'block';
    } else {
        wrap.style.display = 'none';
    }
}
</script>
@endpush
