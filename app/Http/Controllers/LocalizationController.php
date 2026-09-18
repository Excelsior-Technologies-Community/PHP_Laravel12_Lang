<?php

namespace App\Http\Controllers;

use App\Models\LanguageHistory;
use App\Models\LocalizedProduct;
use Illuminate\Support\Facades\DB;

class LocalizationController extends Controller
{
    /**
     * Display localization dashboard.
     */
    public function dashboard()
    {
        $languages = [
            'en' => 'English',
            'hi' => 'Hindi',
            'gu' => 'Gujarati',
            'es' => 'Spanish',
            'fr' => 'French',
        ];

        $totalProducts = LocalizedProduct::count();

        $activeProducts = LocalizedProduct::where('is_active', true)->count();

        $totalLanguages = count($languages);

        $totalTranslations = $totalProducts * $totalLanguages;

        $languageUsage = LanguageHistory::select(
                'locale',
                'language_name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('locale', 'language_name')
            ->orderByDesc('total')
            ->get();

        $recentChanges = LanguageHistory::latest()
            ->take(10)
            ->get();

        return view('localization.dashboard', compact(
            'languages',
            'totalProducts',
            'activeProducts',
            'totalLanguages',
            'totalTranslations',
            'languageUsage',
            'recentChanges'
        ));
    }
}