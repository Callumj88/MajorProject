@extends('layouts.app')

@section('pageTitle', 'User Management')

@section('content')
<div class="container">
    <h2 class="mb-4">Manage Users</h2>

    {{-- Button to go to Laravel's default registration --}}
    <a href="{{ url('/register') }}" class="btn btn-success mb-3">Create New User</a>

    {{-- User table --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>First</th>
                <th>Last</th>
                <th>Email</th>
                <th>Email Verified</th>
                <th>Admin</th>
                <th>Role</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->firstName }}</td>
                <td>{{ $user->lastName }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->email_verified_at ?? 'No' }}</td>
                <td>
                    <input type="checkbox" disabled {{ $user->authLevel == 1 ? 'checked' : '' }}>
                </td>
                <td>{{ $user->role ?? '—' }}</td>
                <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') : '—' }}</td>
                <td>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('Y-m-d') : '—' }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
