<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';

    protected $primaryKey = 'id_kabupaten';

    public $timestamps = false;

    protected $fillable = [
        'nama_kabupaten'
    ];

    public function warung()
    {
        return $this->hasMany(Warung::class, 'id_kabupaten', 'id_kabupaten');
    }
}