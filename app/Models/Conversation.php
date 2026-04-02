<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'subject',
        'status',
        'priority',
        'platform',
        'platform_identifier',
        'last_message_at',
    ];

    protected $casts = [
        'status' => 'string',
        'priority' => 'string',
        'platform' => 'string',
        'last_message_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'info',
            'closed' => 'success',
            'escalated' => 'danger',
            default => 'neutral',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'muted',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'danger',
            default => 'neutral',
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
}
