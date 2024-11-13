<?php
$host = 'localhost';
$dbname = 'joba';
$user = 'root';
$pass = ''; // Егер құпия сөз болмаса, бос қалдырыңыз

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
