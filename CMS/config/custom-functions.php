<?php

use Illuminate\Support\Facades\DB;

/**
 * Get the business contact information.
 *
 * @param string $key
 * @return string|null
 */
if (!function_exists('businessContact')) {
    function businessContact($key) {
        $file = config_path('contact-data.json');
        if (file_exists($file)) {
            $contacts = json_decode(file_get_contents($file), true);
        } else {
            $contacts = [
                'phone'        => '(856) 492-7602',
                'email'        => 'andreasanchez@gmail.com',
                'email2'       => 'crasauto@email.com',
                'address'      => 'Unit 16 Vale Supplier, Park, Resolven, SA11 4SR',
                'openingTimes' => 'Mon - Sat: 9:00 - 18:00',
            ];
        }

        return $contacts[$key] ?? null;
    }
}


/**
 * Retrieve all rows from a specified table.
 *
 * @param string $table
 * @return \Illuminate\Support\Collection
 */
if (!function_exists('getAllData')) {
    function getAllData($table) {
        return DB::table($table)->get();
    }
}

/**
 * Get a section by its ID.
 *
 * @param int $id
 * @return object|null
 */
if (!function_exists('getSectionById')) {
    function getSectionById($id) {
        return DB::table('sections')->where('id', $id)->first();
    }
}

/**
 * Store a new section.
 *
 * @param array $data
 * @return bool
 */
