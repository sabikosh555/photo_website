<?php
session_start();
include 'includes/db.php'; // Дерекқор қосылымын қосу

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageId = $_POST['image_id'];
    $comment = trim($_POST['comment']);

    try {
        // Комментарийді дерекқорға қосу
        $stmt = $conn->prepare("INSERT INTO comments (user_id, image_id, comment) VALUES (:user_id, :image_id, :comment)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'], // Пайдаланушы ID
            'image_id' => $imageId,            // Сурет ID
            'comment' => $comment              // Комментарий мәтіні
        ]);
        // Басты бетке қайта бағыттау
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        echo 'Қате: ' . $e->getMessage();
    }
}
?>
