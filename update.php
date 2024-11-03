<?php
// update.php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email'], $data['nama_pengguna'], $data['kredensial'], $data['nama_lengkap'])) {
    try {
        $stmt = $pdo->prepare("UPDATE user SET nama_pengguna = ?, kredensial = ?, nama_lengkap = ? WHERE email = ?");
        $stmt->execute([$data['nama_pengguna'], $data['kredensial'], $data['nama_lengkap'], $data['email']]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
}