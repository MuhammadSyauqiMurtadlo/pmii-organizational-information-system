<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rayon_id')->nullable()->constrained()->nullOnDelete(); // null = komisariat level
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->enum('category', ['berita', 'artikel', 'opini', 'press_release'])->default('berita');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->datetime('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
