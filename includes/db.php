<?php
// Деректер қорына қосылу
$host = 'localhost';
$dbname = 'joba'; // Сіздің деректер қорыңыздың аты
$username = 'root'; // XAMPP/MAMP қолдансаңыз, әдетте 'root'
$password = 'root'; // Пароль бос болуы мүмкін

try {
    $conn = new PDO("mysql:host=localhost;dbname=joba;charset=utf8", "root", "root");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Дерекқорға қосылу мүмкін болмады: " . $e->getMessage());
}

?>
