<?php

return [
    // Alamat layanan AI (FastAPI + YOLOv8). Kosongkan untuk memakai mode simulasi.
    'fastapi_url' => env('FASTAPI_URL'),

    // Keyakinan minimum agar hasil dianggap meyakinkan (di bawahnya: "Perlu tinjau").
    'ambang' => 0.6,

    // Target pengujian performa dari proposal: inferensi di bawah 2 detik.
    'target_ms' => 2000,
];
