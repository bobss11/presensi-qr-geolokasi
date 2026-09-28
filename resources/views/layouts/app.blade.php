<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Presensi Mahasiswa QR & GPS') - Smart Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 flex flex-col">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                    </div>
                    <div>
                        <a href="{{ url('/') }}" class="text-lg font-bold text-slate-900 tracking-tight">Presensi Mahasiswa</a>
                        <p class="text-xs text-slate-500 hidden sm:block">QR Code Dinamis & Validasi Geolokasi</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @auth
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-slate-500">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ auth()->user()->role === 'dosen' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ strtoupper(auth()->user()->role) }}
                                </span>
                                {{ auth()->user()->nomor_induk ? '• ' . auth()->user()->nomor_induk : '' }}
                            </span>
                        </div>

                        @if(auth()->user()->role === 'dosen')
                            <a href="{{ route('dosen.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition">
                                <i class="fa-solid fa-chalkboard-user mr-1"></i> Kelas Saya
                            </a>
                        @elseif(auth()->user()->role === 'mahasiswa')
                            <a href="{{ route('mahasiswa.scan') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition">
                                <i class="fa-solid fa-camera mr-1"></i> Scan Presensi
                            </a>
                            <a href="{{ route('mahasiswa.riwayat') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition">
                                <i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-500 hover:text-rose-600 p-2 rounded-lg hover:bg-rose-50 transition" title="Logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 px-3 py-2">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg text-emerald-800 text-sm flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg text-rose-800 text-sm flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Sistem Presensi Mahasiswa - QR Dinamis & Haversine Geolocation
    </footer>

    @stack('scripts')
</body>
</html>