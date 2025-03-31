@extends('layouts.app')

@section('pageTitle', 'Pages')


@section('content')
<div class="container">
    <h2>Pages List</h2>
    <a href="{{ route('pages.create') }}" class="btn btn-primary mb-3">Create New Page</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table displaying all CMS pages --}}
<table class="table">
    <thead>
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
        @php
            // Build a lookup of all section names by ID once for all pages
            $allSections = DB::table('sections')->pluck('name', 'id')->toArray();
        @endphp

        @foreach ($pages as $page)
            @php
                // Parse section ID array from JSON
                $order = json_decode($page->sectionIDarray ?? '[]', true);
                $order = is_array($order) ? $order : [];

                // Ensure layout section (ID 10) is first and not duplicated
                array_unshift($order, 10);
                $order = array_unique($order);

                // Map section IDs to names (fallback if not found)
                $sectionNames = array_map(fn($id) => $allSections[$id] ?? "[Missing #$id]", $order);
            @endphp

            <tr>
                <td>{{ $page->id }}</td>
                <td>{{ $page->pageTitle }}</td>
                <td>{{ implode(', ', $sectionNames) }}</td>
                <td>{{ $page->created_at }}</td>
                <td>{{ $page->updated_at }}</td>
                <td>
                    <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('pages.preview', $page->id) }}" class="btn btn-info btn-sm">Preview</a>
                    <form action="{{ route('pages.update', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
