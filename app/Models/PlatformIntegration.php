<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'platform',
        'platform_identifier',
        'status',
        'config',
        'webhook_url',
        'last_sync_at',
    ];

    protected $casts = [
        'platform' => 'string',
        'status' => 'string',
        'config' => 'array',
        'webhook_url' => 'array',
        'last_sync_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getPlatformNameAttribute(): string
    {
        return match($this->platform) {
            'whatsapp' => 'WhatsApp',
            'slack' => 'Slack',
            'email' => 'Email',
            'telegram' => 'Telegram',
            'api' => 'API',
            default => $this->platform,
        };
    }

    public function getPlatformIconAttribute(): string
    {
        return match($this->platform) {
            'whatsapp' => '📱',
            'slack' => '⚡',
            'email' => '📧',
            'telegram' => '✈️',
            'api' => '🔗',
            default => '📡',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'muted',
            'error' => 'danger',
            default => 'neutral',
        };
    }
}
