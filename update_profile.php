<?php
// Дерекқорға қосылу
$servername = "localhost";
$username = "your_username"; // MySQL пайдаланушы аты
$password = "your_password"; // MySQL паролі
$dbname = "photo_sharing";

// Дерекқорға қосылу
$conn = new mysqli($servername, $username, $password, $dbname);

// Қосылу тексеру
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Формадан деректерді алу
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_id = $_SESSION['user_id']; // Сессиядан пайдаланушы идентификаторын алу

    // Деректерді жаңарту сұрауы
    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $user_id);

    if ($stmt->execute()) {
        echo "Профиль сәтті жаңартылды.";
    } else {
        echo "Қате: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
