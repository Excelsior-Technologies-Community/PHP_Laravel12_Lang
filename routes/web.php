<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
/*
|--------------------------------------------------------------------------
| Apply Locale From Session
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) {

    $availableLocales = ['en', 'hi', 'gu', 'es', 'fr'];

    if (in_array($locale, $availableLocales)) {
        session(['locale' => $locale]);
    }

    return back();
});


/*
|--------------------------------------------------------------------------
| Main Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    return view('welcome');
});


Route::get('/form', function () {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    return view('form');
});


Route::post('/form', function (Request $request) {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    $request->validate([
        'email' => 'required|email',
        'name'  => 'required|min:3',
    ]);

    return back()->with('success', __('Form Submitted Successfully'));
});
