<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $image = file_get_contents($_FILES['profile_picture']['tmp_name']);

    // Cek apakah data sudah ada
    $stmt = $pdo->query("SELECT * FROM users WHERE id = 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update jika sudah ada
        $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = 1");
    } else {
        // Insert jika belum ada
        $stmt = $pdo->prepare("INSERT INTO users (profile_picture) VALUES (?)");
    }

    $stmt->execute([$image]);
}

header('Location: index.php');
exit();
?>