<?php
include 'config.php';

// Fetch profile picture from database
$query = "SELECT profile_picture FROM users WHERE id = 1";
$result = $conn->query($query);
$profilePicture = $result->fetch_assoc()['profile_picture'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Edit Profile</title>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <div class="profile-picture">
            <?php if ($profilePicture): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($profilePicture) ?>" id="profileImage">
            <?php else: ?>
                <i class="fas fa-user-circle" id="profileIcon"></i>
            <?php endif; ?>
            <input type="file" id="uploadFile" accept="image/*" style="display: none;">
            <label for="uploadFile" class="upload-button">
                <i class="fas fa-camera"></i>
            </label>
        </div>
        <p>Foto ini akan muncul dalam profil anda, ayo pasang profil terbaikmu!</p>
        <button id="changePhotoButton">Ganti Foto</button>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
