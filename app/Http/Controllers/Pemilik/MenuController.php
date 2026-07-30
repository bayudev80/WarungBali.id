<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index()
    {
        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $menu = Menu::where('id_warung', $warung->id_warung)->orderBy('id_menu')->get();

        return view('pemilik.menu.index', compact('warung', 'menu'));
    }

    public function create()
    {
        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        return view('pemilik.menu.create', compact('warung'));
    }

    public function store(Request $request)
    {
        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $request->validate([
            'nama_menu' => 'required|max:150',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'foto_menu' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga']);
        $data['id_warung'] = $warung->id_warung;

        if ($request->hasFile('foto_menu')) {
            $data['foto_menu'] = $this->uploadFoto($request->file('foto_menu'));
        }

        Menu::create($data);

        return redirect()->route('pemilik.menu.index')
            ->with('success', ucfirst(strtolower($warung->label_menu)) . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $menu = Menu::where('id_warung', $warung->id_warung)->findOrFail($id);

        return view('pemilik.menu.edit', compact('warung', 'menu'));
    }

    public function update(Request $request, $id)
    {
        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $menu = Menu::where('id_warung', $warung->id_warung)->findOrFail($id);

        $request->validate([
            'nama_menu' => 'required|max:150',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'foto_menu' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga']);

        if ($request->hasFile('foto_menu')) {
            $this->deleteFoto($menu->foto_menu);
            $data['foto_menu'] = $this->uploadFoto($request->file('foto_menu'));
        }

        $menu->update($data);

        return redirect()->route('pemilik.menu.index')
            ->with('success', ucfirst(strtolower($warung->label_menu)) . ' berhasil diubah.');
    }

    public function destroy($id)
    {
        $warung = auth()->user()->warung;

        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        $menu = Menu::where('id_warung', $warung->id_warung)->findOrFail($id);

        $this->deleteFoto($menu->foto_menu);
        $menu->delete();

        return redirect()->route('pemilik.menu.index')
            ->with('success', ucfirst(strtolower($warung->label_menu)) . ' berhasil dihapus.');
    }

    private function uploadFoto($file)
    {
        $filename = time() . '_' . \Illuminate\Support\Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/menu'), $filename);

        return $filename;
    }

    private function deleteFoto($filename)
    {
        if ($filename && File::exists(public_path('images/menu/' . $filename))) {
            File::delete(public_path('images/menu/' . $filename));
        }
    }
}
