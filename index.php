<?php
require 'config.php';

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
        .cover-image {
            max-width: 100px; /* Ukuran cover image */
            max-height: 75px;
            width: auto;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Daftar Artikel</h1>
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