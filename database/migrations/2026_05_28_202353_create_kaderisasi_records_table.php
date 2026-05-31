<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kaderisasi_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['MAPABA', 'PKD', 'PKL', 'MKDK', 'other']);
            $table->string('event_name')->nullable(); // Nama kegiatan spesifik
            $table->date('event_date');
            $table->string('location')->nullable();
            $table->string('facilitator')->nullable(); // Pemateri/fasilitator
            $table->enum('status', ['lulus', 'tidak_lulus', 'pending'])->default('pending');
            $table->string('certificate_number')->nullable();
            $table->string('certificate_file')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kaderisasi_records');
    }
};
