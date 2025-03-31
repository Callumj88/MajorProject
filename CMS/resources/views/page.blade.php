@php
    /**
     * Decode the ordered section IDs from the current page
     * These IDs determine which sections are shown and in what order.
     */
    $orderedIds = json_decode($page->sectionIDarray ?? '[]', true);
    $orderedIds = is_array($orderedIds) ? $orderedIds : [];

    /**
     * Remove the layout section ID (10) from the list of ordered IDs.
     * This is done because we will handle the layout separately below.
     */
    $orderedIds = array_filter($orderedIds, fn($id) => $id != 10);

    /**
     * Convert section list for lookup by ID.
     */
    $sectionMap = collect($sections)->keyBy('id');

    /**
     * Initialise an empty string to hold the compiled section content.
     */
    $slotContent = '';

    /**
     * Render each section in order:
     * - Skip any disabled sections
     */
    foreach ($orderedIds as $id) {
        if (isset($sectionMap[$id])) {
            $section = $sectionMap[$id];

            // Skip if the section is marked as disabled
            if (!empty($section->disableSection)) continue;

            $html = $section->partialFile;

            // Fix relative image from the html
            $html = preg_replace('/src=["\']images\//i', 'src="' . asset('images/') . '/', $html);

            // Render Blade content inside the section HTML
            $slotContent .= Blade::render($html);
        } 
    }


    $layoutSection = $sectionMap[10] ?? null;
@endphp

@if ($layoutSection)
    @php
        $layoutContent = $layoutSection->partialFile;

        echo Blade::render($layoutContent, [
            'slot' => $slotContent,
            'pageTitle' => $page->pageTitle,
            'pages' => $pages
        ]);
    @endphp
@else
    {{-- Show fallback error if layout section is missing --}}
    <div style="color: red;">Layout section missing a header and footer.</div>
@endif
