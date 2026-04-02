<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_type',
        'content',
        'metadata',
        'read_at',
    ];

    protected $casts = [
        'sender_type' => 'string',
        'metadata' => 'array',
        'read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function getSenderIconAttribute(): string
    {
        return match($this->sender_type) {
            'client' => '👤',
            'agent' => '🤖',
            'system' => '🔧',
            default => '📝',
        };
    }

    public function getIsReadAttribute(): bool
    {
        return !is_null($this->read_at);
    }

    public function getFormattedContentAttribute(): string
    {
        return nl2br($this->content);
    }
}
