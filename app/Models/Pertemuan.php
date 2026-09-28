<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'jadwal_kuliah_id',
        'pertemuan_ke',
        'qr_token',
        'qr_expires_at',
        'is_active',
    ];

    protected $casts = [
        'pertemuan_ke' => 'integer',
        'qr_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function jadwalKuliah(): BelongsTo
    {
        return $this->belongsTo(JadwalKuliah::class, 'jadwal_kuliah_id');
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'pertemuan_id');
    }

    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->qr_expires_at);
    }

    public function remainingSeconds(): int
    {
        $diff = Carbon::now()->diffInSeconds($this->qr_expires_at, false);
        return max(0, (int) $diff);
    }
}