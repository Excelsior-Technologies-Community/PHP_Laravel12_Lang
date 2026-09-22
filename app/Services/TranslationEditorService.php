<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class TranslationEditorService
{
    protected array $supportedLocales = ['en', 'hi', 'gu', 'es', 'fr'];
    protected string $langPath;

    public function __construct()
    {
        $this->langPath = base_path('lang');
    }

    /**
     * Get all translation keys and their values across all supported languages
     */
    public function getTranslations(): array
    {
        $translations = [];
        $allKeys = [];

        // Load JSON dictionary for each locale
        foreach ($this->supportedLocales as $locale) {
            $filePath = $this->langPath . '/' . $locale . '.json';
            $data = [];

            if (File::exists($filePath)) {
                $content = File::get($filePath);
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }

            $translations[$locale] = $data;
            foreach (array_keys($data) as $k) {
                $allKeys[$k] = true;
            }
        }

        $keyList = array_keys($allKeys);
        sort($keyList);

        $matrix = [];
        $missingCount = 0;

        foreach ($keyList as $key) {
            $row = ['key' => $key, 'values' => [], 'missing' => []];
            foreach ($this->supportedLocales as $locale) {
                $val = $translations[$locale][$key] ?? null;
                $row['values'][$locale] = $val;
                if ($val === null || trim((string)$val) === '') {
                    $row['missing'][] = $locale;
                    $missingCount++;
                }
            }
            $matrix[] = $row;
        }

        return [
            'locales' => $this->supportedLocales,
            'matrix' => $matrix,
            'total_keys' => count($keyList),
            'missing_count' => $missingCount,
        ];
    }

    /**
     * Save translation updates to JSON files
     */
    public function saveTranslations(array $keyValues): bool
    {
        foreach ($this->supportedLocales as $locale) {
            $filePath = $this->langPath . '/' . $locale . '.json';
            $existing = [];

            if (File::exists($filePath)) {
                $decoded = json_decode(File::get($filePath), true);
                if (is_array($decoded)) {
                    $existing = $decoded;
                }
            }

            if (isset($keyValues[$locale]) && is_array($keyValues[$locale])) {
                foreach ($keyValues[$locale] as $key => $val) {
                    if ($val !== null && trim((string)$val) !== '') {
                        $existing[$key] = $val;
                    }
                }
            }

            ksort($existing);
            File::put(
                $filePath,
                json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
        }

        return true;
    }

    /**
     * Add a new translation key across all languages
     */
    public function addKey(string $key, array $initialValues): bool
    {
        $key = trim($key);
        if (empty($key)) {
            return false;
        }

        foreach ($this->supportedLocales as $locale) {
            $filePath = $this->langPath . '/' . $locale . '.json';
            $existing = [];

            if (File::exists($filePath)) {
                $decoded = json_decode(File::get($filePath), true);
                if (is_array($decoded)) {
                    $existing = $decoded;
                }
            }

            $val = $initialValues[$locale] ?? $key;
            $existing[$key] = $val;

            ksort($existing);
            File::put(
                $filePath,
                json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
        }

        return true;
    }
}
