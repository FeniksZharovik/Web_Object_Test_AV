<?php
require 'config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$userId = $_SESSION['user_id'];

// Ambil artikel
$stmt = $pdo->prepare('SELECT judul, paragraf FROM artikel WHERE id = ?');
$stmt->execute([$id]);
$artikel = $stmt->fetch();

if (!$artikel) {
    echo "Artikel tidak ditemukan!";
    exit;
}

// Ambil tag
$stmt = $pdo->prepare('SELECT nama FROM tag WHERE artikel_id = ?');
$stmt->execute([$id]);
$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Ambil reaksi
$stmt = $pdo->prepare('SELECT jenis_reaksi, COUNT(*) as jumlah FROM reaksi WHERE artikel_id = ? GROUP BY jenis_reaksi');
$stmt->execute([$id]);
$reaksi = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Cek bookmark
$stmt = $pdo->prepare('SELECT COUNT(*) FROM bookmark WHERE user_id = ? AND artikel_id = ?');
$stmt->execute([$userId, $id]);
$isBookmarked = $stmt->fetchColumn() > 0;

// Ambil komentar
$stmt = $pdo->prepare('SELECT k.isi, k.tanggal, u.username FROM komentar k JOIN user u ON k.user_id = u.id WHERE k.artikel_id = ? ORDER BY k.tanggal DESC');
$stmt->execute([$id]);
$komentar = $stmt->fetchAll();

// Fungsi untuk menambah reaksi
function toggleReaksi($pdo, $userId, $artikelId, $jenisReaksi) {
    $stmt = $pdo->prepare('SELECT jenis_reaksi FROM reaksi WHERE user_id = ? AND artikel_id = ?');
    $stmt->execute([$userId, $artikelId]);
    $existingReaksi = $stmt->fetchColumn();

    if ($existingReaksi === $jenisReaksi) {
        $stmt = $pdo->prepare('DELETE FROM reaksi WHERE user_id = ? AND artikel_id = ?');
        $stmt->execute([$userId, $artikelId]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO reaksi (user_id, artikel_id, jenis_reaksi) VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE jenis_reaksi = VALUES(jenis_reaksi)');
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
        toggleReaksi($pdo, $userId, $id, 'like');
    } elseif (isset($_POST['dislike'])) {
        toggleReaksi($pdo, $userId, $id, 'dislike');
    } elseif (isset($_POST['bookmark'])) {
        toggleBookmark($pdo, $userId, $id, $isBookmarked);
    } elseif (isset($_POST['komentar'])) {
        $isi = $_POST['isi'];
        $stmt = $pdo->prepare('INSERT INTO komentar (artikel_id, user_id, isi) VALUES (?, ?, ?)');
        $stmt->execute([$id, $userId, $isi]);
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
            display: flex;
        }
        .button {
            flex: 0 0 120px;
            margin: 0 2px;
            padding: 8px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
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
        .komentar-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .komentar {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .komentar:last-child {
            border-bottom: none;
        }
        .no-komentar {
            text-align: center;
            color: #888;
        }
        form {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .read-more {
            color: #007BFF;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
    <script>
        function shareArticle() {
            const url = window.location.href;
            if (navigator.share) {
                navigator.share({
                    title: 'Bagikan Artikel',
                    url: url
                }).then(() => {
                    console.log('Thanks for sharing!');
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link artikel telah disalin ke clipboard!');
                }).catch((err) => {
                    console.error('Could not copy text: ', err);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const komentarIsiElements = document.querySelectorAll('.komentar-isi');
            const maxLength = 100; // Panjang maksimum sebelum "Baca Selengkapnya"

            komentarIsiElements.forEach(isiElement => {
                const fullText = isiElement.textContent;
                if (fullText.length > maxLength) {
                    const truncatedText = fullText.substring(0, maxLength) + '... ';
                    const readMoreLink = document.createElement('span');
                    readMoreLink.textContent = 'Baca Selengkapnya';
                    readMoreLink.classList.add('read-more');
                    
                    let isExpanded = false;

                    readMoreLink.onclick = function() {
                        if (isExpanded) {
                            isiElement.textContent = truncatedText;
                            isiElement.appendChild(readMoreLink);
                            readMoreLink.textContent = 'Baca Selengkapnya';
                        } else {
                            isiElement.textContent = fullText;
                            isiElement.appendChild(readMoreLink);
                            readMoreLink.textContent = 'Sembunyikan';
                        }
                        isExpanded = !isExpanded;
                    };

                    isiElement.textContent = truncatedText;
                    isiElement.appendChild(readMoreLink);
                }
            });
        });
    </script>
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
        <button type="button" class="button" onclick="shareArticle()">
            <i class="fas fa-share icon"></i> Share
        </button>
    </form>
    <h3>Tags:</h3>
    <div>
        <?php foreach ($tags as $tag): ?>
            <span class="tag"><?= htmlspecialchars($tag) ?></span>
        <?php endforeach; ?>
    </div>
    <h3>Komentar (<?= count($komentar) ?>)</h3>
    <form method="post">
        <input type="text" name="isi" placeholder="Tulis komentarmu disini" required>
        <button type="submit" name="komentar"><i class="fas fa-paper-plane"></i></button>
    </form>
    <div class="komentar-container">
        <?php if (empty($komentar)): ?>
            <div class="no-komentar">
                <p>Belum ada komentar. Jadilah yang pertama untuk memberikan komentar!</p>
            </div>
        <?php else: ?>
            <?php foreach ($komentar as $k): ?>
                <div class="komentar">
                    <strong><?= htmlspecialchars($k['username']) ?></strong> (<?= $k['tanggal'] ?>)
                    <p class="komentar-isi"><?= htmlspecialchars($k['isi']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <a href="index.php" class="back-button">Kembali</a>
</body>
</html>