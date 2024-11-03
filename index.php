<?php
require_once 'config.php';

// Proses penyimpanan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelas = $_POST['id_kelas'];
    $kode_kelas = $_POST['kode_kelas'];
    $tingkat_kelas = $_POST['tingkat_kelas'];
    $id_jurusan = $_POST['id_jurusan'];

    $query = "INSERT INTO kelas (id_kelas, kode_kelas, tingkat_kelas, id_jurusan) VALUES ('$id_kelas', '$kode_kelas', '$tingkat_kelas', '$id_jurusan')";

    if (mysqli_query($connection, $query)) {
        echo "Data berhasil disimpan.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Ambil data jurusan untuk dropdown
$jurusanResult = mysqli_query($connection, "SELECT * FROM jurusan");

// Ambil data kelas untuk ditampilkan
$result = mysqli_query($connection, "
    SELECT kelas.id_kelas, kelas.tingkat_kelas, kelas.kode_kelas, jurusan.nama_jurusan 
    FROM kelas 
    INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kelas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="section">
        <div class="container mt-5">
            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                <h1>Data Kelas</h1>
                <button class="btn btn-primary" onclick="document.getElementById('formTambah').style.display='block'">Tambah Data</button>
            </div>
            <div id="formTambah" class="mb-4" style="display:none;">
                <form method="POST" action="" class="p-3 border rounded">
                    <div class="form-group">
                        <label for="id_kelas">ID Kelas:</label>
                        <input type="text" name="id_kelas" id="id_kelas" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_kelas">Kode Kelas:</label>
                        <select name="kode_kelas" id="kode_kelas" class="form-control">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                            <option value="G">G</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tingkat_kelas">Tingkat Kelas:</label>
                        <select name="tingkat_kelas" id="tingkat_kelas" class="form-control">
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jurusan">Jurusan:</label>
                        <select name="id_jurusan" id="jurusan" class="form-control">
                            <?php while ($jurusan = mysqli_fetch_array($jurusanResult)): ?>
                                <option value="<?= $jurusan['id_jurusan'] ?>"><?= htmlspecialchars($jurusan['nama_jurusan']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>ID Kelas</th>
                                    <th>Tingkat Kelas</th>
                                    <th>Kode Kelas</th>
                                    <th>Jurusan</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($data = mysqli_fetch_array($result)) {
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($data['id_kelas']) ?></td>
                                        <td><?= htmlspecialchars($data['tingkat_kelas']) ?></td>
                                        <td><?= htmlspecialchars($data['kode_kelas']) ?></td>
                                        <td><?= htmlspecialchars($data['nama_jurusan']) ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $data['id_kelas'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete.php?id=<?= $data['id_kelas'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>