<?php
// view_article.php
include 'db.php'; // Pastikan untuk menyertakan file koneksi database

// Ambil ID artikel dari parameter GET
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($articleId > 0) {
    // Ambil artikel berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$articleId]);
    $article = $stmt->fetch();

    // Jika artikel tidak ditemukan
    if (!$article) {
        die("Artikel tidak ditemukan.");
    }

    // Ambil gambar yang terkait dengan artikel dan urutkan berdasarkan sort_order
    $stmt = $pdo->prepare("SELECT image, sort_order FROM images WHERE article_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$articleId]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gabungkan teks dan gambar berdasarkan urutan
    $content = $article['content'];
    $contentWithImages = '';
    $imageIndex = 0;

    // Proses konten dan gambar
    preg_match_all('/<img src="([^"]*)"/', $content, $matches, PREG_OFFSET_CAPTURE);
    $offset = 0;

    foreach ($matches[0] as $match) {
        $position = $match[1];
        $contentWithImages .= substr($content, $offset, $position - $offset);
        $offset = $position + strlen($match[0]);

        if (isset($images[$imageIndex])) {
            $contentWithImages .= '<img src="data:image/jpeg;base64,' . base64_encode($images[$imageIndex]['image']) . '" alt="Image" />';
            $imageIndex++;
        }
    }
    $contentWithImages .= substr($content, $offset);

    // Bersihkan konten dari karakter yang tidak diinginkan
    $contentWithImages = htmlspecialchars_decode($contentWithImages);

    // Ganti elemen <blockquote> dengan <div> untuk menonaktifkan penanda ">"
    $contentWithImages = str_replace('<blockquote>', '<div>', $contentWithImages);
    $contentWithImages = str_replace('</blockquote>', '</div>', $contentWithImages);

} else {
    die("ID artikel tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <link href="css/style.css" rel="stylesheet"> <!-- Link ke file CSS -->
</head>
<body>
    <div class="article-content">
        <h2><?php echo htmlspecialchars($article['title']); ?></h2>
        <div><?php echo $contentWithImages; ?></div>
    </div>
    
    <a href="index.php">Kembali ke Daftar Artikel</a>
</body>
</html>