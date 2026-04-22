<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone',
        'channel', 'status', 'project_details', 'last_contacted_at',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
    ];

    protected $attributes = [
        'status'  => 'in_progress',
        'channel' => 'whatsapp',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    // Boot method for cascade deletes
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($client) {
            // Delete all conversations and their messages (cascade handled by DB)
            $client->conversations()->delete();
        });
    }

    // Helper methods
    public function getLastMessageAttribute()
    {
        return $this->conversations()
            ->with(['messages' => fn($q) => $q->latest()->limit(1)])
            ->latest()
            ->first()
            ?->messages
            ?->first()
            ?->content;
    }

    public function getConversationCountAttribute()
    {
        return $this->conversations()->count();
    }
}