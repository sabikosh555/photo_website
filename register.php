<?php
include 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Барлық өрістерді толтырыңыз!';
    } else {
        try {
            // Қайталанған email тексеру
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->rowCount() > 0) {
                $error = 'Бұл email бұрын тіркелген!';
            } else {
                // Жаңа пайдаланушыны қосу
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword
                ]);
                header('Location: login.php'); // Кіру бетіне бағыттау
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Қате: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Тіркелу</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="form-container">
        <h1>Тіркелу</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Атыңыз" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Құпия сөз" required>
            <button type="submit">Тіркелу</button>
        </form>
    </div>
</body>
</html>
