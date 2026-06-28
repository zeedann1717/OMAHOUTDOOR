-- ============================================================
-- OMAH OUTDOOR - Database Setup
-- Versi Terbaru: Includes tabel users + produk
-- Password akun admin: admin123
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================================================
-- Hapus tabel lama jika ada (agar import ulang tidak error)
-- ============================================================
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `produk`;
DROP TABLE IF EXISTS `users`;

-- ============================================================
-- Struktur tabel `users`
-- ============================================================
CREATE TABLE `users` (
  `id`         int(11)                      NOT NULL AUTO_INCREMENT,
  `nama`       varchar(100)                 DEFAULT NULL,
  `no_wa`      varchar(20)                  DEFAULT NULL,
  `role`       enum('user','admin')         NOT NULL DEFAULT 'user',
  `email`      varchar(100)                 NOT NULL,
  `password`   varchar(255)                 NOT NULL,
  `created_at` timestamp                    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data akun admin (password: admin123)
INSERT INTO `users` (`id`, `nama`, `no_wa`, `role`, `email`, `password`, `created_at`) VALUES
(1, 'Zidan Maulana', '081234567890', 'admin', 'maulanazidan4420@gmail.com', '$2y$10$J3.Fp8XxYwT0cIsBkJOmgeD7SAiwicmamKuKkFjUTXky1bNppG0V.', '2026-05-01 07:05:27');

ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- ============================================================
-- Struktur tabel `produk`
-- ============================================================
CREATE TABLE `produk` (
  `id`             int(11)              NOT NULL AUTO_INCREMENT,
  `nama_produk`    varchar(150)         NOT NULL,
  `harga_per_hari` int(11)              NOT NULL,
  `status`         enum('tersedia','disewa') NOT NULL DEFAULT 'tersedia',
  `gambar`         varchar(255)         DEFAULT NULL,
  `created_at`     timestamp            NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data produk awal
INSERT INTO `produk` (`nama_produk`, `harga_per_hari`, `status`, `gambar`) VALUES
('Tenda Dome Kap. 4',    35000, 'tersedia', 'Tenda Dome Kap. 4.jpg'),
('Carrier 60L',          25000, 'tersedia', 'Carrier 60L.jpg'),
('Sleeping Bag Polar Bulu', 10000, 'disewa', 'Sleeping Bag Polar Bulu.jpg'),
('Sepatu Gunung',        20000, 'tersedia', 'Sepatu Gunung.jpg'),
('Kompor Portable',      15000, 'tersedia', 'Kompor Portable.jpg'),
('Matras Foil Aluminium', 5000, 'tersedia', 'Matras Foil Aluminium.jpg');

-- ============================================================
-- Struktur tabel `orders`
-- ============================================================
CREATE TABLE `orders` (
  `id`              int(11)    NOT NULL AUTO_INCREMENT,
  `kode_order`      varchar(30) NOT NULL,
  `user_id`         int(11)    NOT NULL,
  `produk_id`       int(11)    NOT NULL,
  `tanggal_mulai`   date       NOT NULL,
  `tanggal_selesai` date       NOT NULL,
  `durasi_hari`     int(11)    NOT NULL,
  `total_harga`     int(11)    NOT NULL,
  `status`          enum('pending','dikonfirmasi','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `created_at`      timestamp  NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_order` (`kode_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
