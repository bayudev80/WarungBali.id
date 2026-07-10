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
        'password',
        'role',
        'foto'
    ];

    protected $hidden = [
        'password',
    ];

    public function review()
    {
        return $this->hasMany(Review::class, 'id_user', 'id_user');
    }
}