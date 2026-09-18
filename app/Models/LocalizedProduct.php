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
}