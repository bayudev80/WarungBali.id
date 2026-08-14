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
        'id_warung_induk',
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

    public function getTotalFavoritAttribute()
    {
        return $this->favorit->count();
    }

    /**
     * Ambang batas buat badge "Legendaris" & "Favorit Wisatawan" di halaman detail.
     * Diletakkan di sini (bukan angka mati di banyak tempat) supaya gampang diubah
     * kalau nanti mau disesuaikan lagi.
     */
    const MIN_RATING_LEGENDARIS = 4.5;
    const MIN_REVIEW_LEGENDARIS = 5;
    const MIN_FAVORIT_WISATAWAN = 3;

    /**
     * True kalau warung sudah punya reputasi kuat: rating tinggi DAN
     * jumlah ulasannya cukup banyak (bukan cuma bagus dari 1-2 orang).
     */
    public function getIsLegendarisAttribute()
    {
        return $this->average_rating >= self::MIN_RATING_LEGENDARIS
            && $this->total_review >= self::MIN_REVIEW_LEGENDARIS;
    }

    /**
     * True kalau warung sudah difavoritkan cukup banyak pengguna,
     * jadi badge ini beneran mencerminkan minat wisatawan/pengguna lain.
     */
    public function getIsFavoritWisatawanAttribute()
    {
        return $this->total_favorit >= self::MIN_FAVORIT_WISATAWAN;
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
     * Warung utama/induk dari cabang ini (null kalau warung ini bukan cabang).
     */
    public function indukWarung()
    {
        return $this->belongsTo(Warung::class, 'id_warung_induk', 'id_warung');
    }

    /**
     * Daftar cabang milik warung ini (kalau warung ini adalah warung utama).
     * Cuma cabang yang sudah disetujui admin yang ditampilkan ke publik.
     */
    public function cabang()
    {
        return $this->hasMany(Warung::class, 'id_warung_induk', 'id_warung')
            ->where('status', 'approved')
            ->orderBy('nama_warung');
    }

    /**
     * Semua cabang milik warung ini, TANPA filter status -- dipakai di
     * dashboard pemilik supaya cabang yang masih pending/ditolak tetap
     * kelihatan dan bisa dikelola. Beda dengan cabang() di atas yang
     * cuma untuk ditampilkan ke publik.
     */
    public function semuaCabang()
    {
        return $this->hasMany(Warung::class, 'id_warung_induk', 'id_warung')
            ->orderBy('nama_warung');
    }

    /**
     * True kalau warung ini adalah cabang dari warung lain.
     */
    public function getIsCabangAttribute()
    {
        return !is_null($this->id_warung_induk);
    }

    /**
     * Menu yang ditampilkan untuk warung ini. Cabang otomatis memakai menu
     * yang sama dengan warung utamanya (tidak perlu input menu ulang per cabang),
     * jadi kalau warung ini cabang, ambil menu punya induknya.
     */
    public function getMenuTampilAttribute()
    {
        if ($this->is_cabang && $this->indukWarung) {
            return $this->indukWarung->menu;
        }

        return $this->menu;
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
     * Label dinamis untuk layanan khusus tambahan berdasarkan kategori warung.
     */
    public function getLayananLabelAttribute()
    {
        $kategoriNama = strtolower($this->kategori->nama_kategori ?? '');

        if ($this->is_kuliner) {
            return 'Menerima Catering';
        }
        if (str_contains($kategoriNama, 'atk') || str_contains($kategoriNama, 'fotokopi')) {
            return 'Menerima Jasa Cetak & Jilid';
        }
        if (str_contains($kategoriNama, 'pulsa') || str_contains($kategoriNama, 'ppob')) {
            return 'Menerima Pembayaran Tagihan';
        }
        if (str_contains($kategoriNama, 'sembako')) {
            return 'Menerima Layanan Antar (Delivery)';
        }
        if (str_contains($kategoriNama, 'oleh-oleh') || str_contains($kategoriNama, 'bali')) {
            return 'Menerima Paket Oleh-oleh Custom';
        }
        if (str_contains($kategoriNama, 'buah') || str_contains($kategoriNama, 'sayur')) {
            return 'Menerima Pesanan Parsel / Grosir';
        }
        if (str_contains($kategoriNama, 'herbal')) {
            return 'Menerima Konsultasi & Pesanan';
        }

        return 'Menerima Pesanan Khusus';
    }

    /**
     * Ikon Bootstrap Icons yang sesuai dengan jenis layanan tambahan warung.
     * Dipakai di badge card supaya tidak semua warung menampilkan ikon catering.
     */
    public function getLayananIconAttribute()
    {
        $kategoriNama = strtolower($this->kategori->nama_kategori ?? '');

        if ($this->is_kuliner) {
            return 'bi bi-pot-fill';           // Catering
        }
        if (str_contains($kategoriNama, 'atk') || str_contains($kategoriNama, 'fotokopi')) {
            return 'bi bi-printer-fill';       // Jasa Cetak
        }
        if (str_contains($kategoriNama, 'pulsa') || str_contains($kategoriNama, 'ppob')) {
            return 'bi bi-phone-fill';         // Pulsa / Tagihan
        }
        if (str_contains($kategoriNama, 'sembako')) {
            return 'bi bi-truck';              // Delivery
        }
        if (str_contains($kategoriNama, 'oleh-oleh') || str_contains($kategoriNama, 'bali')) {
            return 'bi bi-gift-fill';          // Paket Oleh-oleh
        }
        if (str_contains($kategoriNama, 'buah') || str_contains($kategoriNama, 'sayur')) {
            return 'bi bi-box-seam-fill';      // Parsel / Grosir
        }
        if (str_contains($kategoriNama, 'herbal')) {
            return 'bi bi-capsule-pill';       // Konsultasi
        }

        return 'bi bi-bag-check-fill';         // Default
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

    /**
     * Link WhatsApp (wa.me) dari nomor telepon warung, siap diklik.
     * Nomor lokal yang diawali "0" otomatis diubah ke format internasional "62".
     * Sudah disertai pesan pembuka otomatis.
     */
    public function getWaLinkAttribute()
    {
        $nomor = preg_replace('/[^0-9]/', '', $this->telepon ?? '');

        if (empty($nomor)) {
            return null;
        }

        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        } elseif (substr($nomor, 0, 2) !== '62') {
            $nomor = '62' . $nomor;
        }

        $pesan = 'Halo, saya ingin bertanya tentang ' . $this->nama_warung;

        return 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan);
    }

    /**
     * Link Google Maps berdasarkan nama warung + alamat.
     * Pakai endpoint pencarian resmi Google Maps supaya tidak perlu
     * simpan koordinat (latitude/longitude) di database.
     */
    public function getMapsLinkAttribute()
    {
        $query = $this->nama_warung . ', ' . $this->alamat;

        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($query);
    }
    }

