<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pemilik Warung - WarungBali.id')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Plus Jakarta Sans, Outfit, Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Pemilik Dashboard CSS (load last to override Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('css/pemilik.css') }}?v={{ file_exists(public_path('css/pemilik.css')) ? filemtime(public_path('css/pemilik.css')) : time() }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <div class="d-flex align-items-center justify-content-center mb-1">
                <img src="{{ asset('images/logo.png') }}" alt="WarungBali Logo" width="32" height="32" class="rounded-2 me-2" style="object-fit:cover;">
                <h3 class="mb-0 lh-1">WarungBali<span>.id</span></h3>
            </div>
            <span>Pemilik Warung</span>
        </div>

        <nav class="sidebar-menu">

            @if(auth()->user()->warung)

                <a href="{{ route('pemilik.dashboard') }}"
                    class="{{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pemilik.warung.edit') }}"
                    class="{{ request()->routeIs('pemilik.warung.*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Kelola Warung</span>
                </a>

                <a href="{{ route('pemilik.menu.index') }}"
                    class="{{ request()->routeIs('pemilik.menu.*') ? 'active' : '' }}">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Kelola Menu</span>
                </a>

                <a href="{{ route('pemilik.password.edit') }}"
                    class="{{ request()->routeIs('pemilik.password.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Profil & Keamanan Akun</span>
                </a>

            @else

                <a href="{{ route('pemilik.warung.create') }}"
                    class="{{ request()->routeIs('pemilik.warung.create') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Daftarkan Warung</span>
                </a>

                <a href="{{ route('pemilik.password.edit') }}"
                    class="{{ request()->routeIs('pemilik.password.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Profil & Keamanan Akun</span>
                </a>

            @endif

        </nav>

    </aside>


    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop d-lg-none" id="pemilikSidebarBackdrop"></div>

    <!-- Content -->
    <div class="content">

        <!-- Topbar -->
        <header class="topbar">

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border d-lg-none rounded-3 shadow-none p-1 px-2 me-1" id="pemilikSidebarToggle" aria-label="Menu Pemilik">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h4 class="fw-bold mb-0">
                    @yield('title')
                </h4>
            </div>

            <div class="profile dropdown">

                @php
                    $pemilikUser = Auth::user();
                    $hasPemilikFoto = false;
                    $pemilikFotoUrl = '';
                    if (!empty($pemilikUser->foto)) {
                        if (filter_var($pemilikUser->foto, FILTER_VALIDATE_URL)) {
                            $hasPemilikFoto = true;
                            $pemilikFotoUrl = $pemilikUser->foto;
                        } elseif (file_exists(public_path('images/avatars/' . $pemilikUser->foto))) {
                            $hasPemilikFoto = true;
                            $pemilikFotoUrl = asset('images/avatars/' . $pemilikUser->foto);
                        }
                    }
                    $pemilikInitial = strtoupper(substr(trim($pemilikUser->nama ?: 'P'), 0, 1));
                @endphp

                <div class="topbar-profile-btn" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-md-block lh-sm">
                        <span class="d-block fw-bold text-dark mb-0" style="font-size: 13.5px; letter-spacing: -0.2px;">{{ $pemilikUser->nama }}</span>
                        <span class="text-muted small" style="font-size: 11px; font-weight: 500;">Pemilik Warung</span>
                    </div>
                    @if($hasPemilikFoto)
                        <img src="{{ $pemilikFotoUrl }}" alt="{{ $pemilikUser->nama }}" class="topbar-avatar-img" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex');">
                        <div class="topbar-avatar-circle d-none">
                            {{ $pemilikInitial }}
                        </div>
                    @else
                        <div class="topbar-avatar-circle">
                            {{ $pemilikInitial }}
                        </div>
                    @endif
                    <i class="bi bi-chevron-down text-muted" style="font-size: 11px;"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 p-2" style="min-width: 260px; box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;">
                    {{-- User Info Header --}}
                    <li class="px-3 py-2.5 rounded-3 mb-2" style="background: #FFFDF9; border: 1px solid #FED7AA;">
                        <div class="d-flex align-items-center gap-2 mb-1.5">
                            @if($hasPemilikFoto)
                                <img src="{{ $pemilikFotoUrl }}" alt="{{ $pemilikUser->nama }}" class="rounded-circle shadow-xs" style="width:36px;height:36px;object-fit:cover;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-xs" style="width:36px;height:36px;background:linear-gradient(135deg, #d97706, #b45309);font-size:14px;">
                                    {{ $pemilikInitial }}
                                </div>
                            @endif
                            <div class="text-truncate" style="max-width: 170px;">
                                <div class="fw-bold text-dark text-truncate" style="font-size:13.5px; letter-spacing: -0.2px;">{{ $pemilikUser->nama }}</div>
                                <div class="text-muted small text-truncate" style="font-size:11.5px;">{{ $pemilikUser->email }}</div>
                            </div>
                        </div>
                        <div class="pt-1.5 border-top border-light-subtle d-flex flex-column gap-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
                                    <i class="bi bi-shop me-1"></i> Pemilik Warung
                                </span>
                                <span class="text-success small" style="font-size: 10.5px; font-weight: 600;"><i class="bi bi-check-circle-fill me-1"></i>Terverifikasi</span>
                            </div>
                            @if(!empty($pemilikUser->warung?->nama_warung))
                                <div class="text-dark small fw-semibold text-truncate" style="font-size: 11px;">
                                    <i class="bi bi-geo-alt text-danger me-1"></i>{{ $pemilikUser->warung->nama_warung }}
                                </div>
                            @endif
                        </div>
                    </li>

                    {{-- Menu Items --}}
                    <li>
                        <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold {{ request()->routeIs('pemilik.dashboard') ? 'bg-light text-warning' : '' }}" 
                           href="{{ route('pemilik.dashboard') }}" style="font-size: 13.5px; transition: all 0.2s;">
                            <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #FEF3C7; color: #D97706;">
                                <i class="bi bi-speedometer2"></i>
                            </span>
                            <span>Dashboard Pemilik</span>
                            @if(request()->routeIs('pemilik.dashboard'))
                                <i class="bi bi-check2 text-warning ms-auto"></i>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold {{ request()->routeIs('pemilik.password.edit') ? 'bg-light text-primary' : '' }}" 
                           href="{{ route('pemilik.password.edit') }}" style="font-size: 13.5px; transition: all 0.2s;">
                            <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #EFF6FF; color: #2563EB;">
                                <i class="bi bi-person-gear"></i>
                            </span>
                            <span>Kelola Profil & Keamanan</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold" 
                           href="{{ route('home') }}" target="_blank" style="font-size: 13.5px; transition: all 0.2s;">
                            <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #F0FDF4; color: #16A34A;">
                                <i class="bi bi-globe2"></i>
                            </span>
                            <span>Lihat Website Utama</span>
                            <i class="bi bi-box-arrow-up-right ms-auto text-muted" style="font-size: 11px;"></i>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider my-1.5 opacity-25"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-danger fw-bold" style="font-size: 13.5px; transition: all 0.2s;">
                                <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #FFF1F2; color: #E11D48;">
                                    <i class="bi bi-box-arrow-right"></i>
                                </span>
                                <span>Keluar / Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>

            </div>

        </header>

        <!-- Main Content -->
        <main class="main-content">

            @yield('content')

        </main>

    </div>

    <!-- Modal Konfirmasi Ringan WarungBali.id (Di Tengah Layar) -->
    <div class="modal fade" id="warungBaliConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" id="wbModalIconBg" style="width: 56px; height: 56px; background: #FFF7ED; color: #C85C2E; font-size: 24px;">
                        <i class="bi bi-shield-check" id="wbModalIcon"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark" id="wbModalTitle">Konfirmasi</h5>
                    <p class="text-muted small mb-4 px-2" id="wbModalMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light px-3 py-2 text-secondary fw-semibold border" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 14px; flex: 1;">
                            Batal
                        </button>
                        <button type="button" class="btn text-white px-3 py-2 fw-semibold shadow-sm" id="wbModalConfirmBtn" style="border-radius: 10px; font-size: 14px; background: #C85C2E; flex: 1.2;">
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.showWarungBaliModal = function(options) {
            const modalEl = document.getElementById('warungBaliConfirmModal');
            if (!modalEl) return;
            
            document.getElementById('wbModalTitle').textContent = options.title || 'Konfirmasi';
            document.getElementById('wbModalMessage').innerHTML = options.message || 'Apakah Anda yakin?';
            
            const iconEl = document.getElementById('wbModalIcon');
            iconEl.className = options.icon || 'bi bi-shield-check';
            
            const iconBg = document.getElementById('wbModalIconBg');
            const confirmBtn = document.getElementById('wbModalConfirmBtn');

            if (options.variant === 'danger') {
                iconBg.style.background = '#FEF2F2';
                iconBg.style.color = '#DC2626';
                confirmBtn.style.background = '#DC2626';
            } else if (options.variant === 'success') {
                iconBg.style.background = '#ECFDF5';
                iconBg.style.color = '#059669';
                confirmBtn.style.background = '#059669';
            } else {
                iconBg.style.background = '#FFF7ED';
                iconBg.style.color = '#C85C2E';
                confirmBtn.style.background = '#C85C2E';
            }

            confirmBtn.innerHTML = options.confirmText || 'Ya, Lanjutkan';

            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);

            confirmBtn.onclick = function() {
                bsModal.hide();
                if (typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
            };

            bsModal.show();
        };

        // Event listener pintar untuk mencegat konfirmasi bawaan browser (localhost says)
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const inlineConfirm = form.getAttribute('onsubmit');
            if (inlineConfirm && inlineConfirm.includes('confirm(')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const match = inlineConfirm.match(/confirm\(['"]?([^'"]+)['"]?\)/);
                const confirmMsg = match ? match[1] : 'Lanjutkan tindakan ini?';

                const submitBtn = form.querySelector('button[type="submit"]') || e.submitter;
                const isDanger = (submitBtn && (submitBtn.classList.contains('btn-danger') || submitBtn.classList.contains('btn-outline-danger'))) || confirmMsg.toLowerCase().includes('hapus');

                window.showWarungBaliModal({
                    title: isDanger ? 'Konfirmasi Hapus' : 'Konfirmasi Tindakan',
                    message: confirmMsg,
                    icon: isDanger ? 'bi bi-trash-fill' : 'bi bi-question-circle-fill',
                    variant: isDanger ? 'danger' : 'primary',
                    confirmText: isDanger ? '<i class="bi bi-trash me-1"></i> Ya, Hapus' : '<i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan',
                    onConfirm: function () {
                        form.removeAttribute('onsubmit');
                        form.submit();
                    }
                });
            }
        }, true);

        // Event delegation ringan
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('button[onclick*="confirm("], a[onclick*="confirm("]');
            if (!btn) return;

            const inlineConfirm = btn.getAttribute('onclick');
            if (inlineConfirm && inlineConfirm.includes('confirm(')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const match = inlineConfirm.match(/confirm\(['"]?([^'"]+)['"]?\)/);
                const confirmMsg = match ? match[1] : 'Lanjutkan tindakan ini?';
                const form = btn.closest('form');

                const isDanger = btn.classList.contains('btn-danger') || btn.classList.contains('btn-outline-danger') || confirmMsg.toLowerCase().includes('hapus');
                
                window.showWarungBaliModal({
                    title: isDanger ? 'Konfirmasi Hapus' : 'Konfirmasi Tindakan',
                    message: confirmMsg,
                    icon: isDanger ? 'bi bi-trash-fill' : 'bi bi-question-circle-fill',
                    variant: isDanger ? 'danger' : 'primary',
                    confirmText: isDanger ? '<i class="bi bi-trash me-1"></i> Ya, Hapus' : '<i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan',
                    onConfirm: function () {
                        if (form) {
                            form.submit();
                        } else if (btn.tagName === 'A' && btn.href) {
                            window.location.href = btn.href;
                        }
                    }
                });
            }
        }, true);

        // Auto-dismiss Flash Alerts (Otomatis menghilang setelah 3.5 detik secara halus)
        function initAutoDismissAlerts() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent):not(.no-auto-dismiss):not(.alert-static)');
            alerts.forEach(function(alert) {
                if (alert.dataset.autoDismissInit) return;
                alert.dataset.autoDismissInit = 'true';

                alert.style.transition = 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), max-height 0.4s ease, margin 0.4s ease, padding 0.4s ease';
                alert.style.overflow = 'hidden';
                
                let timeoutId = setTimeout(function() {
                    dismissAlert(alert);
                }, 3500);

                // Jeda timer jika kursor berada di atas notifikasi
                alert.addEventListener('mouseenter', function() {
                    clearTimeout(timeoutId);
                });

                alert.addEventListener('mouseleave', function() {
                    timeoutId = setTimeout(function() {
                        dismissAlert(alert);
                    }, 1500);
                });
            });

            function dismissAlert(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(function() {
                    alert.style.maxHeight = '0px';
                    alert.style.padding = '0px';
                    alert.style.margin = '0px';
                    alert.style.border = 'none';
                    alert.style.outline = 'none';
                    alert.style.boxShadow = 'none';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 350);
                }, 250);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAutoDismissAlerts);
        } else {
            initAutoDismissAlerts();
        }

        // Toggle Sidebar Mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('pemilikSidebarToggle');
            const backdrop = document.getElementById('pemilikSidebarBackdrop');

            function toggleSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (backdrop) backdrop.addEventListener('click', toggleSidebar);
        });
    </script>

    @stack('scripts')

</body>

</html>