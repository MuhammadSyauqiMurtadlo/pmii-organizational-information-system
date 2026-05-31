<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('rayon_id')->constrained()->cascadeOnDelete();
            $table->string('member_number', 50)->unique()->nullable(); // No. Anggota
            $table->date('joined_date')->nullable();
            $table->string('generation')->nullable();  // Misal: "Angkatan 2023"
            $table->enum('level', ['kader', 'anggota_muda', 'anggota', 'anggota_senior'])
                ->default('kader');
            $table->string('position')->nullable(); // Jabatan di rayon
            $table->text('bio')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
