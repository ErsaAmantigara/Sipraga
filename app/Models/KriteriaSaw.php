<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaSaw extends Model
{
    public $timestamps = false;

    protected $table = 'kriteria_saw';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot',
        'jenis',
    ];

    protected $primaryKey = 'kriteria_saw_id';

    protected $casts = [
        'bobot' => 'decimal:2',
    ];

}
