<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Seed data master untuk status aduan dan akses aduan
     */
    public function run(): void
    {
        // 1. Status Aduan
        $statusAduan = [
            ['nama_status' => 'Diajukan', 'urutan' => 1],
            ['nama_status' => 'Diverifikasi', 'urutan' => 2],
            ['nama_status' => 'Ditolak', 'urutan' => 3],
            ['nama_status' => 'Diproses', 'urutan' => 4],
            ['nama_status' => 'Selesai', 'urutan' => 5],
        ];

        foreach ($statusAduan as $status) {
            DB::table('status_aduan')->insert($status);
        }

        // 2. Akses Aduan
        $aksesAduan = [
            [
                'nama_akses_aduan' => 'Publik',
                'keterangan' => 'Aduan dapat dilihat oleh semua orang',
            ],
            [
                'nama_akses_aduan' => 'Privat',
                'keterangan' => 'Aduan hanya dapat dilihat oleh pelapor dan admin',
            ],
        ];

        foreach ($aksesAduan as $akses) {
            DB::table('akses_aduan')->insert($akses);
        }

        // 3. Kategori Aduan
        $kategoriAduan = [
            [
                'nama_kategori' => 'Infrastruktur Jalan',
                'keterangan' => 'Laporan terkait jalan rusak, berlubang, atau perlu perbaikan',
            ],
            [
                'nama_kategori' => 'Penerangan Jalan',
                'keterangan' => 'Laporan lampu jalan mati atau rusak',
            ],
            [
                'nama_kategori' => 'Sampah & Kebersihan',
                'keterangan' => 'Laporan terkait sampah menumpuk, kebersihan lingkungan',
            ],
            [
                'nama_kategori' => 'Air Bersih',
                'keterangan' => 'Laporan terkait masalah air PAM atau air bersih',
            ],
            [
                'nama_kategori' => 'Drainase & Saluran',
                'keterangan' => 'Laporan saluran air tersumbat, banjir, genangan',
            ],
            [
                'nama_kategori' => 'Fasilitas Umum',
                'keterangan' => 'Laporan terkait taman, Terminal, pasar, dll',
            ],
            [
                'nama_kategori' => 'Keamanan & Ketertiban',
                'keterangan' => 'Laporan terkait keamanan lingkungan, premanisme',
            ],
            [
                'nama_kategori' => 'Kesehatan',
                'keterangan' => 'Laporan terkait pelayanan kesehatan, Puskesmas, RS',
            ],
            [
                'nama_kategori' => 'Pendidikan',
                'keterangan' => 'Laporan terkait fasilitas sekolah, pelayanan pendidikan',
            ],
            [
                'nama_kategori' => 'Administrasi Kependudukan',
                'keterangan' => 'Laporan terkait KTP, KK, Akta Kelahiran, dll',
            ],
            [
                'nama_kategori' => 'Lainnya',
                'keterangan' => 'Kategori lain yang tidak termasuk di atas',
            ],
        ];

        foreach ($kategoriAduan as $kategori) {
            DB::table('kategori_aduan')->insert($kategori);
        }

        $this->command->info('✅ Master data berhasil dibuat:');
        $this->command->info('   - 5 Status Aduan: Diajukan, Diverifikasi, Ditolak, Diproses, Selesai');
        $this->command->info('   - 2 Akses Aduan: Publik, Privat');
        $this->command->info('   - 11 Kategori Aduan');
    }
}
