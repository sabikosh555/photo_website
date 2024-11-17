<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/db.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];
    $imageError = $_FILES['image']['error'];
    $imageType = $_FILES['image']['type'];
    $description = trim($_POST['description']); // Сипаттаманы алу

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if ($imageError === 0) {
        if (in_array($imageType, $allowedTypes)) {
            if ($imageSize <= 5000000) { // 5MB шектеу
                $imageNewName = uniqid('', true) . "." . pathinfo($imageName, PATHINFO_EXTENSION);
                $imageDestination = 'uploads/' . $imageNewName;

                if (move_uploaded_file($imageTmpName, $imageDestination)) {
                    try {
                        // description қосылды
                        $stmt = $conn->prepare("INSERT INTO images (user_id, image_path, description) VALUES (:user_id, :image_path, :description)");
                        $stmt->execute([
                            'user_id' => $_SESSION['user_id'],
                            'image_path' => $imageNewName,
                            'description' => $description // Сипаттаманы беру
                        ]);
                        $success = 'Сурет сәтті жүктелді!';
                    } catch (PDOException $e) {
                        $error = 'Қате: ' . $e->getMessage();
                    }
                } else {
                    $error = 'Суретті серверге жүктеу мүмкін болмады.';
                }
            } else {
                $error = 'Сурет файлының көлемі 5MB-тан аспауы керек!';
            }
        } else {
            $error = 'Сурет форматы жарамсыз! JPEG, PNG немесе GIF болуы керек.';
        }
    } else {
        $error = 'Суретті жүктеу кезінде қате пайда болды.';
    }
}

?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Сурет жүктеу</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="form-container">
        <h1>Сурет жүктеу</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <textarea name="description" placeholder="Сурет сипаттамасы" rows="4"></textarea>
            <button type="submit">Жүктеу</button>
        </form>
        <a href="index.php" class="back-button">Басты бетке қайту</a>
    </div>
</body>
</html>
