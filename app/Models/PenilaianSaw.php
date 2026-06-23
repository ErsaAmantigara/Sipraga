<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianSaw extends Model
{
    public $timestamps = false;

    protected $table = 'penilaian_saw';

    protected $fillable = [
        'pengaduan_id',
        'c1_tingkat_urgensi',
        'c2_lama_waktu_pelaporan',
        'c3_jenis_pelanggan',
        'c4_jarak_kelokasi',
        'normalisasi_c1',
        'normalisasi_c2',
        'normalisasi_c3',
        'normalisasi_c4',
        'nilai_preferensi',
        'ranking',
        'kategori_prioritas',
    ];

    protected $primaryKey = 'penilaian_saw_id';

    protected $casts = [
        'c1_tingkat_urgensi' => 'decimal:2',
        'c2_lama_waktu_pelaporan' => 'decimal:2',
        'c3_jenis_pelanggan' => 'decimal:2',
        'c4_jarak_kelokasi' => 'decimal:2',
        'normalisasi_c1' => 'decimal:4',
        'normalisasi_c2' => 'decimal:4',
        'normalisasi_c3' => 'decimal:4',
        'normalisasi_c4' => 'decimal:4',
        'nilai_preferensi' => 'decimal:4',

    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'pengaduan_id');
    }
}
