@extends('pemilik.layouts.app')

@section('title', 'Profil & Keamanan Akun')

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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Terjadi kesalahan:</div>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $hasPemilikFoto = false;
        $pemilikFotoUrl = '';
        if (!empty($user->foto)) {
            if (filter_var($user->foto, FILTER_VALIDATE_URL)) {
                $hasPemilikFoto = true;
                $pemilikFotoUrl = $user->foto;
            } elseif (file_exists(public_path('images/avatars/' . $user->foto))) {
                $hasPemilikFoto = true;
                $pemilikFotoUrl = asset('images/avatars/' . $user->foto);
            }
        }
        $pemilikInitial = strtoupper(substr(trim($user->nama ?: 'P'), 0, 1));
    @endphp

    <div class="mb-4 pb-2 border-bottom">
        <h4 class="fw-bold mb-1 text-dark">Profil & Keamanan Akun Pemilik Warung</h4>
        <p class="text-secondary small mb-0">Kelola foto profil (PP), identitas pemilik, dan kata sandi akun untuk akses dashboard kuliner WarungBali.id.</p>
    </div>

    <!-- SEKSI 1: EDIT PROFIL & FOTO PROFIL (PP) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-4" style="background: #ffffff; border: 1px solid #edf2f7 !important;">
        <div class="d-flex align-items-center gap-3 mb-4 pb-2 border-bottom">
            <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                <i class="bi bi-person-bounding-box"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Foto Profil & Identitas Pemilik</h6>
                <small class="text-muted">Perbarui foto profil (PP) dan informasi akun pemilik warung Anda.</small>
            </div>
        </div>

        <form action="{{ route('pemilik.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row align-items-center g-4">
                <!-- Avatar Preview Column -->
                <div class="col-auto text-center">
                    <div class="position-relative d-inline-block">
                        <div id="avatarContainer" class="rounded-circle shadow-sm overflow-hidden d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border: 3px solid #C85C2E; background: linear-gradient(135deg, #C85C2E, #d97706);">
                            @if($hasPemilikFoto)
                                <img id="previewAvatar" src="{{ $pemilikFotoUrl }}" alt="{{ $user->nama }}" class="w-100 h-100 object-fit-cover" onerror="this.style.display='none'; document.getElementById('initialAvatar').style.display='flex';">
                                <span id="initialAvatar" class="text-white fw-bold fs-2" style="display:none;">{{ $pemilikInitial }}</span>
                            @else
                                <img id="previewAvatar" src="" alt="{{ $user->nama }}" class="w-100 h-100 object-fit-cover" style="display:none;">
                                <span id="initialAvatar" class="text-white fw-bold fs-2">{{ $pemilikInitial }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Avatar Actions & Fields -->
                <div class="col">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required placeholder="Nama lengkap Anda">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-dark">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="email@contoh.com">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Ubah Foto Profil (PP)</label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <input type="file" name="foto" id="pemilikFotoInput" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg,image/webp" style="max-width: 320px;" onchange="previewPemilikFoto(this)">
                                @if($hasPemilikFoto)
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="document.getElementById('formHapusFotoPemilik').submit();">
                                        <i class="bi bi-trash me-1"></i> Hapus Foto
                                    </button>
                                @endif
                            </div>
                            <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, atau WebP. Maksimal ukuran: 3 MB.</small>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end pt-2 border-top">
                    <button type="submit" class="btn text-white fw-semibold rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #C85C2E, #d97706); border: none;">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </form>

        @if($hasPemilikFoto)
            <form id="formHapusFotoPemilik" action="{{ route('pemilik.profile.remove-foto') }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>

    <!-- SEKSI 2: KEAMANAN & PASSWORD -->
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
    function previewPemilikFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('previewAvatar');
                const initial = document.getElementById('initialAvatar');
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                if (initial) initial.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

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
