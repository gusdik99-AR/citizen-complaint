<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed tabel peran dengan role: Admin, OPD, Masyarakat
     */
    public function run(): void
    {
        $roles = ['Admin', 'OPD', 'Masyarakat'];

        foreach ($roles as $role) {
            DB::table('peran')->insert([
                'nama_peran' => $role,
            ]);
        }

        $this->command->info('✅ 3 Peran berhasil dibuat: Admin, OPD, Masyarakat');
    }
}
