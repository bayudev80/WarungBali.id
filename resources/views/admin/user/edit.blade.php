@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')

<style>
    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        background-color: #ffffff;
    }
    .modern-card-body {
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        margin-bottom: 0.4rem;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pt-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Edit Pengguna</h4>
        <p class="text-muted small mb-0 mt-1">Perbarui data pengguna di sistem.</p>
    </div>
    <a href="{{ route('admin.user.index') }}" class="btn btn-light text-secondary border rounded-pill px-4 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card modern-card">
    <div class="modern-card-body">
        <form action="{{ route('admin.user.update', $user->id_user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama lengkap" required>
                @error('nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com" required>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="pemilik" {{ old('role', $user->role) == 'pemilik' ? 'selected' : '' }}>Pemilik Warung</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Pembeli)</option>
                    </select>
                    @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label">Password Baru <span class="text-muted fw-normal">(Opsional)</span></label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                <div class="form-text text-muted">Minimal 6 karakter.</div>
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <hr class="mb-4">

            <div class="d-flex justify-content-end gap-3">
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-save me-1"></i> Perbarui Pengguna
                </button>
            </div>

        </form>
    </div>
</div>

@endsection