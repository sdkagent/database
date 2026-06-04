<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Commerce\Affiliate;
use App\Models\Licensing\ApiClient;
use App\Models\Llm\BotConversation;
use App\Models\Commerce\Cart;
use App\Models\Support\ChatMessage;
use App\Models\Support\ChatSession;
use App\Models\Commerce\FileDownload;
use App\Models\Billing\Invoice;
use App\Models\Licensing\License;
use App\Models\Commerce\Order;
use App\Models\Commerce\OrderStatusHistory;
use App\Models\Commerce\Referral;
use App\Models\Billing\Refund;
use App\Models\Seller\SellerProfile;
use App\Models\Seller\SellerVerification;
use App\Models\Support\Ticket;
use App\Models\Support\TicketMessage;
use App\Models\Product\UserSubscription;
use App\Models\Product\Wishlist;

#[UseFactory]
class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'role', 'name', 'email', 'email_verified_at', 'password', 'phone',
        'status', 'ip_whitelist', 'telegram_chat_id', 'settings', 'avatar_url',
        'last_login_at', 'locale', 'timezone',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'ip_whitelist'      => 'array',
            'settings'          => 'array',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class);
    }

    public function organizationMembers(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function sellerProfile(): HasMany
    {
        return $this->hasMany(SellerProfile::class);
    }

    public function authLogs(): HasMany
    {
        return $this->hasMany(AuthLog::class);
    }

    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function fileDownloads(): HasMany
    {
        return $this->hasMany(FileDownload::class);
    }

    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }

    public function apiClients(): HasMany
    {
        return $this->hasMany(ApiClient::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'sender_id');
    }

    public function chatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function botConversations(): HasMany
    {
        return $this->hasMany(BotConversation::class);
    }

    public function userTwoFa(): HasMany
    {
        return $this->hasMany(UserTwoFa::class);
    }

    public function referredReferrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referred_id');
    }

    public function changedOrderStatuses(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'changed_by');
    }

    public function processedRefunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'processed_by');
    }

    public function verifiedSellerVerifications(): HasMany
    {
        return $this->hasMany(SellerVerification::class, 'verified_by');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function assignedChatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class, 'assigned_to');
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }
}
