<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Asan Home Service</title>
    <meta name="description" content="Asan Home Service Management System">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Luxury CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ════════════════════ SIDEBAR ════════════════════ -->
<aside class="sidebar" id="sidebar">

    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-logo">
            <div class="brand-icon">
                <i class="bi bi-house-heart-fill"></i>
            </div>
            <div class="brand-text">
                <div class="brand-name">Asan Home Service</div>
                <div class="brand-sub">Management System</div>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">

        <div class="nav-section-title">Main</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
                @php $pendingCount = \App\Models\SolarClient::pendingReminders()->count(); @endphp
                @if($pendingCount > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section-title mt-2">Modules</div>

        <div class="nav-item">
            <a href="{{ route('solar-clients.index') }}" class="nav-link {{ request()->routeIs('solar-clients.*') ? 'active' : '' }}" id="nav-solar-clients">
                <i class="bi bi-sun-fill"></i>
                <span>Solar Clients</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}" id="nav-leads">
                <i class="bi bi-megaphone-fill"></i>
                <span>Marketing Leads</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('sofa-cleaning.index') }}" class="nav-link {{ request()->routeIs('sofa-cleaning.*') ? 'active' : '' }}" id="nav-sofa">
                <i class="bi bi-diamond-fill"></i>
                <span>Sofa Cleaning</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('deep-cleaning.index') }}" class="nav-link {{ request()->routeIs('deep-cleaning.*') ? 'active' : '' }}" id="nav-deep">
                <i class="bi bi-brush-fill"></i>
                <span>Deep Cleaning</span>
            </a>
        </div>

        <div class="nav-section-title mt-2">Finance</div>

        <div class="nav-item">
            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" id="nav-expenses">
                <i class="bi bi-cash-stack"></i>
                <span>Expenses</span>
            </a>
        </div>

        <div class="nav-section-title mt-2">Analytics</div>

        <div class="nav-item">
            <a href="{{ route('customer-history.index') }}" class="nav-link {{ request()->routeIs('customer-history.*') ? 'active' : '' }}" id="nav-history">
                <i class="bi bi-person-lines-fill"></i>
                <span>Customer History</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" id="nav-reports">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Reports</span>
            </a>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="nav-section-title mt-2">Admin</div>

        <div class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" id="nav-settings">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </a>
        </div>
        @endif

    </nav>

    <!-- User Footer -->
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role_badge }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-secondary w-100 btn-sm" id="btn-logout">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </button>
        </form>
    </div>

</aside>

<!-- ════════════════════ MAIN ════════════════════ -->
<div class="main-wrapper">

    <!-- Top Navbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" onclick="toggleSidebar()" id="btn-sidebar-toggle" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <!-- Global Search -->
            <form action="{{ route('global.search') }}" method="GET" class="global-search-form" id="global-search-form">
                <div class="global-search-wrap">
                    <i class="bi bi-search global-search-icon"></i>
                    <input
                        type="text"
                        name="q"
                        id="global-search-input"
                        class="global-search-input"
                        placeholder="Search by ID, name, or contact..."
                        value="{{ request('q') }}"
                        autocomplete="off"
                    >
                </div>
            </form>
        </div>

        <div class="topbar-right">
            @php $reminderCount = \App\Models\SolarClient::pendingReminders()->count(); @endphp
            <a href="{{ route('dashboard') }}#reminders" class="topbar-btn" id="topbar-reminders" title="{{ $reminderCount }} pending reminders">
                <i class="bi bi-bell-fill"></i>
                @if($reminderCount > 0)
                    <span class="notif-dot"></span>
                @endif
            </a>

            <a href="{{ route('reports.index') }}" class="topbar-btn" id="topbar-reports" title="Reports">
                <i class="bi bi-bar-chart-line-fill"></i>
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
}

// Auto-dismiss alerts after 5s
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity .5s';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

@stack('scripts')
</body>
</html>
