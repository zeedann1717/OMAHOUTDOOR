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
COMMIT;
