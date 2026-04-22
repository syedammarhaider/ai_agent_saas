<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('platform')->default('api'); // whatsapp, slack, web
            $table->string('external_id')->nullable(); // Slack channel ID, WhatsApp number, etc.
            $table->enum('status', ['open', 'closed', 'pending'])->default('open');
            $table->text('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'platform']);
            $table->index('external_id');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->enum('sender_type', ['client', 'agent'])->default('client');
            $table->string('message_id')->nullable(); // External message ID
            $table->timestamps();
            
            $table->index('conversation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};