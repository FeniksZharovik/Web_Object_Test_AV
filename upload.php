<?php
include 'config.php';

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === 0) {
    $imageData = file_get_contents($_FILES['profilePicture']['tmp_name']);

    // Check if a profile picture already exists
    $query = "SELECT * FROM users WHERE id = 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Update existing profile picture
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = 1");
    } else {
        // Insert new profile picture
        $stmt = $conn->prepare("INSERT INTO users (id, profile_picture) VALUES (1, ?)");
    }

    $stmt->bind_param("s", $imageData);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: index.php");
exit();
?>
