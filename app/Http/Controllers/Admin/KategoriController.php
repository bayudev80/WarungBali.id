<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $filterStatus = $request->input('status');

        $kategori = Kategori::withCount('warung')
            ->when($filterStatus, function ($q) use ($filterStatus) {
                $q->where('status', $filterStatus);
            })
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('nama_kategori', 'ASC')
            ->get();

        $totalKategori   = Kategori::count();
        $pendingCount    = Kategori::where('status', 'pending')->count();
        $approvedCount   = Kategori::where('status', 'approved')->count();

        return view('admin.kategori.index', compact('kategori', 'filterStatus', 'totalKategori', 'pendingCount', 'approvedCount'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|max:100'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'status'        => 'approved',
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|max:100',
            'status'        => 'nullable|in:approved,pending',
        ]);

        $kategori = Kategori::findOrFail($id);

        $data = ['nama_kategori' => $request->nama_kategori];
        if ($request->filled('status')) {
            $data['status'] = $request->status;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diubah.');
    }

    public function approve($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'Kategori "' . $kategori->nama_kategori . '" berhasil disetujui dan kini aktif.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}