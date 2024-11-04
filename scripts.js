document.addEventListener("DOMContentLoaded", () => {
    const likeButtons = document.querySelectorAll(".like-btn");

    likeButtons.forEach(button => {
        button.addEventListener("click", () => {
            const likeCount = button.previousElementSibling.querySelector(".like-count");
            let currentLikes = parseInt(likeCount.textContent);
            likeCount.textContent = currentLikes + 1;
        });
    });
});