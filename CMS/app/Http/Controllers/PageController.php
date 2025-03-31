<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;

class PageController extends Controller
{
    /**
     * Show a public page dynamically based on its title (used as URL).
     *
     * This method looks for a page by its name,
     * loads its sections based on its idarray 
     * checks if they are disabled,
     * puts them into the page layout,
     * and renders the page.
     */
    public function show($pageTitle)
    {
        // Step 1: Find the page by title
        $page = DB::table('pages')
            ->whereRaw('LOWER(pageTitle) = ?', [strtolower($pageTitle)])
            ->first();

        // If page not found, return 404
        if (!$page) {
            abort(404);
        }

        // Step 2: get code using the section ID array from the page table
        $sectionIds = json_decode($page->sectionIDarray ?? '[]', true);
        $sectionIds = is_array($sectionIds) ? $sectionIds : [];

        // Step 3: Fetch all referenced sections from the database
        $sections = DB::table('sections')
            ->whereIn('id', $sectionIds)
            ->get();

        // Step 4: Pass the page and its sections to a view for layout rendering
        return view('page', [
            'page' => $page,
            'sections' => $sections,
            'pages' => DB::table('pages')->get(), 
        ]);
        
    }
}
