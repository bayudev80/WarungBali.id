document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".favorite-btn").forEach((button) => {
        button.addEventListener("click", function () {
            // Jika belum login
            if (this.dataset.login === "false") {
                if (
                    confirm(
                        "Silakan login terlebih dahulu untuk menambahkan favorit.",
                    )
                ) {
                    window.location.href = "/login";
                }

                return;
            }

            const btn = this;

            fetch("/favorit/" + btn.dataset.id, {
                method: "POST",

                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,

                    Accept: "application/json",
                },
            })
                .then((response) => response.json())

                .then((data) => {
                    console.log(data);

                    if(!data.success){
                        alert(data.message);
                        return;
                    }

                    if (data.favorit) {
                        btn.innerHTML =
                            '<i class="fa-solid fa-heart text-danger"></i>';
                    } else {
                        btn.innerHTML = '<i class="fa-regular fa-heart"></i>';
                    }
                })

                .catch((error) => console.error(error));
        });
    });
});
