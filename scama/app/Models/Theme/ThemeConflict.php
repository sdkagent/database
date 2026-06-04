<?php

namespace App\Models\Theme;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ThemeConflict extends Model
{
    use HasFactory;

    protected $table = 'theme_conflicts';

    protected $fillable = [
        'theme_id', 'conflicting_plugin', 'description',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
        ];
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }
}
