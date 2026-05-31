<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rayon_id')->nullable()->constrained()->nullOnDelete(); // null = komisariat
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('objective')->nullable(); // Tujuan kegiatan
            $table->string('location');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->enum('type', ['kajian', 'pelatihan', 'rapat', 'aksi', 'sosial', 'kaderisasi', 'lainnya'])
                ->default('lainnya');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->string('poster')->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->text('report')->nullable(); // Laporan kegiatan
            $table->timestamps();

            $table->index(['status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
