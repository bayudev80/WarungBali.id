<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori'
    ];

    public function warung()
    {
        return $this->hasMany(Warung::class, 'id_kategori', 'id_kategori');
    }

    /**
     * True kalau kategori ini termasuk kuliner (makanan/minuman),
     * bukan produk non-kuliner seperti ATK, Sembako, dll.
     */
    public function getIsKulinerAttribute()
    {
        $nama = strtolower($this->nama_kategori ?? '');

        return str_contains($nama, 'makan')
            || str_contains($nama, 'minum')
            || str_contains($nama, 'kuliner')
            || str_contains($nama, 'jajan');
    }
}