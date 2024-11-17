<?php
include 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Барлық өрістерді толтырыңыз!';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php'); // Басты бетке бағыттау
                exit;
            } else {
                $error = 'Email немесе құпия сөз қате!';
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
    <title>Кіру</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="form-container">
        <h1>Кіру</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Құпия сөз" required>
            <button type="submit">Кіру</button>
        </form>
    </div>
</body>
</html>
