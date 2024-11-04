<?php
$conn = new mysqli('localhost', 'root', '', 'photo_sharing');

// Қосылымды тексеру
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кіру</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        Cute Pink Photo Sharing - Кіру
    </div>
    <form method="POST" action="login.php" class="form">
        <label for="email">Электронды пошта:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Кіру</button>
    </form>

    <?php
    // Логин формасын өңдеу
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servername = "localhost";
        $db_username = "your_username"; // Сіздің MySQL логин
        $db_password = "your_password"; // Сіздің MySQL паролі
        $dbname = "photo_sharing"; // Базаның аты

        $conn = new mysqli('localhost', 'root', '', 'photo_sharing');

        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT id, password FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password);
        
        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id; // Пайдаланушы идентификаторын сессияға жазыңыз
            header("Location: profile.php"); // Профиль бетіне жіберу
            exit();
        } else {
            echo "Логин немесе пароль дұрыс емес.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>