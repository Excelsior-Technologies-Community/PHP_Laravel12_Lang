<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class AutoDetectLocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'hi', 'gu', 'es', 'fr'];

        if (!session()->has('locale')) {
            $detected = $this->detectBrowserLocale($request, $supportedLocales);
            session(['locale' => $detected]);
            App::setLocale($detected);
        } else {
            $locale = session('locale', 'en');
            if (in_array($locale, $supportedLocales, true)) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }

    /**
     * Detect matching locale from browser HTTP_ACCEPT_LANGUAGE header
     */
    private function detectBrowserLocale(Request $request, array $supported): string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (empty($acceptLanguage)) {
            return 'en';
        }

        // Parse Accept-Language header (e.g. "gu-IN,gu;q=0.9,hi;q=0.8,en-US;q=0.7")
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $langStr) {
            $parts = explode(';', trim($langStr));
            $localeCode = strtolower(trim($parts[0]));
            $primaryLang = explode('-', $localeCode)[0];

            if (in_array($primaryLang, $supported, true)) {
                return $primaryLang;
            }
        }

        return 'en';
    }
}
