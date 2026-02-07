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

        // 4. Assign kategori ke OPD Dinas PU
        // Dinas PU menangani: Infrastruktur Jalan, Penerangan Jalan, Drainase & Saluran
        $kategoriIds = [
            DB::table('kategori_aduan')->where('nama_kategori', 'Infrastruktur Jalan')->value('id'),
            DB::table('kategori_aduan')->where('nama_kategori', 'Penerangan Jalan')->value('id'),
            DB::table('kategori_aduan')->where('nama_kategori', 'Drainase & Saluran')->value('id'),
            DB::table('kategori_aduan')->where('nama_kategori', 'Fasilitas Umum')->value('id'),
        ];

        foreach ($kategoriIds as $kategoriId) {
            if ($kategoriId) {
                DB::table('kategori_aduan_opd')->insert([
                    'kategori_aduan_id' => $kategoriId,
                    'opd_id' => $opdDinasId,
                ]);
            }
        }

        // 5. Buat OPD tambahan - Dinas Lingkungan Hidup
        $opdLhId = DB::table('opd')->insertGetId([
            'nama_opd' => 'Dinas Lingkungan Hidup',
            'no_telp' => '0284-321235',
            'alamat' => 'Jl. Pemuda No. 2, Pemalang',
        ]);

        // Assign kategori: Sampah & Kebersihan
        $kategoriLhId = DB::table('kategori_aduan')->where('nama_kategori', 'Sampah & Kebersihan')->value('id');
        if ($kategoriLhId) {
            DB::table('kategori_aduan_opd')->insert([
                'kategori_aduan_id' => $kategoriLhId,
                'opd_id' => $opdLhId,
            ]);
        }

        // 6. Buat OPD tambahan - Dinas Kesehatan
        $opdKesId = DB::table('opd')->insertGetId([
            'nama_opd' => 'Dinas Kesehatan',
            'no_telp' => '0284-321236',
            'alamat' => 'Jl. Pemuda No. 3, Pemalang',
        ]);

        // Assign kategori: Kesehatan
        $kategoriKesId = DB::table('kategori_aduan')->where('nama_kategori', 'Kesehatan')->value('id');
        if ($kategoriKesId) {
            DB::table('kategori_aduan_opd')->insert([
                'kategori_aduan_id' => $kategoriKesId,
                'opd_id' => $opdKesId,
            ]);
        }

        // 7. Buat OPD tambahan - Dinas Pendidikan
        $opdDikId = DB::table('opd')->insertGetId([
            'nama_opd' => 'Dinas Pendidikan',
            'no_telp' => '0284-321237',
            'alamat' => 'Jl. Pemuda No. 4, Pemalang',
        ]);

        // Assign kategori: Pendidikan
        $kategoriDikId = DB::table('kategori_aduan')->where('nama_kategori', 'Pendidikan')->value('id');
        if ($kategoriDikId) {
            DB::table('kategori_aduan_opd')->insert([
                'kategori_aduan_id' => $kategoriDikId,
                'opd_id' => $opdDikId,
            ]);
        }

        // 8. Buat OPD tambahan - Dinas Kependudukan & Catatan Sipil
        $opdDisdukId = DB::table('opd')->insertGetId([
            'nama_opd' => 'Dinas Kependudukan & Catatan Sipil',
            'no_telp' => '0284-321238',
            'alamat' => 'Jl. Pemuda No. 5, Pemalang',
        ]);

        // Assign kategori: Administrasi Kependudukan
        $kategoriDukId = DB::table('kategori_aduan')->where('nama_kategori', 'Administrasi Kependudukan')->value('id');
        if ($kategoriDukId) {
            DB::table('kategori_aduan_opd')->insert([
                'kategori_aduan_id' => $kategoriDukId,
                'opd_id' => $opdDisdukId,
            ]);
        }

        $this->command->info('✅ User Admin dan OPD berhasil dibuat:');
        $this->command->info('   Admin: admin@pemalang.go.id / admin123');
        $this->command->info('   OPD:   pu@pemalang.go.id / opd123');
        $this->command->info('✅ 5 OPD dibuat dengan kategori masing-masing');
    }
}
