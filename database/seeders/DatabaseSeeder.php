<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder untuk peran dan user
        $this->call([
            RoleSeeder::class,        // Buat role: Admin, OPD, Masyarakat
            MasterDataSeeder::class,  // Buat status aduan & akses aduan
            AdminOpdSeeder::class,    // Buat user Admin dan OPD
        ]);
    }
}
