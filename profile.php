<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/db.php';
include 'includes/header.php';

// Пайдаланушының деректерін алу
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Қате: Пайдаланушы табылмады.");
    }
} catch (PDOException $e) {
    die("Қате: " . $e->getMessage());
}

$error = '';
$success = '';

// Профильді жаңарту
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($email)) {
        $error = 'Барлық өрістерді толтырыңыз';
    } else {
        try {
            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email WHERE id = :user_id");
            $stmt->execute(['username' => $username, 'email' => $email, 'user_id' => $_SESSION['user_id']]);
            $success = 'Профиль сәтті өзгертілді!';
        } catch (PDOException $e) {
            $error = "Қате: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Менің профилім</title>
</head>
<body>
    <div class="container">
        <h1>Менің профилім</h1>
        
        <p>Пайдаланушы аты: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

        <!-- Профильді өзгерту батырмасы -->
        <button id="editBtn">Профильді өзгерту</button>

        <!-- Өңдеу формасы -->
        <div id="editForm" style="display: none;">
            <h2>Профильді жаңарту</h2>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="username">Жаңа пайдаланушы аты:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <label for="email">Жаңа email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                
                <button type="submit" name="update_profile">Жаңарту</button>
            </form>
        </div>
    </div>

    <script>
        // Өзгерту батырмасын басқанда форма ашылады
        document.getElementById('editBtn').addEventListener('click', function() {
            var form = document.getElementById('editForm');
            form.style.display = 'block'; // Форманы көрсету
            this.style.display = 'none'; // Өзгерту батырмасын жасыру
        });
    </script>
</body>
</html>
