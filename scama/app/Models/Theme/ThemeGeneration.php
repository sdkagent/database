<?php

namespace App\Models\Theme;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ThemeGeneration extends Model
{
    use HasFactory;

    protected $table = 'theme_generations';

    protected $fillable = [
        'theme_id', 'user_id', 'theme_name', 'theme_description',
        'theme_version', 'theme_variant', 'theme_color_scheme',
        'theme_layout', 'theme_font', 'theme_customization',
        'theme_preview_url', 'theme_download_url', 'theme_screenshot_url',
        'theme_markdown_description', 'theme_template_variables',
        'style', 'complexity', 'source', 'generation_method',
        'priority', 'estimated_completion_time', 'actual_completion_time',
        'progress', 'quality_score', 'status', 'generated_files',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'theme_customization'       => 'json',
            'theme_template_variables'  => 'json',
            'generated_files'           => 'json',
            'estimated_completion_time' => 'integer',
            'actual_completion_time'    => 'integer',
            'progress'                  => 'integer',
            'created_at'                => 'datetime',
            'updated_at'                => 'datetime',
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

    public function logs(): HasMany
    {
        return $this->hasMany(ThemeGenerationLog::class, 'generation_id');
    }
}
