<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DaftarAduanController extends Controller
{
    /**
     * Tampilkan daftar semua aduan
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status');
        $kategori = $request->input('kategori');

        $query = Aduan::with(['kategoriAduan', 'statusAduan', 'masyarakat', 'aksesAduan'])
            ->orderBy('tanggal_lapor', 'desc');

        if ($q) {
            $query->where(function ($b) use ($q) {
                $b->where('no_aduan', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%");
            });
        }

        if ($status) {
            $query->whereHas('statusAduan', function ($b) use ($status) {
                $b->where('nama_status', $status);
            });
        }

        if ($kategori) {
            $query->whereHas('kategoriAduan', function ($b) use ($kategori) {
                $b->where('nama_kategori', $kategori);
            });
        }

        $aduan = $query->paginate(10)->appends($request->only('q', 'status', 'kategori'));

        // Statistik status aduan
        $stats = [
            'diajukan' => Aduan::whereHas('statusAduan', function ($q) {
                $q->where('nama_status', 'Diajukan');
            })->count(),
            'diverifikasi' => Aduan::whereHas('statusAduan', function ($q) {
                $q->where('nama_status', 'Diverifikasi');
            })->count(),
            'diproses' => Aduan::whereHas('statusAduan', function ($q) {
                $q->where('nama_status', 'Diproses');
            })->count(),
            'selesai' => Aduan::whereHas('statusAduan', function ($q) {
                $q->where('nama_status', 'Selesai');
            })->count(),
            'ditolak' => Aduan::whereHas('statusAduan', function ($q) {
                $q->where('nama_status', 'Ditolak');
            })->count(),
        ];

        return Inertia::render('Admin/ManajemenAduan/DaftarAduan', [
            'aduan' => $aduan,
            'stats' => $stats,
        ]);
    }

    /**
     * Tampilkan detail aduan
     */
    public function show(Aduan $aduan)
    {
        $aduan->load(['kategoriAduan', 'statusAduan', 'masyarakat', 'aksesAduan', 'riwayatStatus', 'tanggapan']);

        return Inertia::render('Admin/ManajemenAduan/DetailAduan', [
            'aduan' => $aduan,
        ]);
    }

    /**
     * Update status aduan
     */
    public function updateStatus(Request $request, Aduan $aduan)
    {
        $validated = $request->validate([
            'status_aduan_id' => 'required|exists:status_aduan,id',
            'keterangan' => 'nullable|string',
        ]);


        $aduan->update(['status_aduan_id' => $validated['status_aduan_id']]);

        // Simpan ke riwayat_status_aduan setiap kali status diubah
        $emailSess = session('email');
        $namaSess = session('nama_pengguna');
        $penggunaRow = DB::table('pengguna')->where('email', $emailSess)->orWhere('nama_pengguna', $namaSess)->first();
        $penggunaId = $penggunaRow?->id ?? DB::table('pengguna')->value('id') ?? 1;

        \App\Models\RiwayatStatusAduan::create([
            'aduan_id' => $aduan->id,
            'status_aduan_id' => $validated['status_aduan_id'],
            'catatan' => $validated['keterangan'] ?? '',
            'pengguna_id' => $penggunaId,
            'waktu_status_aduan' => now(),
        ]);

        return redirect()->back()->with('success', 'Status aduan berhasil diperbarui');
    }

    /**
     * Transfer aduan ke OPD tertentu (simpan ke riwayat_status_aduan)
     */
    public function transfer(Request $request, Aduan $aduan)
    {
        $validated = $request->validate([
            'opd_id' => 'required|integer|exists:opd,id',
            'catatan' => 'nullable|string',
        ]);

        // Ambil pengguna_id dari sesi (fallback sederhana bila tidak ada)
        $emailSess = session('email');
        $namaSess = session('nama_pengguna');
        $penggunaRow = DB::table('pengguna')->where('email', $emailSess)->orWhere('nama_pengguna', $namaSess)->first();
        $penggunaId = $penggunaRow?->id ?? DB::table('pengguna')->value('id') ?? 1;

        // Pastikan ada unit_opd untuk opd yang dipilih
        $unit = \App\Models\UnitOpd::where('opd_id', $validated['opd_id'])->first();
        if (!$unit) {
            // Buat unit_opd minimal jika belum ada
            $opd = DB::table('opd')->where('id', $validated['opd_id'])->first();
            $unit = \App\Models\UnitOpd::create([
                'opd_id' => $validated['opd_id'],
                'nama_unit' => $opd ? 'Unit ' . $opd->nama_opd : 'Unit OPD ' . $validated['opd_id'],
                'kode_unit' => null,
                'nama_pengguna' => null,
            ]);
        }

        // Simpan ke riwayat_status_aduan, unit_opd_id harus null saat transfer ke OPD
        \App\Models\RiwayatStatusAduan::create([
            'aduan_id' => $aduan->id,
            'status_aduan_id' => 4, // set ke 4 (Diproses) saat transfer
            'catatan' => $validated['catatan'] ?? '',
            'pengguna_id' => $penggunaId,
            'waktu_status_aduan' => now(),
            'unit_opd_id' => null,
        ]);

        // Optionally update status utama aduan ke 2 juga
        $aduan->update(['status_aduan_id' => 2]);

        return response()->json([
            'success' => true,
            'message' => 'Aduan berhasil ditransfer ke OPD yang dipilih.',
        ]);
    }
}
