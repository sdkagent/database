<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class EmailSetting extends Model
{
    use HasFactory;

    protected $table = 'email_settings';

    protected $fillable = [
        'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
        'from_email', 'from_name', 'encryption', 'is_default',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'smtp_port'  => 'integer',
            'is_default' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
