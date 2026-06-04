<?php

namespace App\Models\Theme;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class Theme extends Model
{
    use HasFactory;

    protected $table = 'themes';

    protected $fillable = [
        'name', 'slug', 'description', 'version', 'author', 'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function settings(): HasMany
    {
        return $this->hasMany(ThemeSetting::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(ThemeAsset::class);
    }

    public function customizations(): HasMany
    {
        return $this->hasMany(ThemeCustomization::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(ThemeUsageLog::class);
    }

    public function updateLogs(): HasMany
    {
        return $this->hasMany(ThemeUpdateLog::class);
    }

    public function conflicts(): HasMany
    {
        return $this->hasMany(ThemeConflict::class);
    }

    public function generalSettings(): HasMany
    {
        return $this->hasMany(ThemeGeneralSetting::class);
    }

    public function generations(): HasMany
    {
        return $this->hasMany(ThemeGeneration::class);
    }
}
