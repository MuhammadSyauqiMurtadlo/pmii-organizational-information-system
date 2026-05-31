<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('target_scope', ['all', 'komisariat', 'rayon'])->default('all');
            $table->foreignId('target_rayon_id')->nullable()->constrained('rayons')->nullOnDelete();
            $table->boolean('is_pinned')->default(false);
            $table->string('attachment')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['target_scope', 'target_rayon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
