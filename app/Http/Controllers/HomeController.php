<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the home page / dashboard based on user role.
     */
    public function index(): Response|RedirectResponse
    {
        // Get authenticated user data and role
        $penggunaId = session('pengguna_id');

        if (!$penggunaId) {
            return redirect()->route('login');
        }

        $user = DB::table('pengguna')
            ->where('id', $penggunaId)
            ->first();

        // Get user role
        $role = DB::table('peran_pengguna')
            ->join('peran', 'peran_pengguna.peran_id', '=', 'peran.id')
            ->where('peran_pengguna.pengguna_id', $penggunaId)
            ->value('peran.nama_peran');

        // Route to appropriate dashboard based on role
        if ($role === 'Masyarakat') {
            return $this->citizenDashboard($penggunaId, $user);
        } elseif ($role === 'OPD') {
            return $this->opdDashboard($penggunaId, $user);
        } else {
            return $this->adminDashboard($user);
        }
    }

    /**
     * Citizen Dashboard
     */
    private function citizenDashboard($penggunaId, $user): Response
    {
        // Get masyarakat ID
        $masyarakatId = DB::table('masyarakat')
            ->where('pengguna_id', $penggunaId)
            ->value('id');

        // Get my complaints
        $myComplaints = [];
        if ($masyarakatId) {
            $myComplaints = DB::table('aduan')
                ->join('kategori_aduan', 'aduan.kategori_aduan_id', '=', 'kategori_aduan.id')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('aduan.masyarakat_id', $masyarakatId)
                ->select(
                    'aduan.id as id',
                    DB::raw('LEFT(aduan.isi_aduan, 50) as judul'),
                    'kategori_aduan.nama_kategori as kategori',
                    'status_aduan.nama_status as status',
                    DB::raw('DATE(aduan.tanggal_dibuat) as tanggal')
                )
                ->orderBy('aduan.tanggal_dibuat', 'desc')
                ->limit(5)
                ->get();
        }

        return Inertia::render('Citizen/Dashboard', [
            'user' => $user,
            'myComplaints' => $myComplaints,
        ]);
    }

    /**
     * OPD Dashboard
     */
    private function opdDashboard($penggunaId, $user): Response
    {
        // Get OPD ID
        $opdId = DB::table('opd_pengguna')
            ->where('pengguna_id', $penggunaId)
            ->value('opd_id');

        // Get stats for this OPD (based on kategori assignment)
        $baseQuery = DB::table('aduan')
            ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
            ->where('kategori_aduan_opd.opd_id', $opdId);

        $stats = [
            'totalAduan' => (clone $baseQuery)->count(),
            'diajukan' => (clone $baseQuery)
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diajukan')
                ->count(),
            'diproses' => (clone $baseQuery)
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diproses')
                ->count(),
            'selesai' => (clone $baseQuery)
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Selesai')
                ->count(),
        ];

        // Get assigned complaints
        $assignedComplaints = DB::table('aduan')
            ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
            ->join('masyarakat', 'aduan.masyarakat_id', '=', 'masyarakat.id')
            ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
            ->where('kategori_aduan_opd.opd_id', $opdId)
            ->select(
                'aduan.id as id',
                DB::raw('LEFT(aduan.isi_aduan, 50) as judul'),
                'masyarakat.nama_lengkap as pelapor',
                'status_aduan.nama_status as status',
                DB::raw('DATE(aduan.tanggal_dibuat) as tanggal')
            )
            ->orderBy('aduan.tanggal_dibuat', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('OPD/Dashboard', [
            'user' => $user,
            'stats' => $stats,
            'assignedComplaints' => $assignedComplaints,
        ]);
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard($user): Response
    {
        // Get statistics from database
        $stats = [
            'totalAduan' => DB::table('aduan')->count(),
            'diajukan' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diajukan')
                ->count(),
            'diverifikasi' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diverifikasi')
                ->count(),
            'diproses' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diproses')
                ->count(),
            'selesai' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Selesai')
                ->count(),
            'ditolak' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Ditolak')
                ->count(),
            'totalUsers' => DB::table('pengguna')->count(),
            'totalOPD' => DB::table('opd')->count(),
        ];

        // Get recent complaints with OPD info
        $recentComplaints = DB::table('aduan')
            ->leftJoin('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
            ->leftJoin('opd', 'kategori_aduan_opd.opd_id', '=', 'opd.id')
            ->join('kategori_aduan', 'aduan.kategori_aduan_id', '=', 'kategori_aduan.id')
            ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
            ->select(
                'aduan.id as id',
                'aduan.no_aduan',
                DB::raw('LEFT(aduan.isi_aduan, 50) as judul'),
                'kategori_aduan.nama_kategori as kategori',
                'status_aduan.nama_status as status',
                'opd.nama_opd as opd',
                DB::raw('DATE(aduan.tanggal_dibuat) as tanggal')
            )
            ->orderBy('aduan.tanggal_dibuat', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentComplaints' => $recentComplaints,
        ]);
    }
}
