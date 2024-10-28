<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $profilePic = $_FILES['profile_pic']['name'];
    $targetDir = "uploads/";

    if ($profilePic) {
        $targetFile = $targetDir . basename($profilePic);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile);
    } else {
        $profilePic = null;
    }

    $query = "INSERT INTO users (name, profile_pic) VALUES ('$name', '$profilePic')";
    $conn->query($query);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Profile</title>
</head>
<body>
    <h1>Upload New Profile</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Enter Name" required>
        <input type="file" name="profile_pic">
        <button type="submit">Upload</button>
    </form>
</body>
</html>