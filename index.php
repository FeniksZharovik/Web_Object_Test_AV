<?php
include 'db.php';

// Ambil data dari database
$stmt = $pdo->query("SELECT * FROM users WHERE id = 1");
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Profile Picture</title>
</head>
<body>
    <div class="profile-container">
        <div id="image-preview" class="profile-icon">
            <?php if ($user && $user['profile_picture']): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_picture']) ?>" alt="Profile Picture" class="circle-img">
            <?php else: ?>
                <i class="fas fa-user-circle fa-5x" id="profile-icon"></i>
            <?php endif; ?>
        </div>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_picture" id="file-input" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <script>
        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('image-preview');
                    previewContainer.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="circle-img">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>