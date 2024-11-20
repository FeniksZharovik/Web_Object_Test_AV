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
            background-color: #f4f4f9;
        }
        h1 {
            color: #333;
            text-align: center;
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
            border-radius: 5px;
        }
        .article-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .article-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 300px;
            transition: transform 0.2s;
        }
        .article-card:hover {
            transform: translateY(-5px);
        }
        .article-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .logout, .profile {
            float: right;
            margin-right: 10px;
        }
        .create-article {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 4px;
            width: 200px;
        }
        .create-article:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Daftar Artikel 
        <a href="logout.php" class="logout">Logout</a>
        <a href="profile.php" class="profile">Profil</a>
    </h1>
    <a href="buat_artikel.php" class="create-article">Buat Artikel Baru</a>
    <div class="article-list">
        <?php foreach ($artikels as $artikel): ?>
            <div class="article-card">
                <a href="lihat_artikel.php?id=<?= $artikel['id'] ?>" class="article-title">
                    <?= htmlspecialchars($artikel['judul']) ?>
                </a>
                <?php $coverImage = getFirstImage($artikel['paragraf']); ?>
                <?php if ($coverImage): ?>
                    <img src="<?= $coverImage ?>" alt="Cover Image" class="cover-image">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>