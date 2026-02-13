<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        // If not authenticated, show public landing page
        if (!$penggunaId) {
            return $this->landingPage();
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
     * Public Landing Page for guests
     */
    private function landingPage(): Response
    {
        // Get public statistics
        $stats = [
            'selesai' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Selesai')
                ->count(),
            'proses' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diproses')
                ->count(),
            'verifikasi' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diverifikasi')
                ->count(),
            'diajukan' => DB::table('aduan')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('status_aduan.nama_status', 'Diajukan')
                ->count(),
        ];

        // Get recent public complaints (all except rejected)
        $publicReports = DB::table('aduan')
            ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
            ->join('kategori_aduan', 'aduan.kategori_aduan_id', '=', 'kategori_aduan.id')
            ->join('akses_aduan', 'aduan.akses_aduan_id', '=', 'akses_aduan.id')
            ->where('akses_aduan.nama_akses_aduan', 'Publik')
            ->where('status_aduan.nama_status', '!=', 'Ditolak')
            ->select(
                'aduan.id',
                DB::raw('LEFT(aduan.isi_aduan, 100) as title'),
                'aduan.lokasi as location',
                'status_aduan.nama_status as status',
                'aduan.foto',
                DB::raw('(SELECT COUNT(*) FROM aduan_fotos WHERE aduan_fotos.aduan_id = aduan.id) as photos_count')
            )
            ->orderBy('aduan.tanggal_dibuat', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($report) {
                // Convert foto path to full URL
                if ($report->foto) {
                    // Check if using S3 or local storage
                    $disk = config('filesystems.default');
                    if ($disk === 's3') {
                        $report->image = Storage::url($report->foto);
                    } else {
                        // For public disk, use asset URL
                        $report->image = asset($report->foto);
                    }
                } else {
                    $report->image = null;
                }

                // Adjust photo count strategy:
                // If aduan.foto exists, count is 1 + photos_count (from additional table)
                // However, our current logic stores ALL photos in aduan_fotos too.
                // So if photos_count > 0, we use that. If 0 but aduan.foto exists, it's 1.
                // But wait, the previous logic says we store ALL in aduan_fotos.
                // So photos_count should be accurate for total.
                // If photos_count is 0 but aduan.foto is set (legacy), then count is 1.
                if ($report->photos_count == 0 && $report->foto) {
                    $report->photos_count = 1;
                }

                unset($report->foto);
                return $report;
            });

        // Hero images from Unsplash
        $heroImages = [
            [
                'url' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?w=1200&h=600&fit=crop',
                'alt' => 'Pemkab Pemalang'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=600&fit=crop',
                'alt' => 'Layanan Masyarakat'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1200&h=600&fit=crop',
                'alt' => 'E-Lapor Online'
            ],
        ];

        return Inertia::render('Landing', [
            'stats' => $stats,
            'publicReports' => $publicReports,
            'heroImages' => $heroImages,
        ]);
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
        // Get current OPD user from session
        $email = session('email');
        $user = null;
        $opdId = null;

        if ($email) {
            $user = DB::table('pengguna')->where('email', $email)->first();
            if ($user) {
                $opdAssignment = DB::table('opd_pengguna')
                    ->where('pengguna_id', $user->id)
                    ->first();
                if ($opdAssignment) {
                    $opdId = $opdAssignment->opd_id;
                }
            }
        }

        // Get statistics
        $stats = [
            'totalAduan' => 0,
            'diajukan' => 0,
            'diproses' => 0,
            'selesai' => 0,
        ];

        $assignedComplaints = [];

        if ($opdId) {
            // Total complaints for this OPD
            $stats['totalAduan'] = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->count();

            // Assigned (status = "Ditugaskan")
            $stats['diajukan'] = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->where('status_aduan.id', 2) // Ditugaskan
                ->count();

            // In Progress (status = "Diproses")
            $stats['diproses'] = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->where('status_aduan.id', 3) // Diproses
                ->count();

            // Completed (status = "Selesai")
            $stats['selesai'] = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->where('status_aduan.id', 4) // Selesai
                ->count();

            // Get assigned complaints with kategori, jenis, lokasi
            $assignedComplaints = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->join('masyarakat', 'aduan.masyarakat_id', '=', 'masyarakat.id')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->join('kategori_aduan', 'aduan.kategori_aduan_id', '=', 'kategori_aduan.id') // kategori
                ->join('akses_aduan', 'aduan.akses_aduan_id', '=', 'akses_aduan.id')           // jenis
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->select(
                    'aduan.id as id',
                    DB::raw('LEFT(aduan.isi_aduan, 50) as judul'),
                    'masyarakat.nama_lengkap as pelapor',
                    'status_aduan.nama_status as status',
                    DB::raw('DATE(aduan.tanggal_dibuat) as tanggal'),
                    'kategori_aduan.nama_kategori as kategori',
                    'akses_aduan.nama_akses_aduan as nama_akses_aduan',
                    'aduan.lokasi as lokasi'
                )
                ->orderBy('aduan.tanggal_dibuat', 'desc')
                ->limit(10)
                ->get();
        }

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
