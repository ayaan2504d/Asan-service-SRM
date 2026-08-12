@extends('layouts.app')

@section('title', 'Customer History')

@section('content')

<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="page-header-left">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-clock-history me-2 text-primary"></i>Customer History</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Customer History</li>
            </ol>
        </nav>
    </div>
</div>

{{-- Search Card --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('customer-history.index') }}" class="row g-3">
            <div class="col-md-9">
                <label for="q" class="form-label fw-semibold">Search Customer</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" id="q" class="form-control bg-light border-start-0" value="{{ $query }}" placeholder="Enter Customer Name or Phone Number..." autocomplete="off" required>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm" id="btn-search-history">
                    <i class="bi bi-person-lines-fill me-1"></i> Search History
                </button>
            </div>
        </form>
    </div>
</div>

@if(!empty($query))

    @if(!$customerInfo)
        <div class="card border-0 shadow-sm rounded-3 py-5 text-center">
            <div class="display-4 text-muted mb-2"><i class="bi bi-person-x-fill"></i></div>
            <h5 class="text-muted">No customer records found for "{{ $query }}".</h5>
            <p class="small text-muted mb-0">Try searching with a different phone number or customer name.</p>
        </div>
    @else

        {{-- Customer Info & Summary Header Cards --}}
        <div class="row g-3 mb-4">
            {{-- Customer Information --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary m-0"><i class="bi bi-person-badge-fill me-2"></i>Customer Information</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold mb-1">{{ $customerInfo['name'] }}</h4>
                        <div class="text-muted mb-2"><i class="bi bi-telephone me-1 text-primary"></i>{{ $customerInfo['phone'] }}</div>
                        <div class="small text-secondary"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $customerInfo['address'] }}</div>
                    </div>
                </div>
            </div>

            {{-- Customer Summary Metrics --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h6 class="fw-bold text-primary m-0"><i class="bi bi-pie-chart-fill me-2"></i>Customer Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 text-center">
                            <div class="col-3">
                                <div class="p-2 bg-light rounded border">
                                    <div class="small text-muted">Total Services</div>
                                    <div class="fs-4 fw-bold text-dark">{{ $summary['total_services'] }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-2 bg-success bg-opacity-10 rounded border border-success">
                                    <div class="small text-success fw-semibold">Completed</div>
                                    <div class="fs-4 fw-bold text-success">{{ $summary['completed_services'] }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-2 bg-warning bg-opacity-10 rounded border border-warning">
                                    <div class="small text-dark fw-semibold">Pending</div>
                                    <div class="fs-4 fw-bold text-dark">{{ $summary['pending_services'] }}</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-2 bg-danger bg-opacity-10 rounded border border-danger">
                                    <div class="small text-danger fw-semibold">Cancelled</div>
                                    <div class="fs-4 fw-bold text-danger">{{ $summary['cancelled_services'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Service History Timeline --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold m-0 text-dark"><i class="bi bi-bezier2 me-2 text-primary"></i>Service History Timeline</h5>
                <span class="small text-muted">Chronological history across all modules (Newest First)</span>
            </div>
            <div class="card-body p-4">
                @if($timeline->isEmpty())
                    <div class="text-center py-4 text-muted">No timeline records found.</div>
                @else
                    <div class="timeline-wrapper">
                        @foreach($timeline as $item)
                        <div class="d-flex gap-3 mb-4 position-relative">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle p-2 {{ $item['module_badge'] }} d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="bg-light flex-grow-1" style="width:2px;min-height:40px;"></div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-3 flex-grow-1">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                        <div>
                                            <span class="badge {{ $item['module_badge'] }} me-1">{{ $item['module'] }}</span>
                                            <span class="badge bg-dark font-monospace">{{ $item['record_id'] }}</span>
                                            <h6 class="fw-bold d-inline-block ms-2 mb-0">{{ $item['title'] }}</h6>
                                        </div>
                                        <div>
                                            <span class="badge {{ $item['status_badge'] }}">{{ $item['status'] }}</span>
                                        </div>
                                    </div>

                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $item['date']?->format('d M Y, h:i A') }}
                                    </div>

                                    <div class="p-2 bg-light rounded text-secondary small mb-2">
                                        {{ $item['details'] }}
                                    </div>

                                    @if(!empty($item['notes']))
                                        <div class="small text-muted italic mb-2">
                                            <i class="bi bi-journal-text me-1"></i> <em>{{ $item['notes'] }}</em>
                                        </div>
                                    @endif

                                    <div class="text-end">
                                        <a href="{{ $item['url'] }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> View Record
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    @endif

@else
    <div class="card border-0 shadow-sm rounded-3 py-5 text-center">
        <div class="display-3 text-muted mb-3"><i class="bi bi-person-search"></i></div>
        <h5 class="text-muted">Search Customer History</h5>
        <p class="small text-muted mb-0">Enter a customer's phone number or name above to view complete service history timeline across all modules.</p>
    </div>
@endif

@endsection
