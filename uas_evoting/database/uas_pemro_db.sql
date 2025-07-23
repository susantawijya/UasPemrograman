-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 03:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uas_pemro_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `kandidat`
--

CREATE TABLE `kandidat` (
  `id_kandidat` int(11) NOT NULL,
  `nomor_urut` int(5) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `fakultas` varchar(100) NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kandidat`
--

INSERT INTO `kandidat` (`id_kandidat`, `nomor_urut`, `nama_lengkap`, `nim`, `fakultas`, `program_studi`, `foto`, `visi`, `misi`) VALUES
(1, 1, 'Susanta Wijaya', '322410009', 'Fakultas Teknologi dan Design', 'Sistem Informasi', '687f5a33d27cd.png', 'Mewujudkan kelas yang solid, nyaman, dan penuh semangat, tempat di mana belajar dan bahagia berjalan berdampingan, tanpa tekanan berlebihan.', '1. Menyampaikan aspirasi teman-teman, termasuk soal tugas yang terlalu banyak.\r\n2. Menciptakan suasana kelas yang santai tapi tetap fokus saat belajar.\r\n3. Mengadakan kegiatan kelas yang seru dan mempererat kebersamaan.\r\n4. Menjadi ketua yang bisa diajak diskusi, bukan cuma ngatur-ngatur.\r\n5. Menjaga kelas tetap rapi, teratur, dan penuh tawa.'),
(2, 2, 'Gede Susanta', '322410004', 'Fakultas Teknologi dan Design', 'Sistem Informasi', '687f5a60d9930.png', 'Foya', 'Foya');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('voter','admin') NOT NULL,
  `status_memilih` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `role`, `status_memilih`) VALUES
(1, 'adminlogin', 'admin123', 'admin', 0),
(2, '322410001', '240781', 'voter', 1),
(3, '322410006', '162089', 'voter', 1),
(4, '322410002', '568029', 'voter', 1),
(5, '322410003', '270568', 'voter', 1),
(6, '322410007', '069214', 'voter', 1),
(7, '322410008', '079416', 'voter', 1),
(8, '322410009', '026857', 'voter', 1),
(9, '322410010', '436728', 'voter', 1),
(10, '322410011', '150739', 'voter', 1),
(11, '322410012', '214598', 'voter', 1),
(12, '322410013', '182349', 'voter', 1),
(13, '322410014', '418603', 'voter', 1),
(14, '322410015', '681923', 'voter', 1),
(15, '322410016', '932086', 'voter', 1),
(16, '322410017', '235908', 'voter', 1),
(17, '322410018', '296854', 'voter', 1),
(18, '322410019', '179380', 'voter', 1),
(19, '322410020', '325649', 'voter', 1),
(20, '322410021', '284736', 'voter', 1),
(21, '322410022', '359486', 'voter', 0);

-- --------------------------------------------------------

--
-- Table structure for table `suara`
--

CREATE TABLE `suara` (
  `id_suara` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `id_kandidat` int(11) NOT NULL,
  `waktu_memilih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suara`
--

INSERT INTO `suara` (`id_suara`, `id_pengguna`, `id_kandidat`, `waktu_memilih`) VALUES
(1, 2, 1, '2025-07-20 18:19:46'),
(2, 3, 1, '2025-07-20 18:25:18'),
(3, 4, 1, '2025-07-20 18:35:52'),
(4, 5, 1, '2025-07-21 03:52:57'),
(5, 6, 1, '2025-07-21 03:58:33'),
(6, 12, 1, '2025-07-21 07:35:07'),
(7, 7, 1, '2025-07-22 07:05:20'),
(8, 8, 1, '2025-07-22 07:21:56'),
(9, 9, 1, '2025-07-22 07:38:58'),
(10, 10, 1, '2025-07-22 09:33:32'),
(11, 11, 1, '2025-07-22 09:46:32'),
(12, 13, 1, '2025-07-22 09:50:48'),
(13, 14, 1, '2025-07-22 10:00:30'),
(14, 15, 1, '2025-07-22 14:33:13'),
(15, 16, 1, '2025-07-22 14:43:10'),
(19, 17, 2, '2025-07-23 01:13:10'),
(20, 18, 1, '2025-07-23 01:58:57'),
(21, 19, 1, '2025-07-23 06:14:14'),
(22, 20, 1, '2025-07-23 08:11:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kandidat`
--
ALTER TABLE `kandidat`
  ADD PRIMARY KEY (`id_kandidat`),
  ADD UNIQUE KEY `nomor_urut` (`nomor_urut`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `suara`
--
ALTER TABLE `suara`
  ADD PRIMARY KEY (`id_suara`),
  ADD UNIQUE KEY `id_pengguna_unique` (`id_pengguna`),
  ADD KEY `fk_suara_kandidat` (`id_kandidat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kandidat`
--
ALTER TABLE `kandidat`
  MODIFY `id_kandidat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `suara`
--
ALTER TABLE `suara`
  MODIFY `id_suara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `suara`
--
ALTER TABLE `suara`
  ADD CONSTRAINT `fk_suara_kandidat` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`),
  ADD CONSTRAINT `fk_suara_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
