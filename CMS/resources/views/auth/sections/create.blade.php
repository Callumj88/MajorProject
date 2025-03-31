@extends('layouts.app')

@section('pageTitle', 'Create a section')

@section('content')
<div class="container">
    <h2>Create New Section</h2>

    {{-- Display any validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sections.store') }}" method="POST" id="section-form">
        @csrf

        {{-- Section name input --}}
        <div class="mb-3">
            <label class="form-label">Section Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        {{-- Optional description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
        </div>

        {{-- Toggle buttons between editors (both supported in create view) --}}
        <div class="mb-2 mt-2">
            <button type="button" class="btn btn-primary btn-sm" id="quill-editor-rte-btn">Rich Text</button>
            <button type="button" class="btn btn-secondary btn-sm" id="quill-editor-code-btn">Code View</button>
        </div>

        {{-- Editor containers --}}
        <div class="mb-3">
            <label class="form-label">Partial File Content</label>
            {!! renderQuillEditor('quill-editor', old('partialFile')) !!}
            {!! renderCodeMirrorEditor('quill-editor', old('partialFile')) !!}

            {{-- Single shared hidden input for both editors --}}
            <input type="hidden" name="partialFile" id="partialFile-hidden">
        </div>

        {{-- Enable/disable toggle --}}
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="disableSection" id="disableSection">
            <label class="form-check-label" for="disableSection">Disable section?</label>
        </div>

        <button type="submit" class="btn btn-primary">Create Section</button>
        <a href="{{ route('sections') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
