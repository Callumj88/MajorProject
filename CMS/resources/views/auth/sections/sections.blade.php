@extends('layouts.app')

@section('pageTitle', 'Sections')

@section('content')
<div class="container">
    <h2 class="mb-4">Sections</h2>

    {{-- Flash message after any success (create, update, delete) --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Button to go create a new section --}}
    <a href="{{ route('sections.create') }}" class="btn btn-primary mb-3">Add New Section</a>

    {{-- Table showing all registered content sections --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Enable/Disable</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sections as $section)
                <tr>
                    <td>{{ $section->id }}</td>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->description }}</td>

                    {{-- Show visually whether section is enabled or disabled --}}
                    <td>
                        @if($section->disableSection)
                            <span class="badge bg-secondary">Disabled</span>
                        @else
                            <span class="badge bg-success">Enabled</span>
                        @endif
                    </td>

                    {{-- Checkbox to enable or disable this section on the fly --}}
                    <td>
                        <form action="{{ route('sections.toggleStatus', $section->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="checkbox" onchange="this.form.submit()" {{ $section->disableSection ? '' : 'checked' }}>
                        </form>
                    </td>

                    <td>
                        {{-- Use different button label depending on if section is simple HTML or full Blade --}}
                        @if(isCompatibleHTML($section->partialFile))
                            <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @else
                            <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-secondary btn-sm">Code View Only</a>
                        @endif

                        {{-- Only admins see delete button --}}
                        @if(Auth::check() && Auth::user()->authLevel === 1)
                            <form action="{{ route('sections.destroy', $section->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this section?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
