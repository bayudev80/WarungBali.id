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
        'status',
        'menerima_catering'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Label dinamis untuk item yang dijual warung ini.
     * Kalau kategorinya mengandung kata "makan" atau "minum" -> "Menu".
     * Selain itu (mis. ATK, Sembako, dll) -> "Produk".
     */
    public function getLabelMenuAttribute()
    {
        return $this->is_kuliner ? 'Menu' : 'Produk';
    }

    /**
     * True kalau warung ini termasuk kategori kuliner (makanan/minuman).
     * Dipakai untuk menentukan apakah opsi "menerima catering" relevan
     * ditampilkan atau tidak, karena catering cuma masuk akal untuk warung makanan.
     */
    public function getIsKulinerAttribute()
    {
        return $this->kategori->is_kuliner ?? false;
    }
    }

