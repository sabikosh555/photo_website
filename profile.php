<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/db.php';

// Пайдаланушының деректерін алу
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Қате: Пайдаланушы табылмады.");
    }

    // Жүктелген суреттерді шығару
    $stmt = $conn->prepare("SELECT * FROM images WHERE user_id = :user_id ORDER BY uploaded_at DESC");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $userImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Қате: " . $e->getMessage());
}

$error = '';
$success = '';

// Пайдаланушыны жою (опционалды)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        session_destroy();
        header('Location: register.php');
        exit;
    } catch (PDOException $e) {
        $error = "Аккаунтты жою мүмкін болмады: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Менің профилім</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1>Менің профилім</h1>
        <p>Пайдаланушы аты: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <h2>Менің суреттерім</h2>
        <?php if (count($userImages) > 0): ?>
            <div class="gallery">
                <?php foreach ($userImages as $image): ?>
                    <div class="image-card">
                        <img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Сурет">
                        <p><?php echo htmlspecialchars($image['description']); ?></p>
                        <a href="delete_image.php?image_id=<?php echo $image['id']; ?>" class="delete-link">Суретті жою</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Сіз әлі сурет жүктеген жоқсыз.</p>
        <?php endif; ?>

        <h2>Аккаунтты жою</h2>
        <form method="POST">
            <button type="submit" name="delete_account">Аккаунтты жою</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
