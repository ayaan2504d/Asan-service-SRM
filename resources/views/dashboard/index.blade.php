@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Overview</li>
            </ol>
        </nav>
    </div>
    <div>
        <span style="font-size:.82rem;color:var(--text-muted);">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->format('l, d F Y') }}
        </span>
    </div>
</div>

{{-- Session Alerts --}}
@if(session('success'))
    <div class="alert alert-success" data-auto-dismiss>
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- ════ STATS GRID ════ --}}
<div class="stats-grid">

    <div class="stat-card blue">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Solar Clients</div>
                <div class="stat-value">{{ number_format($totalSolarClients) }}</div>
                <div class="stat-sub">Total registered clients</div>
            </div>
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        </div>
        <a href="{{ route('solar-clients.index') }}" class="btn btn-sm btn-secondary mt-2">View All</a>
    </div>

    <div class="stat-card purple">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Marketing Leads</div>
                <div class="stat-value">{{ number_format($totalLeads) }}</div>
                <div class="stat-sub">Total leads captured</div>
            </div>
            <div class="stat-icon"><i class="bi bi-megaphone-fill"></i></div>
        </div>
        <a href="{{ route('leads.index') }}" class="btn btn-sm btn-secondary mt-2">View All</a>
    </div>

    <div class="stat-card teal">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Site Visits</div>
                <div class="stat-value">{{ number_format($totalSiteVisits) }}</div>
                <div class="stat-sub">Clients with tracked visits</div>
            </div>
            <div class="stat-icon"><i class="bi bi-geo-alt-fill"></i></div>
        </div>
        <a href="{{ route('solar-clients.index') }}" class="btn btn-sm btn-secondary mt-2">View All</a>
    </div>

    <div class="stat-card green">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">₨ {{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-sub">All modules combined</div>
            </div>
            <div class="stat-icon"><i class="bi bi-currency-exchange"></i></div>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary mt-2">Reports</a>
    </div>

    <div class="stat-card red">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Total Expenses</div>
                <div class="stat-value">₨ {{ number_format($totalExpenses, 0) }}</div>
                <div class="stat-sub">All recorded expenses</div>
            </div>
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
        </div>
        <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-secondary mt-2">View All</a>
    </div>

    <div class="stat-card blue">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Today's Leads</div>
                <div class="stat-value">{{ number_format($todaysLeads) }}</div>
                <div class="stat-sub">Captured today</div>
            </div>
            <div class="stat-icon"><i class="bi bi-calendar-plus"></i></div>
        </div>
        <a href="{{ route('leads.index') }}" class="btn btn-sm btn-secondary mt-2">Leads</a>
    </div>

    <div class="stat-card green">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Today's Revenue</div>
                <div class="stat-value">Rs {{ number_format($todaysRevenue, 0) }}</div>
                <div class="stat-sub">Payments recorded today</div>
            </div>
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary mt-2">Reports</a>
    </div>

    <div class="stat-card orange">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Pending Reminders</div>
                <div class="stat-value">{{ $pendingReminders->count() }}</div>
                <div class="stat-sub">2nd visit follow-ups due</div>
            </div>
            <div class="stat-icon"><i class="bi bi-bell-fill"></i></div>
        </div>
        <a href="#reminders" class="btn btn-sm btn-secondary mt-2">View</a>
    </div>

    <div class="stat-card teal">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">Upcoming Visits</div>
                <div class="stat-value">{{ $upcomingVisits->count() }}</div>
                <div class="stat-sub">Scheduled client visits</div>
            </div>
            <div class="stat-icon"><i class="bi bi-calendar-event"></i></div>
        </div>
        <a href="#upcoming-visits" class="btn btn-sm btn-secondary mt-2">View</a>
    </div>

    <div class="stat-card orange">
        <div class="stat-card-header">
            <div>
                <div class="stat-label">WhatsApp Follow-ups Due</div>
                <div class="stat-value">{{ $pendingFollowUps->count() }}</div>
                <div class="stat-sub">Ready to contact</div>
            </div>
            <div class="stat-icon"><i class="bi bi-whatsapp"></i></div>
        </div>
        <a href="#reminders" class="btn btn-sm btn-secondary mt-2">Send</a>
    </div>

</div>

