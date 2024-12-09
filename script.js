function toggleLike(imageId) {
    var likeButton = document.querySelector("#like-" + imageId);
    
    // Лайк батырмасының күйін бірден өзгерту
    if (likeButton.classList.contains('liked')) {
        likeButton.classList.remove('liked');
        likeButton.innerHTML = '♡'; // Ашық жүрек
    } else {
        likeButton.classList.add('liked');
        likeButton.innerHTML = '❤️'; // Қызыл жүрек
    }

    // AJAX сұрау жасау
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "like.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            // Лайк күйін өзгерту (лайк саны жоқ)
            // Бірақ лайк батырмасы дұрыс күйге түседі
        }
    };
    xhr.send("image_id=" + imageId);
}
