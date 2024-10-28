<?php
include 'db.php';

$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profilePic = $_FILES['profile_pic']['name'];
    $targetDir = "uploads/";

    if ($profilePic) {
        $targetFile = $targetDir . basename($profilePic);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile);
        $query = "UPDATE users SET profile_pic = '$profilePic' WHERE id = $id";
        $conn->query($query);
    }

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="profile-container">
        <h2>Update Profile Picture</h2>
        <div class="profile-pic">
            <img id="profilePreview" src="uploads/<?php echo $user['profile_pic'] ?: 'default-profile.png'; ?>" alt="Profile Picture">
            <a class="edit-icon">
                <i class="fas fa-camera"></i>
            </a>
        </div>
        <form action="update.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display: none;" required>
            <button type="submit" class="btn">Ganti Foto</button>
        </form>
    </div>

    <script>
        document.querySelector('.edit-icon').addEventListener('click', function() {
            document.getElementById('profilePicInput').click();
        });

        document.getElementById('profilePicInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
