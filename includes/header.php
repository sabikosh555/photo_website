<?php
// Сессияның бастапқы күйін тексеру
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Сессия бастау тек бір рет
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<header>
    <nav class="menu">
        <a href="index.php">Басты бет</a>
        <?php if (isLoggedIn()): ?>
            <a href="profile.php">Профиль</a> <!-- Профиль сілтемесі -->
            <a href="upload.php">Сурет жүктеу</a>
            <a href="logout.php">Шығу</a>
        <?php else: ?>
            <a href="login.php">Кіру</a>
            <a href="register.php">Тіркелу</a>
        <?php endif; ?>
    </nav>
</header>
