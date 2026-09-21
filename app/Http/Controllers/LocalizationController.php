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

        /*
        |--------------------------------------------------------------------------
        | Product statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = LocalizedProduct::count();

        $activeProducts = LocalizedProduct::where(
            'is_active',
            true
        )->count();

        $inactiveProducts = LocalizedProduct::where(
            'is_active',
            false
        )->count();

        $totalLanguages = count($languages);

        $totalTranslations = $totalProducts * $totalLanguages;

        $averagePrice = LocalizedProduct::where(
            'is_active',
            true
        )->avg('price') ?? 0;

        $minimumPrice = LocalizedProduct::where(
            'is_active',
            true
        )->min('price') ?? 0;

        $maximumPrice = LocalizedProduct::where(
            'is_active',
            true
        )->max('price') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $totalCategories = LocalizedProduct::where(
            'is_active',
            true
        )
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct('category')
            ->count('category');

        /*
        |--------------------------------------------------------------------------
        | Language usage
        |--------------------------------------------------------------------------
        */

        $languageUsage = LanguageHistory::select(
            'locale',
            'language_name',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('locale', 'language_name')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent changes
        |--------------------------------------------------------------------------
        */

        $recentChanges = LanguageHistory::latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Products by category
        |--------------------------------------------------------------------------
        */

        $categoryUsage = LocalizedProduct::select(
            'category',
            DB::raw('COUNT(*) as total')
        )
            ->where('is_active', true)
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Translation statistics
        |--------------------------------------------------------------------------
        */

        $translationComplete = 0;
        $translationPartial = 0;
        $translationIncomplete = 0;

        $allProducts = LocalizedProduct::all();

        foreach ($allProducts as $product) {
            if ($product->translation_percentage >= 100) {
                $translationComplete++;
            } elseif ($product->translation_percentage >= 60) {
                $translationPartial++;
            } else {
                $translationIncomplete++;
            }
        }

        return view(
            'localization.dashboard',
            compact(
                'languages',
                'totalProducts',
                'activeProducts',
                'inactiveProducts',
                'totalLanguages',
                'totalTranslations',
                'averagePrice',
                'minimumPrice',
                'maximumPrice',
                'totalCategories',
                'languageUsage',
                'recentChanges',
                'categoryUsage',
                'translationComplete',
                'translationPartial',
                'translationIncomplete'
            )
        );
    }
}