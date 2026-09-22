<?php

use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\LocalizedProductController;
use App\Http\Controllers\TranslationEditorController;
use App\Http\Middleware\AutoDetectLocaleMiddleware;
use App\Models\LanguageHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Supported Languages
|--------------------------------------------------------------------------
*/
$supportedLocales = [
    'en' => 'English',
    'hi' => 'Hindi',
    'gu' => 'Gujarati',
    'es' => 'Spanish',
    'fr' => 'French',
];

/*
|--------------------------------------------------------------------------
| Auto-Detect Browser Locale Middleware Group
|--------------------------------------------------------------------------
*/
Route::middleware([AutoDetectLocaleMiddleware::class])->group(function () use ($supportedLocales) {


/*
|--------------------------------------------------------------------------
| Language Switcher
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) use ($supportedLocales) {

    if (array_key_exists($locale, $supportedLocales)) {

        session([
            'locale' => $locale,
        ]);

        App::setLocale($locale);

        LanguageHistory::create([
            'locale' => $locale,
            'language_name' => $supportedLocales[$locale],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    return back();

})->name('language.switch');


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    return view('welcome');

})->name('home');


/*
|--------------------------------------------------------------------------
| Multilingual Form
|--------------------------------------------------------------------------
*/

Route::get('/form', function () {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    return view('form');

})->name('form');


Route::post('/form', function (Request $request) {

    if (session()->has('locale')) {
        App::setLocale(session('locale'));
    }

    $request->validate([
        'email' => 'required|email',
        'name' => 'required|min:3',
    ]);

    return back()->with(
        'success',
        __('Form Submitted Successfully')
    );

})->name('form.submit');


/*
|--------------------------------------------------------------------------
| Product Catalog
|--------------------------------------------------------------------------
*/

Route::get(
    '/products',
    [LocalizedProductController::class, 'index']
)
    ->middleware('set.locale')
    ->name('products.index');


/*
|--------------------------------------------------------------------------
| Product Export
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/export',
    [LocalizedProductController::class, 'export']
)
    ->middleware('set.locale')
    ->name('products.export');


/*
|--------------------------------------------------------------------------
| Favorites
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/favorites',
    [LocalizedProductController::class, 'favorites']
)
    ->middleware('set.locale')
    ->name('products.favorites');


Route::post(
    '/products/{id}/favorite',
    [LocalizedProductController::class, 'toggleFavorite']
)
    ->middleware('set.locale')
    ->name('products.favorite');


/*
|--------------------------------------------------------------------------
| Product Comparison
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/compare',
    [LocalizedProductController::class, 'compare']
)
    ->middleware('set.locale')
    ->name('products.compare');


Route::post(
    '/products/{id}/compare',
    [LocalizedProductController::class, 'toggleCompare']
)
    ->middleware('set.locale')
    ->name('products.compare.toggle');


Route::post(
    '/products/compare/clear',
    [LocalizedProductController::class, 'clearCompare']
)
    ->middleware('set.locale')
    ->name('products.compare.clear');


/*
|--------------------------------------------------------------------------
| Product Details
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/{slug}',
    [LocalizedProductController::class, 'show']
)
    ->middleware('set.locale')
    ->name('products.show');


/*
|--------------------------------------------------------------------------
| Localization Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/localization-dashboard',
    [LocalizationController::class, 'dashboard']
)
    ->middleware('set.locale')
    ->name('localization.dashboard');

/*
|--------------------------------------------------------------------------
| Translation Key Editor & Scanner
|--------------------------------------------------------------------------
*/
Route::get('/localization/editor', [TranslationEditorController::class, 'index'])->name('localization.editor');
Route::post('/localization/editor/save', [TranslationEditorController::class, 'save'])->name('localization.editor.save');
Route::post('/localization/editor/add-key', [TranslationEditorController::class, 'addKey'])->name('localization.editor.add-key');

});