@extends('layouts.app')

@section('title', 'Login - Presensi QR & Geolokasi')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl mx-auto shadow-lg shadow-indigo-200 mb-3">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang</h1>
            <p class="text-xs text-slate-500 mt-1">Masuk ke Sistem Presensi QR Dinamis & Geolokasi</p>
        </div>

        @if($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 text-xs p-3 rounded-xl">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                    placeholder="nama@kampus.ac.id">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                    Ingat saya
                </label>
                <span class="text-slate-400">Password: <code class="font-mono text-indigo-600 font-bold">password</code></span>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-100 transition">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <span class="text-xs text-slate-400 block mb-3 font-semibold uppercase tracking-wider text-center">Demo / Quick Login:</span>
            
            <div class="grid grid-cols-2 gap-2 mb-3">
                <a href="{{ route('quick-login', 'dosen') }}" class="py-2.5 px-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition border border-indigo-200 flex items-center justify-center text-center">
                    <i class="fa-solid fa-chalkboard-user mr-1.5"></i> Login Dosen
                </a>
                <a href="{{ route('quick-login', 'mahasiswa') }}" class="py-2.5 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition border border-emerald-200 flex items-center justify-center text-center">
                    <i class="fa-solid fa-user-graduate mr-1.5"></i> Mhs (Budi)
                </a>
            </div>

            @if(isset($daftarMahasiswa) && $daftarMahasiswa->isNotEmpty())
                <details class="group bg-slate-50 rounded-2xl border border-slate-200/80 p-3 text-xs transition">
                    <summary class="font-bold text-slate-700 hover:text-indigo-600 cursor-pointer flex items-center justify-between list-none">
                        <span class="flex items-center">
                            <i class="fa-solid fa-users text-indigo-500 mr-2"></i>
                            Pilih Mahasiswa Lain ({{ $daftarMahasiswa->count() }} Terdaftar)
                        </span>
                        <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                    </summary>

                    <div class="mt-3 pt-2 border-t border-slate-200/60 max-h-48 overflow-y-auto space-y-1.5 pr-1">
                        @foreach($daftarMahasiswa as $mhs)
                            <a href="{{ route('quick-login', $mhs->id) }}" class="flex items-center justify-between p-2 rounded-xl bg-white hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 transition text-slate-700 hover:text-emerald-700">
                                <div>
                                    <div class="font-bold text-[11px]">{{ $mhs->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $mhs->nomor_induk }} &bull; {{ $mhs->email }}</div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
                                    Login <i class="fa-solid fa-arrow-right ml-0.5"></i>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif
        </div>
    </div>
</div>
@endsection
