document.addEventListener("click", (e) => {
    const btn = e.target.closest(".favorite-btn");

    if (!btn) return;

    // Jika belum login
    if (btn.dataset.login === "false") {
        if (
            confirm("Silakan login terlebih dahulu untuk menambahkan favorit.")
        ) {
            window.location.href = "/login";
        }

        return;
    }

    fetch("/favorit/" + btn.dataset.id, {
        method: "POST",

        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,

            Accept: "application/json",
        },
    })
        .then((response) => response.json())

        .then((data) => {
            console.log(data);

            if (!data.success) {
                alert(data.message);
                return;
            }

            if (data.favorit) {
                btn.innerHTML = '<i class="fa-solid fa-heart text-danger"></i>';
            } else {
                btn.innerHTML = '<i class="fa-regular fa-heart"></i>';
            }
        })

        .catch((error) => console.error(error));
});
