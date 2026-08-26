<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>WarungBali - @yield('title', 'Login')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

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

    <style>
        /* Sembunyikan tombol mata bawaan browser (seperti di Microsoft Edge / Windows) agar tidak ganda */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            position: absolute;
            right: 0;
        }
    </style>

</head>

<body style="background:#FFF7EF;">

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
    </script>

</body>

</html>