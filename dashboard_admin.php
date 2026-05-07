<?php
require_once 'cek_admin.php';
require_once 'koneksi.php';

// Ambil semua user
$query_users = "SELECT * FROM users ORDER BY created_at DESC";
$result_users = mysqli_query($conn, $query_users);
$total_users = mysqli_num_rows($result_users);

// Hitung admin
$query_admin = "SELECT COUNT(*) as total FROM users WHERE role='admin'";
$result_admin = mysqli_query($conn, $query_admin);
$row_admin = mysqli_fetch_assoc($result_admin);
$total_admin = $row_admin['total'];
$total_user_biasa = $total_users - $total_admin;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">🏕️</span>
            <div>
                <h2>OMAH</h2>
                <span>OUTDOOR</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item active">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="#section-users" class="nav-item">
                <span class="nav-icon">👥</span> Data User
            </a>
            <a href="katalog.php" class="nav-item">
                <span class="nav-icon">📦</span> Lihat Katalog
            </a>
            <a href="index.php" class="nav-item">
                <span class="nav-icon">🏠</span> Ke Beranda
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-sidebar-logout">
                <span>🚪</span> Logout
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <!-- TOP BAR -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <h1 class="page-title">Dashboard Admin</h1>
                <p class="page-subtitle">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></strong> 👋</p>
            </div>
            <div class="topbar-right">
                <div class="admin-badge">
                    <span class="badge-avatar">👤</span>
                    <div>
                        <p class="badge-name"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></p>
                        <span class="badge-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- STATS CARDS -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #065f46;">👥</div>
                <div class="stat-info">
                    <p class="stat-label">Total User Terdaftar</p>
                    <h2 class="stat-value"><?= $total_users ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #92400e;">👑</div>
                <div class="stat-info">
                    <p class="stat-label">Total Admin</p>
                    <h2 class="stat-value"><?= $total_admin ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">🙋</div>
                <div class="stat-info">
                    <p class="stat-label">User Biasa</p>
                    <h2 class="stat-value"><?= $total_user_biasa ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #ffe4e6; color: #9f1239;">📦</div>
                <div class="stat-info">
                    <p class="stat-label">Total Produk</p>
                    <h2 class="stat-value">6</h2>
                </div>
            </div>
        </section>

        <!-- TABEL DATA USER -->
        <section id="section-users" class="table-section">
            <div class="table-header">
                <h2>📋 Data User Terdaftar</h2>
                <span class="table-count"><?= $total_users ?> user</span>
            </div>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. WhatsApp</th>
                            <th>Role</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        mysqli_data_seek($result_users, 0);
                        while ($user = mysqli_fetch_assoc($result_users)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="user-cell">
                                    <span class="user-avatar"><?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)) ?></span>
                                    <?= htmlspecialchars($user['nama'] ?? '-') ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['no_wa'] ?? '-') ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin') : ?>
                                    <span class="role-badge admin">👑 Admin</span>
                                <?php else : ?>
                                    <span class="role-badge user">🙋 User</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
