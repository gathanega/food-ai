<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · food.ai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/foodai.css') }}">
</head>
<body>
<div class="app">
    <header class="topbar">
        <div class="topbar-in">
            <a class="brand" href="{{ route('ringkasan') }}" style="text-decoration:none;color:inherit">
                <svg width="38" height="38" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <path d="M4 13V8a4 4 0 0 1 4-4h5M27 4h5a4 4 0 0 1 4 4v5M36 27v5a4 4 0 0 1-4 4h-5M13 36H8a4 4 0 0 1-4-4v-5" stroke="#6fd6e0" stroke-width="3" stroke-linecap="round"/>
                    <path d="M11 22h18a9 9 0 0 1-18 0Z" fill="#6fd6e0"/>
                    <path d="M16 18c0-2 2-2 2-4M22 18c0-2 2-2 2-4" stroke="#6fd6e0" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <div><b>food<span>.ai</span></b><small>Identifikasi visual makanan</small></div>
            </a>
            <nav class="nav" aria-label="Menu utama">
                @php
                    $menu = [
                        'ringkasan' => ['Ringkasan', '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>'],
                        'analisis'  => ['Analisis Foto', '<path d="M4 8V6a2 2 0 0 1 2-2h2M16 4h2a2 2 0 0 1 2 2v2M20 16v2a2 2 0 0 1-2 2h-2M8 20H6a2 2 0 0 1-2-2v-2"/><circle cx="12" cy="12" r="3.5"/>'],
                        'riwayat'   => ['Riwayat', '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'],
                        'sistem'    => ['Arsitektur', '<rect x="3" y="3" width="7" height="6" rx="1.5"/><rect x="14" y="3" width="7" height="6" rx="1.5"/><rect x="8.5" y="15" width="7" height="6" rx="1.5"/><path d="M6.5 9v3h11V9M12 12v3"/>'],
                        'proyek'    => ['Proposal & Jadwal', '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>'],
                    ];
                @endphp
                @foreach ($menu as $rute => [$label, $ikon])
                    <a href="{{ route($rute) }}" @if (request()->routeIs($rute)) aria-current="page" @endif>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ikon !!}</svg>{{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
