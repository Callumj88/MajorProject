@extends('layouts.app')

@section('pageTitle', 'Manage Users')

@section('content')
<div class="container">
    <h2 class="mb-3">Manage Users</h2>

    {{-- Button to add a new user --}}
    <a href="{{ route('register') }}" class="btn btn-primary mb-3">Add New User</a>

    {{-- Success message after update/delete --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table for listing all users --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Auth Level</th>
                <th>Role</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th> {{-- Edit/Delete --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    {{-- Unique user ID --}}
                    <td>{{ $user->id }}</td>

                    {{-- User's first and last name --}}
                    <td>{{ $user->firstName }}</td>
                    <td>{{ $user->lastName }}</td>

                    {{-- Contact email --}}
                    <td>{{ $user->email }}</td>

                    {{-- Auth level (1 = admin, 0 = normal user) --}}
                    <td>
                        @if ($user->authLevel)
                            <span class="badge bg-primary">Admin</span>
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </td>

                    {{-- Optional role title --}}
                    <td>{{ $user->role ?? '-' }}</td>

                    {{-- Timestamps --}}
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('Y-m-d H:i') }}</td>

                    {{-- Action buttons --}}
                    <td>
                        {{-- Edit link --}}
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        {{-- Block deleting own account with a ID match --}}
                        @if(auth()->id() !== $user->id)
                            {{-- Delete and confirmation prompt --}}
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        @else
                            {{-- Prevent admin self-deletion by blocking button --}}
                            <span class="badge bg-info text-dark">You</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
