-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 15 Jan 2026 pada 01.09
-- Versi server: 8.0.40
-- Versi PHP: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siakad_maif`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `distribusi_kelas`
--

CREATE TABLE `distribusi_kelas` (
  `id_distribusi_kelas` int NOT NULL,
  `nis` varchar(15) DEFAULT NULL,
  `id_kelas` varchar(5) DEFAULT NULL,
  `id_tahun` varchar(5) NOT NULL,
  `status_keanggotaan` varchar(15) DEFAULT NULL,
  `semester_masuk` varchar(5) DEFAULT NULL,
  `semester_aktif` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `distribusi_kelas`
--

INSERT INTO `distribusi_kelas` (`id_distribusi_kelas`, `nis`, `id_kelas`, `id_tahun`, `status_keanggotaan`, `semester_masuk`, `semester_aktif`, `created_at`) VALUES
(1, '2022.01.1869', '1', '1', 'Aktif', '4', 4, '2025-07-29 19:26:45'),
(2, 'NIS0001', '1', '1', 'Aktif', '4', 4, '2025-07-29 19:26:45'),
(3, 'NIS0002', '1', '1', 'Aktif', '4', 4, '2025-07-29 19:26:45'),
(4, 'NIS0004', '1', '1', 'Aktif', '4', 4, '2025-07-29 19:26:45'),
(5, 'NIS0007', '1', '1', 'Aktif', '4', 4, '2025-07-29 19:26:45'),
(6, 'NIS0003', '2', '1', 'Aktif', '4', 4, '2025-07-29 19:27:08'),
(7, 'NIS0005', '2', '1', 'Aktif', '4', 4, '2025-07-29 19:27:08'),
(8, 'NIS0006', '2', '1', 'Aktif', '4', 4, '2025-07-29 19:27:08'),
(9, 'NIS0008', '2', '1', 'Aktif', '4', 4, '2025-07-29 19:27:08'),
(10, 'NIS0011', '2', '1', 'Aktif', '4', 4, '2025-07-29 19:27:08'),
(11, 'NIS0009', '3', '2', 'Aktif', '1', 1, '2025-07-29 19:27:34'),
(12, 'NIS0010', '3', '2', 'Aktif', '1', 1, '2025-07-29 19:27:34'),
(13, 'NIS0012', '3', '2', 'Aktif', '1', 1, '2025-07-29 19:27:34'),
(14, 'NIS0013', '3', '2', 'Aktif', '1', 1, '2025-07-29 19:27:34'),
(15, 'NIS0020', '3', '2', 'Aktif', '1', 1, '2025-07-29 19:27:34'),
(16, 'NIS0014', '4', '2', 'Aktif', '1', 1, '2025-07-29 19:27:51'),
(17, 'NIS0015', '4', '2', 'Aktif', '1', 1, '2025-07-29 19:27:51'),
(18, 'NIS0016', '4', '2', 'Aktif', '1', 1, '2025-07-29 19:27:51'),
(19, 'NIS0017', '4', '2', 'Cuti', '1', 1, '2025-09-03 09:49:05'),
(20, 'NIS0018', '5', '2', 'Aktif', '1', 1, '2025-07-30 01:44:40'),
(21, 'NIS0019', '5', '2', 'Cuti', '1', 1, '2025-09-03 09:32:57'),
(22, 'nis887', '1', '2', 'Cuti', '1', 1, '2025-07-30 08:47:03'),
(23, '2022.02.3490', '5', '2', 'Aktif', '4', 4, '2025-12-24 01:59:24'),
(24, '2013.01.9999', '5', '2', 'Aktif', '4', 4, '2025-12-24 01:59:24'),
(25, '202222222', '5', '2', 'Aktif', '4', 4, '2025-12-24 01:59:24'),
(26, 'ni334', '5', '2', 'Cuti', '4', 4, '2025-12-24 11:28:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `distribusi_mk`
--

CREATE TABLE `distribusi_mk` (
  `id_distribusi` int NOT NULL,
  `id_tahun` int NOT NULL,
  `id_mk` varchar(20) NOT NULL,
  `id_kelas` varchar(20) NOT NULL,
  `id_dosen` varchar(20) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `distribusi_mk`
--

INSERT INTO `distribusi_mk` (`id_distribusi`, `id_tahun`, `id_mk`, `id_kelas`, `id_dosen`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(1, 2, '1', '1', '1', 'Senin', '07:00:00', '09:00:00', '2025-07-30 09:28:23', '2025-07-30 09:28:23'),
(2, 2, '1', '2', '1', 'Senin', '13:00:00', '15:00:00', '2025-07-30 09:28:50', '2025-07-30 09:28:50'),
(3, 2, '2', '1', '5', 'Selasa', '07:00:00', '09:00:00', '2025-07-30 09:30:30', '2025-07-30 14:02:44'),
(4, 2, '7', '1', '4', 'Kamis', '08:00:00', '11:00:00', '2025-07-30 09:31:23', '2025-07-30 09:31:23'),
(5, 2, '8', '3', '4', 'Rabu', '08:00:00', '10:00:00', '2025-07-30 09:34:01', '2025-07-30 09:34:01'),
(6, 2, '2', '3', '5', 'Jumat', '07:00:00', '09:00:00', '2025-07-30 09:34:32', '2025-07-30 09:34:32'),
(7, 2, '2', '4', '1', 'Sabtu', '14:00:00', '17:00:00', '2025-07-30 11:31:02', '2025-07-30 16:02:43'),
(8, 2, '6', '3', '2', 'Selasa', '15:00:00', '17:00:00', '2025-07-30 13:06:05', '2025-07-30 14:04:09'),
(9, 2, '8', '1', '2', 'Jumat', '10:00:00', '11:00:00', '2025-07-30 15:46:30', '2025-07-30 15:49:13'),
(10, 2, '2', '2', '5', 'Senin', '07:00:00', '09:00:00', '2025-12-24 18:46:50', '2025-12-24 18:46:50'),
(11, 2, '2', '3', '12', 'Selasa', '18:53:00', '18:54:00', '2025-12-24 18:48:13', '2025-12-24 18:48:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_dosen` varchar(50) DEFAULT NULL,
  `gelar_depan` varchar(10) DEFAULT NULL,
  `gelar_belakang` varchar(10) DEFAULT NULL,
  `tempat_lahir` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `jk` varchar(15) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `pendidikan_terakhir` varchar(20) DEFAULT NULL,
  `bidang_keahlian` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `nik`, `nama_dosen`, `gelar_depan`, `gelar_belakang`, `tempat_lahir`, `tanggal_lahir`, `nomor_hp`, `jk`, `alamat`, `email`, `pendidikan_terakhir`, `bidang_keahlian`, `password`, `is_active`, `last_login_at`, `last_login_ip`) VALUES
