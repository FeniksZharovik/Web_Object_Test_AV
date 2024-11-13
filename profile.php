<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil artikel yang di-bookmark
$stmt = $pdo->prepare('SELECT a.id, a.judul FROM bookmark b JOIN artikel a ON b.artikel_id = a.id WHERE b.user_id = ?');
$stmt->execute([$userId]);
$bookmarkedArticles = $stmt->fetchAll();

// Ambil artikel yang disukai
$stmt = $pdo->prepare('SELECT a.id, a.judul FROM reaksi r JOIN artikel a ON r.artikel_id = a.id WHERE r.user_id = ? AND r.jenis_reaksi = "like"');
$stmt->execute([$userId]);
$likedArticles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 15px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Profil</h1>
    <h2>Artikel yang Di-bookmark</h2>
    <ul>
        <?php foreach ($bookmarkedArticles as $artikel): ?>
            <li>
                <a href="lihat_artikel.php?id=<?= $artikel['id'] ?>">
                    <?= htmlspecialchars($artikel['judul']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Artikel yang Disukai</h2>
    <ul>
        <?php foreach ($likedArticles as $artikel): ?>
            <li>
                <a href="lihat_artikel.php?id=<?= $artikel['id'] ?>">
                    <?= htmlspecialchars($artikel['judul']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="index.php" class="back-button">Kembali</a>
</body>
</html>