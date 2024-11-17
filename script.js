function toggleLike(imageId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "like.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            // Лайк батырмасының күйін өзгерту
            var likeButton = document.querySelector("#like-" + imageId);
            likeButton.classList.toggle('liked');
            // Жүректің түсін ауыстыру
            if (likeButton.classList.contains('liked')) {
                likeButton.innerHTML = '❤️';  // Қызыл жүрек
            } else {
                likeButton.innerHTML = '♡';  // Ашық жүрек
            }
        }
    };
    xhr.send("image_id=" + imageId);
}
