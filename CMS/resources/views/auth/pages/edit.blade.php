@extends('layouts.app')

@section('pageTitle', 'Edit a page')

@section('content')
<div class="container">
    <h2>Edit Page</h2>

    <!-- Form to update an existing page -->
    <form action="{{ route('pages.update', $page->id) }}" method="POST" id="page-form">
        @csrf
        @method('PUT')

        <!-- Editable page title input -->
        <div class="mb-3">
            <label for="pageTitle" class="form-label">Page Title</label>
            <input type="text" name="pageTitle" id="pageTitle" class="form-control" value="{{ old('pageTitle', $page->pageTitle) }}" required>
        </div>

        <!-- Section reordering and management UI -->
        <div class="mb-3">
            <h4>Manage Sections</h4>
            <p class="text-muted small">Drag and drop sections to reorder them. The layout section (ID 10) is fixed and cannot be removed or reordered.</p>

            <!-- Dropdown to add sections -->
            <div class="mb-2">
                <label for="sectionSelect" class="form-label">Add Section:</label>
                <select id="sectionSelect" class="form-select">
                    <option value="">Select a section</option>
                    @foreach($availableSections as $section)
                        @if($section->id != 10)
                            <option value="{{ $section->id }}" data-name="{{ $section->name }}">{{ $section->name }}</option>
                        @endif
                    @endforeach
                </select>
                <button type="button" class="btn btn-secondary mt-2" id="addSectionBtn">Add Section</button>
            </div>

            <!-- Sortable list of current sections -->
            <ul id="section-list" class="list-group">
                <li class="list-group-item fixed-section d-flex justify-content-between align-items-center" data-id="{{ $layoutSection->id }}" data-name="{{ $layoutSection->name }}">
                    <span class="drag-handle flex-grow-1">☰ {{ $layoutSection->name }} (Layout)</span>
                </li>

                @php
                    $ordered = json_decode($page->sectionIDarray ?? '[]', true);
                    $ordered = is_array($ordered) ? $ordered : [];
                @endphp

                @foreach($ordered as $id)
                    @if($id != 10)
                        @php $section = $availableSections->firstWhere('id', $id); @endphp
                        @if($section)
                            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $section->id }}" data-name="{{ $section->name }}">
                                <span class="drag-handle flex-grow-1">☰ {{ $section->name }}</span>
                                <button type="button" class="btn btn-danger btn-sm remove-section">Remove</button>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>

            <!-- Stores JSON array of current section IDs -->
            <input type="hidden" name="sectionOrder" id="sectionOrder">
        </div>

        <!-- Submit and preview controls -->
        <button type="submit" class="btn btn-primary">Update Page</button>
        <button type="button" class="btn btn-info ms-2" id="previewBtn">Preview</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sectionList = document.getElementById('section-list');
    const previewBtn = document.getElementById('previewBtn');

    new Sortable(sectionList, {
        animation: 150,
        handle: '.drag-handle',
        filter: '.fixed-section',
        onEnd: updateSectionOrder
    });

    function updateSectionOrder() {
        const ids = Array.from(sectionList.querySelectorAll('li'))
                         .map(item => item.dataset.id);
        document.getElementById('sectionOrder').value = JSON.stringify(ids);
    }

    document.getElementById('addSectionBtn').addEventListener('click', () => {
        const select = document.getElementById('sectionSelect');
        const sectionId = select.value;
        const sectionName = select.options[select.selectedIndex]?.dataset.name || select.options[select.selectedIndex].text;

        if (!sectionId || !sectionName) return;

        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.setAttribute('data-id', sectionId);
        li.setAttribute('data-name', sectionName);
        li.innerHTML = `
            <span class="drag-handle flex-grow-1">☰ ${sectionName}</span>
            <button type="button" class="btn btn-danger btn-sm remove-section">Remove</button>
        `;
        sectionList.appendChild(li);
        select.value = '';
        updateSectionOrder();
    });

    sectionList.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-section')) {
            const li = e.target.closest('li');
            if (!li.classList.contains('fixed-section')) {
                li.remove();
                updateSectionOrder();
            }
        }
    });

    previewBtn.addEventListener('click', () => {
        const pageTitle = document.getElementById('pageTitle').value.trim() || 'Untitled Page';
        const items = Array.from(sectionList.querySelectorAll('li'));
        const sectionIDs = items.map(item => item.dataset.id);

        const query = new URLSearchParams({
            pageTitle: pageTitle,
            sectionOrder: JSON.stringify(sectionIDs)
        });

        window.open(`/pages/live-preview?${query.toString()}`, '_blank');
    });

    updateSectionOrder();
});
</script>
@endpush
