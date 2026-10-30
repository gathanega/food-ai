@extends('layouts.app')
@section('title', 'Analisis Foto')

@section('content')
<section class="view">
    <div class="head">
        <div>
            <h1>Analisis foto makanan</h1>
            <p>Unggah foto, lalu sistem menandai objek yang terindikasi non-halal beserta tingkat keyakinannya.</p>
        </div>
        @unless (config('foodai.fastapi_url'))
            <span class="tag">Mode simulasi, FASTAPI_URL belum diisi</span>
        @endunless
    </div>

    <div class="grid-an">
        <div class="card">
            <h2>Unggah foto</h2>
            <p class="sub">Format JPG, PNG, atau WebP. Maksimal 10 MB.</p>
            <label class="drop" id="drop" style="margin-top:14px">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 18a4.5 4.5 0 0 1-.6-8.96A6 6 0 0 1 18 8.5a4 4 0 0 1-.5 8"/><path d="M12 21v-9M8.5 15.5 12 12l3.5 3.5"/></svg>
                <strong>Tarik foto ke sini atau pilih berkas</strong>
                <span id="drop-msg">Belum ada foto dipilih</span>
                <input type="file" id="file" accept="image/jpeg,image/png,image/webp">
            </label>
            <div class="preview" id="preview" hidden>
                <img id="pimg" alt="Pratinjau foto yang akan dianalisis">
                <div id="overlay"></div>
            </div>
            <div class="actions">
                <button class="btn" id="go" type="button" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 4.5v15l12-7.5-12-7.5Z"/></svg>
                    <span id="go-t">Mulai analisis</span>
                </button>
                <button class="btn ghost" id="reset" type="button" hidden>Ganti foto</button>
            </div>
        </div>

        <div class="card" id="res"></div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    window.FOODAI = {
        storeUrl: @json(route('analisis.store')),
        classes: @json(collect(\App\Models\Scan::KELAS)->map(fn ($k, $kode) => ['kode' => $kode] + $k)->values()),
        ambang: {{ (float) config('foodai.ambang') }}
    };
</script>
<script src="{{ asset('js/foodai.js') }}"></script>
@endpush
