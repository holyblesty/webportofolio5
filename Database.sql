-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 04, 2026 at 02:44 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyek`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Ahmad Hamim Thohari, S.S.T.,M.T.', '115143', '$2y$10$kiSo7dLmtf.BeQfLG3WDWOzz/n03uyyPsniVuNS.aOUoB4aWHX4mC', 'dosen'),
(2, 'Yeni Rokhayati,S.Si.,M.Sc', '112093', '$2y$10$s72R70qW12h9l.AJjzlHl.a3uMEb/3g8R094qg9AFrq8m2YLApZN2', 'dosen'),
(3, 'Hilda Widyastuti, S.T.,M.T.', '102020', '$2y$10$OHrPQDPvqpWTKMRk72nka.fVAzA6cFTwFL/UVSCXfrPrt8OzBoFYC', 'dosen'),
(4, 'Agus Fatulloh,S.T.,M.T', '107051', '$2y$10$BpFncWlLJjjj8Vv4Pj45w.g5rZJzEZPJr.1KPLoKbHpLo1AqRqlea', 'dosen'),
(5, 'Ir. Dwi Ely Kurniawan,S.Pd.,M.Kom', '112094', '$2y$10$UvdKyTByLQDjchIN2Hi5hOrt2yCrji9WQqxZQ9xPsqUN7MdCUqTB2', 'dosen'),
(6, 'Swono Sibagariang,S.Kom.,M.Kom', '119224', '$2y$10$EJj0zIyvyTPWWDYlnZauY.x7QvfQO/YvPyEhg9uUIe.VTu2Cj65iW', 'dosen'),
(7, 'Muhammad Idris,S.Tr.,M.Tr.Kom', '122283', '$2y$10$0gZheoR9WUjtXCIQ0Th0X.iXBxey3ijaqbooKjTXmy7GSLbgcroSG', 'dosen'),
(8, 'Nadya Satya Handayani,S.Kom.,M.Kom', '125351', '$2y$10$EJyWg6wrlpXjuEi.Z2OnneD5POF5ty1HJhPzYppQABDg/Stx6BSBi', 'dosen'),
(9, 'Sri Rahayu,M.Pd', '125359', '$2y$10$.ieOIDlk1UltdMxoLCw.iOxCsx4jLO/UpOfcunmvp29i1NSLc12tK', 'dosen');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Aliya Putri Ramadhani', '3312511002', '$2y$10$Z3H8GUZQ0kdTIPfZeM0gPO/q58.ZFKqO9KAs.6DD.HNaRNIebqqve', 'mahasiswa'),
(2, 'Muhammad Kevin Fadillah', '3312511001', '$2y$10$kaXBE4iNucMaoDpaxWwDue3zQLset40obsrwYSVTbKE67R.fz3JDG', 'mahasiswa'),
(3, 'Muhammad Subhan Fajriansyah', '3312511004', '$2y$10$3vTd4MVZysQgXOsMUH/f/OESlOSh6o8k2ycX20M3qE8yAGxXg6Xk6', 'mahasiswa'),
(4, 'Firnanda Habib Nur Afian', '3312511006', '$2y$10$tMma6ks8zIH3ROlb0Ud7C.smvY9jPy.2pXEYds8IgMB9/UARr4Pli', 'mahasiswa'),
(5, 'Muhammad Yasir', '3312511007', '$2y$10$GxtL7ApZ4s7KTroRJJZXgOJxyE6Iu9AROkxCVNwoCGdfFDBrd8ECK', 'mahasiswa'),
(6, 'Nalita Nurul Izza', '3312511009', '$2y$10$56/FgL2K9DZoFtzWWgFGh.7lzJR8gPFHcZTCUxq5KieUxI15nD0sa', 'mahasiswa'),
(7, 'Andi Lumban Gaol', '3312511008', '$2y$10$APf5jH4FvPxTTdhJ.GOn5.VYzyIpt7jd34s0FCRsT4kygakXMh.sC', 'mahasiswa'),
(8, 'Dody Sinaga', '3312511010', '$2y$10$r2a/HJX557uA7bkxDABz6eZgWmbiJBXag3YV95O67izqFkurStXpO', 'mahasiswa'),
(9, 'Muhammad Mu\'as', '3312511011', '$2y$10$owayk15ET5.oHXn0/QsWBuouspI83gOCzIkLrdF.OqvlU.GHvmuA6', 'mahasiswa'),
(10, 'M. Khairil Chandra', '3312511014', '$2y$10$brld1qukpzUMB082kxEujOGRT8SQRfkiAnbRvVHw6fVEOLysI35KK', 'mahasiswa'),
(11, 'Divani Putri Olivia Hutagaol', '3312511012', '$2y$10$Gila3xt2MPWclKZweC9ObOFRxHzhFpTIgIiUYWODjmRNrwAkB27ne', 'mahasiswa'),
(12, 'Yoga Putra Agusetiawan', '3312511013', '$2y$10$ZUFIMwvv/yZh12knCqwEm.f4QhEJlaC9AItzgGe0zrxzm29s7Q6Xu', 'mahasiswa'),
(13, 'Qoonitah Novia Damayanti', '3312511015', '$2y$10$n8ZUMod0.gA.0ppN/onrhOevBAvHHBUkbVKBqzOAK0W.FZkPbf0GG', 'mahasiswa'),
(14, 'Intan Rahmadani Putri', '3312511022', '$2y$10$UQgVbqieBsEMBWH5AnbXl.PxMr.q7ea8CGpF3aJpZV8t1c/vJ3iO2', 'mahasiswa'),
(15, 'Afdal Rahman Hakim', '3312511019', '$2y$10$tbuILUNgUSnOGOAnsQP7POc1c2kpGX5THdjPhmhs3S4wWshAzWEhS', 'mahasiswa'),
(16, 'Aldi Ernando Firmansyah', '3312511026', '$2y$10$ZgaE0A2SOG4HGH2aZQExJeWoqqAZb9fo23Cuwl1pxP9Lb/9kjdHD2', 'mahasiswa'),
(17, 'Adetyas Fauzia', '3312511023', '$2y$10$ZxM21blJa4RM4QID9c1w0u5R/EV6UKVtSP8FiCIvdJYk/5tHJsd5a', 'mahasiswa'),
(18, 'Lusiana Hotmauli Panggabean', '3312511024', '$2y$10$wechWhYyc68yGz.09pqggutisOpa2b1xPyQ7pVdhRksiDSms.kdPK', 'mahasiswa'),
(19, 'Maria Putri Agustina Tamba', '3312511025', '$2y$10$BCVZ2EnnDZL1Ks8AJ7gsqeVldTV/9kv02pX0F0v3./AEbu0IDT3PS', 'mahasiswa'),
(20, 'Muhammad Restu Putra', '3312511029', '$2y$10$Wh/m2vfVjoTKpq8vCnpgQeHfI8t60ZQz7F//mguZRZ0TpsbDuux2y', 'mahasiswa'),
(21, 'Muhammad Rafif Akmal', '3312511027', '$2y$10$b1njFb325UajLPXUbkd1fOo4AU9XlvlZ1Ihw4XQtPuSeUT2yh4Eq2', 'mahasiswa'),
(22, 'Juni Friskenny Sinaga', '3312511028', '$2y$10$NPoxeuXeTdLfMwyceelxre2BtJ/vu3vFANoeODilx/qemZs6J0MNa', 'mahasiswa'),
(23, 'Dita Indriyani', '3312511030', '$2y$10$9b7HZZNGQCdxCZw3MJuJSOURAIL0KRVA33KRIKPnP7zYhKuXb9BKa', 'mahasiswa'),
(24, 'Muhammad Farhan', '3312511005', '$2y$10$E/WkCQaZF7SAZQ57z6oyZeXTXQVZmilKeQLMFn9C3X1gDqKBL5C9O', 'mahasiswa'),
(25, 'Muhammad Fachri Sulthani\r\n', '3312511020', '$2y$10$iBUlNJ/BZRkgAmugtm71nOEcpFfuzt2VDSCQNB/sSUlslRAkywWrm', 'mahasiswa');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL,
  `id_portofolio` int NOT NULL,
  `id_dosen` int NOT NULL,
  `nilai` int NOT NULL,
  `catatan` text NOT NULL,
  `tanggal_penilaian` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_portofolio`, `id_dosen`, `nilai`, `catatan`, `tanggal_penilaian`) VALUES
