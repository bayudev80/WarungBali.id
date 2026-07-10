<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

    public $timestamps = false;

    protected $fillable = [
        'id_warung',
        'nama_menu',
        'deskripsi',
        'harga',
        'foto_menu'
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class, 'id_warung', 'id_warung');
    }
}