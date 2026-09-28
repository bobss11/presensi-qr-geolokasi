<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'dosen'
                ? redirect()->route('dosen.dashboard')
                : redirect()->route('mahasiswa.scan');
        }

        $daftarMahasiswa = User::where('role', 'mahasiswa')->orderBy('nomor_induk')->get();

        return view('auth.login', compact('daftarMahasiswa'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return Auth::user()->role === 'dosen'
                ? redirect()->intended(route('dosen.dashboard'))
                : redirect()->intended(route('mahasiswa.scan'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function quickLogin(string $roleOrId)
    {
        if (is_numeric($roleOrId)) {
            $user = User::find($roleOrId);
        } else {
            $user = User::where('role', $roleOrId)->first();
        }

        if (! $user) {
            return redirect()->route('login')->with('error', "Pengguna tidak ditemukan di database.");
        }

        Auth::login($user);
        request()->session()->regenerate();

        return $user->role === 'dosen'
            ? redirect()->route('dosen.dashboard')->with('success', "Berhasil masuk sebagai {$user->name} (Dosen)")
            : redirect()->route('mahasiswa.scan')->with('success', "Berhasil masuk sebagai {$user->name} ({$user->nomor_induk})");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
