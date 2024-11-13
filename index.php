<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query('SELECT id, judul, paragraf FROM artikel');
$artikels = $stmt->fetchAll();

function getFirstImage($htmlContent) {
    $doc = new DOMDocument();
    @$doc->loadHTML($htmlContent);
    $tags = $doc->getElementsByTagName('img');
    if ($tags->length > 0) {
        return $tags->item(0)->getAttribute('src');
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Artikel</title>
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
        .cover-image {
            max-width: 100px;
            max-height: 75px;
            width: auto;
            height: auto;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 15px;
        }
        .logout {
            float: right;
        }
    </style>
</head>
<body>
    <h1>Daftar Artikel <a href="logout.php" class="logout">Logout</a></h1>
    <a href="buat_artikel.php">Buat Artikel Baru</a>
    <ul>
        <?php foreach ($artikels as $artikel): ?>
            <li>
                <a href="lihat_artikel.php?id=<?= $artikel['id'] ?>">
                    <?= htmlspecialchars($artikel['judul']) ?>
                </a>
                <?php $coverImage = getFirstImage($artikel['paragraf']); ?>
                <?php if ($coverImage): ?>
                    <img src="<?= $coverImage ?>" alt="Cover Image" class="cover-image">
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>