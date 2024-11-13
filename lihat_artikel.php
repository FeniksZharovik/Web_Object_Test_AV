<?php
require 'config.php';
session_start();

$id = $_GET['id'];
$userId = $_SESSION['user_id']; // Pastikan pengguna sudah login

$stmt = $pdo->prepare('SELECT judul, paragraf FROM artikel WHERE id = ?');
$stmt->execute([$id]);
$artikel = $stmt->fetch();

if (!$artikel) {
    echo "Artikel tidak ditemukan!";
    exit;
}

// Ambil tag yang terkait dengan artikel
$stmt = $pdo->prepare('SELECT nama FROM tag WHERE artikel_id = ?');
$stmt->execute([$id]);
$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Ambil jumlah reaksi
$stmt = $pdo->prepare('SELECT jenis_reaksi, COUNT(*) as jumlah FROM reaksi WHERE artikel_id = ? GROUP BY jenis_reaksi');
$stmt->execute([$id]);
$reaksi = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Cek status bookmark
$stmt = $pdo->prepare('SELECT COUNT(*) FROM bookmark WHERE user_id = ? AND artikel_id = ?');
$stmt->execute([$userId, $id]);
$isBookmarked = $stmt->fetchColumn() > 0;

// Fungsi untuk menambah reaksi
function tambahReaksi($pdo, $userId, $artikelId, $jenisReaksi) {
    // Cek apakah reaksi sudah ada
    $stmt = $pdo->prepare('SELECT jenis_reaksi FROM reaksi WHERE user_id = ? AND artikel_id = ?');
    $stmt->execute([$userId, $artikelId]);
    $existingReaksi = $stmt->fetchColumn();

    if ($existingReaksi) {
        if ($existingReaksi !== $jenisReaksi) {
            // Update reaksi jika berbeda
            $stmt = $pdo->prepare('UPDATE reaksi SET jenis_reaksi = ? WHERE user_id = ? AND artikel_id = ?');
            $stmt->execute([$jenisReaksi, $userId, $artikelId]);
        }
    } else {
        // Tambahkan reaksi baru
        $stmt = $pdo->prepare('INSERT INTO reaksi (user_id, artikel_id, jenis_reaksi) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $artikelId, $jenisReaksi]);
    }
}

// Fungsi untuk menambah atau menghapus bookmark
function toggleBookmark($pdo, $userId, $artikelId, $isBookmarked) {
    if ($isBookmarked) {
        $stmt = $pdo->prepare('DELETE FROM bookmark WHERE user_id = ? AND artikel_id = ?');
        $stmt->execute([$userId, $artikelId]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO bookmark (user_id, artikel_id) VALUES (?, ?)');
        $stmt->execute([$userId, $artikelId]);
    }
}

// Tangani aksi dari tombol
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['like'])) {
        tambahReaksi($pdo, $userId, $id, 'like');
    } elseif (isset($_POST['dislike'])) {
        tambahReaksi($pdo, $userId, $id, 'dislike');
    } elseif (isset($_POST['bookmark'])) {
        toggleBookmark($pdo, $userId, $id, $isBookmarked);
    }
    header("Location: lihat_artikel.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($artikel['judul']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .ql-editor img {
            max-width: 600px;
            max-height: 450px;
            width: auto;
            height: auto;
        }
        .tag {
            display: inline-block;
            background-color: #e0e0e0;
            color: #333;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 15px;
            font-size: 14px;
        }
        .button-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            margin-right: 10px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
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
        .icon {
            margin-right: 5px;
        }
        .bookmarked {
            color: black;
        }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($artikel['judul']) ?></h1>
    <div class="ql-editor"><?= $artikel['paragraf'] ?></div>
    <form method="post" class="button-container">
        <button type="submit" name="like" class="button">
            <i class="fas fa-thumbs-up icon"></i> Like (<?= $reaksi['like'] ?? 0 ?>)
        </button>
        <button type="submit" name="dislike" class="button">
            <i class="fas fa-thumbs-down icon"></i> Dislike (<?= $reaksi['dislike'] ?? 0 ?>)
        </button>
        <button type="submit" name="bookmark" class="button">
            <i class="fas fa-bookmark icon <?= $isBookmarked ? 'bookmarked' : '' ?>"></i> Bookmark
        </button>
        <a href="#" class="button">
            <i class="fas fa-share icon"></i> Share
        </a>
    </form>
    <h3>Tags:</h3>
    <div>
        <?php foreach ($tags as $tag): ?>
            <span class="tag"><?= htmlspecialchars($tag) ?></span>
        <?php endforeach; ?>
    </div>
    <a href="index.php" class="back-button">Kembali</a>
</body>
</html>