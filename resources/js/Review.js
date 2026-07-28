// ==========================
// STAR RATING PICKER
// ==========================
function paintStars(container, value) {
    const stars = container.querySelectorAll(".star");

    stars.forEach((star) => {
        const starValue = parseInt(star.dataset.value, 10);
        star.style.color = starValue <= value ? "#ffc107" : "#ccc";
    });
}

document.addEventListener("click", (e) => {
    const star = e.target.closest(".star-rating .star");

    if (!star) return;

    const container = star.closest(".star-rating");
    const targetInput = document.getElementById(container.dataset.target);
    const value = parseInt(star.dataset.value, 10);

    targetInput.value = value;
    paintStars(container, value);
});

// Cat bintang sesuai nilai awal (mis. saat form "Perbarui Ulasan" sudah terisi)
document.querySelectorAll(".star-rating").forEach((container) => {
    const targetInput = document.getElementById(container.dataset.target);
    const initialValue = parseInt(targetInput?.value || "0", 10);

    if (initialValue > 0) {
        paintStars(container, initialValue);
    }
});

// ==========================
// SUBMIT ULASAN
// ==========================
document.addEventListener("submit", (e) => {
    const form = e.target.closest(".review-form");

    if (!form) return;

    e.preventDefault();

    const warungId = form.dataset.warungId;
    const ratingInput = form.querySelector('input[name="rating"]');
    const komentarInput = form.querySelector('textarea[name="komentar"]');
    const messageBox = form.querySelector(".review-form-message");
    const submitBtn = form.querySelector('button[type="submit"]');

    messageBox.textContent = "";
    messageBox.className = "review-form-message small mt-2";

    if (!ratingInput.value || parseInt(ratingInput.value, 10) < 1) {
        messageBox.textContent = "Silakan pilih rating bintang terlebih dahulu.";
        messageBox.classList.add("text-danger");
        return;
    }

    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = "Mengirim...";

    fetch("/warung/" + warungId + "/review", {
        method: "POST",

        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "Content-Type": "application/json",
            Accept: "application/json",
        },

        body: JSON.stringify({
            rating: ratingInput.value,
            komentar: komentarInput.value,
        }),
    })
        .then((response) => response.json().then((data) => ({ status: response.status, data })))

        .then(({ status, data }) => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;

            if (status === 401) {
                messageBox.textContent = "Sesi Anda berakhir, silakan login kembali.";
                messageBox.classList.add("text-danger");
                return;
            }

            if (!data.success) {
                messageBox.textContent =
                    data.message || "Gagal mengirim ulasan. Coba lagi.";
                messageBox.classList.add("text-danger");
                return;
            }

            renderReview(warungId, data.review);
            updateSummary(warungId, data.average_rating, data.total_review);

            submitBtn.innerHTML = "Perbarui Ulasan";

            messageBox.textContent = "Terima kasih! Ulasan Anda berhasil disimpan.";
            messageBox.classList.add("text-success");
        })

        .catch((error) => {
            console.error(error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            messageBox.textContent = "Terjadi kesalahan jaringan. Coba lagi.";
            messageBox.classList.add("text-danger");
        });
});

function renderReview(warungId, review) {
    const list = document.getElementById("review-list-" + warungId);

    if (!list) return;

    // Hapus pesan "belum ada ulasan" kalau masih ada
    const emptyState = list.querySelector(".review-empty-state");
    if (emptyState) emptyState.remove();

    // Kalau user ini sudah pernah punya ulasan di daftar, update kartunya.
    // Kalau belum, tambahkan kartu baru di posisi paling atas.
    let card = list.querySelector('[data-review-user="' + review.id_user + '"]');

    const starsHtml = Array.from({ length: 5 }, (_, i) => {
        const filled = i + 1 <= review.rating;
        return (
            '<span class="' +
            (filled ? "text-warning" : "text-secondary") +
            ' fs-5">' +
            (filled ? "★" : "☆") +
            "</span>"
        );
    }).join("");

    const cardHtml =
        '<div class="border rounded-4 p-4 mb-3" data-review-user="' +
        review.id_user +
        '">' +
        '<div class="d-flex justify-content-between align-items-center mb-2">' +
        "<strong>👤 " +
        escapeHtml(review.nama) +
        "</strong>" +
        '<small class="text-secondary">' +
        review.tanggal +
        "</small>" +
        "</div>" +
        '<div class="mb-2">' +
        starsHtml +
        "</div>" +
        '<p class="mb-0">' +
        escapeHtml(review.komentar || "") +
        "</p>" +
        "</div>";

    if (card) {
        card.outerHTML = cardHtml;
    } else {
        list.insertAdjacentHTML("afterbegin", cardHtml);
    }
}

function updateSummary(warungId, averageRating, totalReview) {
    const summary = document.getElementById("review-summary-" + warungId);

    if (!summary) return;

    const avgEl = summary.querySelector(".review-summary-avg");
    const countEl = summary.querySelector(".review-summary-count");

    if (avgEl) avgEl.textContent = parseFloat(averageRating).toFixed(1);
    if (countEl) countEl.textContent = "(" + totalReview + " Ulasan)";
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
