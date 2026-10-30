<?php

namespace Database\Seeders;

use App\Models\Scan;
use Illuminate\Database\Seeder;

/**
 * Data contoh agar dashboard tidak kosong saat pertama dijalankan.
 * Baris ini bukan keluaran model nyata dan ditandai kolom "contoh".
 */
class ScanSeeder extends Seeder
{
    public function run(): void
    {
        // [kelas, keyakinan, inferensi (ms), berkas, menit yang lalu]
        $data = [
            ['sapi', .94, 1120, 'sate-sapi.jpg', 2880], ['ayam', .91, 980, 'ayam-geprek.jpg', 2700],
            ['babi', .88, 1430, 'bbq-pork.jpg', 2400], ['sayur', .96, 760, 'pecel.jpg', 1980],
            ['alkohol', .83, 1310, 'botol-wine.jpg', 1700], ['sapi', .58, 1890, 'rawon-gelap.jpg', 1440],
            ['ayam', .93, 1040, 'soto-ayam.jpg', 1100], ['babi', .79, 2140, 'babi-panggang.jpg', 900],
            ['sayur', .89, 820, 'gado-gado.jpg', 700], ['sapi', .92, 1250, 'bakso.jpg', 480],
            ['ayam', .62, 1570, 'mie-ayam.jpg', 300], ['alkohol', .55, 1710, 'gelas-minuman.jpg', 140],
            ['sayur', .97, 700, 'capcay.jpg', 60], ['babi', .91, 1380, 'char-siu.jpg', 25],
        ];

        foreach ($data as [$kelas, $conf, $ms, $berkas, $menit]) {
            $scan = new Scan([
                'berkas' => $berkas, 'kelas' => $kelas, 'conf' => $conf, 'ms' => $ms,
                'simulasi' => true, 'contoh' => true,
            ]);
            $scan->created_at = now()->subMinutes($menit);
            $scan->updated_at = now()->subMinutes($menit);
            $scan->save();
        }
    }
}
