<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalizedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_translations',
        'description_translations',
        'price',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'name_translations' => 'array',
            'description_translations' => 'array',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->name_translations[$locale]
            ?? $this->name_translations['en']
            ?? 'Unnamed Product';
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->description_translations[$locale]
            ?? $this->description_translations['en']
            ?? '';
    }

    /**
     * Translation completeness.
     */
    public function getTranslationCountAttribute(): int
    {
        $languages = [
            'en',
            'hi',
            'gu',
            'es',
            'fr',
        ];

        $translations = $this->name_translations ?? [];

        return collect($languages)
            ->filter(function ($language) use ($translations) {
                return !empty($translations[$language]);
            })
            ->count();
    }

    /**
     * Translation percentage.
     */
    public function getTranslationPercentageAttribute(): int
    {
        return (int) round(
            ($this->translation_count / 5) * 100
        );
    }

    /**
     * Translation status.
     */
    public function getTranslationStatusAttribute(): string
    {
        if ($this->translation_percentage >= 100) {
            return 'Complete';
        }

        if ($this->translation_percentage >= 60) {
            return 'Partial';
        }

        return 'Incomplete';
    }
}