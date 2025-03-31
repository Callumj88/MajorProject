@php
    $orderedIds = json_decode($page->sectionIDarray ?? '[]', true);
    $orderedIds = is_array($orderedIds) ? $orderedIds : [];

    // Remove layout section (we inject manually below)
    $orderedIds = array_filter($orderedIds, fn($id) => $id != 10);

    $sectionMap = collect($sections)->keyBy('id');

    $slotContent = '';

    foreach ($orderedIds as $id) {
        if (isset($sectionMap[$id])) {
            $section = $sectionMap[$id];

            // Skip disabled sections
            if (!empty($section->disableSection)) continue;

            $html = $section->partialFile;

            // Fix relative paths
            $html = preg_replace('/src=["\']images\//i', 'src="' . asset('images/') . '/', $html);

            // Render Blade inside the section content to get variable calls like the contact information to work.
            $slotContent .= Blade::render($html);
        } else {
            $slotContent .= "<!-- Missing section ID $id -->\n";
        }
    }

    $layoutSection = $sectionMap[10] ?? null;
@endphp

@if ($layoutSection)
    @php
        $layoutContent = $layoutSection->partialFile;

        // Render the layout and inject already-rendered $slotContent and page title
        echo Blade::render($layoutContent, [
            'slot' => $slotContent,
            'pageTitle' => $page->pageTitle,
            'pages' => DB::table('pages')->get(),
        ]);
    @endphp
@else
    <div style="color: red;">Error: header and footer section (ID 10) not found.</div>
@endif


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Find all <a> tags and reset their hrefs
    document.querySelectorAll('a[href]').forEach(anchor => {
        anchor.setAttribute('href', '#');
    });
});
</script>

