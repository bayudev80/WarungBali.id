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
        ]);

        $data = $request->except('foto', '_token');
        $data['id_user'] = auth()->id();
        $data['status']  = 'pending';

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        Warung::create($data);

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Warung berhasil didaftarkan! Mohon tunggu persetujuan admin sebelum warung Anda tayang di website.');
    }

    public function edit()
    {
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
        ]);

        $data = $request->except('foto', '_token', '_method');

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
