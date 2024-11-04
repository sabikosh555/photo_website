<?php
// Деректер базасына қосылу
$conn = new mysqli('localhost', 'root', '', 'photo_sharing');

// Қосылымды тексеру
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Пайдаланушы идентификаторы (сессия арқылы) алу
session_start();
$user_id = $_SESSION['user_id'];

// Суреттерді алу
$sql = "SELECT * FROM images WHERE user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">User Profile</div>

    <h2>Upload Image</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Your Images</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="<?php echo $row['image_path']; ?>" alt="User Image" style="width:100%; border-radius: 10px;">
                <p><?php echo $row['description']; ?></p>
                <button class="button">❤️ Like</button>
                <button class="button">💬 Comment</button>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No images uploaded yet.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>