@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Settings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>
</div>

@include('partials.alerts')

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title"><i class="bi bi-shield-lock me-2 text-primary"></i>Change Password</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
    
    @if(auth()->user()->isAdmin())
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title"><i class="bi bi-people me-2 text-primary"></i>Manage Staff Accounts</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.users.store') }}" class="mb-4 p-3 border rounded bg-light">
                    @csrf
                    <h6>Add New User</h6>
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label mb-1" style="font-size:12px;">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1" style="font-size:12px;">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1" style="font-size:12px;">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Create Staff</button>
                        </div>
                    </div>
                </form>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr>
                                <td class="fw-semibold">{{ $u->name }} @if($u->id == auth()->id()) <span class="badge bg-primary ms-1">You</span> @endif</td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($u->role) }}</span></td>
                                <td>
                                    @if($u->is_active)
                                        <span class="status-badge status-completed" style="font-size:11px;">Active</span>
                                    @else
                                        <span class="status-badge status-cancelled" style="font-size:11px;">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($u->id != auth()->id() && $u->role != 'admin')
                                    <div class="d-flex gap-1">
                                        <form method="POST" action="{{ route('settings.users.toggle', $u) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $u->is_active ? 'warning' : 'success' }} btn-icon" title="{{ $u->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="bi bi-{{ $u->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('settings.users.destroy', $u) }}" onsubmit="return confirm('Delete this user completely?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
