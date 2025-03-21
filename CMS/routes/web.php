<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


//TODO: make routes work with new pages

Route::get('/', function () {
    return view('RealHome', ['pageTitle' => 'Home']);
});

Route::get('/about', function () {
    return view('about', ['pageTitle' => 'About Us']);
});

Route::get('/contact', function () {
    return view('contact', ['pageTitle' => 'Contact Us']);
});


Route::get('/services', function () {
    return view('services', ['pageTitle' => 'Services']);
});

Route::get('/testimonials', function () {
    return view('testimonials', ['pageTitle' => 'Testimonials']);
});

Route::get('/faq', function () {
    return view('faq', ['pageTitle' => 'Frequently Asked Questions']);
});

Route::get('/appointment', function () {
    return view('appointment', ['pageTitle' => 'Appointment Booking']);
});



//Auth::routes();

//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
