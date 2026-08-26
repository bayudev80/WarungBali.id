<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteStatistic extends Model
{
    protected $table = 'site_statistics';

    protected $fillable = [
        'key',
        'label',
        'icon',
        'source_type',
        'manual_value',
        'bonus_value',
        'suffix',
        'prefix',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'manual_value' => 'integer',
        'bonus_value' => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Hitung nilai riil (real-time) dari database berdasarkan key statistik.
     */
    public function getRealValue(): int
    {
        return match ($this->key) {
            'total_warung' => Warung::where('status', 'approved')->count(),
            'total_ulasan' => Review::count(),
            'total_kabupaten' => Kabupaten::count(),
            'total_pengunjung' => PageVisit::countThisMonth(),
            'total_user' => User::count(),
            'total_menu' => Menu::count(),
            default => 0,
        };
    }

    /**
     * Dapatkan nilai akhir yang akan ditampilkan.
     * Jika 'auto': Nilai riil DB + Nilai tambahan (bonus_value)
     * Jika 'manual': Nilai manual kustom (manual_value)
     */
    public function getDisplayValue(): int
    {
        if ($this->source_type === 'auto') {
            return max(0, $this->getRealValue() + ($this->bonus_value ?? 0));
        }

        return max(0, $this->manual_value ?? 0);
    }

    /**
     * Format angka tampilan lengkap dengan pemisah ribuan dan prefix/suffix.
     * Contoh: "50+", "1.250", "Rp 500.000"
     */
    public function getFormattedValue(): string
    {
        $val = number_format($this->getDisplayValue());
        $prefix = $this->prefix ?? '';
        $suffix = $this->suffix ?? '';

        return $prefix . $val . $suffix;
    }

    /**
     * Ambil semua statistik aktif untuk ditampilkan di website publik.
     */
    public static function getPublicStatistics()
    {
        if (!Schema::hasTable('site_statistics')) {
            return collect();
        }

        // Jika belum ada data sama sekali, lakukan seed default otomatis
        if (static::count() === 0) {
            static::seedDefaults();
        }

        return static::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Sumber data otomatis yang didukung sistem.
     */
    public static function getAvailableAutoSources(): array
    {
        return [
            'total_warung' => [
                'label' => 'Warung Terdaftar (Approved)',
                'default_label' => 'Warung Terdaftar',
                'default_icon' => 'bi-shop',
                'default_suffix' => '+',
                'description' => 'Menghitung total warung yang statusnya disetujui (approved).',
            ],
            'total_ulasan' => [
                'label' => 'Ulasan Pengguna (Total Review)',
                'default_label' => 'Ulasan Pengguna',
                'default_icon' => 'bi-chat-square-quote',
                'default_suffix' => '+',
                'description' => 'Menghitung total ulasan yang diberikan oleh pelanggan.',
            ],
            'total_kabupaten' => [
                'label' => 'Kabupaten / Kota',
                'default_label' => 'Kabupaten/Kota',
                'default_icon' => 'bi-geo-alt',
                'default_suffix' => '',
                'description' => 'Menghitung total data kabupaten/kota di Bali.',
            ],
            'total_pengunjung' => [
                'label' => 'Pengunjung Bulan Ini',
                'default_label' => 'Pengunjung Bulan Ini',
                'default_icon' => 'bi-eye',
                'default_suffix' => '',
                'description' => 'Menghitung total pengunjung unik pada bulan berjalan.',
            ],
            'total_user' => [
                'label' => 'Total Pengguna Terdaftar',
                'default_label' => 'Pengguna Aktif',
                'default_icon' => 'bi-people',
                'default_suffix' => '+',
                'description' => 'Menghitung total semua akun pengguna terdaftar.',
            ],
            'total_menu' => [
                'label' => 'Total Menu Makanan/Minuman',
                'default_label' => 'Menu Kuliner',
                'default_icon' => 'bi-cup-straw',
                'default_suffix' => '+',
                'description' => 'Menghitung total menu kuliner yang terdaftar.',
            ],
        ];
    }

    /**
     * Seed 4 statistik standar default website WarungBali.id
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            [
                'key' => 'total_warung',
                'label' => 'Warung Terdaftar',
                'icon' => 'bi-shop',
                'source_type' => 'auto',
                'manual_value' => 0,
                'bonus_value' => 0,
                'suffix' => '+',
                'prefix' => '',
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'key' => 'total_ulasan',
                'label' => 'Ulasan Pengguna',
                'icon' => 'bi-chat-square-quote',
                'source_type' => 'auto',
                'manual_value' => 0,
                'bonus_value' => 0,
                'suffix' => '+',
                'prefix' => '',
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'key' => 'total_kabupaten',
                'label' => 'Kabupaten/Kota',
                'icon' => 'bi-geo-alt',
                'source_type' => 'auto',
                'manual_value' => 0,
                'bonus_value' => 0,
                'suffix' => '',
                'prefix' => '',
                'is_active' => true,
                'urutan' => 3,
            ],
            [
                'key' => 'total_pengunjung',
                'label' => 'Pengunjung Bulan Ini',
                'icon' => 'bi-eye',
                'source_type' => 'auto',
                'manual_value' => 0,
                'bonus_value' => 0,
                'suffix' => '',
                'prefix' => '',
                'is_active' => true,
                'urutan' => 4,
            ],
        ];

        foreach ($defaults as $data) {
            static::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
