<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WarungBali</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('scripts')
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

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="d-flex flex-column min-vh-100">

@include('partials.navbar')

<main class="flex-grow-1">
    @yield('content')
</main>

@include('partials.footer')

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
    // Modal Helper Ringan & Cepat (0 library tambahan, 60fps native transition)
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
        } else if (options.variant === 'warning') {
            iconBg.style.background = '#FFFBEB';
            iconBg.style.color = '#D97706';
            confirmBtn.style.background = '#D97706';
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

    // Event listener pintar untuk mencegat konfirmasi bawaan browser (localhost / 127.0.0.1 says)
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
            const isWarning = (submitBtn && (submitBtn.classList.contains('btn-warning') || submitBtn.classList.contains('btn-outline-warning'))) || confirmMsg.toLowerCase().includes('password') || confirmMsg.toLowerCase().includes('email');
            const isSuccess = (submitBtn && submitBtn.classList.contains('btn-success')) || confirmMsg.toLowerCase().includes('verifikasi') || confirmMsg.toLowerCase().includes('setujui');

            window.showWarungBaliModal({
                title: isDanger ? 'Konfirmasi Hapus' : (isWarning ? 'Konfirmasi Permintaan' : (isSuccess ? 'Konfirmasi Verifikasi' : 'Konfirmasi Tindakan')),
                message: confirmMsg,
                icon: isDanger ? 'bi bi-trash-fill' : (isWarning ? 'bi bi-key-fill' : (isSuccess ? 'bi bi-patch-check-fill' : 'bi bi-question-circle-fill')),
                variant: isDanger ? 'danger' : (isWarning ? 'warning' : (isSuccess ? 'success' : 'primary')),
                confirmText: isDanger ? '<i class="bi bi-trash me-1"></i> Ya, Hapus' : (isWarning ? '<i class="bi bi-send-check me-1"></i> Ya, Kirim' : '<i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan'),
                onConfirm: function () {
                    form.removeAttribute('onsubmit');
                    form.submit();
                }
            });
        }
    }, true);

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
            const isWarning = btn.classList.contains('btn-warning') || btn.classList.contains('btn-outline-warning') || confirmMsg.toLowerCase().includes('password') || confirmMsg.toLowerCase().includes('email');
            const isSuccess = btn.classList.contains('btn-success') || confirmMsg.toLowerCase().includes('verifikasi') || confirmMsg.toLowerCase().includes('setujui');

            window.showWarungBaliModal({
                title: isDanger ? 'Konfirmasi Hapus' : (isWarning ? 'Konfirmasi Permintaan' : (isSuccess ? 'Konfirmasi Verifikasi' : 'Konfirmasi Tindakan')),
                message: confirmMsg,
                icon: isDanger ? 'bi bi-trash-fill' : (isWarning ? 'bi bi-key-fill' : (isSuccess ? 'bi bi-patch-check-fill' : 'bi bi-question-circle-fill')),
                variant: isDanger ? 'danger' : (isWarning ? 'warning' : (isSuccess ? 'success' : 'primary')),
                confirmText: isDanger ? '<i class="bi bi-trash me-1"></i> Ya, Hapus' : (isWarning ? '<i class="bi bi-send-check me-1"></i> Ya, Kirim' : '<i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan'),
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

    // Auto-dismiss Flash Alerts (Otomatis menghilang setelah 4 detik secara halus)
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent):not(.no-auto-dismiss)');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
            alert.style.overflow = 'hidden';
            
            let timeoutId = setTimeout(function() {
                dismissAlert(alert);
            }, 4000);

            // Jeda timer jika kursor berada di atas notifikasi
            alert.addEventListener('mouseenter', function() {
                clearTimeout(timeoutId);
            });

            alert.addEventListener('mouseleave', function() {
                timeoutId = setTimeout(function() {
                    dismissAlert(alert);
                }, 2000);
            });
        });

        function dismissAlert(alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(function() {
                alert.style.maxHeight = '0px';
                alert.style.paddingTop = '0px';
                alert.style.paddingBottom = '0px';
                alert.style.marginTop = '0px';
                alert.style.marginBottom = '0px';
                alert.style.border = 'none';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 300);
        }
    });
</script>

</body>

</html>