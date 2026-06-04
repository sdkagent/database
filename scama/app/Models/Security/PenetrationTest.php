<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PenetrationTest extends Model
{
    use HasFactory;

    protected $table = 'penetration_tests';

    protected $fillable = [
        'test_type', 'target', 'status', 'findings',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'findings'   => 'json',
            'created_at' => 'datetime',
        ];
    }
}
