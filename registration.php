<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php"); // Егер пайдаланушы кірген болса, профильге жіберу
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тіркелу</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        Cute Pink Photo Sharing - Тіркелу
    </div>
    <form method="POST" action="registration.php" class="form">
        <label for="username">Пайдаланушы аты:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Электронды пошта:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Тіркелу</button>
    </form>

    <?php
    // Тіркелу формасын өңдеу
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servername = "localhost";
        $db_username = "your_username"; // Сіздің MySQL логин
        $db_password = "your_password"; // Сіздің MySQL паролі
        $dbname = "photo_sharing"; // Базаның аты

        $conn = new mysqli('localhost', 'your_username', 'your_password', 'photo_sharing');
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Парольді шифрлау

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id; // Жаңа пайдаланушы идентификаторын сессияға жазыңыз
            header("Location: profile.php"); // Профиль бетіне жіберу
            exit();
        } else {
            echo "Тіркелу сәтсіз болды: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>