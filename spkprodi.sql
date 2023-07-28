-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20230703.475871160d
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 28, 2023 at 05:34 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spkprodi`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_dmu`
--

CREATE TABLE `detail_dmu` (
  `id_detail_dmu` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `id_variabel` int(11) NOT NULL,
  `nilai_variabel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `detail_dmu`
--

INSERT INTO `detail_dmu` (`id_detail_dmu`, `id_prodi`, `id_variabel`, `nilai_variabel`) VALUES
(167, 1, 20, 32),
(168, 1, 21, 7),
(169, 1, 22, 30),
(170, 1, 23, 9),
(179, 2, 20, 44),
(180, 2, 21, 8),
(181, 2, 22, 34),
(182, 2, 23, 8),
(183, 3, 20, 20),
(184, 3, 21, 5),
(185, 3, 22, 35),
(186, 3, 23, 7);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama` varchar(50) COLLATE utf8_bin NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(50) COLLATE utf8_bin NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `level` char(1) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama`, `username`, `password`, `id_prodi`, `level`) VALUES
(11, 'Aldian Umbu Tamu Ama, S.Si., M.Cs.', 'umbu', '7e8c918aba66629d9ac84d99fd56b745', 2, 'c'),
(22, 'Ignatius Bias Galih Prasadhya, S.Pd., M.M', 'bias', '1603f79f250bd05d84dcb190bee408bc', 2, 'm'),
(24, 'Danis Putra Perdana, S.Kom., M.M.', 'danis', '257ffa69697c2d5144f0b4b76b51ae95', 3, 'c'),
(25, 'Rivort Pormes, S.Kom., M.Kom.', 'rivort', '52a7e71c34ede309a39fec3778532685', 3, 'm'),
(26, 'Astriyer Jadmiko Nahumury', 'jadmiko', 'dbab21bc3d9f411a9fd3622ad4b7acc6', 1, 'c'),
(27, 'Big Greogory Kaitelapatay, S.Kom., M.Kom.', 'greogory', '5001ecff61744c3de2fecf9faa32c9cf', 1, 'm');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_khusus`
--

CREATE TABLE `pengguna_khusus` (
  `id_pengguna_khusus` int(11) NOT NULL,
  `nama` varchar(50) COLLATE utf8_bin NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(50) COLLATE utf8_bin NOT NULL,
  `level` char(1) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pengguna_khusus`
--

INSERT INTO `pengguna_khusus` (`id_pengguna_khusus`, `nama`, `username`, `password`, `level`) VALUES
(1, 'Ir. Bambang Pranoto, M.B.A', 'bambang', 'a9711cbb2e3c2d5fc97a63e45bbe5076', 'p'),
(2, 'Aji Nur Prasetyo', 'superadmin', '17c4520f6cfd1ab53d8745e84681eb49', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `perhitungan_efisiensi`
--

CREATE TABLE `perhitungan_efisiensi` (
  `id_perhitungan_efisiensi` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `id_variabel` int(11) NOT NULL,
  `nilai_efisiensi` double NOT NULL,
  `rekomendasi` int(11) NOT NULL,
  `nilai_awal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `perhitungan_efisiensi`
--

INSERT INTO `perhitungan_efisiensi` (`id_perhitungan_efisiensi`, `id_prodi`, `id_variabel`, `nilai_efisiensi`, `rekomendasi`, `nilai_awal`) VALUES
(136, 1, 20, 0.91836734693878, 26, 32),
(137, 1, 21, 0.91836734693878, 6, 7),
(138, 2, 20, 0.71428571428571, 23, 44),
(139, 2, 21, 0.71428571428571, 6, 8),
(140, 3, 20, 1, 20, 20),
(141, 3, 21, 1, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` int(11) NOT NULL,
  `cabang_prodi` varchar(50) COLLATE utf8_bin NOT NULL,
  `alamat` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id_prodi`, `cabang_prodi`, `alamat`) VALUES
(1, 'Teknik Rekayasa Multimedia', 'Jl. Setiabudi No. 55'),
(2, 'Bisnis Digital', 'Jl. Abdulrahman Saleh Kav. 783'),
(3, 'Rekayasa Keamanan SIber', 'Jl. Kedungmundu Raya, Ruko Grahawahid No. 7');

-- --------------------------------------------------------

--
-- Table structure for table `variabel`
--

CREATE TABLE `variabel` (
  `id_variabel` int(11) NOT NULL,
  `nama_variabel` varchar(50) COLLATE utf8_bin NOT NULL,
  `jenis_variabel` char(1) COLLATE utf8_bin NOT NULL,
  `satuan` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

--
-- Dumping data for table `variabel`
--

INSERT INTO `variabel` (`id_variabel`, `nama_variabel`, `jenis_variabel`, `satuan`) VALUES
(20, 'Jumlah Mahasiswa', 'i', 'orang'),
(21, 'Jumlah Dosen', 'i', 'orang'),
(22, 'Jumlah Penelitian', 'o', 'kali'),
(23, 'Jumlah Pengabdian', 'o', 'kali');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_dmu`
--
ALTER TABLE `detail_dmu`
  ADD PRIMARY KEY (`id_detail_dmu`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `pengguna_khusus`
--
ALTER TABLE `pengguna_khusus`
  ADD PRIMARY KEY (`id_pengguna_khusus`);

--
-- Indexes for table `perhitungan_efisiensi`
--
ALTER TABLE `perhitungan_efisiensi`
  ADD PRIMARY KEY (`id_perhitungan_efisiensi`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`);

--
-- Indexes for table `variabel`
--
ALTER TABLE `variabel`
  ADD PRIMARY KEY (`id_variabel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_dmu`
--
ALTER TABLE `detail_dmu`
  MODIFY `id_detail_dmu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pengguna_khusus`
--
ALTER TABLE `pengguna_khusus`
  MODIFY `id_pengguna_khusus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `perhitungan_efisiensi`
--
ALTER TABLE `perhitungan_efisiensi`
  MODIFY `id_perhitungan_efisiensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `variabel`
--
ALTER TABLE `variabel`
  MODIFY `id_variabel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
