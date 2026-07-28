<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Warung;
use App\Models\Kabupaten;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WarungController extends Controller
{
    public function create()
    {
        // Admin tidak mendaftarkan warung untuk dirinya sendiri.
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mendaftarkan warung.');
        }

        // Satu pemilik cuma boleh punya satu warung.
        if (auth()->user()->warung) {
            return redirect()->route('pemilik.dashboard');
        }

        $kategori  = Kategori::orderBy('nama_kategori')->get();
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('pemilik.warung.create', compact('kategori', 'kabupaten'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mendaftarkan warung.');
        }

        if (auth()->user()->warung) {
            return redirect()->route('pemilik.dashboard');
        }

        $request->validate([
            'nama_warung'  => 'required|max:150',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'id_kabupaten' => 'required|exists:kabupaten,id_kabupaten',
            'alamat'       => 'required',
            'deskripsi'    => 'nullable|string',
            'telepon'      => 'nullable|max:20',
            'jam_buka'     => 'nullable',
            'jam_tutup'    => 'nullable',
            'harga_min'    => 'nullable|integer|min:0',
            'harga_max'    => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        $data = $request->except('foto', '_token');
        $data['id_user'] = auth()->id();
        $data['status']  = 'pending';
        $data['menerima_catering'] = $request->boolean('menerima_catering');

        // Jaga-jaga: catering cuma relevan untuk warung kategori kuliner.
        $kategoriDipilih = Kategori::find($data['id_kategori'] ?? null);
        if (!$kategoriDipilih || !$kategoriDipilih->is_kuliner) {
            $data['menerima_catering'] = false;
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        Warung::create($data);

        // Akun user biasa yang baru pertama kali mendaftarkan warung
        // otomatis dinaikkan statusnya menjadi "pemilik".
        if (auth()->user()->role !== 'pemilik') {
            auth()->user()->update(['role' => 'pemilik']);
        }

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Warung berhasil didaftarkan! Mohon tunggu persetujuan admin sebelum warung Anda tayang di website.');
    }

    public function edit()
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $kategori  = Kategori::orderBy('nama_kategori')->get();
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('pemilik.warung.edit', compact('warung', 'kategori', 'kabupaten'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $request->validate([
            'nama_warung'  => 'required|max:150',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'id_kabupaten' => 'required|exists:kabupaten,id_kabupaten',
            'alamat'       => 'required',
            'deskripsi'    => 'nullable|string',
            'telepon'      => 'nullable|max:20',
            'jam_buka'     => 'nullable',
            'jam_tutup'    => 'nullable',
            'harga_min'    => 'nullable|integer|min:0',
            'harga_max'    => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        $data = $request->except('foto', '_token', '_method');
        $data['menerima_catering'] = $request->boolean('menerima_catering');

        // Jaga-jaga: catering cuma relevan untuk warung kategori kuliner.
        $kategoriDipilih = Kategori::find($data['id_kategori'] ?? null);
        if (!$kategoriDipilih || !$kategoriDipilih->is_kuliner) {
            $data['menerima_catering'] = false;
        }

        // Perubahan data warung setelah disetujui perlu ditinjau ulang oleh admin.
        if ($warung->status === 'approved') {
            $data['status'] = 'pending';
        }

        if ($request->hasFile('foto')) {
            $this->deleteFoto($warung->foto);
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        $warung->update($data);

        return redirect()->route('pemilik.dashboard')
            ->with('success', $warung->status === 'pending'
                ? 'Perubahan disimpan. Warung Anda perlu ditinjau ulang oleh admin sebelum tayang.'
                : 'Data warung berhasil diubah.');
    }

    private function uploadFoto($file)
    {
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('images/warung'), $filename);

        return $filename;
    }

    private function deleteFoto($filename)
    {
        if ($filename && File::exists(public_path('images/warung/' . $filename))) {
            File::delete(public_path('images/warung/' . $filename));
        }
    }
}