if (!function_exists('storeSection')) {
    function storeSection($data) {
        return DB::table('sections')->insert([
            'name'        => $data['name'],
            'partialFile' => $data['partialFile'],
            'description' => $data['description'] ?? null,
            'disableSection' => isset($data['disableSection']) ? 1 : 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}

/**
 * Checks if the partial file contains Blade/PHP syntax (which may break the page).
 *
 * @param string $html
 * @return bool
 */
if (!function_exists('isCompatibleHTML')) {
    function isCompatibleHTML($html) {
        // Check for Blade syntax indicators: @ or {{
        return !(preg_match('/(@\w+)|({{.*?}})/', $html));
    }
}

// Get the name of a section by its ID// Get section names by array of IDs (used for displaying readable names)
function getSectionNamesByIds($ids) {
    $sections = DB::table('sections')->whereIn('id', $ids)->pluck('name', 'id');
    return array_map(fn($id) => $sections[$id] ?? "Unknown #$id", $ids);
}



/**
 * Renders a QuillJS view.
 *
 * This function outputs the HTML and JavaScript needed to display A Quill rich text editor (default view).
 * 
 * this editor uses a seperate value to codemittor so switching views will not override the value from one the other editor as
 * quilljs messes with the code to make it compatible with itself
 *
 */
if (!function_exists('renderQuillEditor')) {
    function renderQuillEditor($editorId, $initialContent = '') {
        $cleanHTML = trim($initialContent);

        return <<<HTML
<!-- Fonts + Quill Styles -->
<link href="https://fonts.googleapis.com/css2?family=Inter&family=Aldrich&display=swap" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Quill Toolbar -->
<div id="{$editorId}-toolbar">
    <select class="ql-font">
        <option selected value="sans-serif">Sans Serif</option>
        <option value="serif">Serif</option>
        <option value="monospace">Monospace</option>
        <option value="inter">Inter</option>
        <option value="aldrich">Aldrich</option>
    </select>
    <select class="ql-size">
        <option value="small">Small</option>
        <option selected>Normal</option>
        <option value="large">Large</option>
        <option value="huge">Huge</option>
    </select>
    <button class="ql-bold"></button>
    <button class="ql-italic"></button>
    <button class="ql-underline"></button>
    <button class="ql-strike"></button>
    <select class="ql-color">
        <option value="#C3983A" selected></option>
        <option value="#A3A3A3"></option>
        <option value="#e60000"></option>
        <option value="#ff9900"></option>
        <option value="#ffff00"></option>
        <option value="#008a00"></option>
        <option value="#0066cc"></option>
        <option value="#9933ff"></option>
        <option value="#ffffff"></option>
        <option value="#000000"></option>
    </select>
    <select class="ql-background">
        <option value="#101010" selected></option>
        <option value="#C3983A"></option>
        <option value="#A3A3A3"></option>
        <option value="#ffffff"></option>
        <option value="#000000"></option>
    </select>
    <select class="ql-align"></select>
    <button class="ql-link"></button>
    <button class="ql-image"></button>
    <button class="ql-video"></button>
    <button class="ql-clean"></button>
</div>

<!-- Editor Area -->
<div id="{$editorId}" style="height: 300px;"></div>

<!-- Quill Script -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorId = "{$editorId}";
    const quill = new Quill("#" + editorId, {
        theme: 'snow',
        modules: {
            toolbar: "#" + editorId + "-toolbar"
        }
    });

    // Load initial content
    quill.clipboard.dangerouslyPasteHTML(0, `{$cleanHTML}`);

    // Track current editor mode (set globally)
    window.currentEditorMode = window.currentEditorMode || 'rte';

    // On form submit, store content in shared hidden input if Quill is active
    const form = document.getElementById("section-form");
    form.addEventListener("submit", function () {
        if (window.currentEditorMode === 'rte') {
            const hiddenInput = document.getElementById("partialFile-hidden");
            if (hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        }
    });

    // Style adjustments to match site
    const style = document.createElement('style');
    style.innerHTML = `
        :root {
            --headingcolour: #C3983A;
            --backgroundcolour: #101010;
        }

        .ql-container.ql-snow {
            background-color: var(--backgroundcolour) !important;
        }

        .ql-container.ql-snow .ql-editor {
            background-color: var(--backgroundcolour) !important;
            color: #ffffff !important;
        }
    `;
    document.head.appendChild(style);
});
</script>
HTML;
    }
}


/**
 * Render the CodeMirror editor safely without exposing raw textarea.
 * Starts hidden and is toggled via JavaScript only when needed.
 */
if (!function_exists('renderCodeMirrorEditor')) {
    function renderCodeMirrorEditor($editorId, $initialContent = '') {
        return <<<HTML
<link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>

<!-- CodeMirror Container (starts hidden) -->
<div id="{$editorId}-code-container" style="display: none;">
    <div id="{$editorId}-code-editor" style="height: 300px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById("{$editorId}-code-container");
    const wrapper = document.getElementById("{$editorId}-code-editor");
    const hiddenInput = document.getElementById("partialFile-hidden");

    const editor = CodeMirror(wrapper, {
        value: `{$initialContent}`,
        mode: "htmlmixed",
        lineNumbers: true,
        theme: "default",
        lineWrapping: true,
        viewportMargin: Infinity
    });

    // Track current mode: 'rte' or 'code'
    window.currentEditorMode = window.currentEditorMode || 'rte';

    const btnRTE = document.getElementById("{$editorId}-rte-btn");
    const btnCode = document.getElementById("{$editorId}-code-btn");
    const quill = document.getElementById("{$editorId}");
    const toolbar = document.getElementById("{$editorId}-toolbar");

    if (btnRTE && btnCode && quill && toolbar) {
        btnRTE.addEventListener("click", () => {
            window.currentEditorMode = 'rte';
            container.style.display = "none";
            quill.style.display = "block";
            toolbar.style.display = "block";
        });

        btnCode.addEventListener("click", () => {
            window.currentEditorMode = 'code';
            quill.style.display = "none";
            toolbar.style.display = "none";
            container.style.display = "block";
            setTimeout(() => editor.refresh(), 100);
        });

        // Initial state: show Quill, hide CodeMirror
        container.style.display = "none";
        quill.style.display = "block";
        toolbar.style.display = "block";
    } else {
        // Code-only mode fallback
        container.style.display = "block";
        window.currentEditorMode = 'code';
        setTimeout(() => editor.refresh(), 100);
    }

    // Save current editor content only
    const form = document.getElementById("section-form");
    form.addEventListener("submit", function () {
        if (window.currentEditorMode === 'code') {
            hiddenInput.value = editor.getValue();
        }
    });
});
</script>
HTML;
    }
}
?>
