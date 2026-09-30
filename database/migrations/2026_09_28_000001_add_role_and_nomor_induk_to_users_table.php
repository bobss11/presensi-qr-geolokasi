<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['dosen', 'mahasiswa'])->default('mahasiswa')->after('email');
            }
            if (! Schema::hasColumn('users', 'nomor_induk')) {
                $table->string('nomor_induk')->nullable()->unique()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nomor_induk']);
        });
    }
};
