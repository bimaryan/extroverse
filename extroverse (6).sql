-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2024 at 11:11 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `extroverse`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `nama_acara` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `jumlah_tiket_terjual` int(11) DEFAULT 0,
  `cover_foto` varchar(255) NOT NULL,
  `tiket_type` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `nama_acara`, `deskripsi`, `tanggal`, `harga`, `jumlah_tiket`, `lokasi`, `jumlah_tiket_terjual`, `cover_foto`, `tiket_type`, `user_id`) VALUES
(29, '7th Annual Indonesian Scooter Festival', 'Indonesia Scooter Festival (ISF) merupakan event otomotif tahunan yang dihelat sejak tahun 2017. Dalam penyelenggaraan perdananya, gelaran yang bermarkas di Yogyakarta ini sukses menghadirkan kesan menarik tentang scooter. Salah satu jenis moda transportasi yang banyak digandrungi oleh masyarakat Indonesia ini mampu dikemas dan ditampilkan secara eksklusif kepada khalayak luas. Indonesia Scooter Festival tahun ini akan banyak berbeda dengan tahun - tahun sebelumnya, karena di tahun ini acara yang akan berlangsung adalah mengenang perjalanan 6 tahun Indonesia Scooter Festival dan mengambil tema “Family Time”.\r\n\r\nKonten - konten yang akan ada di tahun 2023 ini, ada beberapa tambahan yang di tahun sebelumnya belum pernah ada yaitu konten gallery yang menampilkan memorabilia dokumentasi dari tahun ke tahun ISF dibalut dengan pameran seni dan kreatifitas yang bertemakan Scooter. Ditahun ini juga, diharapkan pengunjung bisa mengajak seluruh anggota keluarganya. Sebab akan ada area playground dan area untuk camping ground.\r\n\r\nSelain itu, setiap harinya akan ada City Riding yang akan mengajak pengunjung untuk berkeliling Yogyakarta dengan naik Vespa bersama keluarganya. Tahun ini akan ada parkir VIP khusus yang membawa Vespa dibawah tahun 1967 dan terdapat 3 stage yang digunakan untuk Talkshow dan Entertaiment.', '2023-12-29', 25000.00, 100, ' The Ratan - Multi Use Building | The Ratan', 0, '../img/konser.png', 'Presale 1', 3),
(34, 'I COMM TO U FESTIVAL', 'Hai Selamat datang generasi baru, selamat menjadi pengunjung warna warni indah milik kami. Perkenalkanlah kami dari ilmu komunikasi angkatan’22 akan memberikan hiburan yang dapat membakar semangat dan menggetarkan jiwa. Dengan ceria dan bangga kami persembahkan puncak acara Festivity Of New Generation yang bisa membersamai perjalanan kalian meraih kemenangan berupa konser yang akan menampilkan Rebellion rose dan band band lokal heroes', '2024-01-13', 50000.00, 100, 'Stadion Batoro Katong | Stadion Batoro katong', 0, '../img/I COMM TO U FESTIVAL.png', 'Presale 1', 3),
(35, 'artventurous 41', 'HUT SMADA dan Bulan Bahasa adalah kegiatan yang dilakukan setiap satu tahun sekali di SMA Negeri 2 Samarinda, Bulan Bahasa adalah tempat dimana dapat menyalurkan bakat bakat siswa siswi, dan di penutupan acara akan selalu ada malam puncak yang mengundang beberapa talent.', '2024-01-04', 140000.00, 100, ' Jl. Kemakmuran | SMA Negeri 2 Samarinda', 0, '../img/artventurous 41.png', 'Presale 1', 3),
(36, 'FESTIVE FEST 2024', 'Siapkan dirimu untuk menyambut sanggar tari ibukota JKT 48 di Kota Klaten !\r\n\r\nDitemani dengan beberapa artis ibukota lainnya, siap memeriahkan Kota Klaten di ACARA FESTIVE FEST 13 JANUARI 2024\r\n\r\nBELI TIKET SEKARANG DAN BERGABUNGLAH DALAM KESERUAN FESTIVE FEST !\r\n\r\nSyarat dan Ketentuan :\r\n\r\nSiapkan data diri seperti email aktif, nomor hp aktif, alamat, nama lengkap sesuai dengan KTP untuk mempermudah proses kamu membeli tiket\r\n\r\n1 orang maksimum hanya dapat membeli 3 tiket\r\n\r\nFestive Fest tidak bertanggungjawab atas pembelian tiket yang dilakukan diluar platform resmi pembelian tiket yang diumumkan di Instagram @festivefest.id\r\n\r\nTiket tidak dapat diuangkan atau ditukar kembali KECUALI terjadi force majeure atau pembatal tak terduga dari pihak promotor\r\n\r\nSetiap pembelian akan mendapatkan e-tiket yang berisi code dikirimkan melalui email yang didaftarkan.\r\n\r\ncode tertera pada tiket yang sudah diterima akan discan saat hari H untuk masuk kedalam venue dan hanya berlaku untuk 1 kali scan.\r\n\r\nTindakan penggandaan tiket dan pemalsuan tiket adalah tindakan melanggar hukum. Festive Fest menghargai hukum yang berlaku dan akan menindak secara hukum bentuk kecurangan tersebut.\r\n\r\nTiket yang berpindah tangan dari pembeli pertama (yang namanya tertera pada tiket) wajib menunjukan bukti terima tiket antar pihak pertama dan pihak kedua berupa surat kuasa.\r\n\r\nFestive Fest memiliki hak untuk mengubah atau menambahkan syarat dan ketentuan tanpa ada pemberitahuan terlebih dahulu\r\n\r\nSemua poin syarat dan ketentuan diatas sudah saya baca dan saya pahami. Saya sadar dan setuju untuk mematuhi syarat dan ketentuan yang berlaku.', '2024-01-13', 150000.00, 100, ' Dodiklatpur Kodam IV Diponegoro | Lapangan Dodiklatpur Klaten', 0, '../img/festivalfest2024.png', 'Presale 1', 3),
(37, 'I COMM TO U FESTIVAL', 'Hai Selamat datang generasi baru, selamat menjadi pengunjung warna warni indah milik kami. Perkenalkanlah kami dari ilmu komunikasi angkatan’22 akan memberikan hiburan yang dapat membakar semangat dan menggetarkan jiwa. Dengan ceria dan bangga kami persembahkan puncak acara Festivity Of New Generation yang bisa membersamai perjalanan kalian meraih kemenangan berupa konser yang akan menampilkan Rebellion rose dan band band lokal heroes', '2024-01-13', 50000.00, 100, 'Stadion Batoro Katong | Stadion Batoro katong', 0, '../img/I COMM TO U FESTIVAL.png', 'Presale 1', 6);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `pembelian_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `jumlah_tiket_dibeli` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `waktu_pembelian` timestamp NOT NULL DEFAULT current_timestamp(),
  `nik` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrasi_tiket`
