<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'pertemuan_id',
        'mahasiswa_id',
        'status',
        'waktu_presensi',
        'latitude_mahasiswa',
        'longitude_mahasiswa',
        'jarak_meter',
    ];

    protected $casts = [
        'waktu_presensi' => 'datetime',
        'latitude_mahasiswa' => 'float',
        'longitude_mahasiswa' => 'float',
        'jarak_meter' => 'float',
    ];

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}