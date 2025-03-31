@extends('layouts.app')

@section('pageTitle', 'Edit Business Contact Info')

@section('content')
<div class="container">
    <h2>Edit Business Contact Info</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('contact.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="{{ old('phone', $contacts['phone']) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Primary Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $contacts['email']) }}" required>
        </div>

        <div class="mb-3">
            <label for="email2" class="form-label">Secondary Email</label>
            <input type="email" class="form-control" name="email2" value="{{ old('email2', $contacts['email2']) }}">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" name="address" required>{{ old('address', $contacts['address']) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="openingTimes" class="form-label">Opening Times</label>
            <input type="text" class="form-control" name="openingTimes" value="{{ old('openingTimes', $contacts['openingTimes']) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Contact Info</button>
    </form>
</div>
@endsection
