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
    /**
     * Halaman panduan & penjelasan alur kemitraan bagi pengguna yang sudah login.
     */
    public function panduan()
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mendaftarkan warung.');
        }

        // Jika sudah memiliki warung, langsung arahkan ke dashboard pemilik
        if (auth()->user()->warung) {
            return redirect()->route('pemilik.dashboard');
        }

        return view('pemilik.warung.panduan');
    }

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
            'foto'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        // Whitelist eksplisit: jangan pernah pakai except() untuk data yang
        // langsung di-mass-assign, karena field seperti "status", "id_user",
        // dan "id_warung_induk" ada di $fillable dan bisa disisipkan lewat
        // request oleh user. Warung yang didaftarkan lewat form ini SELALU
        // warung utama/berdiri sendiri -- kalau pemilik nanti mau buka
        // cabang, itu dilakukan lewat menu "Tambah Cabang" di dashboard,
        // bukan dengan memilih induk sendiri di form ini.
        $data = $request->only([
            'nama_warung', 'id_kategori', 'id_kabupaten', 'alamat',
            'deskripsi', 'telepon', 'jam_buka', 'jam_tutup',
            'harga_min', 'harga_max',
        ]);
        $data['id_user'] = auth()->id();
        $data['status']  = 'pending';
        $data['menerima_catering'] = $request->boolean('menerima_catering');

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
            'foto'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        // Whitelist eksplisit -- lihat catatan di store(). Ini juga menutup
        // celah di mana pemilik bisa menyisipkan field "status", "id_user",
        // atau "id_warung_induk" ke request untuk melewati proses verifikasi
        // admin atau mengubah warung utamanya sendiri jadi cabang.
        $data = $request->only([
            'nama_warung', 'id_kategori', 'id_kabupaten', 'alamat',
            'deskripsi', 'telepon', 'jam_buka', 'jam_tutup',
            'harga_min', 'harga_max',
        ]);
        $data['menerima_catering'] = $request->boolean('menerima_catering');

        // Jika warung sebelumnya berstatus approved atau rejected, ubah menjadi pending
        // dan reset catatan penolakan agar ditinjau ulang oleh admin.
        if ($warung->status === 'approved' || $warung->status === 'rejected') {
            $data['status']           = 'pending';
            $data['alasan_penolakan'] = null;
        }

        if ($request->hasFile('foto')) {
            $this->deleteFoto($warung->foto);
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        $warung->update($data);

        return redirect()->route('pemilik.dashboard')
            ->with('success', $data['status'] === 'pending'
                ? 'Perubahan data warung berhasil disimpan dan diajukan ke admin untuk verifikasi.'
                : 'Data warung berhasil diperbarui.');
    }

    /**
     * Form tambah cabang baru. Hanya bisa diakses oleh pemilik yang sudah
     * punya warung utama (bukan cabang), karena warung induknya otomatis
     * warung milik pemilik yang sedang login -- tidak dipilih manual dari
     * daftar warung lain seperti sebelumnya.
     */
    public function createCabang()
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        if ($warung->is_cabang) {
            return redirect()->route('pemilik.dashboard')
                ->with('error', 'Warung cabang tidak bisa membuka cabang baru lagi.');
        }

        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('pemilik.warung.cabang.create', compact('warung', 'kabupaten'));
    }

    public function storeCabang(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        if ($warung->is_cabang) {
            return redirect()->route('pemilik.dashboard')
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
        // bukan dipilih manual -- supaya jelas cabang ini "anak" dari warung
        // yang sedang login, bukan warung sembarangan.
        $data['id_kategori']     = $warung->id_kategori;
        $data['id_warung_induk'] = $warung->id_warung;
        $data['id_user']         = $warung->id_user;
        $data['status']          = 'pending';
        $data['menerima_catering'] = $request->boolean('menerima_catering');

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        Warung::create($data);

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Cabang baru berhasil ditambahkan! Mohon tunggu persetujuan admin sebelum cabang ini tayang di website.');
    }

    public function editCabang($id)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        // Pastikan cabang yang diedit benar-benar anak dari warung milik
        // pemilik yang sedang login, bukan cabang milik warung lain.
        $cabang = Warung::where('id_warung_induk', $warung->id_warung)
            ->findOrFail($id);

        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('pemilik.warung.cabang.edit', compact('warung', 'cabang', 'kabupaten'));
    }

    public function updateCabang(Request $request, $id)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $cabang = Warung::where('id_warung_induk', $warung->id_warung)
            ->findOrFail($id);

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

        $data['menerima_catering'] = $request->boolean('menerima_catering');

        // Perubahan data cabang yang sudah disetujui perlu ditinjau ulang admin.
        if ($cabang->status === 'approved') {
            $data['status'] = 'pending';
        }

        if ($request->hasFile('foto')) {
            $this->deleteFoto($cabang->foto);
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        $cabang->update($data);

        return redirect()->route('pemilik.dashboard')
            ->with('success', $cabang->status === 'pending'
                ? 'Perubahan cabang disimpan. Cabang perlu ditinjau ulang oleh admin sebelum tayang.'
                : 'Data cabang berhasil diubah.');
    }

    public function destroyCabang($id)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Akun admin tidak dapat mengelola warung pemilik.');
        }

        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $cabang = Warung::where('id_warung_induk', $warung->id_warung)
            ->findOrFail($id);

        $this->deleteFoto($cabang->foto);
        $cabang->delete();

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Cabang berhasil dihapus.');
    }

    private function uploadFoto($file)
    {
        // Nama file acak (bukan nama asli yang di-sanitize) supaya tidak ada
        // celah path traversal / nama file berbahaya dari input pengguna.
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
