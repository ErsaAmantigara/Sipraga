<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'no_hp',
        'password',
        'is_active',
        'cabang_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function profilePelanggan(): HasOne
    {
        return $this->hasOne(ProfilePelanggan::class, 'user_id', 'user_id');
    }


    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'user_id', 'user_id');
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'cabang_id');
    }

    public function pengerjaan(): HasMany
    {
        return $this->hasMany(Pengerjaan::class, 'user_id', 'user_id');
    }
}