@extends('layouts.app')
@section('title', 'Ringkasan')

@section('content')
<section class="view">
    <div class="head">
        <div>
            <h1>Ringkasan pemindaian</h1>
            <p>Pantau hasil identifikasi visual makanan halal dan non-halal, waktu respons AI, dan batas kemampuan sistem.</p>
        </div>
        @if ($stat['total'] > 0 && $terbaru->every(fn ($s) => $s->contoh))
            <span class="tag">Data contoh, bukan hasil model nyata</span>
        @endif
    </div>

    <div class="card strip">
        <div><div class="k">Total pemindaian</div><div class="v">{{ $stat['total'] }}</div><div class="n">foto sudah dianalisis</div></div>
        <div><div class="k">Terindikasi non-halal</div><div class="v bad">{{ $stat['nonhalal'] }}<small>{{ $stat['nonhalal_pct'] }}%</small></div><div class="n">keyakinan {{ (int) (config('foodai.ambang') * 100) }}% atau lebih</div></div>
        <div><div class="k">Perlu tinjau</div><div class="v warn">{{ $stat['tinjau'] }}<small>{{ $stat['tinjau_pct'] }}%</small></div><div class="n">keyakinan di bawah {{ (int) (config('foodai.ambang') * 100) }}%</div></div>
        <div>
            <div class="k">Rata-rata inferensi</div>
            <div class="v {{ $stat['avg_ms'] < config('foodai.target_ms') ? 'ok' : 'warn' }}">{{ number_format($stat['avg_ms'] / 1000, 2, ',', '.') }}<small>detik</small></div>
            <div class="n">{{ $stat['lewat'] }} pemindaian melewati target 2 detik</div>
        </div>
    </div>

    <div class="grid2">
        <div class="card">
            <div class="card-head">
                <div><h2>Pemindaian terakhir</h2><p class="sub">5 unggahan terbaru</p></div>
                <a href="{{ route('riwayat') }}" style="color:var(--brand);font-weight:600;font-size:13px">Lihat semua</a>
            </div>
            <div class="rows">
                @forelse ($terbaru as $s)
                    <div class="row">
                        <div class="t">{{ $s->nama_kelas }}</div>
                        <span class="pill {{ $s->status_cls }}">{{ $s->status_label }}</span>
                        <div class="m"><span class="mono">{{ $s->kode }}</span> · {{ $s->berkas }} · {{ (int) round($s->conf * 100) }}%</div>
                    </div>
                @empty
                    <p class="sub">Belum ada pemindaian. Unggah foto di menu Analisis Foto atau jalankan seeder contoh.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-head"><div><h2>Objek yang terdeteksi</h2><p class="sub">Jumlah pemindaian per kelas</p></div></div>
            <div class="bars">
                @foreach (\App\Models\Scan::KELAS as $kode => $info)
                    <div class="bar">
                        <div class="l">{{ $info['nama'] }}<i>{{ $info['halal'] ? 'kelas halal' : 'kelas non-halal' }}</i></div>
                        <div class="tr"><div class="fl {{ $info['halal'] ? '' : 'nh' }}" style="width: {{ $sebaran[$kode] / $maks * 100 }}%"></div></div>
                        <div class="c">{{ $sebaran[$kode] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h2>Waktu inferensi per pemindaian</h2><p class="sub">Target pengujian performa: di bawah 2 detik per gambar</p></div></div>
        <div id="chart-infer"></div>
        <div class="legend"><span><i></i>Waktu inferensi</span><span><i class="t"></i>Batas target 2 detik</span></div>
    </div>

    <div class="note">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2.5 20h19L12 3Z"/><path d="M12 10v4M12 17.5v.01"/></svg>
        <div>
            <h3>Alat saring visual tingkat pertama</h3>
            <p>AI hanya membaca apa yang terlihat di foto. Bahan yang sudah menyatu dalam makanan, seperti rhum cair atau lemak babi (lard) di dalam adonan kue, tidak bisa dikenali. Hasil di sini bukan pengganti uji laboratorium maupun sertifikasi resmi LPPOM MUI.</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    window.FOODAI = {
        series: @json($seri),
        targetMs: {{ (int) config('foodai.target_ms') }}
    };
</script>
<script src="{{ asset('js/foodai.js') }}"></script>
@endpush
