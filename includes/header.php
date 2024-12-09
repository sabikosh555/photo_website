<?php
// Сессияны тексеру немесе бастау
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Сессияның бастапқы күйін тексеру үшін функция анықтау
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        // Пайдаланушы жүйеге кіргенін тексеру
        return isset($_SESSION['user_id']);
    }
}
?>
<header>
    <h1>Photoshare</h1>
    <nav class="menu">
        <a href="index.php">Басты бет</a>
        <?php if (isLoggedIn()): ?>
            <a href="profile.php">Профиль</a> 
            <a href="upload.php">Сурет жүктеу</a>
            <a href="logout.php">Шығу</a>
        <?php else: ?>
            <a href="login.php">Кіру</a>
            <a href="register.php">Тіркелу</a>
        <?php endif; ?>
    </nav>
</header>
