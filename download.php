<?php
// download.php

// Қажетті параметрлерді тексеру
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = 'uploads/' . $file;

    // Файл бар екенін тексеру
    if (file_exists($filePath)) {
        // Заголовоктар арқылы файлды жіберу
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Файл табылмады.";
    }
} else {
    echo "Файл таңдалмаған.";
}