(1, 1, 1, 100, 'mantap sekali nak', '2025-12-02 20:28:57'),
(2, 2, 1, 80, 'bagus', '2026-01-02 20:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `portofolio`
--

CREATE TABLE `portofolio` (
  `id_portofolio` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `repo_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `portofolio`
--

INSERT INTO `portofolio` (`id_portofolio`, `id_mahasiswa`, `judul`, `deskripsi`, `gambar`, `repo_link`) VALUES
(1, 1, 'Buku Tamu Tata Usaha', 'Website yang melayani buku tamu tata usaha', '1767357926_bukutu.jpeg', 'http://localhost/WEBSITE%20KAMI/buku-tamu-tata-usaha.html'),
(2, 2, 'Buku Tamu Tata Usaha', 'Website yang melayani buku tamu tata usaha', '1767358012_bukutu.jpeg', 'buku-tamu-tata-usaha.html'),
(3, 3, 'Pengelolaan Rapat', 'Website yang mengelola rapat', '1767357515_WhatsApp Image 2025-12-30 at 10.21.59.jpeg', 'https://github.com/muhamfarhan1999-byte/aplikasi-rapat'),
(4, 24, 'Pengelolaan Rapat', 'Website yang mengelola rapat', '1767357474_WhatsApp Image 2025-12-30 at 10.21.59.jpeg', 'https://github.com/muhamfarhan1999-byte/aplikasi-rapat'),
(5, 4, 'Pengelolaan Rapat', 'Website yang mengelola rapat', '1767357580_WhatsApp Image 2025-12-30 at 10.21.59.jpeg', 'https://github.com/muhamfarhan1999-byte/aplikasi-rapat'),
(6, 5, 'Pengelolaan Rapat', 'Website yang mengelola rapat', '1767357607_WhatsApp Image 2025-12-30 at 10.21.59.jpeg', 'https://github.com/muhamfarhan1999-byte/aplikasi-rapat'),
(7, 6, 'Pencatatan Notulen', 'Website notulen', '1766595087_WhatsApp Image 2025-12-24 at 23.36.21.jpeg', 'https://github.com/nalitanurulizza-maker/NOTULEN.git'),
(8, 7, 'Pencatatan Notulen', 'Website notulen', '1767021628_WhatsApp Image 2025-12-24 at 23.36.21.jpeg', 'https://github.com/nalitanurulizza-maker/NOTULEN.git'),
(9, 8, 'Pencatatan Notulen', 'Website notulen', '1767021650_WhatsApp Image 2025-12-24 at 23.36.21.jpeg', 'https://github.com/nalitanurulizza-maker/NOTULEN.git'),
(10, 9, 'Pencatatan Notulen', 'Website notulen', '1767021669_WhatsApp Image 2025-12-24 at 23.36.21.jpeg', 'https://github.com/nalitanurulizza-maker/NOTULEN.git'),
(11, 10, 'Pengelolaan Surat Peringatan Sp', 'Website untuk mengelola surat peringatan sp', '-', 'pengelolaan-surat-peringatan-sp.html'),
(12, 11, 'Pengelolaan Surat Peringatan Sp', 'Website untuk mengelola surat peringatan sp', '-', 'pengelolaan-surat-peringatan-sp.html'),
(13, 12, 'Pengelolaan Surat Peringatan Sp', 'Website untuk mengelola surat peringatan sp', '-', 'pengelolaan-surat-peringatan-sp.html'),
(14, 13, 'Pengelolaan Surat Peringatan Sp', 'Website untuk mengelola surat peringatan sp', '-', 'pengelolaan-surat-peringatan-sp.html'),
(15, 14, 'Jadwal Perkuliahan Mahasiswa (Pribadi)', 'Website untuk jadwal perkuliahan', '-', 'jadwal-perkuliahan-mahasiswa-(pribadi).html'),
(16, 15, 'Jadwal Perkuliahan Mahasiswa (Pribadi)', 'Website untuk jadwal perkuliahan', '-', 'jadwal-perkuliahan-mahasiswa-(pribadi).html'),
(17, 25, 'Jadwal Perkuliahan Mahasiswa (Pribadi)', 'Website untuk jadwal perkuliahan', '-', 'jadwal-perkuliahan-mahasiswa-(pribadi).html'),
(18, 16, 'Web Informasi Event Kampus', 'Website untuk menunjukkan informasi event kampus', '-', 'https://github.com/aldiernandofirmansyah-png/WebEvent7.git'),
(19, 17, 'Web Informasi Event Kampus', 'Website untuk menunjukkan informasi event kampus', '-', 'https://github.com/aldiernandofirmansyah-png/WebEvent7.git'),
(20, 18, 'Web Informasi Event Kampus', 'Website untuk menunjukkan informasi event kampus', '-', 'https://github.com/aldiernandofirmansyah-png/WebEvent7.git'),
(21, 19, 'Web Informasi Event Kampus', 'Website untuk menunjukkan informasi event kampus', '-', 'https://github.com/aldiernandofirmansyah-png/WebEvent7.git'),
(22, 20, 'Aplikasi Pengumuman Akademik Online', 'Website untuk menjadi pengumuman akademik', '1767358543_aplikasipengumuman.png', 'aplikasi-pengumuman-akademik-online.html'),
(23, 21, 'Aplikasi Pengumuman Akademik Online', 'Website untuk menjadi pengumuman akademik', '1767358596_aplikasipengumuman.png', 'aplikasi-pengumuman-akademik-online.html'),
(24, 22, 'Aplikasi Pengumuman Akademik Online', 'Website untuk menjadi pengumuman akademik', '1767358619_aplikasipengumuman.png', 'aplikasi-pengumuman-akademik-online.html'),
(25, 23, 'Aplikasi Pengumuman Akademik Online', 'Website untuk menjadi pengumuman akademik', '1767358637_aplikasipengumuman.png', 'aplikasi-pengumuman-akademik-online.html');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `portofolio`
--
ALTER TABLE `portofolio`
  ADD PRIMARY KEY (`id_portofolio`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `portofolio`
--
ALTER TABLE `portofolio`
  MODIFY `id_portofolio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
