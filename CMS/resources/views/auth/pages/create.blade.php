@extends('layouts.app')

@section('pageTitle', 'Create a Page')


@section('content')
<div class="container">
    <h2>Create New Page</h2>

    <!-- Form for creating a new CMS page -->
    <form action="{{ route('pages.store') }}" method="POST" id="page-form">
        @csrf

        <!-- Input for the page title -->
        <div class="mb-3">
            <label for="pageTitle" class="form-label">Page Title</label>
            <input type="text" name="pageTitle" id="pageTitle" class="form-control" required>
        </div>

        <!-- Section builder with layout enforcement and ordering -->
        <div class="mb-3">
            <h4>Manage Sections</h4>
            <p class="text-muted small">
                Drag and drop sections to reorder them. The layout section (ID 10) is fixed and cannot be removed or reordered.
            </p>

            <!-- Dropdown of available sections to add -->
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

            <!-- Sortable section list (layout section is fixed) -->
            <ul id="section-list" class="list-group">
                <li class="list-group-item fixed-section d-flex justify-content-between align-items-center" data-id="{{ $layoutSection->id }}" data-name="{{ $layoutSection->name }}">
                    <span class="drag-handle flex-grow-1">☰ {{ $layoutSection->name }} (Layout)</span>
                </li>
            </ul>

            <!-- Hidden field to store the order of section IDs -->
            <input type="hidden" name="sectionOrder" id="sectionOrder">
        </div>

        <!-- Submit and modal preview buttons -->
        <button type="submit" class="btn btn-primary">Create Page</button>
        <button type="button" class="btn btn-info ms-2" id="previewBtn">Preview</button>
    </form>
</div>

<!-- Modal for real-time preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalLabel">Live Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="previewContent">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close Preview</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- SortableJS and Bootstrap JS for drag-and-drop ordering and modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sectionList = document.getElementById('section-list');
    const previewBtn = document.getElementById('previewBtn');

    // Enable SortableJS drag-and-drop
    new Sortable(sectionList, {
        animation: 150,
        handle: '.drag-handle',
        filter: '.fixed-section',
        onEnd: updateSectionOrder
    });

    // Sync section order into hidden input field
    function updateSectionOrder() {
        const ids = Array.from(sectionList.querySelectorAll('li')).map(item => item.dataset.id);
        document.getElementById('sectionOrder').value = JSON.stringify(ids);
    }

    // Add section from dropdown to list
    document.getElementById('addSectionBtn').addEventListener('click', () => {
        const select = document.getElementById('sectionSelect');
        const sectionId = select.value;
        const sectionName = select.options[select.selectedIndex]?.dataset.name;

        if (!sectionId || !sectionName) return;

        // Create new list item with drag + remove
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

    // Remove non-fixed section from list
    sectionList.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-section')) {
            const li = e.target.closest('li');
            if (!li.classList.contains('fixed-section')) {
                li.remove();
                updateSectionOrder();
            }
        }
    });

    // Generate the live preview modal from list + title
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
