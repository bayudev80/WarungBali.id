<footer class="bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5 col-md-6">
                <h3 class="text-warning fw-bold mb-3">
                    WarungBali
                </h3>
                <p class="text-secondary mb-0" style="max-width: 420px; line-height: 1.6;">
                    Platform informasi warung lokal terbaik di Pulau Bali. Temukan beragam kuliner autentik dan dukung UMKM Bali.
                </p>
            </div>

            <div class="col-lg-3 col-md-3 col-6">
                <h5 class="text-white fw-semibold mb-3">Menu</h5>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                    <li>
                        <a href="{{ route('home') }}" class="footer-link text-secondary text-decoration-none">
                            <i class="bi bi-chevron-right me-1 small opacity-50"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#warung" class="footer-link text-secondary text-decoration-none">
                            <i class="bi bi-chevron-right me-1 small opacity-50"></i> Warung
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#kategori-section" class="footer-link text-secondary text-decoration-none">
                            <i class="bi bi-chevron-right me-1 small opacity-50"></i> Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('favorit.index') }}" class="footer-link text-secondary text-decoration-none">
                            <i class="bi bi-chevron-right me-1 small opacity-50"></i> Favorit Saya
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tentang') }}" class="footer-link text-secondary text-decoration-none">
                            <i class="bi bi-chevron-right me-1 small opacity-50"></i> Tentang Kami
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-3 col-6">
                <h5 class="text-white fw-semibold mb-3">Kontak</h5>
                <div class="d-flex flex-column gap-2 text-secondary">
                    <p class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-warning"></i>
                        <span>info@warungbali.id</span>
                    </p>
                    <p class="mb-0">
                        <a href="https://wa.me/6282146789679" target="_blank" rel="noopener" class="footer-link text-secondary text-decoration-none d-flex align-items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-success"></i>
                            <span>082146789679 (Chat WhatsApp)</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <hr class="border-secondary opacity-25 my-4">

        <p class="text-center text-secondary mb-0 small">
            © 2026 WarungBali. Seluruh hak cipta dilindungi.
        </p>
    </div>
</footer>