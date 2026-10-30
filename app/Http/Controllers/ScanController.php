<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class ScanController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image'    => 'Berkas harus berupa gambar.',
            'foto.mimes'    => 'Format harus JPG, PNG, atau WebP.',
            'foto.max'      => 'Ukuran foto melebihi 10 MB. Pilih foto yang lebih kecil.',
        ]);

        /** @var UploadedFile $foto */
        $foto = $request->file('foto');
        $url = config('foodai.fastapi_url');

        if ($url) {
            $hasil = $this->panggilFastApi(rtrim($url, '/') . '/predict', $foto);
            if ($hasil instanceof JsonResponse) {
                return $hasil;
            }
            $data = $hasil;
        } else {
            $data = $this->simulasi($foto);
        }

        $scan = Scan::create($data + ['berkas' => $foto->getClientOriginalName()]);

        return response()->json([
            'kode'         => $scan->kode,
            'kelas'        => $scan->kelas,
            'nama_kelas'   => $scan->nama_kelas,
            'conf'         => $scan->conf,
            'ms'           => $scan->ms,
            'box'          => $scan->box,
            'status'       => $scan->status,
            'status_label' => $scan->status_label,
            'status_cls'   => $scan->status_cls,
            'lewat_target' => $scan->lewat_target,
            'ambang'       => config('foodai.ambang'),
            'simulasi'     => $scan->simulasi,
        ]);
    }

    /**
     * Mengirim foto ke layanan AI Python (FastAPI) dan membaca hasilnya.
     * Kontrak: POST /predict (multipart, field "file") membalas
     * {"kelas": "babi", "conf": 0.91, "box": {"x": 12, "y": 18, "w": 40, "h": 35}}
     * dengan box dalam persen terhadap lebar dan tinggi gambar.
     */
    private function panggilFastApi(string $endpoint, UploadedFile $foto): array|JsonResponse
    {
        $mulai = microtime(true);

        try {
            $res = Http::timeout(15)
                ->attach('file', file_get_contents($foto->getRealPath()), $foto->getClientOriginalName())
                ->post($endpoint);
        } catch (ConnectionException $e) {
            return response()->json(['message' => 'Layanan AI tidak dapat dihubungi. Coba lagi sebentar lagi.'], 503);
        }

        if ($res->status() === 404) {
            return response()->json(['message' => 'Tidak ada objek makanan yang dikenali pada foto ini.'], 422);
        }

        $d = $res->json();
        if ($res->failed() || ! is_array($d) || ! isset(Scan::KELAS[$d['kelas'] ?? ''])) {
            return response()->json(['message' => 'Layanan AI membalas dengan format yang tidak dikenali.'], 502);
        }

        return [
            'kelas'    => $d['kelas'],
            'conf'     => (float) ($d['conf'] ?? 0),
            'box'      => $d['box'] ?? null,
            'ms'       => (int) round((microtime(true) - $mulai) * 1000),
            'simulasi' => false,
        ];
    }

    /** Mode simulasi: kelas dan skor dihitung dari nama dan ukuran berkas, bukan dari isi gambar. */
    private function simulasi(UploadedFile $foto): array
    {
        $h = crc32($foto->getClientOriginalName() . ':' . $foto->getSize());
        $kelas = array_keys(Scan::KELAS)[$h % count(Scan::KELAS)];

        return [
            'kelas'    => $kelas,
            'conf'     => (50 + (($h >> 3) % 46)) / 100,
            'ms'       => 800 + (($h >> 6) % 1200),
            'box'      => [
                'x' => 10 + (($h >> 5) % 28),
                'y' => 14 + (($h >> 7) % 26),
                'w' => 34 + (($h >> 9) % 24),
                'h' => 30 + (($h >> 11) % 24),
            ],
            'simulasi' => true,
        ];
    }
}
