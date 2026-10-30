<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->string('berkas');
            $table->string('kelas', 20);
            $table->float('conf');
            $table->unsignedInteger('ms');
            $table->json('box')->nullable();
            $table->boolean('simulasi')->default(false);
            $table->boolean('contoh')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