--

CREATE TABLE `registrasi_tiket` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `transaction_status` enum('1','2','3','4','5') NOT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrasi_tiket`
--

INSERT INTO `registrasi_tiket` (`id`, `nama`, `email`, `tanggal_lahir`, `gender`, `order_id`, `transaction_id`, `transaction_status`, `event_id`) VALUES
(52, 'ziki', 'camink2nd@gmail.com', '2004-07-04', 'male', 659667, '7fd52443-20d7-4415-bffb-9aed0e1698e1', '2', 36),
(53, 'ziki', 'camink2nd@gmail.com', '2004-07-04', 'male', 6596680, '7e880de2-f5b9-462c-93dc-cec988cf2d09', '2', 35),
(54, 'ziki', 'camink2nd@gmail.com', '2004-07-04', 'male', 65966, '4e1bc1f9-5cb5-4289-9bfe-8643d20e07c9', '2', 29),
(55, 'ziki', 'camink2nd@gmail.com', '2004-07-04', 'male', 65966, '4e1bc1f9-5cb5-4289-9bfe-8643d20e07c9', '2', 36),
(56, 'ziki', 'camink2nd@gmail.com', '2004-07-04', 'male', 65966, '4e1bc1f9-5cb5-4289-9bfe-8643d20e07c9', '2', 34),
(57, 'ziki', 'camink2nd@gmail.com', '2004-07-04', 'male', 65966, '4e1bc1f9-5cb5-4289-9bfe-8643d20e07c9', '2', 37),
(58, 'Aditya Wisnu Setya Pamungkas', 'wisnu@gmail.com', '2000-01-11', 'male', 6596702, '', '1', 36);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `nomor_tiket` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','terjual') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pengguna','penjual') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `email`, `job`, `phone`, `gender`, `dob`, `profile_image`) VALUES
(1, 'admin123', '$2y$10$ASfJmCm.6Y/Ok6V9037vtO0o0fPfXbH7oHgDj.1EArdULxhHhHrLK', 'admin', 'admin@gmail.com', NULL, NULL, NULL, NULL, '../profil/logo/logo polindra.png'),
(2, 'pengguna', '$2y$10$F7KYqHXeTtoafkXBg8f0v.Kz.J.F6nXEey87Kr.315nkTlYMbukJ.', 'pengguna', 'pengguna@gmail.com', NULL, NULL, NULL, NULL, '../profil/logo/ristek.png'),
(3, 'penjual', '$2y$10$FiCkoQZl5.8FyME42yQQxOd/XGbE2sX7VvU3E4a6b9YBiUsT9lfzW', 'penjual', 'penjual@gmail.com', NULL, '1234567890', 'male', NULL, '../profil/logo/ristek.png'),
(5, 'bima12345', '$2y$10$fBeg7O9Czc.PL3dpic2jduuS7.8m931Gt7sDW.CWqdIzSPdHB7WLa', 'pengguna', 'bimaryan046@gmail.com', NULL, NULL, NULL, NULL, NULL),
(6, 'ziki', '$2y$10$pjHNKxKm59RikJ0SK4i4Yu.R8Gzr6A9Pc9FAYY0crSUjBP2PnoBvK', 'penjual', 'ziki@gmail.com', NULL, '0123456789', 'male', NULL, '../profil/logo/ux.png'),
(7, 'munengsih', '$2y$10$r2jWqngrfFvTEKddoPO/sOCV1LqfBwQ4a1tGRiomxKFGnm13/4MtS', 'pengguna', 'msb@gmail.com', NULL, NULL, NULL, NULL, NULL),
(9, 'munengsih1', '$2y$10$juv713nVOPIs1.QrMaNHn.kaM4PTxOHehY6Sx2Wg0hPajfyOjxRxe', 'penjual', 'msb1@gmail.com', NULL, '01234567890', 'female', NULL, '../profil/logo/ui-ux.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`,`token`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`pembelian_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `registrasi_tiket`
--
ALTER TABLE `registrasi_tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `nomor_tiket` (`nomor_tiket`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `pembelian_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrasi_tiket`
--
ALTER TABLE `registrasi_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
