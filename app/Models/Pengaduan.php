<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Pengaduan extends Model
{
    public $timestamps = false;

    protected $table = 'pengaduan';

    protected $fillable = [
        'user_id',
        'nomor_pengaduan',
        'tanggal_pengaduan',
        'tanggal_selesai',
        'jenis_keluhan',
        'deskripsi_keluhan',
        'stand_meter_terakhir',
        'foto_keluhan',
        'status_pengaduan',
        'keterangan_cs',
    ];

    protected $primaryKey = 'pengaduan_id';

    protected $casts = [
        'tanggal_pengaduan' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'stand_meter_terakhir' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function penilaianSaw()
    {
        return $this->hasOne(PenilaianSaw::class, 'pengaduan_id', 'pengaduan_id');
    }

    public function pengerjaan()
    {
        return $this->hasOne(Pengerjaan::class, 'pengaduan_id', 'pengaduan_id');
    }

}
