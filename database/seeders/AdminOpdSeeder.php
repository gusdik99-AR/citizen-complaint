<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminOpdSeeder extends Seeder
{
    /**
     * Seed user Admin dan OPD (bukan dari form register)
     */
    public function run(): void
    {
        // 1. Buat user Admin
        $adminId = DB::table('pengguna')->insertGetId([
            'nama_pengguna' => 'Administrator',
            'email' => 'admin@pemalang.go.id',
            'password_hash' => Hash::make('admin123'),
            'status_verifikasi' => true,
            'email_verifikasi' => now(),
            'token_verifikasi' => null,
            'status_aktif' => true,
            'tanggal_dibuat' => now(),
            'tanggal_diubah' => now(),
        ]);

        // Assign role Admin
        $roleAdmin = DB::table('peran')->where('nama_peran', 'Admin')->first();
        DB::table('peran_pengguna')->insert([
            'pengguna_id' => $adminId,
            'peran_id' => $roleAdmin->id,
        ]);

        // 2. Buat user OPD (contoh: Dinas Pekerjaan Umum)
        $opdId = DB::table('pengguna')->insertGetId([
            'nama_pengguna' => 'OPD Dinas PU',
            'email' => 'pu@pemalang.go.id',
            'password_hash' => Hash::make('opd123'),
            'status_verifikasi' => true,
            'email_verifikasi' => now(),
            'token_verifikasi' => null,
            'status_aktif' => true,
            'tanggal_dibuat' => now(),
            'tanggal_diubah' => now(),
        ]);

        // Assign role OPD
        $roleOpd = DB::table('peran')->where('nama_peran', 'OPD')->first();
        DB::table('peran_pengguna')->insert([
            'pengguna_id' => $opdId,
            'peran_id' => $roleOpd->id,
        ]);

        // 3. Buat data OPD (Dinas Pekerjaan Umum)
        $opdDinasId = DB::table('opd')->insertGetId([
            'nama_opd' => 'Dinas Pekerjaan Umum',
            'no_telp' => '0284-321234',
            'alamat' => 'Jl. Pemuda No. 1, Pemalang',
        ]);

        // Link user OPD dengan OPD Dinas
        DB::table('opd_pengguna')->insert([
            'pengguna_id' => $opdId,
            'opd_id' => $opdDinasId,
        ]);

        $this->command->info('✅ User Admin dan OPD berhasil dibuat:');
        $this->command->info('   Admin: admin@pemalang.go.id / admin123');
        $this->command->info('   OPD:   pu@pemalang.go.id / opd123');
    }
}
