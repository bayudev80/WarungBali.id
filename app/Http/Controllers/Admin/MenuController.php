<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function index($warungId)
    {
        $warung = Warung::findOrFail($warungId);
        $menu   = Menu::where('id_warung', $warungId)->orderBy('id_menu')->get();

        return view('admin.warung.menu.index', compact('warung', 'menu'));
    }

    public function create($warungId)
    {
        $warung = Warung::findOrFail($warungId);

        return view('admin.warung.menu.create', compact('warung'));
    }

    public function store(Request $request, $warungId)
    {
        $warung = Warung::findOrFail($warungId);

        $request->validate([
            'nama_menu' => 'required|max:150',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'foto_menu' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto_menu', '_token');
        $data['id_warung'] = $warung->id_warung;

        if ($request->hasFile('foto_menu')) {
            $data['foto_menu'] = $this->uploadFoto($request->file('foto_menu'));
        }

        Menu::create($data);

        return redirect()->route('admin.warung.menu.index', $warung->id_warung)
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit($warungId, $id)
    {
        $warung = Warung::findOrFail($warungId);
        $menu   = Menu::where('id_warung', $warungId)->findOrFail($id);

        return view('admin.warung.menu.edit', compact('warung', 'menu'));
    }

    public function update(Request $request, $warungId, $id)
    {
        $warung = Warung::findOrFail($warungId);
        $menu   = Menu::where('id_warung', $warungId)->findOrFail($id);

        $request->validate([
            'nama_menu' => 'required|max:150',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'foto_menu' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto_menu', '_token', '_method');

        if ($request->hasFile('foto_menu')) {
            $this->deleteFoto($menu->foto_menu);
            $data['foto_menu'] = $this->uploadFoto($request->file('foto_menu'));
        }

        $menu->update($data);

        return redirect()->route('admin.warung.menu.index', $warung->id_warung)
            ->with('success', 'Menu berhasil diubah.');
    }

    public function destroy($warungId, $id)
    {
        $menu = Menu::where('id_warung', $warungId)->findOrFail($id);

        $this->deleteFoto($menu->foto_menu);
        $menu->delete();

        return redirect()->route('admin.warung.menu.index', $warungId)
            ->with('success', 'Menu berhasil dihapus.');
    }

    private function uploadFoto($file)
    {
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
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