(1, '33442', 'Muslim', 'Dr.', 'M.Kc', 'palu', '2025-05-12', '081913866560', 'Laki-laki', 'palu', 'palu@co.vp', 'S2', 'Fiqh', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 1, NULL, NULL),
(2, '1771033112150001', 'Firman', 'Dr.', 'S.Pd.I', 'banyuwangi', '1998-08-08', '085231861556', 'Laki-laki', 'Bali Barat', 'bali@fg.com', 'S1', 'Aqidah', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 1, NULL, NULL),
(4, '1123', 'Sukron', '', 'M.HI', 'Jember', '1980-11-01', '0812321423', 'Laki-laki', 'SUkorejo', 'a@a.com', 'S2', 'Fiqh', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 1, NULL, NULL),
(5, '22345', 'Miftah', '', 'M.EI', 'Bali', '2000-02-02', '08123456789', 'Laki-laki', 'Banyuputih', 'd@d.com', 'S2', 'Aqidah', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 1, NULL, NULL),
(6, '2222345', 'suparman', 'Dr', '', 'jember', '2000-03-01', '08123456780', 'Perempuan', 'sukorejo', 'suparman@co.id', 'S2', '-', '$2y$10$JENdw01vc7pCr7BnNwmhMODtBabjO2cnFfOaK.SukOHLtCcbFz3Fu', 1, NULL, NULL),
(9, '88234', 'JUHARMIN', '', 'M.KM', 'Bali', '1990-10-10', '082343534', 'Laki-laki', 'Sukorejo', 'adi@do.kom', 'S2', 'Aqidah', NULL, 1, NULL, NULL),
(12, '223442', 'Holis', 'Dr', 'M.H.I', 'Situbondo', '1980-01-01', '097234234', 'Laki-laki', 'Sukorejo', 'adi@do.kom', 'S2', 'Fiqh', '$2y$10$yOEZuvyAf1gV0bzU4iAlquVqoJQqVzVmcoxz7aizTOhwl7n68cw8q', 1, NULL, NULL),
(13, '223442', 'qdd', 'q', 'q', 'q', '2025-01-01', '085336180371', 'Laki-laki', 'sumberanyar banyuputih situbondo jawa timur', 'ris@ibrahimy.ac.id', 'S1', 'Fiqh', '$2y$10$WRBFMPfMgVhdcODiG9v9MeLhYXjhrJqgrrpwlxL41FnqKP2q2AGQW', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `nama_kelas` varchar(30) DEFAULT NULL,
  `id_tahun` int DEFAULT NULL,
  `jenjang` varchar(5) DEFAULT NULL,
  `semester` varchar(5) DEFAULT NULL,
  `kategori` varchar(10) NOT NULL,
  `dosen_wali` varchar(5) NOT NULL,
  `status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `id_tahun`, `jenjang`, `semester`, `kategori`, `dosen_wali`, `status`) VALUES
(1, 'M1 A', 1, 'M1', '4', 'Putra', '', 'Aktif'),
(2, 'M1 B', 1, 'M1', '4', 'Putri', '', 'Aktif'),
(3, 'M2 A', 2, 'M2', '1', 'Putra', '', 'Aktif'),
(4, 'M2 B', 2, 'M2', '1', 'Putri', '', 'Aktif'),
(5, 'M2 C', 2, 'M1', '4', 'Putra', '', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `krs`
--

CREATE TABLE `krs` (
  `id_krs` int NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `id_matkul` int DEFAULT NULL,
  `id_kelas` int DEFAULT NULL,
  `semester` int DEFAULT NULL,
  `id_tahun` varchar(10) DEFAULT NULL,
  `nilai_angka` int DEFAULT NULL,
  `nilai_revisi` int NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `krs`
--

INSERT INTO `krs` (`id_krs`, `nis`, `id_matkul`, `id_kelas`, `semester`, `id_tahun`, `nilai_angka`, `nilai_revisi`, `keterangan`, `create_at`) VALUES
(1, '2022.01.1869', 1, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(2, '2022.01.1869', 2, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(3, '2022.01.1869', 7, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(4, '2022.01.1869', 8, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(5, 'NIS0001', 1, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(6, 'NIS0001', 2, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(7, 'NIS0001', 7, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(8, 'NIS0001', 8, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(9, 'NIS0002', 1, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(10, 'NIS0002', 2, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(11, 'NIS0002', 7, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(12, 'NIS0002', 8, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(13, 'NIS0004', 1, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(14, 'NIS0004', 2, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(15, 'NIS0004', 7, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(16, 'NIS0004', 8, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(17, 'NIS0007', 1, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(18, 'NIS0007', 2, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(19, 'NIS0007', 7, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50'),
(20, 'NIS0007', 8, 1, 4, '2', NULL, 0, NULL, '2025-12-28 02:31:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nis` varchar(20) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `nama_mahasiswa` varchar(200) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `jk` varchar(15) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biaya_pendidikan` varchar(50) DEFAULT NULL,
  `password` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tgl_input` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`nis`, `nim`, `nama_mahasiswa`, `tempat_lahir`, `tanggal_lahir`, `nomor_hp`, `jk`, `alamat`, `email`, `biaya_pendidikan`, `password`, `status`, `tgl_input`) VALUES
