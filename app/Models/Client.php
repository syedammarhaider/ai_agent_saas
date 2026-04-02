<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'avatar',
        'status',
        'platforms',
        'total_revenue',
    ];

    protected $casts = [
        'status' => 'string',
        'platforms' => 'array',
        'total_revenue' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->through('conversations');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function platformIntegrations(): HasMany
    {
        return $this->hasMany(PlatformIntegration::class);
    }

    // Accessors for computed properties
    public function getTotalConversationsAttribute(): int
    {
        return $this->conversations()->count();
    }

    public function getTotalInvoicesAttribute(): int
    {
        return $this->invoices()->count();
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->invoices()->where('status', 'paid')->sum('total_amount');
    }

    public function getPendingAmountAttribute(): float
    {
        return $this->invoices()->where('status', 'sent')->sum('total_amount');
    }

    public function getOpenTasksAttribute(): int
    {
        return $this->tasks()->where('status', 'pending')->count();
    }

    public function getOpenConversationsAttribute(): int
    {
        return $this->conversations()->where('status', 'open')->count();
    }

    public function getActivePlatformsAttribute(): array
    {
        return $this->platformIntegrations()->where('status', 'active')->pluck('platform')->toArray();
    }
}
