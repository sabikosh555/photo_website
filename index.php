<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Қолданушы ID (логин жасағанын тексеру)
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

try {
    // Суреттерді дерекқордан алу
    $stmt = $conn->prepare("
        SELECT images.id, images.description, images.image_path, users.username 
        FROM images 
        JOIN users ON images.user_id = users.id 
        ORDER BY images.id DESC
    ");
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Барлық комментарийлерді алу
    $commentsStmt = $conn->prepare("
        SELECT comments.*, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE comments.image_id = :image_id 
        ORDER BY comments.created_at ASC
    ");
} catch (PDOException $e) {
    die("Қате: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Басты бет</title>
    <style>
        .gallery { display: flex; flex-wrap: wrap; gap: 20px; }
        .image-card { width: 300px; border: 1px solid #ccc; border-radius: 8px; padding: 10px; }
        .image-card img { width: 100%; height: auto; border-radius: 5px; }
        .comments { margin-top: 10px; font-size: 14px; color: #555; }
        .comment { margin-bottom: 5px; }
        .comment-username { font-weight: bold; }
        .comment-form textarea { width: 100%; resize: none; }
        .like-button { cursor: pointer; font-size: 18px; color: #999; }
        .like-button.liked { color: #e74c3c; }
        .like-count { margin-left: 5px; font-size: 16px; color: #333; }
    </style>
    <script>
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

    </script>
</head>
<body>
    <div class="container">
        <div class="gallery">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                    <div class="image-card">
                        <p><strong>Жүктеген:</strong> <?php echo htmlspecialchars($image['username']); ?></p>
                        <img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Сурет">

                        <!-- Сипаттаманы көрсету -->
                        <p class="description"><?php echo htmlspecialchars($image['description']); ?></p>

                        <!-- Лайк батырмасы -->
                        <?php
                        // Лайк бар-жоғын тексеру
                        $likeStmt = $conn->prepare("SELECT * FROM likes WHERE image_id = :image_id AND user_id = :user_id");
                        $likeStmt->execute(['image_id' => $image['id'], 'user_id' => $userId]);
                        $isLiked = $likeStmt->rowCount() > 0;

                        // Лайктар санын алу
                        $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM likes WHERE image_id = :image_id");
                        $countStmt->execute(['image_id' => $image['id']]);
                        $likeCount = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
                        ?>
                        <span id="like-<?php echo $image['id']; ?>" 
                              class="like-button <?php echo $isLiked ? 'liked' : ''; ?>"
                              onclick="toggleLike(<?php echo $image['id']; ?>)">
                            <?php echo $isLiked ? '❤️' : '♡'; ?>
                        </span>
                        
                        </span>

                        <!-- Жүктеу батырмасы -->
                        <a href="download.php?file=<?php echo urlencode($image['image_path']); ?>" class="download-button">Жүктеу</a>

                        <!-- Комментарийлер -->
                        <div class="comments">
                            <?php
                            $commentsStmt->execute(['image_id' => $image['id']]);
                            $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($comments as $comment): ?>
                                <div class="comment">
                                    <span class="comment-username"><?php echo htmlspecialchars($comment['username']); ?>:</span>
                                    <?php echo htmlspecialchars($comment['comment']); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Комментарий жазу формасы -->
                        <?php if ($userId): ?>
                            <form method="POST" action="comment.php" class="comment-form">
                                <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                <textarea name="comment" placeholder="Комментарий жазыңыз..." rows="2" required></textarea>
                                <button type="submit">Жіберу</button>
                            </form>
                        <?php else: ?>
                            <p>Комментарий жазу үшін <a href="login.php">кіруіңіз</a> қажет.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Суреттер жоқ.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
