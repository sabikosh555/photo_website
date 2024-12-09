<?php
session_start();
include 'includes/db.php';

if (isset($_POST['image_id']) && isset($_SESSION['user_id'])) {
    $imageId = $_POST['image_id'];
    $userId = $_SESSION['user_id'];

    // Лайк бар-жоғын тексеру
    $likeStmt = $conn->prepare("SELECT * FROM likes WHERE image_id = :image_id AND user_id = :user_id");
    $likeStmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
    $isLiked = $likeStmt->rowCount() > 0;

    // Егер лайк болса, алып тастау, болмаса қосу
    if ($isLiked) {
        $deleteStmt = $conn->prepare("DELETE FROM likes WHERE image_id = :image_id AND user_id = :user_id");
        $deleteStmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
        $liked = false;
    } else {
        $insertStmt = $conn->prepare("INSERT INTO likes (image_id, user_id) VALUES (:image_id, :user_id)");
        $insertStmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
        $liked = true;
    }

    // JSON жауап қайтару (лайк саны енді жоқ)
    echo json_encode(['liked' => $liked]);
} else {
    echo json_encode(['error' => 'User not logged in or image_id not provided']);
}
?>


