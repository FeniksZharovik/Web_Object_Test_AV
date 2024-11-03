<?php
$host = 'localhost';
$dbname = 'stmik_ids';
$username = 'root'; // sesuaikan dengan username database Anda
$password = ''; // sesuaikan dengan password database Anda

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}
?>