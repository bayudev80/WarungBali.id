<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'google_id',
        'password',
        'role',
        'status_akun',
        'foto'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Nonaktifkan remember_token karena kolom tidak ada pada tabel users.
     */
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // No-op
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'id_user', 'id_user');
    }
    public function favorit()
    {
    return $this->hasMany(Favorit::class, 'id_user', 'id_user');
    }

    /**
     * Warung UTAMA milik user ini (khusus role pemilik). Sengaja dibatasi
     * whereNull('id_warung_induk') -- kalau user ini punya cabang juga,
     * cabang-cabang itu ikut punya id_user yang sama (supaya kolom id_user
     * yang NOT NULL di database tetap terisi), tapi relasi ini harus tetap
     * konsisten mengembalikan warung UTAMA-nya saja, bukan salah satu
     * cabangnya, karena banyak tempat lain (dashboard, edit, dsb) asumsinya
     * "satu pemilik = satu warung utama yang dikelola di sini".
     */
    public function warung()
    {
        return $this->hasOne(Warung::class, 'id_user', 'id_user')
            ->whereNull('id_warung_induk');
    }
}