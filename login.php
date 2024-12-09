<?php
include 'includes/db.php';

session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Жаңылыстар жоқ па, тексеріңіз
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Барлық өрістерді толтырыңыз!';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = htmlspecialchars($user['username']);
                header('Location: index.php');
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кіру</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="form-wrapper">
        <div class="form-container">
            <h1>Кіру</h1>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Құпия сөз" required>
                <button type="submit">Кіру</button>
            </form>
        </div>
    </div>
</body>
</html>
