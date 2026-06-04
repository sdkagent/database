<?php

namespace App\Models\Theme;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ThemeCustomization extends Model
{
    use HasFactory;

    protected $table = 'theme_customizations';

    protected $fillable = [
        'theme_id', 'user_id', 'custom_css', 'custom_js',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
