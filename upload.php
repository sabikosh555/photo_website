<?php
// Деректер базасына қосылу
$conn = new mysqli('localhost', 'root', '', 'photo_sharing');

// Қосылымды тексеру
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    $description = "Description for the image"; // Опционалды

    // Суретті сақтау
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Деректер базасына ақпаратты сақтау
        $sql = "INSERT INTO images (user_id, image_path, description) VALUES ('$user_id', '$target', '$description')";
        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>