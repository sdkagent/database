<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\Currency;
use App\Models\Auth\User;

#[UseFactory]
class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'employee_number',
        'department_id',
        'job_position_id',
        'reports_to',
        'hire_date',
        'termination_date',
        'employment_type',
        'status',
        'base_salary',
        'currency_id',
        'emergency_contact',
    ];

    protected function casts(): array
    {
        return [
            'hire_date'         => 'date',
            'termination_date'  => 'date',
            'employment_type'   => 'string',
            'status'            => 'string',
            'base_salary'       => 'decimal:2',
            'emergency_contact' => 'json',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    public function reportsTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'reports_to');
    }

    public function employeeContracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class, 'employee_id');
    }

    public function employeeDocuments(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id');
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class, 'employee_id');
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class, 'employee_id');
    }

    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class, 'employee_id');
    }
}
