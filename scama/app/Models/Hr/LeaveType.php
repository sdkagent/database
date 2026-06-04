<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';

    protected $fillable = [
        'name',
        'code',
        'days_allowed',
        'is_paid',
        'carry_forward',
        'max_carry_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'days_allowed'   => 'integer',
            'is_paid'        => 'boolean',
            'carry_forward'  => 'boolean',
            'max_carry_days' => 'integer',
            'is_active'      => 'boolean',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type_id');
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class, 'leave_type_id');
    }
}
