@extends('layouts.app')

@section('pageTitle', 'Edit User')

@section('content')
<div class="container">
    <h2>Edit User - {{ $user->firstName }} {{ $user->lastName }}</h2>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Update user form --}}
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- First Name --}}
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="firstName" class="form-control" value="{{ old('firstName', $user->firstName) }}" required>
        </div>

        {{-- Last Name --}}
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="lastName" class="form-control" value="{{ old('lastName', $user->lastName) }}" required>
        </div>
              
        {{-- Role --}}
        <div class="mb-3">
            <label class="form-label">Role</label>
            <input type="text" name="role" class="form-control" value="{{ old('role', $user->role) }}">
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>



        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        {{-- Admin Checkbox --}}
        <div class="form-check form-switch mb-3">
            {{-- make sure to return a value if unchecked--}}
            <input type="hidden" name="authLevel" value="0">
            <input type="checkbox" class="form-check-input" name="authLevel" id="authLevel" value="1" {{ old('authLevel', $user->authLevel) ? 'checked' : '' }}>
            <label class="form-check-label" for="authLevel">Administrator</label>
        </div>


        {{-- Submit / Cancel --}}
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
