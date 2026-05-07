-- phpMyAdmin SQL Dump
-- Omah Outdoor - Updated with role system

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Database: `omah_outdoor`

-- Struktur tabel `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data contoh akun admin
INSERT INTO `users` (`id`, `nama`, `no_wa`, `role`, `email`, `password`, `created_at`) VALUES
-- Password: admin123
(1, 'Zidan Maulana', '081234567890', 'admin', 'maulanazidan4420@gmail.com', '$2y$10$J3.Fp8XxYwT0cIsBkJOmgeD7SAiwicmamKuKkFjUTXky1bNppG0V.', '2026-05-01 07:05:27');

-- Index
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
-- Struktur tabel `produk`
CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(150) NOT NULL,
  `harga_per_hari` int(11) NOT NULL,
  `status` enum('tersedia','disewa') NOT NULL DEFAULT 'tersedia',
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produk` (`nama_produk`, `harga_per_hari`, `status`, `gambar`) VALUES
('Tenda Dome Kap. 4', 35000, 'tersedia', 'Tenda Dome Kap. 4.jpg'),
('Carrier 60L', 25000, 'tersedia', 'Carrier 60L.jpg'),
('Sleeping Bag Polar Bulu', 10000, 'disewa', 'Sleeping Bag Polar Bulu.jpeg'),
('Sepatu Gunung', 20000, 'tersedia', 'Sepatu Gunung.jpg'),
('Kompor Portable', 15000, 'tersedia', 'Kompor Portable.jpg'),
('Matras Foil Aluminium', 5000, 'tersedia', 'Matras Foil Aluminium.jpg');

COMMIT;
