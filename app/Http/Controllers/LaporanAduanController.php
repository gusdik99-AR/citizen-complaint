<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LaporanAduanController extends Controller
{
    /**
     * Tampilkan daftar laporan pengaduan dengan format tabel
     */
    public function index(Request $request)
    {
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        $query = Aduan::with([
            'masyarakat.pengguna',
            'kategoriAduan.opd',
            'statusAduan',
            'riwayatStatus.unitOpd'
        ])->whereIn('status_aduan_id', [2, 4, 5])
            ->orderBy('tanggal_lapor', 'desc');

        // Filter berdasarkan date range
        if ($dari) {
            $query->whereDate('tanggal_lapor', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_lapor', '<=', $sampai);
        }

        // Jika pengguna terkait OPD, batasi hasil sesuai OPD mereka
        $user = null;
        $email = session('email');
        $opdId = null;
        if ($email) {
            $user = DB::table('pengguna')
                ->leftjoin('peran_pengguna', 'pengguna.id', '=', 'peran_pengguna.pengguna_id')
                ->leftjoin('peran', 'peran_pengguna.peran_id', '=', 'peran.id')
                ->where('pengguna.email', $email)
                ->select('pengguna.*', 'peran.id as peran_id', 'peran.nama_peran')
                ->first();

            $opdId = DB::table('opd_pengguna')->where('pengguna_id', $user->id ?? 0)->value('opd_id');
        }

        if ($opdId) {
            $query->whereHas('kategoriAduan', function ($q) use ($opdId) {
                $q->whereHas('opd', function ($q2) use ($opdId) {
                    $q2->where('opd.id', $opdId);
                });
            });
        }

        $aduanList = $query->paginate(10)->appends($request->only('dari', 'sampai'));

        // Format data laporan untuk pagination
        $laporanPengaduan = $aduanList->map(function ($aduan) {
            $latestUnit = $aduan->riwayatStatus->sortByDesc('id')->first();
            $unitName = $latestUnit && $latestUnit->unitOpd ? $latestUnit->unitOpd->nama_unit : 'Sekretariat';

            $opdName = $aduan->kategoriAduan && $aduan->kategoriAduan->opd
                ? $aduan->kategoriAduan->opd->pluck('nama_opd')->implode(', ')
                : '-';

            $lokasi = $aduan->lokasi;
            if (!$lokasi && $aduan->latitude && $aduan->longitude) {
                $lokasi = "{$aduan->latitude}, {$aduan->longitude}";
            }

            return [
                'id' => $aduan->id,
                'no_aduan' => $aduan->no_aduan ?? ('ADU-' . $aduan->tanggal_lapor->format('Ymd') . '-' . str_pad($aduan->id % 1000, 4, '0', STR_PAD_LEFT)),
                'tanggal_lapor' => $aduan->tanggal_lapor,
                'nama_lengkap' => $aduan->masyarakat->pengguna->nama_pengguna ?? $aduan->masyarakat->nama_lengkap ?? 'N/A',
                'unit' => $unitName,
                'kategori' => $aduan->kategoriAduan->nama_kategori ?? 'N/A',
                'opd' => $opdName,
                'lokasi' => $lokasi ?? '-',
                'statusAduan' => $aduan->statusAduan?->nama_status ?? 'Diajukan'
            ];
        });

        // Statistik ringkas (jika OPD ditetapkan, statistik disesuaikan untuk OPD tersebut)
        $statsQuery = Aduan::query();
        if ($opdId) {
            $statsQuery->whereHas('kategoriAduan', function ($q) use ($opdId) {
                $q->whereHas('opd', function ($q2) use ($opdId) {
                    $q2->where('opd.id', $opdId);
                });
            });
        }

        $stats = [
            'total' => $statsQuery->count(),
            'hari_ini' => (clone $statsQuery)->whereDate('tanggal_lapor', today())->count(),
            'bulan_ini' => (clone $statsQuery)->whereMonth('tanggal_lapor', now()->month)->whereYear('tanggal_lapor', now()->year)->count(),
            'tahun_ini' => (clone $statsQuery)->whereYear('tanggal_lapor', now()->year)->count(),
        ];

        // Get user data with role
        $user = null;
        $email = session('email');
        if ($email) {
            $user = DB::table('pengguna')
                ->leftjoin('peran_pengguna', 'pengguna.id', '=', 'peran_pengguna.pengguna_id')
                ->leftjoin('peran', 'peran_pengguna.peran_id', '=', 'peran.id')
                ->where('pengguna.email', $email)
                ->select('pengguna.*', 'peran.id as peran_id', 'peran.nama_peran')
                ->first();
        }

        return Inertia::render('Admin/Laporan/LaporanAduan', [
            'user' => $user,
            'aduanList' => $aduanList,
            'laporanPengaduan' => $laporanPengaduan,
            'stats' => $stats,
        ]);
    }

    /**
     * Additional method to check if user is OPD
     */
    private function isUserOPD($user) {}

    /**
     * Tampilkan halaman laporan pengaduan dengan format khusus (FixLaporanAduan)
     */
    public function fixLaporan(Request $request)
    {
        $aduanId = $request->query('aduan');

        $query = Aduan::with(['masyarakat.pengguna', 'kategoriAduan', 'statusAduan']);

        // Jika ada parameter aduan, filter hanya laporan tersebut
        if ($aduanId) {
            $query->where('id', $aduanId);
        }

        $aduanList = $query->orderBy('tanggal_lapor', 'desc')->get();

        // Format data laporan untuk setiap aduan
        $laporanPengaduan = $aduanList->map(function ($aduan, $index) {

            $lokasi = $aduan->lokasi ?? $aduan->lokasi_aduan;
            if (!$lokasi && $aduan->latitude && $aduan->longitude) {
                $lokasi = "{$aduan->latitude}, {$aduan->longitude}";
            }

            return [
                'id' => $aduan->id,
                'nomor_laporan' => $this->generateNomorLaporan($aduan->id, $aduan->tanggal_lapor),
                'tanggal_lapor' => $aduan->tanggal_lapor,
                'nama_lengkap' => $aduan->masyarakat?->pengguna?->nama_pengguna ?? $aduan->masyarakat?->nama_lengkap ?? 'N/A',
                'alamat' => $aduan->masyarakat?->alamat ?? 'N/A',
                'nomor_telepon' => $aduan->masyarakat?->pengguna?->nomor_telepon ?? 'N/A',
                'nomor_identitas' => $aduan->masyarakat?->nomor_identitas ?? 'N/A',
                'status' => $aduan->masyarakat?->status ?? 'Non Pegawai',
                'email' => $aduan->masyarakat?->pengguna?->email ?? 'N/A',
                'sumber_pengaduan' => 'Sistem Online',
                'perihal' => substr($aduan->isi_aduan, 0, 100),
                'uraian' => $aduan->isi_aduan,
                'tempat_kejadian' => $lokasi ?? '-',
                'tanggal_kejadian' => $aduan->tanggal_kejadian ?? $aduan->tanggal_lapor,
                'waktu_kejadian' => $aduan->waktu_kejadian ?? '00:00:00',
                'kategori' => $aduan->kategoriAduan?->nama_kategori ?? 'N/A',
                'status_aduan' => $aduan->statusAduan?->nama_status ?? 'Baru',
            ];
        });

        // Statistik ringkas
        $stats = [
            'total' => $aduanList->count(),
            'hari_ini' => Aduan::whereDate('tanggal_lapor', today())->count(),
            'bulan_ini' => Aduan::whereMonth('tanggal_lapor', now()->month)->whereYear('tanggal_lapor', now()->year)->count(),
            'tahun_ini' => Aduan::whereYear('tanggal_lapor', now()->year)->count(),
        ];

        // Get user data
        $user = null;
        $email = session('email');
        if ($email) {
            $user = DB::table('pengguna')->where('email', $email)->first();
        }

        return Inertia::render('Admin/Laporan/FixLaporanAduan', [
            'user' => $user,
            'laporanPengaduan' => $laporanPengaduan,
            'stats' => $stats,
        ]);
    }

    /**
     * Generate nomor laporan dengan format: XXXXXX[DDMM][YY]XXX
     */
    private function generateNomorLaporan($aduanId, $tanggalLapor)
    {
        $date = \Carbon\Carbon::parse($tanggalLapor);
        $datePart = strtoupper($date->format('dmy'));
        $suffix = str_pad($aduanId % 1000, 3, '0', STR_PAD_LEFT);
        return 'ADUAN' . $datePart . $suffix;
    }

    /**
     * Tampilkan detail laporan pengaduan
     */
    public function show(Aduan $aduan)
    {

        return Inertia::render('Admin/Laporan/FixLaporanAduan', [
            'peran' => $aduan,
        ]);
    }

    /**
     * Export laporan ke PDF (menggabungkan data dari beberapa tabel terkait)
     */
    public function export(Request $request)
    {
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        $query = Aduan::with([
            'kategoriAduan.opd',
            'statusAduan',
            'masyarakat.pengguna',
            'aksesAduan',
            'tanggapan',
            'riwayatStatus.unitOpd'
        ])->orderBy('tanggal_lapor', 'desc');

        if ($dari) {
            $query->whereDate('tanggal_lapor', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_lapor', '<=', $sampai);
        }

        // jika pengguna OPD, batasi hasil sesuai OPD
        $email = session('email');
        $opd = null;
        if ($email) {
            $pengguna = DB::table('pengguna')->where('email', $email)->first();
            $opdId = DB::table('opd_pengguna')->where('pengguna_id', $pengguna?->id ?? 0)->value('opd_id');
            if ($opdId) {
                $opd = \App\Models\Opd::find($opdId);
                $query->whereHas('kategoriAduan', function ($q) use ($opdId) {
                    $q->whereHas('opd', function ($q2) use ($opdId) {
                        $q2->where('opd.id', $opdId);
                    });
                });
            }
        }

        $rows = $query->get();

        if ($rows->count() === 0) {
            return response()->json(['message' => 'no_data'], 200);
        }

        // Render HTML view for PDF
        $html = view('pdf.laporan_aduan', [
            'rows' => $rows,
            'dari' => $dari,
            'sampai' => $sampai,
            'opd' => $opd,
        ])->render();

        // Require Dompdf; if not installed, return helpful JSON error
        if (!class_exists(\Dompdf\Dompdf::class)) {
            return response()->json(['message' => 'missing_pdf_library', 'hint' => 'Install dompdf via: composer require dompdf/dompdf'], 200);
        }

        $dompdf = new \Dompdf\Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $filename = 'laporan_' . ($dari ?? 'all') . '_' . ($sampai ?? 'all') . '.pdf';

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
