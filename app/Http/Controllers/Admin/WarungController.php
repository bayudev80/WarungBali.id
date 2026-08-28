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
    public function index(Request $request)
    {
        $search        = trim((string) $request->input('search'));
        $kategoriTerpilih = $request->input('kategori');
        $kabupatenTerpilih = $request->input('kabupaten');

        $warung = Warung::with(['kategori', 'kabupaten'])
            ->when($search !== '', function ($query) use ($search) {
                // Mendukung pencarian dengan format kode (misal: WRG-0006 atau 0006)
                $idSearch = preg_replace('/^WRG-0*/i', '', $search);
                $idSearch = ltrim($idSearch, '0');

                $query->where(function ($q) use ($search, $idSearch) {
                    $q->where('nama_warung', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhere('telepon', 'like', "%{$search}%");
                        
                    if (is_numeric($idSearch) && $idSearch > 0) {
                        $q->orWhere('warung.id_warung', $idSearch);
                    }
                });
            })
            ->when($kategoriTerpilih, function ($query) use ($kategoriTerpilih) {
                $query->where('warung.id_kategori', $kategoriTerpilih);
            })
            ->when($kabupatenTerpilih, function ($query) use ($kabupatenTerpilih) {
                $query->where('warung.id_kabupaten', $kabupatenTerpilih);
            })
            // Diurutkan berdasarkan kabupaten, lalu kategori dan nama warung.
            ->join('kategori', 'kategori.id_kategori', '=', 'warung.id_kategori')
            ->join('kabupaten', 'kabupaten.id_kabupaten', '=', 'warung.id_kabupaten')
            ->orderBy('kabupaten.nama_kabupaten', 'asc')
            ->orderBy('kategori.nama_kategori', 'asc')
            ->orderBy('warung.nama_warung', 'asc')
            ->select('warung.*')
            ->paginate(15)
            ->withQueryString();

        $kategori = Kategori::orderBy('nama_kategori')->get();
        $semuaKabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('admin.warung.index', compact('warung', 'kategori', 'search', 'kategoriTerpilih', 'semuaKabupaten', 'kabupatenTerpilih'));
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
            'foto'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        // Warung yang didaftarkan lewat form ini SELALU warung utama/berdiri
        // sendiri -- kalau admin mau menambahkan cabang untuk warung yang
        // sudah ada, itu dilakukan lewat tombol "+ Cabang" di halaman Data
        // Warung, bukan dengan memilih induk manual di sini.
        $data = $request->except('foto', '_token', 'id_warung_induk');
        $data['id_user'] = auth()->id();
        $data['menerima_catering'] = $request->boolean('menerima_catering');

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
            'foto'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        // Form edit umum ini tidak lagi mengubah hubungan induk/cabang
        // warung -- itu ditentukan otomatis lewat alur "+ Cabang", bukan
        // dipilih manual, supaya tidak ada yang bisa memindahkan warung
        // jadi cabang dari warung sembarangan.
        $data = $request->except('foto', '_token', '_method', 'id_warung_induk');
        $data['menerima_catering'] = $request->boolean('menerima_catering');

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
        $warung = Warung::with('kategori')->findOrFail($id);
        $warung->update([
            'status'           => 'approved',
            'alasan_penolakan' => null,
        ]);

        // Jika kategori warung ini diajukan baru (pending), otomatis setujui kategorinya
        if ($warung->kategori && $warung->kategori->status === 'pending') {
            $warung->kategori->update(['status' => 'approved']);
        }

        return redirect()->back()
            ->with('success', 'Warung "' . $warung->nama_warung . '" disetujui dan sekarang tayang di website.');
    }

    public function reject(Request $request, $id)
    {
        $warung = Warung::findOrFail($id);

        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500',
        ]);

        $alasan = $request->input('alasan_penolakan') ?: 'Data atau foto warung belum memenuhi syarat kelengkapan.';

        $warung->update([
            'status'           => 'rejected',
            'alasan_penolakan' => $alasan,
        ]);

        return redirect()->back()
            ->with('success', 'Warung "' . $warung->nama_warung . '" ditolak dengan catatan: "' . $alasan . '".');
    }

    /**
     * Form tambah cabang baru untuk warung yang dipilih admin dari daftar
     * Data Warung. Warung induknya otomatis warung yang diklik (tidak
     * dipilih manual dari dropdown), supaya jelas cabang ini "anak" dari
     * warung tersebut.
     */
    public function createCabang($id)
    {
        $warung = Warung::findOrFail($id);

        if ($warung->is_cabang) {
            return redirect()->route('admin.warung.index')
                ->with('error', 'Warung cabang tidak bisa membuka cabang baru lagi.');
        }

        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('admin.warung.cabang.create', compact('warung', 'kabupaten'));
    }

    public function storeCabang(Request $request, $id)
    {
        $warung = Warung::findOrFail($id);

        if ($warung->is_cabang) {
            return redirect()->route('admin.warung.index')
                ->with('error', 'Warung cabang tidak bisa membuka cabang baru lagi.');
        }

        $request->validate([
            'nama_warung'  => 'required|max:150',
            'id_kabupaten' => 'required|exists:kabupaten,id_kabupaten',
            'alamat'       => 'required',
            'deskripsi'    => 'nullable|string',
            'telepon'      => 'nullable|max:20',
            'jam_buka'     => 'nullable',
            'jam_tutup'    => 'nullable',
            'harga_min'    => 'nullable|integer|min:0',
            'harga_max'    => 'nullable|integer|min:0',
            'foto'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'nama_warung', 'id_kabupaten', 'alamat',
            'deskripsi', 'telepon', 'jam_buka', 'jam_tutup',
            'harga_min', 'harga_max',
        ]);

        // Cabang otomatis mengikuti kategori dan warung induknya sendiri --
        // bukan dipilih manual.
        $data['id_kategori']     = $warung->id_kategori;
        $data['id_warung_induk'] = $warung->id_warung;
        $data['id_user']         = $warung->id_user;
        $data['menerima_catering'] = $request->boolean('menerima_catering');

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        Warung::create($data);

        return redirect()->route('admin.warung.index')
            ->with('success', 'Cabang baru untuk "' . $warung->nama_warung . '" berhasil ditambahkan.');
    }

    private function uploadFoto($file)
    {
        $filename = time() . '_' . \Illuminate\Support\Str::random(12) . '.' . $file->getClientOriginalExtension();
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