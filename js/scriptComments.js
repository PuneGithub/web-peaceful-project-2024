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

            // form.classList.toggle("hidden");
            // commentsBoxs.classList.toggle("hidden");
        })
    })
})