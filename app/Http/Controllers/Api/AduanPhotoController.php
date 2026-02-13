<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AduanPhotoController extends Controller
{
    /**
     * List photos for an aduan stored in public/upload_aduan/{no_aduan}
     *
     * GET /api/opd/aduan/{id}/photos
     */
    public function index($id)
    {
        $aduan = Aduan::find($id);
        if (! $aduan) {
            return response()->json(["success" => false, "message" => "Aduan not found", "data" => []], 404);
        }

        $noAduan = $aduan->no_aduan ?? null;
        if (! $noAduan) {
            return response()->json(["success" => true, "data" => []]);
        }

        $dir = public_path("upload_aduan/{$noAduan}");
        if (! File::exists($dir)) {
            return response()->json(["success" => true, "data" => []]);
        }

        $files = File::files($dir);
        $names = array_map(function ($f) {
            return $f->getFilename();
        }, $files);

        return response()->json(["success" => true, "data" => $names]);
    }
}
