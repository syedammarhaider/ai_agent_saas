<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platform_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('platform', ['whatsapp', 'slack', 'email', 'telegram', 'api'])->default('whatsapp');
            $table->string('platform_identifier'); // WhatsApp number, Slack channel, email address, etc.
            $table->enum('status', ['active', 'inactive', 'error'])->default('active');
            $table->json('config')->nullable(); // Platform-specific configuration
            $table->json('webhook_url')->nullable(); // For webhooks
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'platform']);
            $table->index(['platform', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_integrations');
    }
};
