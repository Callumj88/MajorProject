@extends('layouts.app')

@section('pageTitle', 'Pages')

@section('content')
<div class="container">
    <h2 class="mb-4">Pages</h2>

    {{-- Success message, shown after creating or updating a page --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Button to navigate to the page creation screen --}}
    <a href="{{ route('pages.create') }}" class="btn btn-primary mb-3">Create New Page</a>

    {{-- Main table listing all pages in the system --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Page Title</th>
                <th>Sections</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                @php
                    // Decode section ID array and get actual section names to display
                    $sectionIds = json_decode($page->sectionIDarray ?? '[]', true);
                    $sectionNames = DB::table('sections')->whereIn('id', $sectionIds)->pluck('name')->toArray();
                @endphp
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>{{ $page->pageTitle }}</td>

                    {{-- Display each section as a colored badge --}}
                    <td>
                        @foreach ($sectionNames as $name)
                            <span class="badge bg-info text-dark">{{ $name }}</span>
                        @endforeach
                    </td>

                    <td>{{ \Carbon\Carbon::parse($page->created_at)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($page->updated_at)->format('Y-m-d') }}</td>
                    <td>
                        {{-- Edit page --}}
                        <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        {{-- Delete page with confirmation --}}
                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                        {{-- Preview the final output of this page --}}
                        <a href="{{ route('pages.preview', $page->id) }}" class="btn btn-info btn-sm">Preview</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
