<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    /**
     * Reverse geocoding - get address from coordinates
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lon = $request->lon;

        try {
            // Call Nominatim API from server-side (no CORS issue)
            $response = Http::withHeaders([
                'User-Agent' => 'CitizenComplaintApp/1.0 (Laravel)',
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lon,
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Build readable address
                $address = $data['address'] ?? [];
                $parts = [];

                // Road/Street
                if (!empty($address['road'])) $parts[] = $address['road'];
                elseif (!empty($address['street'])) $parts[] = $address['street'];
                elseif (!empty($address['pedestrian'])) $parts[] = $address['pedestrian'];

                // Suburb/Village
                if (!empty($address['hamlet'])) $parts[] = $address['hamlet'];
                elseif (!empty($address['suburb'])) $parts[] = $address['suburb'];
                elseif (!empty($address['village'])) $parts[] = $address['village'];
                elseif (!empty($address['neighbourhood'])) $parts[] = $address['neighbourhood'];

                // City/Town
                if (!empty($address['city'])) $parts[] = $address['city'];
                elseif (!empty($address['town'])) $parts[] = $address['town'];
                elseif (!empty($address['municipality'])) $parts[] = $address['municipality'];
                elseif (!empty($address['county'])) $parts[] = $address['county'];

                // State
                if (!empty($address['state'])) $parts[] = $address['state'];

                $addressString = !empty($parts)
                    ? implode(', ', $parts)
                    : ($data['display_name'] ?? 'Lokasi tidak dikenali');

                return response()->json([
                    'success' => true,
                    'address' => $addressString,
                    'full_data' => $data,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan alamat dari API',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
