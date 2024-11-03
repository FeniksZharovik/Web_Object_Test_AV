<?php
require_once 'config.php';

$id_kelas = $_GET['id'];
$query = "DELETE FROM kelas WHERE id_kelas = '$id_kelas'";

if (mysqli_query($connection, $query)) {
    echo "Data berhasil dihapus.";
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_error($connection);
}
?>