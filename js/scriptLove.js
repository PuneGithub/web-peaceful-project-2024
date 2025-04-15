document.addEventListener("DOMContentLoaded", function () {
    const loveButtons = document.querySelectorAll(".love-btn");

    loveButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const postId = this.getAttribute("data-postid");
            const btnLove = this.getAttribute("btnLove");
            const loveCountSpan = this.closest(".card-white").querySelector("#loveCount");
            const heartIcon = this.querySelector(".heart-icon");

            fetch("system/loveSystem.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `postId=${postId}&btnLove=${btnLove}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // เปลี่ยนไอคอน Love
                        if (data.action === "liked") {
                            heartIcon.innerHTML = "<i class='text-red-400 fa-solid fa-heart'></i>";
                            loveCountSpan.textContent = `Loves: ${parseInt(loveCountSpan.textContent.split(": ")[1]) + 1}`;
                        } else if (data.action === "unliked") {
                            heartIcon.innerHTML = "<i class='text-red-300 fa-solid fa-heart'></i>";
                            loveCountSpan.textContent = `Loves: ${parseInt(loveCountSpan.textContent.split(": ")[1]) - 1}`;
                        }
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
    });
});

