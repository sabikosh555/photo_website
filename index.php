<?php
include 'includes/db.php';
include 'includes/header.php';

// Барлық суреттерді шығару
try {
    $stmt = $conn->prepare("SELECT images.id, images.image_path, users.username FROM images 
                            JOIN users ON images.user_id = users.id 
                            ORDER BY images.id DESC");
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Қате: " . $e->getMessage());
}

// Лайк қосу немесе алу үшін функция
function isLiked($imageId, $userId, $conn) {
    if ($userId) {
        $stmt = $conn->prepare("SELECT * FROM likes WHERE image_id = :image_id AND user_id = :user_id");
        $stmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
    return false;
}

// Логин арқылы пайдаланушы ID алу
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Басты бет</title>
    <script>
        function toggleLike(imageId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "like.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    var likeButton = document.querySelector("#like-" + imageId);
                    likeButton.classList.toggle('liked');
                    if (likeButton.classList.contains('liked')) {
                        likeButton.innerHTML = '❤️';
                    } else {
                        likeButton.innerHTML = '♡';
                    }
                }
            };
            xhr.send("image_id=" + imageId);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Photoshare</h1>
        <div class="gallery">
            <?php foreach ($images as $image): ?>
                <div class="image-card">
                    <img src="uploads/<?php echo $image['image_path']; ?>" alt="Сурет">
                    <p>Жүктеген: <?php echo $image['username']; ?></p>

                    <!-- Лайк батырмасы -->
                    <span id="like-<?php echo $image['id']; ?>" 
                          class="like-button <?php echo isLiked($image['id'], $user_id, $conn) ? 'liked' : ''; ?>"
                          onclick="toggleLike(<?php echo $image['id']; ?>)">
                        <?php echo isLiked($image['id'], $user_id, $conn) ? '❤️' : '♡'; ?>
                    </span>
                    
                    <a href="download.php?file=<?php echo urlencode($image['image_path']); ?>" class="download-button">Жүктеу</a>

                    <form method="POST" action="comment.php">
                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                        <textarea name="comment" placeholder="Комментарий жазыңыз"></textarea>
                        <button type="submit">Комментарий қалдыру</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
