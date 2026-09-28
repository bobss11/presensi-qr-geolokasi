@extends('layouts.app')

@section('title', 'Dashboard Dosen - Kelola Perkuliahan')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Dosen</h1>
        <p class="text-sm text-slate-500">Kelola jadwal perkuliahan, generate sesi QR presensi, dan pantau kehadiran mahasiswa.</p>
    </div>
</div>

@if($jadwalList->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
        <i class="fa-solid fa-chalkboard-user text-4xl text-slate-300 mb-3"></i>
        <h3 class="text-base font-bold text-slate-800">Belum Ada Jadwal Kuliah</h3>
        <p class="text-sm text-slate-500 max-w-md mx-auto mt-1">Anda belum memiliki jadwal kuliah yang terdaftar di dalam sistem.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($jadwalList as $jadwal)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700 font-mono">
                            {{ $jadwal->mataKuliah->kode_mk }}
                        </span>
                        <span class="text-xs text-slate-500 font-medium">
                            <i class="fa-regular fa-clock mr-1"></i> {{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-slate-900 mb-1">{{ $jadwal->mataKuliah->nama_mk }}</h2>

                    <div class="text-xs text-slate-500 space-y-1 mb-5">
                        <div><i class="fa-solid fa-location-dot text-rose-500 w-4"></i> Koordinat Kelas: {{ $jadwal->latitude_kelas }}, {{ $jadwal->longitude_kelas }}</div>
                        <div><i class="fa-solid fa-bullseye text-emerald-500 w-4"></i> Toleransi Radius: {{ $jadwal->radius_meter }} meter</div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mb-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Riwayat Pertemuan:</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($jadwal->pertemuan->sortBy('pertemuan_ke') as $p)
                                <a href="{{ route('dosen.pertemuan.qr', $p->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold {{ $p->isExpired() ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' }} transition">
                                    P-{{ $p->pertemuan_ke }}
                                    <span class="ml-1.5 px-1.5 py-0.2 bg-white rounded-full text-[10px] text-slate-600 font-bold">
                                        {{ $p->presensi->count() }} mhs
                                    </span>
                                </a>
                            @empty
                                <span class="text-xs text-slate-400 italic">Belum ada sesi pertemuan.</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 flex items-center justify-between gap-2">
                    <form method="POST" action="{{ route('dosen.pertemuan.store', $jadwal->id) }}" class="inline-flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="pertemuan_ke" value="{{ ($jadwal->pertemuan->max('pertemuan_ke') ?? 0) + 1 }}">
                        <button type="submit" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-plus mr-1.5"></i> Buka Pertemuan {{ ($jadwal->pertemuan->max('pertemuan_ke') ?? 0) + 1 }}
                        </button>
                    </form>

                    <a href="{{ route('dosen.jadwal.export_rekap', $jadwal->id) }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-xl transition">
                        <i class="fa-solid fa-file-csv text-emerald-600 mr-1.5"></i> Rekap CSV
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection