<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteStatistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * Tampilkan halaman kelola statistik sederhana & terpadu.
     */
    public function index()
    {
        // Pastikan default data terisi jika belum ada
        if (SiteStatistic::count() === 0) {
            SiteStatistic::seedDefaults();
        }

        $statistics = SiteStatistic::orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.statistik.index', compact('statistics'));
    }

    /**
     * Simpan semua perubahan statistik sekaligus dalam 1 klik konfirmasi.
     */
    public function updateAll(Request $request)
    {
        $statsData = $request->input('stats', []);

        foreach ($statsData as $id => $data) {
            $stat = SiteStatistic::find($id);
            if ($stat) {
                $sourceType  = $data['source_type'] ?? 'auto';
                $manualValue = isset($data['manual_value']) ? max(0, (int) $data['manual_value']) : 0;
                $label       = !empty($data['label']) ? trim($data['label']) : $stat->label;
                $suffix      = isset($data['has_plus']) && $data['has_plus'] == '1' ? '+' : ($data['suffix'] ?? '');
                $isActive    = isset($data['is_active']) && $data['is_active'] == '1';

                $stat->update([
                    'label'        => $label,
                    'source_type'  => $sourceType,
                    'manual_value' => $manualValue,
                    'suffix'       => $suffix,
                    'is_active'    => $isActive,
                ]);
            }
        }

        return redirect()->route('admin.statistik.index')
            ->with('success', 'Statistik website berhasil diperbarui dan langsung aktif di halaman utama!');
    }

    /**
     * Reset semua statistik kembali ke nilai otomatis riil database.
     */
    public function resetDefaults()
    {
        SiteStatistic::seedDefaults();

        // Reset semua ke auto dan manual_value 0
        SiteStatistic::query()->update([
            'source_type'  => 'auto',
            'manual_value' => 0,
            'bonus_value'  => 0,
            'is_active'    => true,
        ]);

        // Kembalikan label default
        SiteStatistic::where('key', 'total_warung')->update(['label' => 'Warung Terdaftar', 'suffix' => '+']);
        SiteStatistic::where('key', 'total_ulasan')->update(['label' => 'Ulasan Pengguna', 'suffix' => '+']);
        SiteStatistic::where('key', 'total_kabupaten')->update(['label' => 'Kabupaten/Kota', 'suffix' => '']);
        SiteStatistic::where('key', 'total_pengunjung')->update(['label' => 'Pengunjung Bulan Ini', 'suffix' => '']);

        return redirect()->route('admin.statistik.index')
            ->with('success', 'Semua statistik telah disamakan kembali dengan data asli database!');
    }
}
