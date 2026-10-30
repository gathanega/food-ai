@extends('layouts.app')
@section('title', 'Proposal dan Jadwal')

@section('content')
<section class="view">
    <div class="head">
        <div>
            <h1>Proposal dan jadwal</h1>
            <p>Ringkasan proposal penelitian, rencana delapan minggu, dan cara pengujiannya.</p>
        </div>
    </div>

    <div class="card">
        <span class="sub" style="text-transform:uppercase;letter-spacing:.08em;font-weight:700;font-size:11px">Judul penelitian</span>
        <h2 class="title-big">Integrasi Teknologi Artificial Intelligence (AI) Berbasis Computer Vision pada Aplikasi Web untuk Identifikasi Visual Makanan Halal dan Haram</h2>
        <div class="facts">
            <div><small>Mata kuliah</small><b>Agama Islam</b></div>
            <div><small>Dosen pembina</small><b>Dr. Sugeng Santoso, S.Pd., M.Pd.</b></div>
            <div><small>Program studi</small><b>Teknologi Rekayasa Perangkat Lunak, Fakultas Vokasi</b></div>
            <div><small>Institusi</small><b>Universitas Brawijaya</b></div>
            <div><small>Disusun</small><b>Malang, 9 Oktober 2026</b></div>
        </div>
    </div>

    <div class="grid2">
        <div class="card">
            <div class="card-head"><h2>Tujuan penelitian</h2></div>
            <ul class="bul">
                <li>Merancang kerangka kerja aplikasi web yang menghubungkan unggah foto dengan layanan AI untuk pemrosesan citra secara <i>real-time</i>.</li>
                <li>Melatih dan mengevaluasi model <i>Deep Learning</i> agar mendeteksi objek terindikasi haram dengan presisi tinggi.</li>
                <li>Membangun basis data riwayat pemindaian sebagai pendataan digital yang transparan.</li>
            </ul>
        </div>
        <div class="card">
            <div class="card-head"><h2>Target luaran</h2></div>
            <ul class="bul">
                <li>Prototipe aplikasi web interaktif identifikasi visual makanan halal/haram berbasis <i>microservices</i>.</li>
                <li>Dataset citra makanan halal dan non-halal yang sudah terlabel.</li>
            </ul>
            <div class="card-head" style="margin-top:20px"><h2>Tim penyusun</h2></div>
            <div class="team">
                <span>Gathan Ega Fyandhika</span><span>Layana Balin Santoso</span><span>Aura Maysa Islami</span><span>Eqtada Bilhadi Muhammad</span><span>'Abdul 'Aziz</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h2>Jadwal pelaksanaan</h2><p class="sub">Rencana delapan minggu</p></div></div>
        <div class="gantt">
            <div class="gwk"><span>M1</span><span>M2</span><span>M3</span><span>M4</span><span>M5</span><span>M6</span><span>M7</span><span>M8</span></div>
            @foreach ([
                ['Analisis kebutuhan, UI/UX, dataset awal', 'M1–2', 1],
                ['Training YOLOv8 dan REST API FastAPI', 'M3–4', 3],
                ['Backend, antarmuka web, integrasi database', 'M5–6', 5],
                ['Pengujian menyeluruh, Precision dan Recall, laporan', 'M7–8', 7],
            ] as [$kegiatan, $minggu, $mulai])
                <div class="gr">
                    <div class="gl2">{{ $kegiatan }}<i>{{ $minggu }}</i></div>
                    <div class="gtr"><b style="grid-column: {{ $mulai }} / span 2"></b></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h2>Tahapan pengembangan</h2><p class="sub">Mengikuti System Development Life Cycle</p></div></div>
        <div class="sdlc">
            <div><small>Tahap 1</small><b>Analisis dan Desain</b><span>Kebutuhan pengguna dan rancangan UI/UX web.</span></div>
            <div><small>Tahap 2</small><b>Pengumpulan Data</b><span>Mengumpulkan dan melabeli citra halal dan non-halal.</span></div>
            <div><small>Tahap 3</small><b>Pelatihan Model AI</b><span>Melatih YOLOv8 dengan framework Python.</span></div>
            <div><small>Tahap 4</small><b>Backend dan API</b><span>REST API dengan Laravel dan FastAPI.</span></div>
            <div><small>Tahap 5</small><b>Integrasi Sistem</b><span>Menggabungkan antarmuka web, backend, dan database.</span></div>
            <div><small>Tahap 6</small><b>Pengujian dan Evaluasi</b><span>Uji fungsi dan ukur akurasi model.</span></div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h2>Rencana pengujian</h2><p class="sub">Tiga aspek dan indikator keberhasilannya</p></div></div>
        <div class="tbl">
            <table style="min-width:560px">
                <thead><tr><th>Jenis pengujian</th><th>Tujuan dan indikator keberhasilan</th></tr></thead>
                <tbody>
                    <tr><td><b>Fungsional</b></td><td>Interaksi Laravel, FastAPI, dan database berjalan lancar tanpa error.</td></tr>
                    <tr><td><b>Model AI</b></td><td>Mengevaluasi Precision dan Recall model dalam mengenali kontaminan visual.</td></tr>
                    <tr><td><b>Performa</b></td><td>Waktu respons inferensi gambar di bawah 2 detik per pengujian.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
