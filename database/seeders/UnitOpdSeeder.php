<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Remove existing unit_opd data then insert fresh example units with nama_pengguna
        DB::table('unit_opd')->delete();
        $opds = DB::table('opd')->get();

        // Realistic Indonesian names for diverse units
        $dummyUsers = [
            'budi.santoso',
            'siti.nurhaliza', 
            'ahmad.riyadi',
            'ani.wijaya',
            'hendra.kusuma',
            'dewi.lestari',
            'rudi.hermawan',
            'eka.putri',
            'bambang.sutrisno',
            'tina.marcelia',
            'doni.setiawan',
            'sri.handayani',
            'rio.pratama',
            'lina.sukma',
            'marco.santoso',
            'yanti.rahayu',
            'irfan.hidayat',
            'bella.monica',
            'joko.widodo',
            'megawati.soekarno',
            'soeharto.prabowo',
            'wahyu.setiawan',
            'ratna.kusuma',
            'taufik.hidayat',
        ];

        // Comprehensive list of unit types commonly found in government agencies
        $templates = [
            ['name' => 'Sekretariat', 'code' => 'SKR'],
            ['name' => 'Bidang Teknis', 'code' => 'BDG-TK'],
            ['name' => 'Pelayanan Publik', 'code' => 'PLS-PUB'],
            ['name' => 'Perencanaan', 'code' => 'REN'],
            ['name' => 'Infrastruktur', 'code' => 'INF'],
            ['name' => 'Keuangan', 'code' => 'KEU'],
            ['name' => 'Operasional', 'code' => 'OPS'],
            ['name' => 'Monitoring & Evaluasi', 'code' => 'MON-EVL'],
            ['name' => 'Sumber Daya Manusia', 'code' => 'SDM'],
            ['name' => 'Logistik & Perlengkapan', 'code' => 'LOG'],
        ];

        $userIndex = 0;

        foreach ($opds as $opd) {
            $inserts = [];
            foreach ($templates as $idx => $template) {
                $username = $dummyUsers[$userIndex % count($dummyUsers)];
                $userIndex++;
                
                $kode = $template['code'] . sprintf('%03d', $opd->id);

                $inserts[] = [
                    'opd_id' => $opd->id,
                    'nama_unit' => $template['name'] . ' ' . $opd->nama_opd,
                    'kode_unit' => $kode,
                    'nama_pengguna' => $username,
                    'tanggal_dibuat' => now(),
                    'tanggal_diubah' => now(),
                ];
            }

            DB::table('unit_opd')->insert($inserts);
        }
    }
}
