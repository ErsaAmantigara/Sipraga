<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Pengerjaan extends Model
{
    protected $table = 'pengerjaan';

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pengerjaan',
        'foto_sebelum',
        'foto_proses',
        'foto_sesudah',
        'keterangan_teknisi',
        'material',
        'rating_nilai',
        'rating_komentar',
        'tanggal_rating',
    ];

    protected $primaryKey = 'pengerjaan_id';

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_rating' => 'datetime',
        'rating_nilai' => 'integer',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id', 'pengaduan_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
