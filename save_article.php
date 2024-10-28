<?php
// save_article.php
include 'db.php';

$title = $_POST['title'];
$content = $_POST['content'];

try {
    $pdo->beginTransaction();

    // Simpan artikel ke tabel articles
    $stmt = $pdo->prepare("INSERT INTO articles (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);
    $articleId = $pdo->lastInsertId();

    // Proses gambar yang disisipkan di dalam konten
    preg_match_all('/<img src="([^"]*)"/', $content, $matches, PREG_OFFSET_CAPTURE);
    $offset = 0;
    $sortOrder = 0;

    foreach ($matches[1] as $match) {
        $imagePath = $match[0];
        $position = $match[1];

        if (!empty($imagePath)) {
            // Ambil isi file gambar
            $imageData = file_get_contents($imagePath);

            // Simpan gambar ke tabel images dengan urutan
            $stmt = $pdo->prepare("INSERT INTO images (article_id, image, sort_order) VALUES (?, ?, ?)");
            $stmt->execute([$articleId, $imageData, $sortOrder++]);

            // Hapus tag gambar dari konten
            $content = substr_replace($content, '', $position - $offset, strlen($match[0]));
            $offset += strlen($match[0]);
        }
    }

    // Hapus tag <p> yang kosong
    $content = preg_replace('/<p>\s*<\/p>/', '', $content);

    // Update konten artikel tanpa tag gambar
    $stmt = $pdo->prepare("UPDATE articles SET content = ? WHERE id = ?");
    $stmt->execute([$content, $articleId]);

    $pdo->commit();
    echo "Artikel berhasil disimpan! <a href='view_article.php?id=$articleId'>Lihat Artikel</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    die("Gagal menyimpan artikel: " . $e->getMessage());
}
?>
