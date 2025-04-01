@extends('layouts.app')

@section('pageTitle', 'Manage Customers - create and edit not done for this section in prototype.  only way to fill is via appointment form')

@section('content')
<div class="container">
    <h2 class="mb-3">Customers</h2>

    {{-- Show success messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Customers table --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Notes</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->firstName }}</td>
                    <td>{{ $customer->lastName }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->address ?? '-' }}</td>
                    <td>{{ $customer->notes ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->updated_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        {{-- Delete customer --}}
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
