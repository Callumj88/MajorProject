@extends('layouts.app')

@section('pageTitle', 'Manage Vehicles - create and edit not done for this section in prototype.  only way to fill is via appointment form')

@section('content')
<div class="container">
    <h2 class="mb-3">Vehicles</h2>

    {{-- Display success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Vehicle list table --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Customer ID</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->make ?? '-' }}</td>
                    <td>{{ $vehicle->model ?? '-' }}</td>
                    <td>{{ $vehicle->year ?? '-' }}</td>
                    <td>{{ $vehicle->customerID }}</td>
                    <td>{{ \Carbon\Carbon::parse($vehicle->created_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($vehicle->updated_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        {{-- Delete vehicle --}}
                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Delete this vehicle?');">
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