{{-- ════ REMINDERS ════ --}}
@if($pendingReminders->count() > 0)
<div class="reminder-card" id="reminders">
    <div class="reminder-title">
        <i class="bi bi-bell-fill"></i>
        Pending 2nd Visit Reminders ({{ $pendingReminders->count() }})
    </div>
    @foreach($pendingReminders as $reminder)
    <div class="reminder-item">
        <div class="d-flex align-items-center gap-3">
            <span class="id-badge solar">{{ $reminder->client_id }}</span>
            <div>
                <div class="fw-semibold" style="font-size:.875rem;">{{ $reminder->name ?? 'N/A' }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);">{{ $reminder->contact ?? '—' }}</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div style="text-align:right;">
                <div style="font-size:.78rem;color:#c05621;font-weight:600;">
                    <i class="bi bi-calendar-event me-1"></i>
                    Due: {{ $reminder->reminder_date?->format('d M Y') }}
                </div>
                @if($reminder->first_visit_date)
                <div style="font-size:.72rem;color:var(--text-muted);">
                    First visit: {{ $reminder->first_visit_date->format('d M Y') }}
                </div>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('solar-clients.show', $reminder) }}" class="btn btn-sm btn-secondary" id="view-reminder-{{ $reminder->id }}">
                    <i class="bi bi-eye"></i>
                </a>
                @include('partials.whatsapp-button', [
                    'wa' => $whatsapp,
                    'number' => $reminder->whatsapp_number ?: $reminder->contact,
                    'message' => $whatsapp->render('solar_follow_up_template', $reminder),
                    'label' => 'Send WhatsApp Follow-up',
                    'class' => 'btn btn-sm btn-success',
                ])
                <form method="POST" action="{{ route('reminders.dismiss', $reminder) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning" id="dismiss-reminder-{{ $reminder->id }}" title="Dismiss reminder">
                        <i class="bi bi-check-lg"></i> Done
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($upcomingVisits->count() > 0)
<div class="card mb-4" id="upcoming-visits">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-calendar-event me-2" style="color:var(--primary)"></i>Upcoming Visits</h5>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>Customer ID</th><th>Name</th><th>Contact</th><th>Next Visit</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($upcomingVisits as $visitClient)
                @php
                    $nextVisit = collect([$visitClient->first_visit_date, $visitClient->second_visit_date, $visitClient->third_visit_date, $visitClient->fourth_visit_date])
                        ->filter(fn($date) => $date && $date->toDateString() >= now()->toDateString())
                        ->sort()
                        ->first();
                @endphp
                <tr>
                    <td><span class="id-badge solar">{{ $visitClient->client_id }}</span></td>
                    <td class="fw-semibold">{{ $visitClient->name ?? '-' }}</td>
                    <td>{{ $visitClient->contact ?? '-' }}</td>
                    <td>{{ $nextVisit?->format('d M Y') ?? '-' }}</td>
                    <td><a href="{{ route('solar-clients.show', $visitClient) }}" class="btn btn-sm btn-secondary btn-icon"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ════ TWO-COLUMN: ACTIVITIES + LATEST CUSTOMERS ════ --}}
<div class="row g-4">

    {{-- Recent Activities --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-activity me-2" style="color:var(--primary)"></i>Recent Activities</h5>
                <span style="font-size:.75rem;color:var(--text-muted);">Last 10 records across all modules</span>
            </div>
            <div class="table-wrapper">
                @if($recentActivities->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <div class="empty-state-text">No activities yet.</div>
                    </div>
                @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                        <tr>
                            <td>
                                <span class="status-badge status-inprogress" style="font-size:.68rem;">
                                    {{ $activity['type'] }}
                                </span>
                            </td>
                            <td><span class="id-badge solar">{{ $activity['id'] }}</span></td>
                            <td class="fw-semibold">{{ $activity['name'] ?? '—' }}</td>
                            <td style="color:var(--text-secondary);">{{ $activity['contact'] ?? '—' }}</td>
                            <td style="color:var(--text-muted);font-size:.78rem;">{{ $activity['date']?->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- Latest Customers --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-person-plus-fill me-2" style="color:var(--primary)"></i>Latest Solar Clients</h5>
                <a href="{{ route('solar-clients.index') }}" style="font-size:.78rem;color:var(--primary);">View all</a>
            </div>
            <div class="card-body p-0">
                @if($latestCustomers->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-person-x"></i></div>
                        <div class="empty-state-text">No clients yet.</div>
                        <a href="{{ route('solar-clients.create') }}" class="btn btn-primary btn-sm">Add First Client</a>
                    </div>
                @else
                @foreach($latestCustomers as $client)
                <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid var(--border-light);">
                    <div class="avatar-sm">{{ strtoupper(substr($client->name ?? 'N', 0, 2)) }}</div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold" style="font-size:.855rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $client->name ?? 'Unknown' }}
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $client->contact ?? '—' }}</div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div class="id-badge solar">{{ $client->client_id }}</div>
                        @if($client->amount || $client->second_amount)
                        <div style="font-size:.73rem;color:var(--success);font-weight:600;margin-top:2px;">
                            ₨ {{ number_format($client->total_amount, 0) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
