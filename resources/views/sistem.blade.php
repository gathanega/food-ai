@extends('layouts.app')
@section('title', 'Arsitektur Sistem')

@section('content')
<section class="view">
    <div class="head">
        <div>
            <h1>Arsitektur sistem</h1>
            <p>Aplikasi dibagi menjadi layanan-layanan kecil. Pemrosesan gambar dipisah dari server utama supaya beban berat model tidak memperlambat web.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h2>Empat komponen utama</h2><p class="sub">Dari antarmuka pengguna sampai penyimpanan riwayat</p></div></div>
        <div class="flow">
            <div class="node"><span class="lab">Frontend</span><h3>Antarmuka web</h3><span class="tech">Blade + JavaScript</span><p>Tampilan responsif untuk mengunggah foto dan membaca hasil.</p></div>
            <div class="node"><span class="lab">Backend dan API Gateway</span><h3>Server utama</h3><span class="tech">Laravel (PHP)</span><p>Menerima permintaan, mengelola riwayat, dan memanggil layanan AI.</p></div>
            <div class="node ai"><span class="lab">AI Microservice</span><h3>Layanan inferensi</h3><span class="tech">Python FastAPI + YOLOv8</span><p>Mendeteksi objek pada gambar dan mengembalikan kotak batas serta skor keyakinan.</p></div>
            <div class="node"><span class="lab">Database</span><h3>Riwayat pemindaian</h3><span class="tech">SQLite / MySQL via Eloquent</span><p>Menyimpan setiap hasil identifikasi untuk pendataan yang transparan.</p></div>
        </div>
    </div>

    <div class="grid2">
        <div class="card">
            <div class="card-head"><div><h2>Alur satu permintaan</h2><p class="sub">Apa yang terjadi setelah foto diunggah</p></div></div>
            <ol class="steps">
                <li><div><b>Pengguna mengunggah foto</b><span>Halaman Analisis Foto mengirim berkas ke rute <span class="mono">POST /analisis</span>.</span></div></li>
                <li><div><b>Laravel memanggil layanan AI</b><span>ScanController meneruskan foto ke <span class="mono">/predict</span> di FastAPI.</span></div></li>
                <li><div><b>YOLOv8 menjalankan inferensi</b><span>Model menandai objek dan menghitung skor keyakinan.</span></div></li>
                <li><div><b>Hasil dikembalikan dan dicatat</b><span>Status tampil di layar, lalu riwayat tersimpan lewat model Scan.</span></div></li>
            </ol>
        </div>
        <div class="card">
            <div class="card-head"><div><h2>Data dan pelatihan model</h2><p class="sub">Rencana pengumpulan dataset</p></div></div>
            <ul class="bul">
                <li><b>Kelas halal:</b> citra sapi, ayam, dan sayuran.</li>
                <li><b>Kelas non-halal:</b> citra daging babi dan botol minuman beralkohol.</li>
                <li><b>Dataset:</b> ribuan citra terlabel dengan kotak batas.</li>
                <li><b>Model:</b> YOLOv8 menghasilkan <i>bounding box</i> dan persentase keyakinan untuk tiap objek.</li>
                <li><b>Evaluasi:</b> ukur <i>Precision</i> dan <i>Recall</i> dalam mengenali kontaminan visual.</li>
            </ul>
        </div>
    </div>

    <div class="note">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2.5 20h19L12 3Z"/><path d="M12 10v4M12 17.5v.01"/></svg>
        <div>
            <h3>Batas kemampuan computer vision</h3>
            <p>Sistem bekerja dari piksel yang terlihat kamera dan tidak melakukan analisis biokimia. Antarmuka wajib menjelaskan batas ini kepada pengguna agar literasi halal tetap terbentuk dan hasil AI tidak disalahartikan sebagai sertifikasi.</p>
        </div>
    </div>
</section>
@endsection
