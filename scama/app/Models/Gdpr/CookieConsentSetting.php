<?php

namespace App\Models\Gdpr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class CookieConsentSetting extends Model
{
    use HasFactory;

    protected $table = 'cookie_consent_settings';

    protected $fillable = [
        'name', 'slug', 'description', 'required', 'default_granted',
    ];

    protected function casts(): array
    {
        return [
            'required'        => 'boolean',
            'default_granted' => 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }
}
