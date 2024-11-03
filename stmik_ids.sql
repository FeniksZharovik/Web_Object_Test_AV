-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2024 at 04:40 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stmik_ids`
--

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `kode_jurusan` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`, `kode_jurusan`) VALUES
(1, 'Teknik Informatika', 'TI'),
(2, 'Sistem Informasi', 'SI'),
(3, 'Manajemen Informatika', 'MI'),
(4, 'Teknik Informatika', 'TI'),
(5, 'Sistem Informasi', 'SI'),
(6, 'Manajemen Informatika', 'MI'),
(7, 'Teknik Komputer', 'TK'),
(8, 'Teknik Elektro', 'TE'),
(9, 'Teknik Informatika', 'TI'),
(10, 'Sistem Informasi', 'SI'),
(11, 'Manajemen Informatika', 'MI'),
(12, 'Teknik Komputer', 'TK'),
(13, 'Teknik Elektro', 'TE');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` char(5) NOT NULL,
  `kode_kelas` enum('A','B','C','D','E','F','G') NOT NULL,
  `tingkat_kelas` enum('X','XI','XII') NOT NULL,
  `id_jurusan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `kode_kelas`, `tingkat_kelas`, `id_jurusan`) VALUES
('K001', 'A', 'X', 8),
('K002', 'B', 'XI', 2),
('K003', 'C', 'XII', 3),
('K004', 'D', 'X', 4),
('K005', 'E', 'XI', 5),
('K006', 'F', 'XII', 1),
('K007', 'G', 'X', 2),
('K008', 'A', 'XI', 3),
('K009', 'B', 'XII', 4),
('K010', 'C', 'X', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
