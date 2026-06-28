<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Daftar | Omah Outdoor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="auth-container">
        <div class="auth-box">

            <div class="auth-tabs">
                <button id="tab-login" class="tab-btn active" onclick="switchForm('login')">Login</button>
                <button id="tab-register" class="tab-btn" onclick="switchForm('register')">Daftar</button>
            </div>

            <div class="auth-header">
                <a href="index.php" class="logo-back">🏕️ OMAH <span>OUTDOOR</span></a>
                <h2 id="auth-title">Selamat Datang Kembali</h2>
                <p id="auth-desc">Silakan masuk untuk mulai menyewa perlengkapan.</p>
            </div>

            <!-- FORM LOGIN -->
            <form id="form-login" class="auth-form" action="proses_login.php" method="POST">
                <div class="input-group">
                    <label for="email-login">Email</label>
                    <input type="email" id="email-login" name="email" placeholder="Contoh: 123@email.com" required>
                </div>
                <div class="input-group">
                    <label for="password-login">Password</label>
                    <input type="password" id="password-login" name="password" placeholder="Masukkan password" required>
                    <!-- Checkbox Lihat Password untuk Login -->
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="show-pass-login" onclick="lihatPassword('password-login')" style="width: auto; cursor: pointer;">
                        <label for="show-pass-login" style="font-size: 14px; cursor: pointer;"> Tampilkan Password</label>
                    </div>
                </div>
                <div class="form-options">
                    <label><input type="checkbox" style="width: auto;"> Ingat Saya</label>
                    <a href="#">Lupa Password?</a>
                </div>
                <button type="submit" class="btn-auth">Masuk Sekarang</button>
            </form>

            <!-- FORM DAFTAR -->
            <form id="form-register" class="auth-form hidden" action="proses_daftar.php" method="POST">
                <div class="input-group">
                    <label for="name-reg">Nama Lengkap</label>
                    <input type="text" id="name-reg" name="nama" placeholder="Nama sesuai KTP" required>
                </div>
                <div class="input-group">
                    <label for="email-reg">Email</label>
                    <input type="email" id="email-reg" name="email" placeholder="123@email.com" required>
                </div>
                <div class="input-group">
                    <label for="phone-reg">Nomor WhatsApp</label>
                    <input type="tel" id="phone-reg" name="no_wa" placeholder="0812xxxx" required>
                </div>
                <div class="input-group">
                    <label for="password-reg">Password Baru</label>
                    <input type="password" id="password-reg" name="password" placeholder="Minimal 8 karakter" required>
                    <!-- Checkbox Lihat Password untuk Daftar -->
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="show-pass-reg" onclick="lihatPassword('password-reg')" style="width: auto; cursor: pointer;">
                        <label for="show-pass-reg" style="font-size: 14px; cursor: pointer;"> Tampilkan Password</label>
                    </div>
                </div>
                <button type="submit" class="btn-auth btn-register">Buat Akun</button>
            </form>

            <div class="auth-footer">
                <p>Kembali ke <a href="index.php">Beranda</a></p>
            </div>
        </div>
    </div>
    <script src="assets/js/script.js?v=1"></script>
</body>
</html>
