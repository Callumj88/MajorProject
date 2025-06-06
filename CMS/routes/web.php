<?php
use App\Http\Controllers\auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PageController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;




// Enable default Laravel auth routes
Auth::routes(['register' => false, 'verify' => true]);


/*
|--------------------------------------------------------------------------
| Regular Logged-In Protected Routes (Email not required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('auth.home', ['pageTitle' => 'Dashboard']);
    })->name('home');
    

    /*
    |--------------------------------------------------------------------------
    | Sections Management Routes
    |--------------------------------------------------------------------------
    */

    // Route to list all available sections in the system
// Route to list all sections in the system
Route::get('/sections', function () {
    $sections = getAllData('sections'); // Custom helper function to fetch section records
    return view('auth.sections.sections', ['sections' => $sections, 'pageTitle' => 'Sections']);
})->name('sections');

// Route to display the form for creating a new section
Route::get('/sections/create', function () {
    return view('auth.sections.create')->with('pageTitle', 'Create Section');
})->name('sections.create');

// Route to store a newly created section in the database
Route::post('/sections', function (Request $request) {
    // Validate incoming request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'partialFile' => 'required|string',
        'description' => 'nullable|string',
        'disableSection' => 'nullable|boolean',
    ]);

    // Save the validated data using a custom helper
    storeSection($validated);

    return redirect()->route('sections')->with('success', 'Section created successfully.');
})->name('sections.store');

// Route to display the form for editing an existing section
Route::get('/sections/{id}/edit', function ($id) {
    $section = getSectionById($id); // Custom helper to retrieve a section by ID
    return view('auth.sections.edit', ['section' => $section, 'pageTitle' => 'Edit Section']);
})->name('sections.edit');

// Route to update an existing section with new values
Route::put('/sections/{id}', function (Request $request, $id) {
    // Validate submitted input data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'partialFile' => 'required|string',
        'description' => 'nullable|string',
        'disableSection' => 'nullable|boolean',
    ]);

    // Perform the update using Query Builder
    DB::table('sections')->where('id', $id)->update([
        'name' => $validated['name'],
        'partialFile' => $validated['partialFile'],
        'description' => $validated['description'],
        'disableSection' => isset($validated['disableSection']) ? 1 : 0,
        'updated_at' => now(),
    ]);

    return redirect()->route('sections')->with('success', 'Section updated successfully.');
})->name('sections.update');

// Route to permanently delete a section from the database
Route::delete('/sections/{id}', function ($id) {
    // Only allow deletion if the user is logged in and has admin privileges
    if (!Auth::check() || Auth::user()->authLevel !== 1) {
        abort(403, 'Access denied.');
    }

    DB::table('sections')->where('id', $id)->delete();
    return redirect()->route('sections')->with('success', 'Section deleted successfully.');
})->name('sections.destroy');

// Route to toggle the enabled/disabled status of a section using a checkbox UI
Route::put('/sections/{id}/toggle-status', function ($id) {
    $section = DB::table('sections')->where('id', $id)->first();

    if (!$section) {
        return redirect()->route('sections')->with('error', 'Section not found.');
    }

    // Toggle the disableSection flag
    DB::table('sections')->where('id', $id)->update([
        'disableSection' => $section->disableSection ? 0 : 1,
        'updated_at' => now(),
    ]);

    return redirect()->route('sections')->with('success', 'Section status updated.');
})->name('sections.toggleStatus');





// ===========================================
//  staff account Management and regiistration
// =============================================

// Show user table
Route::get('/users', function () {
    $users = DB::table('users')->get();
    return view('auth.users.users', compact('users'))->with('pageTitle', 'Manage Users');
})->name('users');

// Show edit form for a user
Route::get('/users/{id}/edit', function ($id) {
    $user = DB::table('users')->where('id', $id)->first();
    return view('auth.users.edit', compact('user'))->with('pageTitle', 'Edit User');
})->name('users.edit');

