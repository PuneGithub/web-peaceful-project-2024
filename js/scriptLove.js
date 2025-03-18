document.addEventListener("DOMContentLoaded", function (){
    document.querySelectorAll(".love-btn").forEach((button) => {
        button.addEventListener("click", function () {
            let postId = this.getAttribute("data-postId");
            let heartIcon = this.querySelector(".heart-icon");
            let loveCountSpan = this.querySelector(".love-count");

            fetch("love.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "postId=" + postId,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let currentCount = parseInt(loveCountSpan.textContent);
                    if (data.action === "liked") {
                        loveCountSpan.textContent = currentCount + 1;
                        heartIcon.textContent = "❤️";
                    } else {
                        loveCountSpan.textContent = currentCount - 1;
                        heartIcon.textContent = "🤍"
                    }
                }
            })
        })
    })
})