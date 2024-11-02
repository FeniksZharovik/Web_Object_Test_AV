<?php
// index.php
include 'config.php';

try {
    $stmt = $pdo->query("SELECT email, username, kredensial, nama_lengkap, profile_pic FROM user");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Daftar Pengguna</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Username</th>
                <th>Kredensial</th>
                <th>Nama Lengkap</th>
                <th>Foto Profil</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($user['username'] ?? '') ?></td>
                    <td><?= htmlspecialchars($user['kredensial'] ?? '') ?></td>
                    <td><?= htmlspecialchars($user['nama_lengkap'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($user['profile_pic'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_pic']) ?>" alt="Foto Profil" width="50">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>