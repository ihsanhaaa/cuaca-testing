<?php

namespace App\Http\Controllers;

use App\Models\Cuaca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CuacaController extends Controller
{
    public function index()
    {
        $response = Http::get('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=61.01.01.2012');
        $data = $response->json();

        // Ambil informasi cuaca
        $cuaca = $data['data'][0]['cuaca'] ?? 'Tidak tersedia';
        $kecamatan = $data['data'][0]['kecamatan'] ?? 'Tidak diketahui';

        return view('welcome', compact('cuaca', 'kecamatan'));
    }
}
