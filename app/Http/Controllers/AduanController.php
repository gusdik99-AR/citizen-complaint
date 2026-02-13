<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
            'foto' => 'required',
            'foto.*' => 'image|max:2048', // max 2MB per file
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'nullable|string',
            'jenis' => 'required|exists:akses_aduan,id',
            'kategori' => 'required|exists:kategori_aduan,id',
            'deskripsi' => 'required|string|min:20',
        ], [
            'foto.required' => 'Foto bukti wajib diupload',
            'foto.*.image' => 'File harus berupa gambar',
            'foto.*.max' => 'Ukuran foto maksimal 2MB',
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

            // Handle files
            $files = $request->file('foto');
            if (!is_array($files)) {
                $files = [$files];
            }

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
            $destinationDir = public_path('upload_aduan/' . $noAduan);

            if (!File::exists($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }

            $mainPhotoPath = null;
            $uploadedPhotos = [];

            foreach ($files as $index => $file) {
                $filename = $noAduan . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move($destinationDir, $filename);
                $path = 'upload_aduan/' . $noAduan . '/' . $filename;

                if ($index === 0) {
                    $mainPhotoPath = $path;
                }
                $uploadedPhotos[] = $path;
            }

            // Ambil opd_id dari kategori_aduan_opd
            $opdId = DB::table('kategori_aduan_opd')
                ->where('kategori_aduan_id', $validated['kategori'])
                ->value('opd_id');

            // Insert into aduan table
            $aduanId = DB::table('aduan')->insertGetId([
                'no_aduan' => $noAduan,
                'tanggal_lapor' => now(),
                'isi_aduan' => $validated['deskripsi'],
                'lokasi' => $validated['lokasi'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'foto' => $mainPhotoPath, // Store main photo for backward compatibility
                'masyarakat_id' => $masyarakat->id,
                'kategori_aduan_id' => $validated['kategori'],
                'akses_aduan_id' => $validated['jenis'],
                'opd_id' => $opdId,
                'status_aduan_id' => 1, // Default: Diajukan (urutan 1)
                'tanggal_selesai' => null,
                'tanggal_dibuat' => now(),
                'tanggal_diubah' => now(),
            ]);

            // Insert into aduan_fotos table
            $photoRecords = [];
            foreach ($uploadedPhotos as $path) {
                $photoRecords[] = [
                    'aduan_id' => $aduanId,
                    'path' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('aduan_fotos')->insert($photoRecords);

            // Create initial status history
            DB::table('riwayat_status_aduan')->insert([
                'aduan_id' => $aduanId,
                'status_aduan_id' => 1, // Diajukan
                'waktu_status_aduan' => now(),
                'catatan' => 'Aduan berhasil diajukan',
                'pengguna_id' => $penggunaId,
                'tanggal_dibuat' => now(),
                'tanggal_diubah' => now(),
            ]);

            DB::commit();

            // Redirect back to home with success message
            return redirect()->route('home')->with('success', 'Aduan berhasil dilaporkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // If upload fails, cleanup directory
            if (isset($destinationDir) && File::exists($destinationDir)) {
                File::deleteDirectory($destinationDir);
            }

            // Log error for debugging
            Log::error('Error creating aduan: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan aduan. Silakan coba lagi.'
            ])->withInput();
        }
    }

    /**
     * Display the specified complaint
     */
    public function show($id)
    {
        // Get authenticated user ID from session (null if guest)
        $penggunaId = session('pengguna_id');

        // Get complaint with all related data
        $aduan = DB::table('aduan')
            ->join('status_aduan', 'aduan.status_aduan_id', '=', 'status_aduan.id')
            ->join('kategori_aduan', 'aduan.kategori_aduan_id', '=', 'kategori_aduan.id')
            ->join('akses_aduan', 'aduan.akses_aduan_id', '=', 'akses_aduan.id')
            ->join('masyarakat', 'aduan.masyarakat_id', '=', 'masyarakat.id')
            ->join('pengguna', 'masyarakat.pengguna_id', '=', 'pengguna.id')
            ->where('aduan.id', $id)
            ->select(
                'aduan.*',
                'status_aduan.nama_status as status',
                'kategori_aduan.nama_kategori as kategori',
                'akses_aduan.nama_akses_aduan as akses',
                'pengguna.nama_pengguna as pelapor',
                'masyarakat.pengguna_id as pelapor_pengguna_id'
            )
            ->first();

        // Check if complaint exists
        if (!$aduan) {
            abort(404, 'Aduan tidak ditemukan');
        }

        // Access control
        $isGuest = !$penggunaId;
        $isOwner = $penggunaId && $aduan->pelapor_pengguna_id == $penggunaId;
        $isPublic = $aduan->akses === 'Publik';

        // Get user role if authenticated
        $userRole = null;
        if ($penggunaId) {
            $userRole = DB::table('peran_pengguna')
                ->join('peran', 'peran_pengguna.peran_id', '=', 'peran.id')
                ->where('peran_pengguna.pengguna_id', $penggunaId)
                ->value('peran.nama_peran');
        }

        $isAdminOrOpd = in_array($userRole, ['Admin', 'OPD']);

        // Check access permission
        if ($isGuest && !$isPublic) {
            // Guest can only view public complaints
            abort(403, 'Anda tidak memiliki akses ke aduan ini');
        }

        if (!$isGuest && !$isOwner && !$isPublic && !$isAdminOrOpd) {
            // Authenticated user can only view their own private complaints or public complaints
            // Admin and OPD can view all
            abort(403, 'Anda tidak memiliki akses ke aduan ini');
        }

        // Get status history, hide status_aduan_id == 6
        $riwayatStatus = DB::table('riwayat_status_aduan')
            ->join('status_aduan', 'riwayat_status_aduan.status_aduan_id', '=', 'status_aduan.id')
            ->join('pengguna', 'riwayat_status_aduan.pengguna_id', '=', 'pengguna.id')
            ->where('riwayat_status_aduan.aduan_id', $id)
            ->where('riwayat_status_aduan.status_aduan_id', '!=', 6)
            ->select(
                'riwayat_status_aduan.*',
                'status_aduan.nama_status as status',
                'pengguna.nama_pengguna as petugas'
            )
            ->orderBy('riwayat_status_aduan.waktu_status_aduan', 'desc')
            ->get();

        // Get responses from OPD
        $tanggapan = DB::table('tanggapan_aduan')
            ->join('pengguna', 'tanggapan_aduan.pengguna_id', '=', 'pengguna.id')
            ->where('tanggapan_aduan.aduan_id', $id)
            ->select(
                'tanggapan_aduan.*',
                'pengguna.nama_pengguna as petugas'
            )
            ->orderBy('tanggapan_aduan.tanggal_dibuat', 'desc')
            ->get();

        // Get photos
        $fotos = DB::table('aduan_fotos')
            ->where('aduan_id', $id)
            ->get();

        $fotoUrls = [];
        $disk = config('filesystems.default');

        if ($fotos->isNotEmpty()) {
            foreach ($fotos as $foto) {
                if ($disk === 's3') {
                    $fotoUrls[] = Storage::url($foto->path);
                } else {
                    $fotoUrls[] = asset($foto->path); // Ensure path is correct relative to public
                }
            }
        } elseif ($aduan->foto) {
            // Fallback to single photo if no aduan_fotos records (backward compatibility)
            if ($disk === 's3') {
                $fotoUrls[] = Storage::url($aduan->foto);
            } else {
                $fotoUrls[] = asset($aduan->foto);
            }
        }

        // Generate main image URL for backward compatibility in view
        if ($aduan->foto) {
            if ($disk === 's3') {
                $aduan->foto_url = Storage::url($aduan->foto);
            } else {
                $aduan->foto_url = asset($aduan->foto);
            }
        } else {
            $aduan->foto_url = null;
        }

        return Inertia::render('AduanDetail', [
            'aduan' => $aduan,
            'riwayatStatus' => $riwayatStatus,
            'tanggapan' => $tanggapan,
            'fotos' => $fotoUrls, // Pass array of photo URLs
            'isOwner' => $isOwner,
            'isGuest' => $isGuest,
        ]);
    }

    /**
     * API: Get riwayat status aduan (JSON) untuk popup di dashboard admin
     */
    public function history($id)
    {
        // Pastikan aduan ada
        $aduan = DB::table('aduan')->where('id', $id)->first();
        if (!$aduan) {
            return response()->json(['message' => 'Aduan tidak ditemukan'], 404);
        }

        $riwayatStatus = DB::table('riwayat_status_aduan')
            ->join('status_aduan', 'riwayat_status_aduan.status_aduan_id', '=', 'status_aduan.id')
            ->join('pengguna', 'riwayat_status_aduan.pengguna_id', '=', 'pengguna.id')
            ->where('riwayat_status_aduan.aduan_id', $id)
            ->where('riwayat_status_aduan.status_aduan_id', '!=', 6)
            ->select(
                'riwayat_status_aduan.id',
                'riwayat_status_aduan.waktu_status_aduan',
                'riwayat_status_aduan.catatan',
                'status_aduan.nama_status as status',
                'pengguna.nama_pengguna as petugas'
            )
            ->orderBy('riwayat_status_aduan.waktu_status_aduan', 'desc')
            ->get();

        return response()->json([
            'aduan_id' => $id,
            'riwayat' => $riwayatStatus,
        ]);
    }

    /**
     * Vote for a complaint
     */
    public function vote($id)
    {
        try {
            // Check if complaint exists
            $aduan = DB::table('aduan')->where('id', $id)->first();

            if (!$aduan) {
                return response()->json(['error' => 'Aduan tidak ditemukan'], 404);
            }

            // Increment vote count
            DB::table('aduan')
                ->where('id', $id)
                ->increment('jumlah_vote');

            // Get updated vote count
            $newVoteCount = DB::table('aduan')->where('id', $id)->value('jumlah_vote');

            return response()->json([
                'success' => true,
                'jumlah_vote' => $newVoteCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }
}
