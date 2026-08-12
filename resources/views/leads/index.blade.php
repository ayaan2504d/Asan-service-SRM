@extends('layouts.app')

@section('title', 'Marketing Leads')

@section('content')

<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="page-header-left">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-megaphone-fill me-2 text-primary"></i>Marketing Leads</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Marketing Leads</li>
            </ol>
        </nav>
    </div>
    <div class="mt-2 mt-sm-0">
        <a href="{{ route('leads.create') }}" class="btn btn-primary shadow-sm" id="btn-add-lead-index">
            <i class="bi bi-plus-lg me-1"></i> Add Marketing Lead
        </a>
    </div>
</div>

{{-- Session Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" data-auto-dismiss>
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Lead Status KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-xl">
        <a href="{{ route('leads.index', array_merge(request()->except('status'), [])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 text-center py-3 h-100 {{ !request('status') ? 'border-primary border-2' : '' }}">
                <div class="small text-muted text-uppercase fw-semibold">All Leads</div>
                <div class="fs-2 fw-bold text-primary mt-1">{{ $counts['total'] }}</div>
                <div class="small text-muted"><i class="bi bi-megaphone me-1"></i>Total</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-xl">
        <a href="{{ route('leads.index', array_merge(request()->except('status'), ['status'=>'Pending'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 text-center py-3 h-100 {{ request('status')=='Pending' ? 'border-warning border-2' : '' }}">
                <div class="small fw-semibold text-uppercase" style="color:#b08800">Pending</div>
                <div class="fs-2 fw-bold mt-1" style="color:#b08800">{{ $counts['pending'] }}</div>
                <div class="small text-muted"><i class="bi bi-hourglass-split me-1"></i>Awaiting</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-xl">
        <a href="{{ route('leads.index', array_merge(request()->except('status'), ['status'=>'Follow Up'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 text-center py-3 h-100 {{ request('status')=='Follow Up' ? 'border-info border-2' : '' }}">
                <div class="small fw-semibold text-uppercase text-info">Follow Up</div>
                <div class="fs-2 fw-bold text-info mt-1">{{ $counts['follow_up'] }}</div>
                <div class="small text-muted"><i class="bi bi-telephone-outbound me-1"></i>In Progress</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-xl">
        <a href="{{ route('leads.index', array_merge(request()->except('status'), ['status'=>'Done'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 text-center py-3 h-100 {{ request('status')=='Done' ? 'border-success border-2' : '' }}">
                <div class="small fw-semibold text-uppercase text-success">Done</div>
                <div class="fs-2 fw-bold text-success mt-1">{{ $counts['done'] }}</div>
                <div class="small text-muted"><i class="bi bi-check-circle me-1"></i>Converted</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-sm-4 col-xl">
        <a href="{{ route('leads.index', array_merge(request()->except('status'), ['status'=>'Cancel'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-3 text-center py-3 h-100 {{ request('status')=='Cancel' ? 'border-danger border-2' : '' }}">
                <div class="small fw-semibold text-uppercase text-danger">Cancelled</div>
                <div class="fs-2 fw-bold text-danger mt-1">{{ $counts['cancel'] }}</div>
                <div class="small text-muted"><i class="bi bi-x-circle me-1"></i>Not Converted</div>
            </div>
        </a>
    </div>
</div>



{{-- Filter Form --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search ID, name, phone, campaign..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-6 col-md-2">
                <select name="service_type" class="form-select bg-light">
                    <option value="">All Services</option>
                    <option value="Solar Cleaning" {{ request('service_type') == 'Solar Cleaning' ? 'selected' : '' }}>Solar Cleaning</option>
                    <option value="Sofa Cleaning" {{ request('service_type') == 'Sofa Cleaning' ? 'selected' : '' }}>Sofa Cleaning</option>
                    <option value="Deep Cleaning" {{ request('service_type') == 'Deep Cleaning' ? 'selected' : '' }}>Deep Cleaning</option>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select name="lead_source" class="form-select bg-light">
                    <option value="">All Sources</option>
                    <option value="Facebook" {{ request('lead_source') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Instagram" {{ request('lead_source') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="WhatsApp" {{ request('lead_source') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="Reference" {{ request('lead_source') == 'Reference' ? 'selected' : '' }}>Reference</option>
                    <option value="Other" {{ request('lead_source') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select name="status" class="form-select bg-light">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Follow Up" {{ request('status') == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
                    <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                    <option value="Cancel" {{ request('status') == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                </select>
            </div>

            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['search', 'service_type', 'lead_source', 'status', 'from', 'to']))
                    <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Leads Table --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        @if($leads->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3 text-muted display-4"><i class="bi bi-megaphone-fill"></i></div>
                <h5 class="text-muted">No marketing leads found.</h5>
                <a href="{{ route('leads.create') }}" class="btn btn-primary mt-2">Add First Marketing Lead</a>
            </div>
        @else
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Lead ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Service Type</th>
                    <th>Campaign</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td><span class="badge bg-dark text-white font-monospace">{{ $lead->lead_id }}</span></td>
                    <td class="fw-semibold">
                        <a href="{{ route('leads.show', $lead) }}" class="text-decoration-none text-dark">
                            {{ $lead->name }}
                        </a>
                    </td>
                    <td>
                        <a href="tel:{{ $lead->contact }}" class="text-decoration-none text-muted">
                            <i class="bi bi-telephone me-1"></i>{{ $lead->contact }}
                        </a>
                    </td>
                    <td>
                        @if($lead->effective_service_type == 'Solar Cleaning')
                            <span class="badge bg-warning text-dark"><i class="bi bi-sun me-1"></i>Solar Cleaning</span>
                        @elseif($lead->effective_service_type == 'Sofa Cleaning')
                            <span class="badge bg-info text-dark"><i class="bi bi-diamond me-1"></i>Sofa Cleaning</span>
                        @else
                            <span class="badge bg-primary"><i class="bi bi-brush me-1"></i>Deep Cleaning</span>
                        @endif
                    </td>
                    <td><span class="small text-muted">{{ $lead->campaign_name ?: '—' }}</span></td>
                    <td><span class="badge bg-light text-dark border">{{ $lead->effective_lead_source }}</span></td>
                    <td>
                        @if($lead->effective_status == 'Pending')
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                        @elseif($lead->effective_status == 'Follow Up')
                            <span class="badge bg-info text-white"><i class="bi bi-telephone-outbound me-1"></i>Follow Up</span>
                        @elseif($lead->effective_status == 'Done')
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Done</span>
                        @elseif($lead->effective_status == 'Cancel')
                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Cancel</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="{{ route('leads.show', $lead) }}">
                                        <i class="bi bi-eye text-primary me-2"></i> View Details
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('leads.edit', $lead) }}">
                                        <i class="bi bi-pencil text-warning me-2"></i> Edit Lead
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Lead Conversion</h6></li>
                                <li>
                                    <form method="POST" action="{{ route('leads.convert.solar', $lead) }}" onsubmit="return confirm('Convert this lead to Solar Client?')">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-sun-fill text-warning me-2"></i> Convert To Solar Client
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('leads.convert.sofa', $lead) }}" onsubmit="return confirm('Convert this lead to Sofa Cleaning?')">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-diamond-fill text-info me-2"></i> Convert To Sofa Cleaning
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('leads.convert.deep', $lead) }}" onsubmit="return confirm('Convert this lead to Deep Cleaning?')">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-brush-fill text-primary me-2"></i> Convert To Deep Cleaning
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i> Delete Lead
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($leads->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} of {{ $leads->total() }} leads</div>
        {{ $leads->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
