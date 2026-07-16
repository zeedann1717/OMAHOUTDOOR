# 🏕️ OMAH OUTDOOR — Panduan Setup Lengkap
![Status](https://img.shields.io/badge/Status-Siap_Presentasi-success)
> Buat seluruh anggota Kelompok 6 — baca sampai habis biar gak error!

---

## ⚡ PERTAMA KALI SETUP (Laptop Baru / Baru Clone)

### Tahap 1 — Clone Project dari GitHub

**Kalau pakai XAMPP:**
1. Buka folder `C:\xampp\htdocs\`
2. Klik kanan → **Open Git Bash here**
3. Ketik:
```
git clone https://github.com/zeedann1717/OMAHOUTDOOR.git
```

**Kalau pakai Laragon:**
1. Buka folder `D:\laragon\www\` (atau di mana Laragon kamu terinstall)
2. Klik kanan → **Open Git Bash here**
3. Ketik:
```
git clone https://github.com/zeedann1717/OMAHOUTDOOR.git
```

---

### Tahap 2 — Buat File `koneksi.php` (WAJIB, Tidak Ikut Git!)

> ⚠️ File ini sengaja **tidak ikut** di GitHub karena tiap orang beda konfigurasi.
> Kamu harus buat sendiri setiap kali clone di laptop baru!

Buat file baru bernama **`koneksi.php`** di dalam folder `OMAHOUTDOOR/`, isi sesuai aplikasi yang kamu pakai:

**Kalau pakai XAMPP:**
```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "omah_outdoor";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

**Kalau pakai Laragon:**
```php
<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "omah_outdoor";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

---

### Tahap 3 — Setup Database

1. Pastikan **Apache** dan **MySQL** sudah **Running** (hijau) di XAMPP / Laragon
2. Buka browser → ketik `http://localhost/phpmyadmin`
3. Klik **New** di sidebar kiri → beri nama **`omah_outdoor`** → klik **Create**
4. Klik database `omah_outdoor` yang baru dibuat
5. Klik tab **Import** di menu atas
6. Klik **Choose File** → cari file **`omah_outdoor.sql`** di folder project
7. Scroll ke bawah → klik **Go / Impor**
8. Tunggu sampai muncul notif hijau "Import has been successfully finished"

---

### Tahap 4 — Coba Jalankan

Buka browser → ketik:
```
http://localhost/OMAHOUTDOOR/login.php
```

**Akun Admin (untuk testing):**
| Field | Value |
|-------|-------|
| Email | `admin123@gmail.com` |
| Password | `admin123` |

Kalau bisa login dan masuk ke dashboard → **setup berhasil!** ✅

---

## 🔄 SETIAP KALI MAU NGODING

Sebelum mulai ngoding, **wajib** pull dulu biar dapat update terbaru:

```
git pull origin main
```

> Lakukan ini setiap kali buka laptop dan mau lanjut ngerjain!

---

## 📤 SETELAH SELESAI NGODING

Kalau sudah selesai bikin / edit fitur, push ke GitHub:

```
git add .
git commit -m "tulis apa yang kamu kerjain"
git push origin main
```

> ⚠️ **Jangan push `koneksi.php`!** File itu sudah masuk `.gitignore` jadi otomatis tidak ikut.

---

## ❓ TROUBLESHOOTING — Error Umum

| Error | Penyebab | Solusi |
|-------|----------|--------|
| Halaman tidak bisa dibuka | Apache/MySQL belum jalan | Start Apache & MySQL di XAMPP/Laragon |
| "Koneksi gagal" / blank page | `koneksi.php` belum ada atau password salah | Buat ulang `koneksi.php` sesuai panduan Tahap 2 |
| Login selalu gagal / error DB | Database belum diimport atau struktur lama | Hapus DB lama, buat ulang, import `omah_outdoor.sql` terbaru |
| Port 80 bentrok | Ada aplikasi lain pakai port 80 | Ganti port Apache ke 8080 di settings XAMPP/Laragon, akses via `localhost:8080` |
| `git pull` bentrok / conflict | Ada file yang sama diedit berdua | Hubungi Zidan untuk resolve conflict |

---

## 📁 Struktur File Project

```
OMAHOUTDOOR/
├── assets/
│   ├── css/
│   │   ├── style.css       → CSS halaman utama
│   │   ├── admin.css       → CSS dashboard admin
│   │   └── auth.css        → CSS halaman login
│   ├── images/             → Foto produk
│   └── js/
│       └── script.js       → JavaScript
├── index.php               → Halaman beranda
├── login.php               → Halaman login & daftar
├── katalog.php             → Halaman katalog produk
├── keunggulan.php          → Halaman keunggulan
├── logout.php              → Proses logout
├── proses_login.php        → Backend login
├── proses_daftar.php       → Backend daftar akun
├── proses_produk.php       → Backend kelola produk (admin)
├── dashboard_admin.php     → Dashboard admin
├── kelola_produk.php       → Halaman kelola produk (admin)
├── cek_admin.php           → Guard: hanya admin yang bisa akses
├── cek_login.php           → Guard: hanya yang sudah login
├── koneksi.php             → ⚠️ TIDAK ADA DI GIT, buat sendiri!
└── omah_outdoor.sql        → File database, import ke phpMyAdmin
```

---

## 👥 Developed by Kelompok 6

- Zidan Maulana
- Bintang Putra Dinata
- Muhammad Rasyid Murtadho
- Arif Maulana Prastiya
- Gesa Santiko Gumelar
---
<p align="center">
  &copy; 2026 Omah Outdoor - Kelompok 6. All Rights Reserved.
</p>