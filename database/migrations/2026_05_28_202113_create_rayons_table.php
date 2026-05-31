<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rayons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komisariat_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('faculty'); // Nama Fakultas
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->json('social_media')->nullable();
            $table->year('founded_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rayons');
    }
};
