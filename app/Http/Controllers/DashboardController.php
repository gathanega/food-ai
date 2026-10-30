<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function ringkasan(): View
    {
        $semua = Scan::orderBy('id')->get();
        $n = $semua->count();
        $persen = fn (int $a) => $n > 0 ? (int) round($a / $n * 100) : 0;

        $nonhalal = $semua->filter(fn ($s) => $s->status === 'nonhalal')->count();
        $tinjau = $semua->filter(fn ($s) => $s->status === 'tinjau')->count();

        $stat = [
            'total'        => $n,
            'nonhalal'     => $nonhalal,
            'nonhalal_pct' => $persen($nonhalal),
            'tinjau'       => $tinjau,
            'tinjau_pct'   => $persen($tinjau),
            'avg_ms'       => $n > 0 ? (float) $semua->avg('ms') : 0.0,
            'lewat'        => $semua->filter(fn ($s) => $s->lewat_target)->count(),
        ];

        $sebaran = [];
        foreach (Scan::KELAS as $kode => $info) {
            $sebaran[$kode] = $semua->where('kelas', $kode)->count();
        }

        return view('ringkasan', [
            'stat'    => $stat,
            'terbaru' => $semua->sortByDesc('id')->take(5)->values(),
            'sebaran' => $sebaran,
            'maks'    => max(1, max($sebaran)),
            'seri'    => $semua->take(-14)->values()->map(fn ($s) => ['kode' => $s->kode, 'ms' => $s->ms]),
        ]);
    }

    public function analisis(): View
    {
        return view('analisis');
    }

    public function riwayat(Request $request): View
    {
        $status = $request->query('status', 'semua');
        if (! in_array($status, ['semua', 'nonhalal', 'aman', 'tinjau'], true)) {
            $status = 'semua';
        }

        $semua = Scan::orderByDesc('id')->get();
        $baris = $status === 'semua'
            ? $semua
            : $semua->filter(fn ($s) => $s->status === $status)->values();

        return view('riwayat', [
            'status' => $status,
            'baris'  => $baris,
            'total'  => $semua->count(),
        ]);
    }

    public function sistem(): View
    {
        return view('sistem');
    }

    public function proyek(): View
    {
        return view('proyek');
    }
}
