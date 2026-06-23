<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Cabang extends Model
{
    public $timestamps = false;

    protected $table = 'cabang';
    protected $primaryKey = 'cabang_id';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_id', 'cabang_id');
    }
}
