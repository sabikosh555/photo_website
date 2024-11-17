<?php
session_start();

// Егер пайдаланушы кірмеген болса, логин бетіне бағыттау
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/db.php';

if (isset($_GET['image_id'])) {
    try {
        // Алдымен суреттің жолын алу
        $stmt = $conn->prepare("SELECT image_path FROM images WHERE id = :image_id AND user_id = :user_id");
        $stmt->execute([
            'image_id' => $_GET['image_id'],
            'user_id' => $_SESSION['user_id']
        ]);

        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            // Файлды серверден жою
            $filePath = 'uploads/' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath); // Файлды жою
            }

            // Дерекқордан жазбаны жою
            $stmt = $conn->prepare("DELETE FROM images WHERE id = :image_id AND user_id = :user_id");
            $stmt->execute([
                'image_id' => $_GET['image_id'],
                'user_id' => $_SESSION['user_id']
            ]);

            // Профиль бетіне қайта бағыттау
            header('Location: profile.php');
            exit;
        } else {
            echo "Сурет табылмады немесе оны жоюға рұқсатыңыз жоқ.";
        }
    } catch (PDOException $e) {
        die("Қате: " . $e->getMessage());
    }
} else {
    header('Location: profile.php');
    exit;
}
?>
