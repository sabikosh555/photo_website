<?php
// Деректер қорына қосылу
$host = 'localhost';
$dbname = 'joba'; // Сіздің деректер қорыңыздың аты
$username = 'root'; // XAMPP/MAMP қолдансаңыз, әдетте 'root'
$password = 'root'; // Пароль бос болуы мүмкін

try {
    // Дерекқор қосылымын орнату
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Қосылымның дұрыс орнатылғанын тексеру
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Қосылым сәтті болған жағдайда хабарлама
    echo "Дерекқорға қосылу сәтті!";
} catch (PDOException $e) {
    // Қате болған жағдайда хабарлама шығару
    die("Дерекқорға қосылу мүмкін болмады: " . $e->getMessage());
}
?>
