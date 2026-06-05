<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class EmployeeDocument extends Model
{
    use HasFactory;

    protected $table = 'employee_documents';

    protected $fillable = [
        'employee_id',
        'document_type',
        'file_name',
        'file_path',
        'expiry_date',
        'is_verified',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => 'string',
            'expiry_date'   => 'date',
            'is_verified'   => 'boolean',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
