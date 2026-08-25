<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin WarungBali.id')</title>

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

    <!-- Admin Dashboard CSS (load last to override Bootstrap & ensure sticky header) -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ file_exists(public_path('css/admin.css')) ? filemtime(public_path('css/admin.css')) : time() }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <div class="d-flex align-items-center justify-content-center mb-1">
                <img src="{{ asset('images/logo.png') }}" alt="WarungBali Logo" width="32" height="32" class="rounded-2 me-2" style="object-fit:cover;">
                <h3 class="mb-0 lh-1">WarungBali<span>.id</span></h3>
            </div>
            <span>Admin Panel</span>
        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
                class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Kelola Kategori</span>
            </a>

            <a href="{{ route('admin.warung.index') }}"
                class="{{ (request()->routeIs('admin.warung.*') && !request()->routeIs('admin.warung.verifikasi')) ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Kelola Warung</span>
            </a>

            <a href="{{ route('admin.user.index') }}"
                class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Kelola Pengguna</span>
            </a>

            @php
                $isVerifikasiActive = request()->routeIs('admin.pemilik-akun.*') || request()->routeIs('admin.warung.verifikasi');
            @endphp
            <div class="sidebar-dropdown {{ $isVerifikasiActive ? 'show' : '' }}">
                <button type="button" class="sidebar-dropdown-toggle {{ $isVerifikasiActive ? 'active' : '' }}" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#menuVerifikasi" 
                    aria-expanded="{{ $isVerifikasiActive ? 'true' : 'false' }}"
                    aria-controls="menuVerifikasi">
                    <div class="menu-left">
                        <i class="bi bi-shield-check menu-icon"></i>
                        <span>Kelola Verifikasi</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        @if(isset($totalPendingVerifikasi) && $totalPendingVerifikasi > 0)
                            <span class="sidebar-badge">{{ $totalPendingVerifikasi }}</span>
                        @endif
                        <i class="bi bi-chevron-down chevron-icon"></i>
                    </div>
                </button>
                <div class="collapse {{ $isVerifikasiActive ? 'show' : '' }}" id="menuVerifikasi">
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.pemilik-akun.index') }}"
                            class="{{ request()->routeIs('admin.pemilik-akun.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>
                            <span class="flex-grow-1">Verifikasi Akun Pemilik</span>
                            @if(isset($pendingAkunCount) && $pendingAkunCount > 0)
                                <span class="sidebar-badge badge-sub">{{ $pendingAkunCount }}</span>
                            @endif
                        </a>

                        <div class="sidebar-submenu-divider"></div>

                        <a href="{{ route('admin.warung.verifikasi') }}"
                            class="{{ request()->routeIs('admin.warung.verifikasi') ? 'active' : '' }}">
                            <i class="bi bi-shop-window"></i>
                            <span class="flex-grow-1">Verifikasi Warung</span>
                            @if(isset($pendingWarungCount) && $pendingWarungCount > 0)
                                <span class="sidebar-badge badge-sub">{{ $pendingWarungCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.review.index') }}"
                class="{{ request()->routeIs('admin.review.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>Kelola Review</span>
            </a>

            <a href="{{ route('admin.favorit.index') }}"
                class="{{ request()->routeIs('admin.favorit.*') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>Kelola Favorit</span>
            </a>

        </nav>

    </aside>

    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop d-lg-none" id="adminSidebarBackdrop"></div>

    <!-- Content -->
    <div class="content">

        <!-- Topbar -->
        <header class="topbar">

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border d-lg-none rounded-3 shadow-none p-1 px-2 me-1" id="adminSidebarToggle" aria-label="Menu Admin">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h4 class="fw-bold mb-0">
                    @yield('title')
                </h4>
            </div>

            <div class="profile dropdown">

                <div class="d-flex align-items-center gap-3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-md-block">
                        <span class="d-block fw-bold text-dark mb-0 lh-1" style="font-size: 14px;">{{ Auth::user()->nama }}</span>
                        <span class="text-muted small" style="font-size: 12px;">Administrator</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm text-white" style="width: 42px; height: 42px; font-size: 16px; background-color: #C85C2E;">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2" style="min-width: 200px;">
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-secondary" href="{{ route('home') }}" target="_blank">
                            <i class="bi bi-globe2"></i> Ke Website Utama
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-25"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger fw-medium">
                                <i class="bi bi-box-arrow-right"></i> Keluar
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
        // Modal Helper Ringan & Cepat
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

        // Event listener pintar untuk mencegat konfirmasi form bawaan browser (localhost says)
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const inlineConfirm = form.getAttribute('onsubmit');
            if (inlineConfirm && inlineConfirm.includes('confirm(')) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const match = inlineConfirm.match(/confirm\(['"]?([^'"]+)['"]?\)/);
                const confirmMsg = match ? match[1] : 'Lanjutkan tindakan ini?';

                const submitBtn = form.querySelector('button[type="submit"]') || e.submitter;
                const isDanger = (submitBtn && (submitBtn.classList.contains('btn-danger') || submitBtn.classList.contains('btn-outline-danger'))) || confirmMsg.toLowerCase().includes('hapus') || confirmMsg.toLowerCase().includes('tolak');
                const isWarning = (submitBtn && (submitBtn.classList.contains('btn-warning') || submitBtn.classList.contains('btn-outline-warning') || submitBtn.classList.contains('btn-info'))) || confirmMsg.toLowerCase().includes('password') || confirmMsg.toLowerCase().includes('email');
                const isSuccess = (submitBtn && submitBtn.classList.contains('btn-success')) || confirmMsg.toLowerCase().includes('verifikasi') || confirmMsg.toLowerCase().includes('setujui') || confirmMsg.toLowerCase().includes('terima');

                window.showWarungBaliModal({
                    title: isDanger ? 'Konfirmasi Hapus / Tolak' : (isWarning ? 'Konfirmasi Permintaan' : (isSuccess ? 'Konfirmasi Verifikasi' : 'Konfirmasi Tindakan')),
                    message: confirmMsg,
                    icon: isDanger ? 'bi bi-trash-fill' : (isWarning ? 'bi bi-key-fill' : (isSuccess ? 'bi bi-patch-check-fill' : 'bi bi-question-circle-fill')),
                    variant: isDanger ? 'danger' : (isWarning ? 'warning' : (isSuccess ? 'success' : 'primary')),
                    confirmText: isDanger ? '<i class="bi bi-trash me-1"></i> Ya, Lanjutkan' : (isWarning ? '<i class="bi bi-send-check me-1"></i> Ya, Kirim' : '<i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan'),
                    onConfirm: function () {
                        form.removeAttribute('onsubmit');
                        form.submit();
                    }
                });
            }
        }, true);

        // Event delegation ringan (0 overhead)
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

                const isDanger = btn.classList.contains('btn-danger') || btn.classList.contains('btn-outline-danger') || confirmMsg.toLowerCase().includes('hapus') || confirmMsg.toLowerCase().includes('tolak');
                const isSuccess = btn.classList.contains('btn-success') || confirmMsg.toLowerCase().includes('verifikasi') || confirmMsg.toLowerCase().includes('setujui') || confirmMsg.toLowerCase().includes('terima');
                
                window.showWarungBaliModal({
                    title: isDanger ? 'Konfirmasi Hapus / Tolak' : (isSuccess ? 'Konfirmasi Verifikasi' : 'Konfirmasi Tindakan'),
                    message: confirmMsg,
                    icon: isDanger ? 'bi bi-exclamation-triangle-fill' : (isSuccess ? 'bi bi-patch-check-fill' : 'bi bi-question-circle-fill'),
                    variant: isDanger ? 'danger' : (isSuccess ? 'success' : 'primary'),
                    confirmText: isDanger ? '<i class="bi bi-trash me-1"></i> Ya, Lanjutkan' : '<i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan',
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
            const toggleBtn = document.getElementById('adminSidebarToggle');
            const backdrop = document.getElementById('adminSidebarBackdrop');

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