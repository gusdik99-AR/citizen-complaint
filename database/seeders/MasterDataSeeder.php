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

        $this->command->info('✅ Master data berhasil dibuat:');
        $this->command->info('   - 5 Status Aduan: Diajukan, Diverifikasi, Ditolak, Diproses, Selesai');
        $this->command->info('   - 2 Akses Aduan: Publik, Privat');
    }
}
