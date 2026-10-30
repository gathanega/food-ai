@extends('layouts.app')
@section('title', 'Riwayat Pemindaian')

@section('content')
<section class="view">
    <div class="head">
        <div>
            <h1>Riwayat pemindaian</h1>
            <p>Setiap hasil identifikasi dicatat agar bisa ditelusuri kembali. Catatan ini disimpan di database aplikasi.</p>
        </div>
        @if ($baris->contains(fn ($s) => $s->contoh))
            <span class="tag">Baris bertanda "contoh" adalah data contoh</span>
        @endif
    </div>

    <div class="card">
        <div class="card-head">
            <div class="chips" role="group" aria-label="Filter status">
                @foreach (['semua' => 'Semua', 'nonhalal' => 'Terindikasi non-halal', 'aman' => 'Tidak terindikasi', 'tinjau' => 'Perlu tinjau'] as $kode => $label)
                    <a class="chip" href="{{ route('riwayat', $kode === 'semua' ? [] : ['status' => $kode]) }}" aria-pressed="{{ $status === $kode ? 'true' : 'false' }}">{{ $label }}</a>
                @endforeach
            </div>
            <span class="sub">{{ $baris->count() }} dari {{ $total }} catatan</span>
        </div>
        <div class="tbl">
            <table>
                <thead>
                    <tr><th>ID</th><th>Waktu</th><th>Berkas</th><th>Objek</th><th>Status</th><th class="num">Keyakinan</th><th class="num">Inferensi</th></tr>
                </thead>
                <tbody>
                    @forelse ($baris as $s)
                        <tr>
                            <td class="mono">{{ $s->kode }}</td>
                            <td>{{ $s->created_at->translatedFormat('j M, H:i') }}</td>
                            <td>{{ $s->berkas }}@if ($s->contoh)<span class="ex">contoh</span>@else<span class="ex" style="color:var(--brand)">baru</span>@endif</td>
                            <td>{{ $s->nama_kelas }}</td>
                            <td><span class="pill {{ $s->status_cls }}">{{ $s->status_label }}</span></td>
                            <td class="num">{{ (int) round($s->conf * 100) }}%</td>
                            <td class="num {{ $s->lewat_target ? 'slow' : '' }}">{{ number_format($s->ms / 1000, 2, ',', '.') }} dtk</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="sub">Tidak ada catatan untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
