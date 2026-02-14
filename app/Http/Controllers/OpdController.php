<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Aduan;
use App\Models\UnitOpd;
use App\Models\StatusAduan;
use Illuminate\Support\Facades\DB;

class OpdController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/OpdIndex');
    }

    public function create()
    {
        return Inertia::render('Admin/OpdCreate');
    }

    public function store(Request $request)
    {
        // implement storing logic
        return redirect()->route('master.opd');
    }

    public function edit($opd)
    {
        return Inertia::render('Admin/OpdEdit', ['opdId' => $opd]);
    }

    public function update(Request $request, $opd)
    {
        // implement update logic
        return redirect()->route('master.opd');
    }

    public function destroy($opd)
    {
        // implement destroy logic
        return redirect()->route('master.opd');
    }

    // OPD dashboard
    public function dashboard()
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

        // Initialize stats with 0 for all statuses
        $stats = [
            'diajukan' => 0,      // ID 1
            'diverifikasi' => 0,  // ID 2
            'ditolak' => 0,       // ID 3
            'diproses' => 0,      // ID 4
            'selesai' => 0,       // ID 5
        ];

        $assignedComplaints = [];

        if ($opdId) {
            // Fetch counts grouped by status_aduan_id for this OPD
            $counts = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->select('aduan.status_aduan_id', DB::raw('count(*) as total'))
                ->groupBy('aduan.status_aduan_id')
                ->pluck('total', 'status_aduan_id');

            // Map counts to stats array
            $stats['diajukan'] = $counts->get(1, 0);
            $stats['diverifikasi'] = $counts->get(2, 0);
            $stats['ditolak'] = $counts->get(3, 0);
            $stats['diproses'] = $counts->get(4, 0);
            $stats['selesai_opd'] = $counts->get(6, 0);

            // Get assigned complaints (latest 10)
            $assignedComplaints = DB::table('aduan')
                ->join('kategori_aduan_opd', 'aduan.kategori_aduan_id', '=', 'kategori_aduan_opd.kategori_aduan_id')
                ->join('masyarakat', 'aduan.masyarakat_id', '=', 'masyarakat.id')
                ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
                ->join('kategori_aduan', 'aduan.kategori_aduan_id', '=', 'kategori_aduan.id')
                ->join('akses_aduan', 'aduan.akses_aduan_id', '=', 'akses_aduan.id')
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->whereIn('aduan.status_aduan_id', [4, 6])
                ->select(
                    'aduan.id as id',
                    DB::raw('LEFT(aduan.isi_aduan, 50) as judul'),
                    'masyarakat.nama_lengkap as pelapor',
                    'status_aduan.nama_status as status',
                    DB::raw('DATE(aduan.tanggal_dibuat) as tanggal'),
                    'kategori_aduan.nama_kategori as kategori',
                    'akses_aduan.nama_akses_aduan',
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
     * Show the wizard for a given aduan using real DB data.
     */
    public function wizard($aduanId)
    {
        $aduan = Aduan::with(['masyarakat.pengguna', 'kategoriAduan', 'aksesAduan', 'statusAduan', 'riwayatStatus.statusAduan', 'riwayatStatus.pengguna'])->find($aduanId);
        if (!$aduan) {
            abort(404);
        }

        // Current logged-in pengguna from session (if any)
        $user = null;
        $email = session('email');
        if ($email) {
            $user = DB::table('pengguna')->where('email', $email)->first();
        }

        // Attempt to determine OPD info for current user if assigned
        $currentUserOpd = null;
        if ($user) {
            $currentUserOpd = DB::table('opd')
                ->join('opd_pengguna', 'opd.id', '=', 'opd_pengguna.opd_id')
                ->where('opd_pengguna.pengguna_id', $user->id)
                ->select('opd.id', 'opd.nama_opd')
                ->first();
        }

        // Load units and status options to populate selects. If the current user belongs
        // to an OPD, return only units for that OPD so OPD users see their own units.
        if ($currentUserOpd) {
            $units = UnitOpd::where('opd_id', $currentUserOpd->id)->orderBy('nama_unit')->get();
        } else {
            // Fallback: empty collection (do not expose other OPD units to non-OPD users)
            $units = collect();
        }

        $statusOptions = StatusAduan::orderBy('id')->get();

        return Inertia::render('OPD/AduanWizard', [
            'aduan' => $aduan,
            'units' => $units,
            'statusOptions' => $statusOptions,
            'currentUserOpd' => $currentUserOpd,
            'user' => $user,
        ]);
    }

    /**
     * Return PICs for a given unit. If the unit has a `nama_pengguna` field set,
     * return that as the PIC. Otherwise, return users associated with the unit's OPD.
     */
    public function getPicByUnit($unitOpdId)
    {
        $unit = UnitOpd::find($unitOpdId);
        if (!$unit) {
            return response()->json(['success' => false, 'message' => 'Unit not found', 'pics' => []]);
        }

        // If a specific username is stored on the unit, return it directly
        if (!empty($unit->nama_pengguna)) {
            return response()->json([
                'success' => true,
                'unit_opd' => $unit,
                'pics' => [],
            ]);
        }

        // Otherwise return pengguna linked to the same OPD via opd_pengguna
        $pics = DB::table('opd_pengguna')
            ->join('pengguna', 'opd_pengguna.pengguna_id', '=', 'pengguna.id')
            ->where('opd_pengguna.opd_id', $unit->opd_id)
            ->select('pengguna.id', 'pengguna.nama_pengguna', 'pengguna.email')
            ->get();

        return response()->json(['success' => true, 'pics' => $pics]);
    }

    public function saveWizardPartial(Request $request, $aduan)
    {
        // Keep existing stub behaviour: acknowledge save. Full saving logic handled elsewhere.
        return response()->json(['saved' => true]);
    }

    public function getWizardDraft($aduan)
    {
        // Try to read draft from aduan_wizard table if exists
        $draft = DB::table('aduan_wizard')->where('aduan_id', $aduan)->first();
        return response()->json(['success' => true, 'data' => $draft]);
    }

    /**
     * Get all photos from the aduan folder
     */
    public function getAduanPhotos($aduanId)
    {
        $aduan = Aduan::find($aduanId);
        if (!$aduan) {
            return response()->json(['success' => false, 'message' => 'Aduan not found', 'data' => []]);
        }

        $noAduan = $aduan->no_aduan;
        $uploadDir = public_path("upload_aduan/{$noAduan}");

        $photos = [];
        if (is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($fileExt, $allowedExts)) {
                    $photos[] = $file;
                }
            }
        }

        return response()->json(['success' => true, 'data' => $photos]);
    }

    public function submitWizard(Request $request, $aduan)
    {
        $aduanModel = Aduan::find($aduan);
        if (!$aduanModel) {
            abort(404);
        }

        // Get current pengguna from session (if any)
        $pengguna = null;
        $email = session('email');
        if ($email) {
            $pengguna = DB::table('pengguna')->where('email', $email)->first();
        }
        $penggunaId = $pengguna ? $pengguna->id : null;

        $data = $request->all();

        // Untuk OPD, status_aduan_id harus 4 (Diproses)
        $statusId = 4;

        // waktu status dan catatan, pastikan format timestamp
        $rawWaktu = $data['waktu_status']['waktu_status'] ?? $data['waktu_status']['waktu'] ?? null;
        if ($rawWaktu) {
            // Jika input hanya tanggal, tambahkan waktu sekarang
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawWaktu)) {
                $waktuStatus = \Carbon\Carbon::parse($rawWaktu . ' ' . now()->format('H:i:s'))
                    ->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
            } else {
                $waktuStatus = \Carbon\Carbon::parse($rawWaktu)
                    ->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
            }
        } else {
            $waktuStatus = now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
        }
        $catatan = $data['waktu_status']['catatan_waktu'] ?? $data['waktu_status']['catatan'] ?? '';

        // unit kerja
        $unitOpdId = $data['assignment']['unit_kerja'] ?? null;

        // Update the aduan status in aduan table
        $aduanModel->update(['status_aduan_id' => $statusId]);

        // Insert new riwayat_status_aduan for every update
        DB::table('riwayat_status_aduan')->insert([
            'aduan_id' => $aduanModel->id,
            'status_aduan_id' => $statusId,
            'catatan' => $catatan,
            'pengguna_id' => $penggunaId,
            'unit_opd_id' => $unitOpdId,
            'waktu_status_aduan' => $waktuStatus,
            'tanggal_dibuat' => now(),
            'tanggal_diubah' => now(),
        ]);

        // Insert or update tanggapan_aduan for this pengguna if penggunaId exists
        if ($penggunaId) {
            DB::table('tanggapan_aduan')->updateOrInsert(
                ['aduan_id' => $aduanModel->id, 'pengguna_id' => $penggunaId],
                ['tanggal_tanggapan' => $waktuStatus]
            );
        }

        // Optional: delete any draft saved for this aduan
        // DB::table('aduan')->where('aduan_id', $aduanModel->id)->delete();    

        // Redirect OPD user back to their OPD dashboard with a success flash message
        return redirect()
            ->route('opd.dashboard')
            ->with('success', 'Aduan berhasil diselesaikan dan pelapor akan diberitahu.');
    }
}
