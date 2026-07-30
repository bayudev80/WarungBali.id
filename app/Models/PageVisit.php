<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $table = 'page_visits';

    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'visited_date',
    ];

    /**
     * Hitung jumlah kunjungan unik pada bulan berjalan.
     * Dipakai untuk statistik "Pengunjung/Bulan" di halaman utama.
     */
    public static function countThisMonth(): int
    {
        return static::whereYear('visited_date', now()->year)
            ->whereMonth('visited_date', now()->month)
            ->count();
    }
}
