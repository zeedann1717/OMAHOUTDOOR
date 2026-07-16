<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

$user_id = (int) $_SESSION['id'];
$res     = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
$user    = mysqli_fetch_assoc($res);

$pesan = $_GET['pesan'] ?? '';
$notif = ''; $notif_class = '';
if ($pesan === 'profil_sukses')     { $notif = '✅ Profil berhasil diperbarui!';          $notif_class = 'notif-sukses'; }
elseif ($pesan === 'nama_kosong')   { $notif = '⚠️ Nama tidak boleh kosong!';              $notif_class = 'notif-gagal'; }
elseif ($pesan === 'pass_sukses')   { $notif = '✅ Password berhasil diganti!';             $notif_class = 'notif-sukses'; }
elseif ($pesan === 'pass_lama_salah')   { $notif = '❌ Password lama salah!';              $notif_class = 'notif-gagal'; }
elseif ($pesan === 'pass_terlalu_pendek') { $notif = '❌ Password baru minimal 6 karakter!'; $notif_class = 'notif-gagal'; }
elseif ($pesan === 'pass_tidak_cocok')  { $notif = '❌ Konfirmasi password tidak cocok!';  $notif_class = 'notif-gagal'; }

$initial = strtoupper(substr($user['nama'] ?? 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Omah Outdoor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
        <ul class="nav-links">
            <li><a href="index.php">Beranda</a></li>
            
            <!-- LOGIKA HAK AKSES NAVBAR -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                <!-- Menu Khusus Admin: Katalog & Riwayat disembunyikan -->
                <li><a href="profil.php" class="active">👤 Profil</a></li>
                <li><a href="dashboard_admin.php" class="btn-login" style="background-color: #e7c56a; color: #1b4332; font-weight: bold; border-radius: 8px; padding: 6px 12px;">🛠️ Dashboard Admin</a></li>
            <?php else : ?>
                <!-- Menu Khusus User Biasa -->
                <li><a href="katalog.php">Katalog</a></li>
                <li><a href="riwayat_order.php">Riwayat Order</a></li>
                <li><a href="profil.php" class="active">👤 Profil</a></li>
            <?php endif; ?>
            
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
        <button class="hamburger" onclick="toggleMenu()" aria-label="Toggle Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="profil-container">

    <?php if ($notif) : ?>
    <div class="profil-notif <?= $notif_class ?>"><?= $notif ?></div>
    <?php endif; ?>

    <!-- HEADER PROFIL -->
    <div class="profil-hero">
        <div class="profil-avatar"><?= $initial ?></div>
        <div class="profil-hero-info">
            <h1><?= htmlspecialchars($user['nama'] ?? '-') ?></h1>
            <p><?= htmlspecialchars($user['email']) ?></p>
            <span class="profil-role <?= $user['role'] === 'admin' ? 'role-admin' : 'role-user' ?>">
                <?= $user['role'] === 'admin' ? '👑 Administrator' : '🙋 User' ?>
            </span>
        </div>
    </div>

    <!-- RESPONSIVE GRID LAYOUT -->
    <div class="profil-grid" style="grid-template-columns: <?= $user['role'] === 'admin' ? '1fr 1fr' : '1fr 1fr' ?>;">

        <!-- FORM EDIT PROFIL -->
        <div class="profil-card">
            <h2 class="profil-card-title">✏️ Edit Profil</h2>
            <form action="proses_profil.php" method="POST">
                <input type="hidden" name="action" value="update_profil">
                <div class="profil-form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required placeholder="Nama lengkap kamu">
                </div>
                <div class="profil-form-group">
                    <label>Email</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled title="Email tidak bisa diubah">
                    <small>Email tidak dapat diubah</small>
                </div>
                <div class="profil-form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="tel" name="no_wa" value="<?= htmlspecialchars($user['no_wa'] ?? '') ?>" placeholder="Contoh: 08123456789">
                </div>
                <button type="submit" class="profil-btn-save">💾 Simpan Perubahan</button>
            </form>
        </div>

        <!-- FORM GANTI PASSWORD -->
        <div class="profil-card" id="ganti-password">
            <h2 class="profil-card-title">🔒 Ganti Password</h2>
            <form action="proses_profil.php" method="POST">
                <input type="hidden" name="action" value="ganti_password">
                <div class="profil-form-group">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" required placeholder="Masukkan password saat ini">
                </div>
                <div class="profil-form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" required placeholder="Minimal 6 karakter">
                </div>
                <div class="profil-form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_konfirm" required placeholder="Ulangi password baru">
                </div>
                <button type="submit" class="profil-btn-save" style="background:#2d6a4f;">🔑 Ganti Password</button>
            </form>
        </div>

        <!-- KONDISIONAL: KARTU INFO AKUN HANYA UNTUK USER BIASA -->
        <?php if ($user['role'] !== 'admin') : ?>
            <div class="profil-card" style="grid-column: span 2;">
                <h2 class="profil-card-title">📊 Info Akun</h2>
                <?php
                $res_order = mysqli_query($conn, "SELECT status, COUNT(*) as total FROM orders WHERE user_id=$user_id GROUP BY status");
                $o = ['pending'=>0,'dikonfirmasi'=>0,'selesai'=>0,'dibatalkan'=>0];
                while($r = mysqli_fetch_assoc($res_order)) $o[$r['status']] = $r['total'];
                $total_order_user = array_sum($o);
                ?>
                <div class="profil-info-list" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                    <div class="profil-info-item">
                        <span class="profil-info-label">📅 Bergabung</span>
                        <span class="profil-info-val"><?= date('d M Y', strtotime($user['created_at'])) ?></span>
                    </div>
                    <div class="profil-info-item">
                        <span class="profil-info-label">🛒 Total Order</span>
                        <span class="profil-info-val"><?= $total_order_user ?></span>
                    </div>
                    <div class="profil-info-item">
                        <span class="profil-info-label">⏳ Menunggu</span>
                        <span class="profil-info-val"><?= $o['pending'] ?></span>
                    </div>
                    <div class="profil-info-item">
                        <span class="profil-info-label">🎉 Selesai</span>
                        <span class="profil-info-val"><?= $o['selesai'] ?></span>
                    </div>
                </div>
                <a href="riwayat_order.php" class="profil-btn-riwayat" style="margin-top: 15px;">📜 Lihat Riwayat Order</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.profil-container { max-width: 1000px; margin: 0 auto; padding: 100px 20px 60px; }

.profil-notif {
    padding: 14px 20px; border-radius: 10px;
    margin-bottom: 24px; font-weight: 600; font-size: 14px;
}
.notif-sukses { background:#d1fae5; color:#065f46; }
.notif-gagal  { background:#fee2e2; color:#991b1b; }

.profil-hero {
    background: linear-gradient(135deg, #1b4332, #2d6a4f);
    border-radius: 20px; padding: 32px;
    display: flex; align-items: center; gap: 24px;
    margin-bottom: 28px; color: white;
}
.profil-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    background: #95d5b2; color: #1b4332;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 700;
    flex-shrink: 0;
}
.profil-hero-info h1 { font-family:'Poppins',sans-serif; font-size:22px; margin-bottom:4px; }
.profil-hero-info p  { font-size:14px; opacity:.85; margin-bottom:10px; }
.profil-role {
    padding: 4px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
}
.role-admin { background:#fef3c7; color:#92400e; }
.role-user  { background:#dbeafe; color:#1e40af; }

.profil-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.profil-card {
    background: #fff; border-radius: 16px;
    padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,.06);
}

.profil-card-title {
    font-family: 'Poppins', sans-serif;
    font-size: 17px; color: #1b4332;
    margin-bottom: 22px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}

.profil-form-group { margin-bottom: 16px; }
.profil-form-group label {
    display: block; font-size: 12px; font-weight: 700;
    color: #64748b; text-transform: uppercase;
    letter-spacing: .4px; margin-bottom: 6px;
}
.profil-form-group input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    font-size: 14px; font-family: 'Inter', sans-serif;
    outline: none; transition: border-color .2s;
    background: #fafafa;
}
.profil-form-group input:focus {
    border-color: #2d6a4f; background: #fff;
}
.profil-form-group input:disabled {
    background: #f1f5f9; color: #94a3b8; cursor: not-allowed;
}
.profil-form-group small { display:block; font-size:11px; color:#94a3b8; margin-top:4px; }

.profil-btn-save {
    width: 100%; padding: 12px;
    background: #1b4332; color: #fff;
    border: none; border-radius: 10px;
    font-size: 14px; font-weight: 700;
    font-family: 'Poppins', sans-serif;
    cursor: pointer; transition: background .2s, transform .2s;
    margin-top: 8px;
}
.profil-btn-save:hover { background: #2d6a4f; transform: translateY(-2px); }

.profil-info-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
.profil-info-item {
    display: flex; justify-content: space-between;
    align-items: center; padding: 10px 14px;
    background: #f8fafc; border-radius: 8px;
    font-size: 14px;
}
.profil-info-label { color: #64748b; font-weight: 500; }
.profil-info-val   { font-weight: 700; color: #1b4332; }

.profil-btn-riwayat {
    display: block; text-align: center;
    padding: 11px; background: #f0fdf4;
    color: #1b4332; border-radius: 10px;
    font-weight: 700; font-size: 14px;
    text-decoration: none; transition: background .2s;
}
.profil-btn-riwayat:hover { background: #d1fae5; }

@media (max-width: 768px) {
    .profil-grid { grid-template-columns: 1fr !important; }
    .profil-hero { flex-direction: column; text-align: center; }
    .profil-info-list { grid-template-columns: 1fr !important; }
}
</style>

<script src="assets/js/script.js"></script>
</body>
</html>