// Update a user
Route::put('/users/{id}', function (Request $request, $id) {
    $validated = $request->validate([
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'email' => 'required|email|max:150',
        'authLevel' => 'required|boolean',
        'role' => 'nullable|string|max:50',
    ]);

    DB::table('users')->where('id', $id)->update([
        'firstName' => $validated['firstName'],
        'lastName' => $validated['lastName'],
        'email' => $validated['email'],
        'authLevel' => $validated['authLevel'],
        'role' => $validated['role'],
        'updated_at' => now(),
    ]);

    return redirect()->route('users')->with('success', 'User updated.');
})->name('users.update');

// Delete a user
Route::delete('/users/{id}', function ($id) {
    DB::table('users')->where('id', $id)->delete();
    return redirect()->route('users')->with('success', 'User deleted.');
})->name('users.destroy');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);






        /*
        |--------------------------------------------------------------------------
        | CMS Page Builder Routes
        |--------------------------------------------------------------------------
        */

    // View all pages
    Route::get('/pages', function () {
        $pages = DB::table('pages')->get();
        return view('auth.pages.pages', compact('pages'))->with('pageTitle', 'Pages');
    })->name('pages');

    // Show the page creation form
    Route::get('/pages/create', function () {
        $availableSections = DB::table('sections')->get();
        $layoutSection = DB::table('sections')->where('id', 10)->first();
        return view('auth.pages.create', compact('availableSections', 'layoutSection'))->with('pageTitle', 'Create Page');
    })->name('pages.create');

    // Store the new page in the database
    Route::post('/pages', function (Request $request) {
        $validated = $request->validate([
            'pageTitle' => 'required|string|max:255',
            'sectionOrder' => 'required|string'
        ]);

        $sectionOrder = json_decode($validated['sectionOrder'], true);

        DB::table('pages')->insert([
            'pageTitle' => $validated['pageTitle'],
            'sectionIDarray' => json_encode($sectionOrder),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pages')->with('success', 'Page created successfully!');
    })->name('pages.store');

    // Show the page editing form
    Route::get('/pages/{id}/edit', function ($id) {
        $page = DB::table('pages')->where('id', $id)->first();
        $availableSections = DB::table('sections')->get();
        $layoutSection = DB::table('sections')->where('id', 10)->first();
        return view('auth.pages.edit', compact('page', 'availableSections', 'layoutSection'))->with('pageTitle', 'Edit Page');
    })->name('pages.edit');

    // Update a page with new data
    Route::put('/pages/{id}', function (Request $request, $id) {
        $validated = $request->validate([
            'pageTitle' => 'required|string|max:255',
            'sectionOrder' => 'required|string'
        ]);

        $sectionOrder = json_decode($validated['sectionOrder'], true);

        DB::table('pages')->where('id', $id)->update([
            'pageTitle' => $validated['pageTitle'],
            'sectionIDarray' => json_encode($sectionOrder),
            'updated_at' => now(),
        ]);

        return redirect()->route('pages')->with('success', 'Page updated successfully!');
    })->name('pages.update');


    Route::get('/pages/live-preview', function (Request $request) {
        $sectionIDs = json_decode($request->query('sectionOrder'), true) ?? [];
        $pageTitle = $request->query('pageTitle') ?? 'Untitled Page';
    
        // Always include layout section first
        array_unshift($sectionIDs, 10);
        $sectionIDs = array_unique($sectionIDs);
    
        $sections = DB::table('sections')->whereIn('id', $sectionIDs)->get();
        $page = (object)[
            'pageTitle' => $pageTitle,
            'sectionIDarray' => json_encode($sectionIDs),
        ];
    
        return view('auth.pages.preview', [
            'page' => $page,
            'sections' => $sections,
            'pages' => DB::table('pages')->get(), // for nav
        ]);
    })->name('pages.live-preview');


    // Delete a page
    Route::delete('/pages/{id}', function ($id) {
        DB::table('pages')->where('id', $id)->delete();
        return redirect()->route('pages')->with('success', 'Page deleted successfully.');
    })->name('pages.destroy');

    // Preview the page output by rendering each section’s partial
    Route::get('/pages/preview/{id}', function ($id) {
        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) abort(404);

        $sectionIds = json_decode($page->sectionIDarray ?? '[]', true);
        $sectionIds = is_array($sectionIds) ? $sectionIds : [];

        // Always include layout section first
        array_unshift($sectionIds, 10);
        $sectionIds = array_unique($sectionIds);

        // Grab only the sections we need
        $sections = DB::table('sections')
            ->whereIn('id', $sectionIds)
            ->get();

        return view('auth.pages.preview', [
            'page' => $page,
            'sections' => $sections,
            'pages' => DB::table('pages')->get(), 
        ]);
    })->name('pages.preview');

    Route::get('/contact-details/edit', function () {
        // Load the contact info from config or hardcoded fallback
        $contacts = [
            'phone' => businessContact('phone'),
            'email' => businessContact('email'),
            'email2' => businessContact('email2'),
            'address' => businessContact('address'),
            'openingTimes' => businessContact('openingTimes'),
        ];
        return view('auth.sections.contact-edit', compact('contacts'));
    })->name('contact.edit');
    
    Route::post('/contact-details/update', function (Request $request) {
        $validated = $request->validate([
            'phone' => 'required|string|max:50',
            'email' => 'required|email',
            'email2' => 'nullable|email',
            'address' => 'required|string|max:255',
            'openingTimes' => 'required|string|max:255',
        ]);
    
        // Save to a JSON file instead (since PHP config can't be written to directly)
        file_put_contents(config_path('contact-data.json'), json_encode($validated, JSON_PRETTY_PRINT));
    
        return redirect()->route('contact.edit')->with('success', 'Contact details updated successfully.');
    })->name('contact.update');
    



/*
|--------------------------------------------------------------------------
| Appointment form and calendar
|--------------------------------------------------------------------------
*/



// Show the calendar view for staff/admins
Route::get('/calendar', function () {
    return view('auth.calendar.calendar')->with('pageTitle', 'Appointments Calendar');
})->name('calendar');

// API endpoint for calendar to fetch appointment data (used in JS calendar)
Route::get('/calendar/data', function () {
    $appointments = DB::table('appointments')
        ->leftJoin('customers', 'appointments.customerID', '=', 'customers.id')
        ->leftJoin('vehicles', 'vehicles.customerID', '=', 'customers.id')
        ->select(
            'appointments.id',
            DB::raw("DATE(appointments.appointmentDate) as date"),
            'appointments.appointmentTime',
            'appointments.message',
            DB::raw("CONCAT(customers.firstName, ' ', customers.lastName) as customerName"),
            'customers.phone',
            'customers.email',
            'vehicles.make',
            'vehicles.model',
            'vehicles.year'
        )
        ->get();

    $events = $appointments->map(function ($a) {
        $start = Carbon::parse("{$a->date} {$a->appointmentTime}");
        $end = (clone $start)->addHour();

        return [
            'id'          => $a->id,
            'title'       => $a->customerName ?? 'Unknown',
            'start'       => $start->toIso8601String(),
            'end'         => $end->toIso8601String(),
            'description' => $a->message ?? '',
            'contact'     => trim("{$a->phone} / {$a->email}"),
            'vehicle'     => trim("{$a->make} {$a->model} {$a->year}"),
        ];
    });

    return response()->json($events);
})->name('calendar.data');

// Handle storing new appointment from public form
Route::post('/appointments', function (Request $request) {
    // Full validation with specific rules for phone and date
    $validated = $request->validate([
        'first_name'        => 'required|string|max:100',
        'last_name'         => 'required|string|max:100',
        'email'             => 'required|email|max:255',
        'phone'             => ['required', 'regex:/^\d{11}$/'], // Must be exactly 11 digits
        'vehicle_make'      => 'nullable|string|max:100',
        'vehicle_model'     => 'nullable|string|max:100',
        'vehicle_year'      => 'nullable|integer|min:1900|max:' . now()->year, // Can’t be in the future
        'appointment_date'  => 'required|date|after:today', // Must be a future date
        'appointment_time'  => 'required|date_format:H:i', // Valid 24h format
        'message'           => 'nullable|string|max:1000',
    ]);

    // Step 1: Save the customer
    $customerId = DB::table('customers')->insertGetId([
        'firstName'   => $validated['first_name'],
        'lastName'    => $validated['last_name'],
        'email'       => $validated['email'],
        'phone'       => $validated['phone'],
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    // Step 2: Save vehicle if any field is filled in
    if (
        $validated['vehicle_make'] || 
        $validated['vehicle_model'] || 
        $validated['vehicle_year']
    ) {
        DB::table('vehicles')->insert([
            'customerID' => $customerId,
            'make'       => $validated['vehicle_make'],
            'model'      => $validated['vehicle_model'],
            'year'       => $validated['vehicle_year'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Step 3: Combine date and time for appointment
    $datetime = Carbon::createFromFormat('Y-m-d H:i', $validated['appointment_date'] . ' ' . $validated['appointment_time']);

    // Step 4: Save appointment
    DB::table('appointments')->insert([
        'customerID'      => $customerId,
        'appointmentDate' => $datetime, // Stored as datetime
        'appointmentTime' => $validated['appointment_time'], // Also stored separately
        'message'         => $validated['message'] ?? null,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    return redirect()->back()->with('success', 'Appointment successfully booked!');
})->name('appointments.store');

// Allow staff to delete appointment from calendar
Route::delete('/calendar/delete/{id}', function ($id) {
    DB::table('appointments')->where('id', $id)->delete();
    return response()->json(['success' => true]);
})->name('calendar.delete');

// Alternative JS-based calendar appointment creation
Route::post('/calendar/store', function (Request $request) {
    $data = json_decode($request->getContent(), true);

    // Basic validation
    if (!isset($data['title']) || !isset($data['start'])) {
        return response()->json(['success' => false, 'error' => 'Missing required fields.'], 400);
    }

    // Lookup customer by name
    $customer = DB::table('customers')
        ->where(DB::raw("CONCAT(firstName, ' ', lastName)"), $data['title'])
        ->first();

    $customerId = $customer->id ?? 0;
    $date = date('Y-m-d', strtotime($data['start']));
    $time = date('H:i:s', strtotime($data['start']));

    DB::table('appointments')->insert([
        'customerID'      => $customerId,
        'appointmentDate' => "{$date} {$time}",
        'appointmentTime' => $time,
        'message'         => $data['description'] ?? null,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    return response()->json(['success' => true]);
})->name('calendar.store');



/*
|--------------------------------------------------------------------------
| Customer & Vehicle Management Routes
|--------------------------------------------------------------------------
*/

// Show all customers in a styled table view
Route::get('/customers', function () {
    $customers = DB::table('customers')->get();
    return view('auth.customers.customers', compact('customers'))->with('pageTitle', 'Manage Customers');
})->name('customers');

// Delete a customer by ID (with confirm on view)
Route::delete('/customers/{id}', function ($id) {
    DB::table('customers')->where('id', $id)->delete();
    return redirect()->route('customers')->with('success', 'Customer deleted.');
})->name('customers.destroy');

// Show all vehicles in a styled table view
Route::get('/vehicles', function () {
    $vehicles = DB::table('vehicles')->get();
    return view('auth.vehicles.vehicles', compact('vehicles'))->with('pageTitle', 'Manage Vehicles');
})->name('vehicles');

// Delete a vehicle by ID (with confirm on view)
Route::delete('/vehicles/{id}', function ($id) {
    DB::table('vehicles')->where('id', $id)->delete();
    return redirect()->route('vehicles')->with('success', 'Vehicle deleted.');
})->name('vehicles.destroy');







});// end of auth middleware

/*
|--------------------------------------------------------------------------
| Verified Email Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    //when email smtp is paid for or a free alternative is found move all non dashboard routes here.  
    //current version only works for my email so only 1 can be authorised has ben tested and works
    
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
//before final version move all edit/create/delete/user routes here has ebhen tested and it works

});


// public views
Route::get('/', function () {
    return app(PageController::class)->show('home');
})->name('home.alias');


Route::get('/{pageTitle}', [PageController::class, 'show'])
    ->where('pageTitle', '.*');

     
