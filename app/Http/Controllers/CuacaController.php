<?php

namespace App\Http\Controllers;

use App\Models\Cuaca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CuacaController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function getCuaca(Request $request)
    {
        $kodeDesa = $request->input('desa');

        if (!$kodeDesa) {
            return response()->json(['error' => 'Kode desa tidak diberikan.'], 400);
        }

        $url = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4={$kodeDesa}";

        try {
            $response = Http::get($url);
            $data = $response->json();

            if (!isset($data['data'][0])) {
                return response()->json(['error' => 'Data cuaca tidak ditemukan untuk desa ini.'], 404);
            }

            $lokasi = $data['data'][0]['lokasi'];
            $cuacaList = collect($data['data'][0]['cuaca'])->flatten(1); // Menggabungkan semua array dalam `cuaca`

            // Ambil cuaca yang paling dekat dengan waktu sekarang
            $now = now();
            $cuacaTerdekat = $cuacaList
                ->filter(fn($cuaca) => isset($cuaca['local_datetime']))
                ->sortBy(fn($cuaca) => abs($now->diffInSeconds($cuaca['local_datetime'])))
                ->first();

            if (!$cuacaTerdekat) {
                return response()->json(['error' => 'Data cuaca tidak tersedia.'], 404);
            }

            return response()->json([
                'lokasi' => [
                    'provinsi' => $lokasi['provinsi'],
                    'kotkab' => $lokasi['kotkab'],
                    'kecamatan' => $lokasi['kecamatan'],
                    'desa' => $lokasi['desa'],
                    'lon' => $lokasi['lon'],
                    'lat' => $lokasi['lat']
                ],
                'cuaca' => [
                    'tanggal' => $cuacaTerdekat['local_datetime'],
                    'suhu' => $cuacaTerdekat['t'] . '°C',
                    'kondisi' => $cuacaTerdekat['weather_desc'],
                    'kelembaban' => $cuacaTerdekat['hu'] . '%',
                    'arah_angin' => $cuacaTerdekat['wd'],
                    'kecepatan_angin' => $cuacaTerdekat['ws'] . ' km/h',
                    'ikon' => $cuacaTerdekat['image']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data cuaca.', 'message' => $e->getMessage()], 500);
        }
    }
}
