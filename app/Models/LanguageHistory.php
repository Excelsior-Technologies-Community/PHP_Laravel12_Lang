<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'language_name',
        'ip_address',
        'user_agent',
    ];
}