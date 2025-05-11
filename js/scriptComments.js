document.addEventListener("DOMContentLoaded", function () {
    //เลือก comment ทั้งหมด
    const commentBtn = document.querySelectorAll("button[id^='toggleComment_']");

    commentBtn.forEach(button => {
        button.addEventListener("click", function () {
            const postId = this.id.split("_")[1];
            const form = document.getElementById("commentForm_" + postId)
            const commentsBoxs = document.getElementById("commentsBoxs_" + postId);

            const isHidden = form.classList.contains("hidden");

            if (isHidden) {
                form.classList.remove("hidden");
                commentsBoxs.classList.remove("hidden");
            } else {
                form.classList.add("hidden");
                commentsBoxs.classList.add("hidden");
            }

        })
    })
    
    // Comment Form System
    const commentForms = document.querySelectorAll(".commentForm");
    commentForms.forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const postId = this.dataset.postid;
            const username = this.querySelector('input[name="username"]');
            const commentInput = this.querySelector('input[name="text"]');
            const commentText = commentInput.value;

            if (commentText.trim() === "") return;

            const formData = new URLSearchParams();
            formData.append("postId", postId);
            formData.append("comment", commentText);
            formData.append("username", username);
            formData.append("btnComment", "1");

            fetch("system/commentSystem.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: formData.toString()
            })
                .then(response => response.text())
                .then(data => {
                    const commentsBoxs = document.getElementById("commentsBoxs_" + postId)
                    commentsBoxs.innerHTML += data;
                    commentInput.value = "";
                })
        })
    })

})
