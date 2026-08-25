@extends('pemilik.layouts.app')

@section('title', 'Keamanan & Password')

@section('content')

<div class="content-box">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4 pb-2 border-bottom">
        <h4 class="fw-bold mb-1 text-dark">Keamanan Akun Pemilik Warung</h4>
        <p class="text-secondary small mb-0">Kelola kata sandi akun pemilik warung Anda untuk menjaga keamanan akses dashboard kuliner.</p>
    </div>

    <div class="row g-4">
        
        <!-- KOLOM 1: UBAH PASSWORD MANDIRI -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: #ffffff; border: 1px solid #edf2f7 !important;">
                
                <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Ubah Password Mandiri</h6>
                        <small class="text-muted">Ganti password akun Anda dengan kombinasi baru pilihan Anda.</small>
                    </div>
                </div>

                <form action="{{ route('pemilik.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(!empty($user->password))
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Password Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="current_password" id="pemilik_current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan password saat ini" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassField('pemilik_current_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password" id="pemilik_new_password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassField('pemilik_new_password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-check-circle"></i></span>
                            <input type="password" name="password_confirmation" id="pemilik_confirm_password" class="form-control" placeholder="Ulangi password baru" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassField('pemilik_confirm_password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn text-white fw-semibold rounded-pill px-4 py-2 w-100" style="background: linear-gradient(135deg, #C85C2E, #d97706); border: none;">
                        <i class="bi bi-check-lg me-1"></i> Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM 2: MINTA PASSWORD VIA EMAIL -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: #ffffff; border: 1px solid #edf2f7 !important;">
                
                <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
                    <div class="rounded-3 bg-warning bg-opacity-15 text-warning-emphasis d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                        <i class="bi bi-envelope-at-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Kirim Password ke Email</h6>
                        <small class="text-muted">Generate password baru otomatis dan kirim ke email terdaftar.</small>
                    </div>
                </div>

                <div class="p-3 rounded-4 mb-4" style="background: #FFF7ED; border: 1.5px solid #FDBA74;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-info-circle-fill text-warning fs-4 flex-shrink-0 mt-1"></i>
                        <div>
                            <div class="fw-bold text-dark mb-1">Email Tujuan: {{ $user->email }}</div>
                            <p class="text-secondary small mb-0" style="line-height: 1.5;">
                                Sistem akan membuat kombinasi kata sandi baru yang acak dan aman, lalu langsung mengirimkannya ke alamat email pemilik warung Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 mb-4 border">
                    <h6 class="fw-bold text-dark small mb-2">Panduan Keamanan:</h6>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>Password lama Anda akan segera dinonaktifkan.</li>
                        <li>Pastikan Anda memiliki akses aktif ke alamat email di atas.</li>
                        <li>Periksa kotak masuk (*inbox*) atau folder *spam* email Anda.</li>
                    </ul>
                </div>

                <form action="{{ route('pemilik.password.email') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuat password baru dan mengirimkannya ke email {{ $user->email }}? Password lama Anda akan langsung digantikan.');" class="mt-auto">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-outline-dark fw-semibold rounded-pill px-4 py-2 w-100 border-0 text-dark" style="background: #f59e0b;">
                        <i class="bi bi-send-fill me-1"></i> Kirim Password Baru ke Email Saya
                    </button>
                </form>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function togglePassField(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush

@endsection
