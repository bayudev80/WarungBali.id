<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    protected $table = 'warung';

    protected $primaryKey = 'id_warung';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_kategori',
        'id_kabupaten',
        'nama_warung',
        'alamat',
        'deskripsi',
        'telepon',
        'jam_buka',
        'jam_tutup',
        'harga_min',
        'harga_max',
        'foto',
        'status'
    ];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'id_warung', 'id_warung');
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'id_warung', 'id_warung');
    }
    public function getAverageRatingAttribute()
    {
    return round($this->review->avg('rating') ?? 0, 1);
    }

    public function getTotalReviewAttribute()
    {
    return $this->review->count();
    }
    public function favorit()
    {
    return $this->hasMany(Favorit::class, 'id_warung', 'id_warung');
    }
    public function kategori()
    {
    return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
    public function kabupaten()
{
    return $this->belongsTo(Kabupaten::class, 'id_kabupaten', 'id_kabupaten');
}

    /**
     * Label dinamis untuk item yang dijual warung ini.
     * Kalau kategorinya mengandung kata "makan" atau "minum" -> "Menu".
     * Selain itu (mis. ATK, Sembako, dll) -> "Produk".
     */
    public function getLabelMenuAttribute()
    {
        $nama = strtolower($this->kategori->nama_kategori ?? '');

        if (str_contains($nama, 'makan') || str_contains($nama, 'minum') || str_contains($nama, 'kuliner') || str_contains($nama, 'jajan')) {
            return 'Menu';
        }

        return 'Produk';
    }
    }

