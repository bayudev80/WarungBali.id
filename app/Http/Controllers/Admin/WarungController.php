<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warung;
use App\Models\Kabupaten;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WarungController extends Controller
{
    public function index()
    {
        $warung = Warung::with(['kategori', 'kabupaten'])
            ->orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')")
            ->latest('id_warung')
            ->paginate(10);

        return view('admin.warung.index', compact('warung'));
    }

    /**
     * Daftar pengajuan warung yang menunggu persetujuan admin.
     */
    public function verifikasi()
    {
        $warung = Warung::with(['kategori', 'kabupaten', 'user'])
            ->where('status', 'pending')
            ->latest('id_warung')
            ->paginate(10);

        return view('admin.warung.verifikasi', compact('warung'));
    }

    public function create()
    {
        $kategori  = Kategori::orderBy('nama_kategori')->get();
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('admin.warung.create', compact('kategori', 'kabupaten'));
    }

    public function store(Request $request)
    {
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

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        Warung::create($data);

        return redirect()->route('admin.warung.index')
            ->with('success', 'Warung berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $warung    = Warung::findOrFail($id);
        $kategori  = Kategori::orderBy('nama_kategori')->get();
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('admin.warung.edit', compact('warung', 'kategori', 'kabupaten'));
    }

    public function update(Request $request, $id)
    {
        $warung = Warung::findOrFail($id);

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

        if ($request->hasFile('foto')) {
            $this->deleteFoto($warung->foto);
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        $warung->update($data);

        return redirect()->route('admin.warung.index')
            ->with('success', 'Warung berhasil diubah.');
    }

    public function destroy($id)
    {
        $warung = Warung::findOrFail($id);

        $this->deleteFoto($warung->foto);
        $warung->delete();

        return redirect()->route('admin.warung.index')
            ->with('success', 'Warung berhasil dihapus.');
    }

    public function approve($id)
    {
        $warung = Warung::findOrFail($id);
        $warung->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'Warung "' . $warung->nama_warung . '" disetujui dan sekarang tayang di website.');
    }

    public function reject($id)
    {
        $warung = Warung::findOrFail($id);
        $warung->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Warung "' . $warung->nama_warung . '" ditolak.');
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
