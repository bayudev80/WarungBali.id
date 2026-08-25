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

</head>

<body style="background:#FFF7EF;">

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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