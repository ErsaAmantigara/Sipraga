<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ProfilePelanggan extends Model
{
    public $timestamps = false;

    protected $table = 'profile_pelanggan';

    protected $fillable = [
        'user_id',
        'no_id_pelanggan',
        'jenis_pelanggan',
        'alamat',
        'latitude',
        'longitude',
    ];

    protected $primaryKey = 'profile_pelanggan_id';

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
