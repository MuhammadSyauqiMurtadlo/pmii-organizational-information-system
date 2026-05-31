<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rayon_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->string('nim', 20)->nullable()->unique()->after('name');    // Nomor Induk Mahasiswa
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->enum('status', ['active', 'inactive', 'alumni'])->default('active')->after('avatar');
            $table->string('student_faculty')->nullable()->after('status');
            $table->string('student_major')->nullable()->after('student_faculty');
            $table->year('entry_year')->nullable()->after('student_major');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rayon_id']);
            $table->dropColumn(['rayon_id', 'nim', 'phone', 'avatar', 'status',
                'student_faculty', 'student_major', 'entry_year']);
        });
    }
};
