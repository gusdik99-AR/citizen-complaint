<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AduanController extends Controller
{
    /**
     * Show the form for creating a new complaint
     */
    public function create()
    {
        // Get jenis options from akses_aduan table
        $jenisOptions = DB::table('akses_aduan')
            ->select('id', 'nama_akses_aduan')
            ->get();

        // Get kategori options from kategori_aduan table
        $kategoriOptions = DB::table('kategori_aduan')
            ->select('id', 'nama_kategori')
            ->get();

        return Inertia::render('Citizen/CreateAduan', [
            'jenisOptions' => $jenisOptions,
            'kategoriOptions' => $kategoriOptions,
        ]);
    }

    /**
     * Store a newly created complaint in storage
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'foto' => 'required|image|max:2048', // max 2MB
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'jenis' => 'required|exists:akses_aduan,id',
            'kategori' => 'required|exists:kategori_aduan,id',
            'deskripsi' => 'required|string|min:20',
        ], [
            'foto.required' => 'Foto bukti wajib diupload',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 2MB',
            'latitude.required' => 'Lokasi diperlukan',
            'longitude.required' => 'Lokasi diperlukan',
            'jenis.required' => 'Jenis aduan harus dipilih',
            'jenis.exists' => 'Jenis aduan tidak valid',
            'kategori.required' => 'Kategori aduan harus dipilih',
            'kategori.exists' => 'Kategori aduan tidak valid',
            'deskripsi.required' => 'Deskripsi aduan harus diisi',
            'deskripsi.min' => 'Deskripsi minimal 20 karakter',
        ]);

        try {
            DB::beginTransaction();

            // Get authenticated user ID from session
            $penggunaId = session('pengguna_id');

            // Get masyarakat_id from pengguna_id
            $masyarakat = DB::table('masyarakat')
                ->where('pengguna_id', $penggunaId)
                ->first();

            if (!$masyarakat) {
                return back()->withErrors([
                    'error' => 'Data masyarakat tidak ditemukan. Silakan hubungi administrator.'
                ])->withInput();
            }

            // Upload foto
            $fotoPath = $request->file('foto')->store('aduan', 'public');

            // Generate no_aduan (format: ADU-YYYYMMDD-XXXX)
            $today = now()->format('Ymd');
            $latestAduan = DB::table('aduan')
                ->where('no_aduan', 'like', "ADU-{$today}-%")
                ->orderBy('no_aduan', 'desc')
                ->first();

            if ($latestAduan) {
                $lastNumber = (int) substr($latestAduan->no_aduan, -4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $noAduan = "ADU-{$today}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Insert into aduan table
            DB::table('aduan')->insert([
                'no_aduan' => $noAduan,
                'tanggal_lapor' => now(),
                'isi_aduan' => $validated['deskripsi'],
                'lokasi' => $validated['lokasi'] ?? ($validated['latitude'] . ', ' . $validated['longitude']),
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'foto' => $fotoPath,
                'masyarakat_id' => $masyarakat->id,
                'kategori_aduan_id' => $validated['kategori'],
                'akses_aduan_id' => $validated['jenis'],
                'status_aduan_id' => 1, // Default: Diajukan (urutan 1)
                'tanggal_selesai' => null,
                'tanggal_dibuat' => now(),
                'tanggal_diubah' => now(),
            ]);

            DB::commit();

            // Redirect back to home with success message
            return redirect()->route('home')->with('success', 'Aduan berhasil dilaporkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // If upload fails, delete the uploaded file
            if (isset($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            // Log error for debugging
            \Log::error('Error creating aduan: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan aduan. Silakan coba lagi.'
            ])->withInput();
        }
    }
}
