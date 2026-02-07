<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        // Cari pengguna berdasarkan email
        $pengguna = DB::table('pengguna')
            ->where('email', $request->email)
            ->first();

        // Cek apakah pengguna ditemukan dan password cocok
        if (!$pengguna || !Hash::check($request->password, $pengguna->password_hash)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->onlyInput('email');
        }

        // Cek status aktif
        if (!$pengguna->status_aktif) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif',
            ])->onlyInput('email');
        }

        // Simpan session login
        session([
            'pengguna_id' => $pengguna->id,
            'nama_pengguna' => $pengguna->nama_pengguna,
            'email' => $pengguna->email,
            'is_logged_in' => true,
        ]);

        return redirect()->intended('/')->with('success', 'Login berhasil');
    }

    /**
     * Proses register
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|digits:16|unique:masyarakat,nik',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'nik.required' => 'NIK wajib diisi',
            'nik.digits' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke tabel pengguna
            $penggunaId = DB::table('pengguna')->insertGetId([
                'nama_pengguna' => $request->nama_lengkap,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'status_verifikasi' => false,
                'email_verifikasi' => null,
                'token_verifikasi' => null,
                'status_aktif' => true,
                'tanggal_dibuat' => now(),
                'tanggal_diubah' => now(),
            ]);

            // 2. Simpan ke tabel masyarakat
            DB::table('masyarakat')->insert([
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap,
                'no_telp' => null,
                'alamat' => null,
                'pengguna_id' => $penggunaId,
                'tanggal_dibuat' => now(),
                'tanggal_diubah' => now(),
            ]);

            // 3. Assign role "Masyarakat" ke user baru
            $roleMasyarakat = DB::table('peran')->where('nama_peran', 'Masyarakat')->first();

            if ($roleMasyarakat) {
                DB::table('peran_pengguna')->insert([
                    'pengguna_id' => $penggunaId,
                    'peran_id' => $roleMasyarakat->id,
                ]);
            }

            DB::commit();

            // Return success response tanpa redirect (akan di-handle di frontend)
            return back()->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Proses logout
     */
    public function logout()
    {
        // Hapus semua session
        session()->flush();

        return redirect()->route('login')->with('success', 'Logout berhasil');
    }
}
