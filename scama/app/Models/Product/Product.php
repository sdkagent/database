<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Commerce\CartItem;
use App\Models\Licensing\License;
use App\Models\Commerce\OrderItem;
use App\Models\Seller\SellerProfile;

#[UseFactory]
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'seller_id', 'name', 'slug', 'description', 'type', 'base_price',
        'sku', 'stock', 'download_limit', 'total_sales', 'avg_rating',
        'review_count', 'version', 'download_url', 'status', 'demo_url', 'docs_url',
    ];

    protected function casts(): array
    {
        return [
            'base_price'     => 'decimal:2',
            'stock'          => 'integer',
            'download_limit' => 'integer',
            'total_sales'    => 'integer',
            'avg_rating'     => 'decimal:2',
            'review_count'   => 'integer',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }

    public function hardwareRequirements(): HasMany
    {
        return $this->hasMany(ProductHardwareRequirement::class);
    }

    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_items', 'product_id', 'category_id');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
