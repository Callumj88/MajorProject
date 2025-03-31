@extends('layouts.app')

{{-- Page title --}}
@section('pageTitle', 'Sections – manage reusable content blocks (including contact info)')

@section('content')
<div class="container">
    <h2>Sections</h2>

    {{-- Button to create a new section --}}
    <a href="{{ route('sections.create') }}" class="btn btn-primary mb-3">Add New Section</a>

    {{-- Flash message after success operations --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Start of sections table --}}
    <table class="table mt-3 table-bordered">
        <thead>
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
            @php
                // Reorder: show the "contact" section first
                $sections = collect($sections);
                $contactSection = $sections->firstWhere('name', 'contact');
                $otherSections = $sections->reject(fn($s) => strtolower($s->name) === 'contact');
                $orderedSections = collect();
                if ($contactSection) $orderedSections->push($contactSection);
                $orderedSections = $orderedSections->merge($otherSections);
            @endphp

            @foreach ($orderedSections as $section)
                <tr>
                    {{-- Always just display ID (not a link) --}}
                    <td>{{ $section->id }}</td>

                    {{-- Section name --}}
                    <td>{{ $section->name }}</td>

                    {{-- Section description --}}
                    <td>{{ $section->description }}</td>

                    {{-- Status badge --}}
                    <td>
                        @if($section->disableSection)
                            <span class="badge bg-secondary">Disabled</span>
                        @else
                            <span class="badge bg-success">Enabled</span>
                        @endif
                    </td>

                    {{-- Enable/disable toggle checkbox --}}
                    <td>
                        <form action="{{ route('sections.toggleStatus', $section->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input
                                type="checkbox"
                                onchange="this.form.submit()"
                                {{ $section->disableSection ? '' : 'checked' }}
                            >
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td>
                        @php $nameLower = strtolower($section->name); @endphp

                        {{-- Special case: if section is "contact", use contact edit route --}}
                        @if($nameLower === 'contact')
                            <a href="{{ route('contact.edit') }}" class="btn btn-warning btn-sm">Edit Contact</a>
                        @elseif(isCompatibleHTML($section->partialFile))
                            <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @else
                            <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-secondary btn-sm">Code View Only</a>
                        @endif

                        {{-- Admin-only delete --}}
                        @if(Auth::check() && Auth::user()->authLevel === 1)
                            <form
                                action="{{ route('sections.destroy', $section->id) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this section?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
