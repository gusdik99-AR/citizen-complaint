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
                ->join('lokasi', 'aduan.lokasi_id', '=', 'lokasi.id')                          // lokasi
                ->where('kategori_aduan_opd.opd_id', $opdId)
                ->select(
                    'aduan.id as id',
                    DB::raw('LEFT(aduan.isi_aduan, 50) as judul'),
                    'masyarakat.nama_lengkap as pelapor',
                    'status_aduan.nama_status as status',
                    DB::raw('DATE(aduan.tanggal_dibuat) as tanggal'),
                    'kategori_aduan.nama_kategori as kategori',
                    'akses_aduan.nama_akses_aduan as nama_akses_aduan',
                    'lokasi.nama_lokasi as lokasi'
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
        $aduan = Aduan::with(['masyarakat.pengguna', 'kategoriAduan', 'aksesAduan', 'statusAduan'])->find($aduanId);
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
        $penggunaId = $pengguna->id ?? null;

        $data = $request->all();

        // Determine status id if provided (status_pengerjaan may contain nama_status)
        $statusId = null;
        if (!empty($data['status']['status_pengerjaan'] ?? null)) {
            $statusName = $data['status']['status_pengerjaan'];
            $statusRow = StatusAduan::where('nama_status', $statusName)->first();
            if ($statusRow) $statusId = $statusRow->id;
        }

        // waktu status and catatan
        $waktuStatus = $data['waktu_status']['waktu_status'] ?? $data['waktu_status']['waktu'] ?? now();
        $catatan = $data['waktu_status']['catatan_waktu'] ?? $data['waktu_status']['catatan'] ?? null;



        // unit kerja
        $unitOpdId = $data['assignment']['unit_kerja'] ?? null;

        // Update the aduan status in aduan table
        if ($statusId) {
            $aduanModel->update(['status_aduan_id' => $statusId]);
        }

        // Upsert riwayat_status_aduan keyed by aduan_id (one riwayat per aduan)
        DB::table('riwayat_status_aduan')->updateOrInsert(
            ['aduan_id' => $aduanModel->id],
            [
                'status_aduan_id' => $statusId,
                'catatan' => $catatan,
                'pengguna_id' => $penggunaId,
                'unit_opd_id' => $unitOpdId,
                'waktu_status_aduan' => $waktuStatus,

                'tanggal_dibuat' => now(),
                'tanggal_diubah' => now(),
            ]
        );

        // Validasi input
        $validated = $request->validate([
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_target'  => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ]);

        // Ambil model berdasarkan ID
        $aduan = Aduan::findOrFail($aduanModel->id);

        // Update data
        $aduan->update($validated);

        // Insert or update tanggapan_aduan for this pengguna if penggunaId exists
        if ($penggunaId) {
            DB::table('tanggapan_aduan')->updateOrInsert(
                ['aduan_id' => $aduanModel->id, 'pengguna_id' => $penggunaId],
                ['tanggal_tanggapan' => $waktuStatus]
            );
        }

        // Optional: delete any draft saved for this aduan
        DB::table('aduan_wizard')->where('aduan_id', $aduanModel->id)->delete();

        // Redirect OPD user back to their OPD dashboard with a success flash message
        return redirect()
            ->route('opd.dashboard')
            ->with('success', 'Aduan berhasil diselesaikan dan pelapor akan diberitahu.');
    }
}
