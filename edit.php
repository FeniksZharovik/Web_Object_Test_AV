<?php
require_once 'config.php';

$id_kelas = $_GET['id'];
$query = "SELECT * FROM kelas WHERE id_kelas = '$id_kelas'";
$result = mysqli_query($connection, $query);
$data = mysqli_fetch_array($result);

// Ambil data jurusan untuk dropdown
$jurusanResult = mysqli_query($connection, "SELECT * FROM jurusan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_kelas = $_POST['kode_kelas'];
    $tingkat_kelas = $_POST['tingkat_kelas'];
    $id_jurusan = $_POST['id_jurusan'];

    $updateQuery = "UPDATE kelas SET kode_kelas = '$kode_kelas', tingkat_kelas = '$tingkat_kelas', id_jurusan = '$id_jurusan' WHERE id_kelas = '$id_kelas'";

    if (mysqli_query($connection, $updateQuery)) {
        echo "Data berhasil diupdate.";
        header("Location: index.php");
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<form method="POST" action="">
    <label for="kode_kelas">Kode Kelas:</label>
    <select name="kode_kelas" id="kode_kelas">
        <option value="A" <?= $data['kode_kelas'] == 'A' ? 'selected' : '' ?>>A</option>
        <option value="B" <?= $data['kode_kelas'] == 'B' ? 'selected' : '' ?>>B</option>
        <option value="C" <?= $data['kode_kelas'] == 'C' ? 'selected' : '' ?>>C</option>
        <option value="D" <?= $data['kode_kelas'] == 'D' ? 'selected' : '' ?>>D</option>
        <option value="E" <?= $data['kode_kelas'] == 'E' ? 'selected' : '' ?>>E</option>
        <option value="F" <?= $data['kode_kelas'] == 'F' ? 'selected' : '' ?>>F</option>
        <option value="G" <?= $data['kode_kelas'] == 'G' ? 'selected' : '' ?>>G</option>
    </select>
    
    <label for="tingkat_kelas">Tingkat Kelas:</label>
    <select name="tingkat_kelas" id="tingkat_kelas">
        <option value="X" <?= $data['tingkat_kelas'] == 'X' ? 'selected' : '' ?>>X</option>
        <option value="XI" <?= $data['tingkat_kelas'] == 'XI' ? 'selected' : '' ?>>XI</option>
        <option value="XII" <?= $data['tingkat_kelas'] == 'XII' ? 'selected' : '' ?>>XII</option>
    </select>
    
    <label for="jurusan">Jurusan:</label>
    <select name="id_jurusan" id="jurusan">
        <?php while ($jurusan = mysqli_fetch_array($jurusanResult)): ?>
            <option value="<?= $jurusan['id_jurusan'] ?>" <?= $data['id_jurusan'] == $jurusan['id_jurusan'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($jurusan['nama_jurusan']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <button type="submit">Update</button>
</form>