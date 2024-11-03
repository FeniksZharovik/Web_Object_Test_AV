<?php
// index.php
include 'config.php';

try {
    $stmt = $pdo->query("SELECT email, nama_pengguna, kredensial, nama_lengkap, profile_pic FROM user");
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
    <script>
        function enableEdit(rowId) {
            const row = document.getElementById(rowId);
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => input.removeAttribute('readonly'));
            row.querySelector('.edit-btn').style.display = 'none';
            row.querySelector('.save-btn').style.display = 'inline';
        }
        function saveEdit(rowId) {
            const row = document.getElementById(rowId);
            const inputs = row.querySelectorAll('input');
            const data = {};
            inputs.forEach(input => {
                data[input.name] = input.value;
                input.setAttribute('readonly', true);
            });
            fetch('update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Data berhasil diperbarui');
                } else {
                    alert('Gagal memperbarui data: ' + data.message);
                }
            });
            row.querySelector('.edit-btn').style.display = 'inline';
            row.querySelector('.save-btn').style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Daftar Pengguna</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Nama Pengguna</th>
                <th>Kredensial</th>
                <th>Nama Lengkap</th>
                <th>Foto Profil</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr id="row-<?= htmlspecialchars($user['email']) ?>">
                    <td><input type="text" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly></td>
                    <td><input type="text" name="nama_pengguna" value="<?= htmlspecialchars($user['nama_pengguna'] ?? '') ?>" readonly></td>
                    <td><input type="text" name="kredensial" value="<?= htmlspecialchars($user['kredensial'] ?? '') ?>" readonly></td>
                    <td><input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap'] ?? '') ?>" readonly></td>
                    <td>
                        <?php if (!empty($user['profile_pic'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_pic']) ?>" alt="Foto Profil" width="50">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="edit-btn" onclick="enableEdit('row-<?= htmlspecialchars($user['email']) ?>')">Edit</button>
                        <button class="save-btn" onclick="saveEdit('row-<?= htmlspecialchars($user['email']) ?>')" style="display:none;">Save</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
