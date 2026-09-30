<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jadwal_kuliah')) {
            Schema::create('jadwal_kuliah', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
                $table->string('hari');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->decimal('latitude_kelas', 10, 8);
                $table->decimal('longitude_kelas', 11, 8);
                $table->unsignedInteger('radius_meter')->default(50);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kuliah');
    }
};
