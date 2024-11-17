<?php
session_start();
include 'includes/db.php';

if (isset($_POST['image_id']) && isset($_SESSION['user_id'])) {
    $imageId = $_POST['image_id'];
    $userId = $_SESSION['user_id'];

    // Лайк бар ма, жоқ па тексереміз
    $stmt = $conn->prepare("SELECT * FROM likes WHERE image_id = :image_id AND user_id = :user_id");
    $stmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
    $like = $stmt->fetch();

    if ($like) {
        // Лайк бар болса, оны алып тастаймыз
        $stmt = $conn->prepare("DELETE FROM likes WHERE image_id = :image_id AND user_id = :user_id");
        $stmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
    } else {
        // Лайк жоқ болса, оны қосамыз
        $stmt = $conn->prepare("INSERT INTO likes (image_id, user_id) VALUES (:image_id, :user_id)");
        $stmt->execute(['image_id' => $imageId, 'user_id' => $userId]);
    }
    echo json_encode(['status' => 'success']);
}
?>
