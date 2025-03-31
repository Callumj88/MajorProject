@extends('layouts.app')

@section('pageTitle', 'Edit a section')

@section('content')
<div class="container">
    <h2>Edit Section - {{ $section->name }}</h2>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sections.update', $section->id) }}" method="POST" id="section-form">
        @csrf
        @method('PUT')

        {{-- Section Name --}}
        <div class="mb-3">
            <label class="form-label">Section Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $section->name) }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description', $section->description) }}">
        </div>

        {{-- Editor --}}
        <div class="mb-3">
            <label class="form-label">Partial File Content</label>

            @if (!isCompatibleHTML($section->partialFile))
                {{-- Blade/PHP content detected: Quill must not load --}}
                {!! renderCodeMirrorEditor('code-editor', $section->partialFile) !!}
            @else
                {{-- Safe HTML: both editors supported --}}
                <div class="mb-2 mt-2">
                    <button type="button" class="btn btn-primary btn-sm" id="quill-editor-rte-btn">Rich Text</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="quill-editor-code-btn">Code View</button>
                </div>

                {!! renderQuillEditor('quill-editor', $section->partialFile) !!}
                {!! renderCodeMirrorEditor('quill-editor', $section->partialFile) !!}
            @endif

            {{-- Shared hidden input that both editors update --}}
            <input type="hidden" name="partialFile" id="partialFile-hidden">
        </div>

        {{-- Disable checkbox --}}
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="disableSection" id="disableSection"
                {{ old('disableSection', $section->disableSection) ? 'checked' : '' }}>
            <label class="form-check-label" for="disableSection">Disable section?</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Section</button>
        <a href="{{ route('sections') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
