<?php
include 'db.php';

$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="profile-pic">
                <?php if ($row['profile_pic']): ?>
                    <img src="uploads/<?php echo $row['profile_pic']; ?>" alt="Profile Picture">
                <?php else: ?>
                    <i class="fas fa-user-circle"></i>
                <?php endif; ?>
                <a href="update.php?id=<?php echo $row['id']; ?>" class="edit-icon">
                    <i class="fas fa-camera"></i>
                </a>
            </div>
            <p>Foto ini akan muncul dalam profil anda, ayo pasang profile terbaikmu!</p>
            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn">Ganti Foto</a>
        <?php endwhile; ?>
    </div>
</body>
</html>
