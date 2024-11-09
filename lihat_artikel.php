<?php
require 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT judul, paragraf FROM artikel WHERE id = ?');
$stmt->execute([$id]);
$artikel = $stmt->fetch();

if (!$artikel) {
    echo "Artikel tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($artikel['judul']) ?></title>
    <style>
        .ql-editor img {
            max-width: 600px; /* Membatasi lebar gambar lebih besar */
            max-height: 450px; /* Membatasi tinggi gambar lebih besar */
            width: auto;
            height: auto;
        }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
    <div class="ql-editor"><?= $artikel['paragraf'] ?></div>
</body>
</html>