<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    /** Kelas yang dikenali model. Kunci harus sama dengan nama kelas pada dataset YOLOv8. */
    public const KELAS = [
        'babi'    => ['nama' => 'Daging babi', 'halal' => false],
        'alkohol' => ['nama' => 'Botol minuman beralkohol', 'halal' => false],
        'sapi'    => ['nama' => 'Daging sapi', 'halal' => true],
        'ayam'    => ['nama' => 'Ayam', 'halal' => true],
        'sayur'   => ['nama' => 'Sayuran', 'halal' => true],
    ];

    public const STATUS = [
        'nonhalal' => ['label' => 'Terindikasi non-halal', 'cls' => 'bad'],
        'aman'     => ['label' => 'Tidak terindikasi', 'cls' => 'ok'],
        'tinjau'   => ['label' => 'Perlu tinjau', 'cls' => 'warn'],
    ];

    protected $fillable = ['berkas', 'kelas', 'conf', 'ms', 'box', 'simulasi', 'contoh'];

    protected $casts = [
        'conf'     => 'float',
        'ms'       => 'integer',
        'box'      => 'array',
        'simulasi' => 'boolean',
        'contoh'   => 'boolean',
    ];

    public function getKodeAttribute(): string
    {
        return 'FA-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getNamaKelasAttribute(): string
    {
        return self::KELAS[$this->kelas]['nama'] ?? $this->kelas;
    }

    public function getStatusAttribute(): string
    {
        if ($this->conf < config('foodai.ambang')) {
            return 'tinjau';
        }

        return (self::KELAS[$this->kelas]['halal'] ?? false) ? 'aman' : 'nonhalal';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status]['label'];
    }

    public function getStatusClsAttribute(): string
    {
        return self::STATUS[$this->status]['cls'];
    }

    public function getLewatTargetAttribute(): bool
    {
        return $this->ms > config('foodai.target_ms');
    }
}
