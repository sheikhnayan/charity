@extends('user.main')

@section('page-title', 'Manage Users')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users Management</h5>
                <a href="{{ route('users.manage-users.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Create User
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->website ? $user->website->name : 'N/A' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->label ?? $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('users.manage-users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('users.manage-users.destroy', $user->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
