*PANDUAN LANJUTIN PROJECT OMAH OUTDOOR (Buat Anggota Kelompok)*

Tahap 1: Tarik Kodingan ke XAMPP

1.Buka folder C:\xampp\htdocs\ di laptop kalian.

2.Klik kanan di area kosong, pilih Open Git Bash here.

3.Ketik perintah ini buat narik semua file dari GitHub:
*git clone https://github.com/zeedann1717/OMAHOUTDOOR.git*

4.Nanti bakal otomatis muncul folder OMAHOUTDOOR di dalem htdocs kalian.

Tahap 2: Nyalain Database (PENTING!)
Kodingan PHP nggak bakal jalan kalau database-nya nggak dipasang.

1.Buka aplikasi XAMPP Control Panel, klik Start pada Apache dan MySQL (sampai hijau).

2.Buka browser, ketik alamat: localhost/phpmyadmi.

3.Bikin database baru (klik menu "New" di kiri), kasih nama persis: omah_outdoor, lalu klik Buat/Create.

4.Klik database omah_outdoor yang barusan dibikin.

5.Liat menu di atas, klik tab Impor (sebelahnya Ekspor).

6.Klik tombol Choose File (Pilih File), terus cari dan masukin file omah_outdoor.sql yang ada di dalem folder project hasil download-an tadi.

7.Scroll mentok ke bawah, klik tombol Impor (atau Go).


Tahap 3: Testing & Lanjut Ngoding

1.Buka browser, ketik: localhost/OMAHOUTDOOR/login.php

2.Coba tes daftar akun atau login. Kalau masuk, berarti laptop kalian udah sukses terhubung ke database lokal!

3.Sekarang kalian bisa buka foldernya di VS Code dan lanjutin ngerjain bagian masing-masing.

Tiap kali baru buka laptop mau ngelanjutin tugas, WAJIB buka Git Bash di dalem folder OMAHOUTDOOR dan ketik:
*git pull origin main*
Ini biar kodingan kalian selalu dapet update terbaru dari yang lain, jadi kodingannya nggak tabrakan/bentrok!