('2013.01.9999', '8887744', 'Maulana Malik', 'Jember', '2001-08-05', '812222333445', 'Laki-laki', 'Bondowoso', 'miftah@gmail.com', 'Mandiri', '$2y$10$kXrpGqa.EiI5iJJ920VdcOP/jD3GPv1pZ5mxZIZuQR7QakFwqKhU6', 'Aktif', '0000-00-00 00:00:00'),
('2022.01.1861', '2234232', 'retno', 'situbondo', '2000-05-01', '1234567890', 'Perempuan', 'Sukorejo', 'ris@ibrahimy.ac.id', 'Beasiswa Baznas', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Cuti', '0000-00-00 00:00:00'),
('2022.01.1869', '2234234', 'idris', 'sodung', '2002-08-12', '0812331222', 'Laki-laki', 'sed', 'amls@jk.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('2022.02.3490', '22222', 'Firda Aini', 'Sukorejo', '2001-08-05', '812222333445', 'Laki-laki', 'Bondowoso', 'miftah@gmail.com', 'Mandiri', '$2y$10$2SFPyG5mcHaIthr5dVU/4.M4Zt9mxEJ41Kk8KD3PqH9eswlwnZw2K', 'Aktif', '2025-12-19 06:07:53'),
('202222222', '889083', 'Muslim', 'Jakarta', '1999-08-12', '081222333444', 'Laki-laki', 'Sukorejo', 'aaa@ad.com', 'Mandiri', '$2y$10$PfN.kZj4qRLpNkQ9WYtiwOHbCBJRgr6vsd/h2/yjhjmXsp2DwRuzC', 'Aktif', '0000-00-00 00:00:00'),
('ni334', '990923', 'Sinaruddin', 'Kupang', '2000-09-08', '812321343', 'Laki-laki', 'Sukorejo', 'kupang@co.ud', 'Mandiri', NULL, 'Cuti', '0000-00-00 00:00:00'),
('NIS0001', 'NIM0001', 'Ahmad Fauzi', 'Jakarta', '2002-01-15', '081234567890', 'Laki-laki', 'Jl. Merdeka No.1', 'ahmad1@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0002', 'NIM0002', 'Budi Santoso', 'Bandung', '2001-12-20', '081234567891', 'Laki-laki', 'Jl. Sudirman No.2', 'budi2@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0003', 'NIM0003', 'Citra Lestari', 'Surabaya', '2003-03-10', '081234567892', 'Laki-laki', 'Jl. Melati No.3', 'citra3@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0004', 'NIM0004', 'Dedi Kurniawan', 'Yogyakarta', '2002-07-08', '081234567893', 'Laki-laki', 'Jl. Anggrek No.4', 'dedi4@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0005', 'NIM0005', 'Eka Prasetya', 'Bekasi', '2001-05-25', '081234567894', 'Laki-laki', 'Jl. Kenanga No.5', 'eka5@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0006', 'NIM0006', 'Fitri Andayani', 'Depok', '2003-08-19', '081234567895', 'Laki-laki', 'Jl. Dahlia No.6', 'fitri6@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0007', 'NIM0007', 'Gilang Saputra', 'Tangerang', '2002-09-14', '081234567896', 'Laki-laki', 'Jl. Cempaka No.7', 'gilang7@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0008', 'NIM0008', 'Hana Oktavia', 'Semarang', '2002-11-03', '081234567897', 'Laki-laki', 'Jl. Flamboyan No.8', 'hana8@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0009', 'NIM0009', 'Indra Gunawan', 'Malang', '2001-10-22', '081234567898', 'Laki-laki', 'Jl. Mawar No.9', 'indra9@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0010', 'NIM0010', 'Joko Susilo', 'Medan', '2003-02-17', '081234567899', 'Laki-laki', 'Jl. Teratai No.10', 'joko10@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0011', 'NIM0011', 'Kiki Ramadhani', 'Padang', '2002-03-23', '081234567800', 'Laki-laki', 'Jl. Sakura No.11', 'kiki11@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0012', 'NIM0012', 'Lestari Dewi', 'Palembang', '2003-06-12', '081234567801', 'Laki-laki', 'Jl. Semangka No.12', 'lestari12@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0013', 'NIM0013', 'Muhammad Rizki', 'Batam', '2001-07-27', '081234567802', 'Laki-laki', 'Jl. Durian No.13', 'rizki13@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0014', 'NIM0014', 'Nina Kartika', 'Solo', '2002-10-30', '081234567803', 'Laki-laki', 'Jl. Apel No.14', 'nina14@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0015', 'NIM0015', 'Oka Mahendra', 'Bogor', '2003-09-09', '081234567804', 'Laki-laki', 'Jl. Mangga No.15', 'oka15@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0016', 'NIM0016', 'Putri Ayu', 'Pekanbaru', '2002-04-05', '081234567805', 'Laki-laki', 'Jl. Pepaya No.16', 'putri16@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0017', 'NIM0017', 'Qori Azizah', 'Makassar', '2003-01-11', '081234567806', 'Laki-laki', 'Jl. Jeruk No.17', 'qori17@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Cuti', '0000-00-00 00:00:00'),
('NIS0018', 'NIM0018', 'Rangga Aditya', 'Balikpapan', '2001-06-20', '081234567807', 'Laki-laki', 'Jl. Nangka No.18', 'rangga18@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0019', 'NIM0019', 'Siti Rahma', 'Pontianak', '2002-12-28', '081234567808', 'Perempuan', 'Jl. Pisang No.19', 'siti19@example.com', 'Beasiswa LPPD', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS0020', 'NIM0020', 'Teguh', 'Manado', '2003-05-13', '081234567809', 'Laki-laki', 'Jl. Salak No.20', 'teguh20@example.com', 'Mandiri', '$2y$10$o3m42BRK0tYGkrYpFHT/w.werNfnBFBCMhZ0/y4pbtZLnly5I9i/a', 'Aktif', '0000-00-00 00:00:00'),
('NIS00203', 'NIS0020', 'zahrahan', 'papua', '2000-09-08', '081998223447', 'Laki-laki', 'papua puncak jaya', 'zahrahan@gmail.com', 'Mandiri', '$2y$10$r2j.Mr0OvAcIa3p0Lusuauu3S/XDT.rpQF89nkLdDtVBiMWQPotkK', 'Aktif', '2025-12-19 06:06:55'),
('nis99901', 'nim776', 'Miftahul Arifin', 'Bondowoso', '2001-08-05', '812222333445', 'Laki-laki', 'Bondowoso', 'miftah@gmail.com', 'Mandiri', '$2y$10$sHrI7Mb/xnp.U26jL9pb.OB/3h7HNjfOvnmQQ8iZgqBUfo6DsEl.G', 'Cuti', '2025-12-19 06:06:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `matakuliah`
--

CREATE TABLE `matakuliah` (
  `id_matakuliah` int NOT NULL,
  `kode_matakuliah` varchar(20) DEFAULT NULL,
  `nama_matakuliah` varchar(50) DEFAULT NULL,
  `sks` int DEFAULT NULL,
  `jenjang` varchar(5) DEFAULT NULL,
  `semester` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `matakuliah`
--

INSERT INTO `matakuliah` (`id_matakuliah`, `kode_matakuliah`, `nama_matakuliah`, `sks`, `jenjang`, `semester`) VALUES
(1, 'FQH01', 'Fiqih', 2, 'M2', 2),
(2, 'aqd123', 'Aqidah akhlaq', 3, 'M1', 1),
(6, 'MKK12c', 'Ushul Fiqh', 3, 'M2', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggaran`
--

CREATE TABLE `pelanggaran` (
  `id_pelanggaran` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `jenjang` varchar(10) NOT NULL,
  `semester` int NOT NULL,
  `jenis_pelanggaran` varchar(100) NOT NULL,
  `sanksi` varchar(100) NOT NULL,
  `tanggal_pelanggaran` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pelanggaran`
--

INSERT INTO `pelanggaran` (`id_pelanggaran`, `nis`, `jenjang`, `semester`, `jenis_pelanggaran`, `sanksi`, `tanggal_pelanggaran`, `created_at`) VALUES
(2, 'NIS0019', 'M1', 3, 'Tidak kuliah ', 'hafalan', '2025-09-11', '2025-09-11 02:11:39'),
(3, 'nis887', 'M1', 4, 'tidak menyapu kamar', 'mengaji', '2025-09-18', '2025-09-17 08:11:22'),
(5, 'nis887', 'M1', 4, 'tidak mandi', 'baca yasin', '2025-09-01', '2025-09-17 08:23:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `is_active`, `last_login_at`, `last_login_ip`) VALUES
(1, 'petugas', '$2y$10$Rc/Mlwa4OiRXGYk9pUop7uUJ5x.5RFyX3Ng58RRF8XdtXucz2BjOS', 1, '2025-11-18 02:25:14', '::1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `id_distribusi` varchar(5) NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `id_matkul` int DEFAULT NULL,
  `id_dosen` int DEFAULT NULL,
  `id_tahun` varchar(10) DEFAULT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `nilai` int DEFAULT NULL,
  `nilai_revisi` int NOT NULL,
  `huruf` varchar(2) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekap_presensi`
--

CREATE TABLE `rekap_presensi` (
  `id_rekap` int NOT NULL,
  `id_krs` int NOT NULL,
  `jumlah_hadir` int DEFAULT '0',
  `jumlah_izin` int DEFAULT '0',
  `jumlah_sakit` int DEFAULT '0',
  `jumlah_alpha` int DEFAULT '0',
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rekap_presensi`
--

INSERT INTO `rekap_presensi` (`id_rekap`, `id_krs`, `jumlah_hadir`, `jumlah_izin`, `jumlah_sakit`, `jumlah_alpha`, `uploaded_at`) VALUES
(6, 17, 5, 0, 0, 5, '2025-07-30 06:34:25'),
(7, 20, 10, 0, 0, 0, '2025-07-30 06:34:25'),
(8, 23, 2, 2, 5, 1, '2025-07-30 06:34:25'),
(9, 26, 8, 0, 1, 1, '2025-07-30 06:34:25'),
(10, 29, 7, 1, 1, 1, '2025-07-30 06:34:25'),
(11, 16, 10, 0, 0, 0, '2025-07-30 06:34:43'),
(12, 19, 10, 0, 0, 0, '2025-07-30 06:34:43'),
(13, 22, 10, 0, 0, 0, '2025-07-30 06:34:43'),
(14, 25, 10, 0, 0, 0, '2025-07-30 06:34:43'),
(15, 28, 1, 0, 0, 9, '2025-07-30 06:34:43'),
(16, 3, 5, 3, 2, 0, '2025-07-30 06:40:25'),
(17, 6, 8, 0, 2, 0, '2025-07-30 06:40:25'),
(18, 9, 9, 0, 0, 1, '2025-07-30 06:40:25'),
(19, 12, 3, 5, 1, 1, '2025-07-30 06:40:25'),
(20, 15, 10, 0, 0, 0, '2025-07-30 06:40:25'),
(21, 2, 8, 0, 0, 2, '2025-07-30 06:40:59'),
(22, 5, 9, 0, 1, 0, '2025-07-30 06:40:59'),
(23, 8, 10, 0, 0, 0, '2025-07-30 06:40:59'),
(24, 11, 10, 0, 0, 0, '2025-07-30 06:40:59'),
(25, 14, 3, 4, 2, 1, '2025-07-30 06:40:59'),
(26, 1, 10, 0, 0, 0, '2025-07-30 06:41:18'),
(27, 4, 0, 10, 0, 0, '2025-07-30 06:41:18'),
(28, 7, 10, 0, 0, 0, '2025-07-30 06:41:18'),
(29, 10, 0, 0, 0, 10, '2025-07-30 06:41:18'),
(30, 13, 0, 5, 0, 5, '2025-07-30 06:41:18'),
(39, 31, 10, 0, 0, 0, '2025-07-30 06:44:40'),
(40, 32, 5, 5, 0, 0, '2025-07-30 06:44:40'),
(41, 33, 5, 0, 5, 0, '2025-07-30 06:44:40'),
(42, 34, 0, 10, 0, 0, '2025-07-30 06:44:40'),
(43, 18, 9, 0, 0, 0, '2025-07-30 08:57:38'),
(44, 21, 5, 0, 5, 0, '2025-07-30 08:57:38'),
(45, 24, 3, 3, 3, 1, '2025-07-30 08:57:38'),
(46, 27, 8, 0, 1, 1, '2025-07-30 08:57:38'),
(47, 30, 6, 2, 2, 0, '2025-07-30 08:57:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_semester`
--

CREATE TABLE `riwayat_semester` (
  `id_riwayat_semester` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `id_kelas` int NOT NULL,
  `semester` int NOT NULL,
  `id_tahun` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `riwayat_semester`
--

INSERT INTO `riwayat_semester` (`id_riwayat_semester`, `nis`, `id_kelas`, `semester`, `id_tahun`, `status`, `created_at`) VALUES
(1, 'NIS0018', 5, 1, '2', 'Aktif', '2025-09-09 06:40:08'),
(2, 'NIS0019', 5, 1, '2', 'Cuti', '2025-09-09 06:40:08'),
(3, 'NIS0018', 5, 2, '2', 'Aktif', '2025-12-24 01:35:03'),
(4, 'NIS0019', 5, 2, '2', 'Cuti', '2025-12-24 01:35:03'),
(5, 'NIS0018', 5, 3, '2', 'Aktif', '2025-12-24 01:35:06'),
(6, 'NIS0019', 5, 3, '2', 'Cuti', '2025-12-24 01:35:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `id_tahun` int NOT NULL,
  `tahun_akademik` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`id_tahun`, `tahun_akademik`, `semester`, `tanggal_mulai`, `tanggal_selesai`, `status`) VALUES
(1, '2024/2025', 'Ganjil', '2024-08-01', '2024-12-01', 'Tidak Aktif'),
(2, '2025/2026', 'Ganjil', '2025-01-07', '2025-07-24', 'Aktif'),
(6, '2026/2027', 'Genap', '2025-12-06', '2025-12-07', 'Tidak Aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `distribusi_kelas`
--
ALTER TABLE `distribusi_kelas`
  ADD PRIMARY KEY (`id_distribusi_kelas`);

--
-- Indeks untuk tabel `distribusi_mk`
--
ALTER TABLE `distribusi_mk`
  ADD PRIMARY KEY (`id_distribusi`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD UNIQUE KEY `ux_dosen_nomor_hp` (`nomor_hp`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id_krs`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nis`),
  ADD UNIQUE KEY `ux_mahasiswa_nis` (`nis`);

--
-- Indeks untuk tabel `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`id_matakuliah`);

--
-- Indeks untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD PRIMARY KEY (`id_pelanggaran`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `ux_pengguna_username` (`username`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD UNIQUE KEY `uniq_nilai` (`nis`,`id_kelas`,`id_matkul`,`id_tahun`);

--
-- Indeks untuk tabel `rekap_presensi`
--
ALTER TABLE `rekap_presensi`
  ADD PRIMARY KEY (`id_rekap`),
  ADD UNIQUE KEY `id_krs` (`id_krs`);

--
-- Indeks untuk tabel `riwayat_semester`
--
ALTER TABLE `riwayat_semester`
  ADD PRIMARY KEY (`id_riwayat_semester`);

--
-- Indeks untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`id_tahun`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `distribusi_kelas`
--
ALTER TABLE `distribusi_kelas`
  MODIFY `id_distribusi_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `distribusi_mk`
--
ALTER TABLE `distribusi_mk`
  MODIFY `id_distribusi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `krs`
--
ALTER TABLE `krs`
  MODIFY `id_krs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `id_matakuliah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  MODIFY `id_pelanggaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekap_presensi`
--
ALTER TABLE `rekap_presensi`
  MODIFY `id_rekap` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `riwayat_semester`
--
ALTER TABLE `riwayat_semester`
  MODIFY `id_riwayat_semester` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  MODIFY `id_tahun` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
