<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ComplianceReport extends Model
{
    use HasFactory;

    protected $table = 'compliance_reports';

    protected $fillable = [
        'report_type', 'status', 'findings',
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
