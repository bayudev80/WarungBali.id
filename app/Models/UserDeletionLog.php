<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeletionLog extends Model
{
    protected $table = 'user_deletion_logs';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'role',
        'alasan_kategori',
        'alasan_detail',
        'deleted_by_name',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
