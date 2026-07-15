<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $table = 'favorit';

    protected $primaryKey = 'id_favorit';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_warung'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function warung()
    {
        return $this->belongsTo(Warung::class, 'id_warung', 'id_warung');
    }
}