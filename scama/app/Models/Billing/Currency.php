<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Hr\Employee;
use App\Models\Hr\EmployeeContract;
use App\Models\Procurement\PurchaseInvoice;
use App\Models\Procurement\PurchaseOrder;
use App\Models\Procurement\Supplier;
use App\Models\Procurement\SupplierPricelist;
use App\Models\Procurement\SupplierQuotation;

#[UseFactory]
class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_places',
        'is_base',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'decimal_places' => 'integer',
            'is_base'        => 'boolean',
            'is_active'      => 'boolean',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function exchangeRates(): HasMany
    {
        return $this->hasMany(ExchangeRate::class, 'from_currency_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'currency_id');
    }

    public function supplierPricelists(): HasMany
    {
        return $this->hasMany(SupplierPricelist::class, 'currency_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'currency_id');
    }

    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class, 'currency_id');
    }

    public function supplierQuotations(): HasMany
    {
        return $this->hasMany(SupplierQuotation::class, 'currency_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'currency_id');
    }

    public function employeeContracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class, 'currency_id');
    }
}
