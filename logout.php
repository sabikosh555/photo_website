<?php
session_start();
session_destroy(); // Сессияны жою
header("Location: login.php"); // Логин бетіне жіберу
exit();
?>