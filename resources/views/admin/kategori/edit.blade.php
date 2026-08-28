@extends('admin.layouts.app')

@section('title','Edit Kategori')

@section('content')
<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <h2 class="mb-0 text-gray-800">Edit Kategori</h2>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit me-2"></i>Form Edit Kategori</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.kategori.update',$kategori->id_kategori) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" class="form-control form-control-lg rounded-3" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" placeholder="Masukkan nama kategori" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Status Kategori <span class="text-danger">*</span></label>
                            <select name="status" class="form-select rounded-3">
                                <option value="approved" {{ old('status', $kategori->status) === 'approved' ? 'selected' : '' }}>Disetujui (Approved / Aktif)</option>
                                <option value="pending" {{ old('status', $kategori->status) === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
                            </select>
                        </div>
                        
                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm border">
                                <i class="fas fa-arrow-left me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection