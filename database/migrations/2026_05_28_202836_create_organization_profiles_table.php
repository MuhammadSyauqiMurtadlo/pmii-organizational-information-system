<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komisariat_id')->constrained()->cascadeOnDelete();
            $table->text('history')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->json('organizational_structure')->nullable(); // JSON struktur pengurus
            $table->string('chief_name')->nullable();    // Nama Ketua
            $table->string('secretary_name')->nullable();
            $table->string('treasurer_name')->nullable();
            $table->year('period_start')->nullable();
            $table->year('period_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_profiles');
    }
};
