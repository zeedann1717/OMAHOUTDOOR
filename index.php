<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omah Outdoor | Sewa Alat Mudah & Cepat</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --primary: #1b4332;
            --primary-light: #2d6a4f;
            --accent: #e67e22;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #fafafa;
            color: var(--text-main);
            overflow-x: hidden;
        }

        .container {
            width: 100%; max-width: 1200px;
            margin: 0 auto; padding: 0 24px;
        }

        /* === MODERN GLASS NAVBAR === */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0;
            background: rgba(27, 67, 50, 0.85);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 18px 0; z-index: 1000; transition: var(--transition);
        }

        .nav-wrapper {
            display: flex; justify-content: space-between; align-items: center;
        }

        .logo {
            font-size: 20px; font-weight: 800; color: var(--white);
            text-decoration: none; letter-spacing: 0.5px;
            display: flex; align-items: center; gap: 8px;
        }
        .logo span { color: #52b788; }

        .nav-links {
            display: flex; align-items: center; gap: 24px; list-style: none;
        }
        .nav-links a {
            color: rgba(255, 255, 255, 0.8); text-decoration: none;
            font-size: 14px; font-weight: 600; transition: var(--transition);
        }
        .nav-links a:hover { color: var(--white); }

        /* Button Auth Styles */
        .btn-nav-login {
            background: rgba(255, 255, 255, 0.15); color: var(--white) !important;
            padding: 10px 20px; border-radius: var(--radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-nav-login:hover { background: rgba(255, 255, 255, 0.25); }

        .btn-nav-admin {
            background: linear-gradient(135deg, var(--accent), #d35400);
            color: var(--white) !important; padding: 10px 20px;
            border-radius: var(--radius); box-shadow: 0 4px 12px rgba(230, 126, 34, 0.3);
        }
        .btn-nav-admin:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(230, 126, 34, 0.4); }

        .btn-nav-logout {
            color: #ff6b6b !important; padding: 10px 14px; font-weight: 700 !important;
        }

        /* === HERO CINEMATIC SECTION WITH NEW IMAGE === */
        .hero {
            position: relative; min-height: 100vh;
            /* Menggunakan foto pilihan lo yang disimpan secara lokal */
            background: linear-gradient(rgba(11, 30, 20, 0.5), rgba(11, 30, 20, 0.8)), 
                        url('assets/images/outdoor.jpg') center/cover no-repeat;
            display: flex; align-items: center; padding-top: 80px; text-align: center;
        }

        .hero-content {
            max-width: 760px; margin: 0 auto; color: var(--white);
            display: flex; flex-direction: column; align-items: center; gap: 24px;
        }

        .badge {
            background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 700;
            letter-spacing: 0.5px; text-transform: uppercase; color: #52b788;
            backdrop-filter: blur(4px);
        }

        .hero-content h1 {
            font-size: 52px; font-weight: 800; line-height: 1.2;
            letter-spacing: -1px; text-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .hero-content p {
            font-size: 16px; line-height: 1.6; color: rgba(255, 255, 255, 0.85);
            font-weight: 400; max-width: 640px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-buttons {
            display: flex; gap: 16px; margin-top: 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2d6a4f, #1b4332);
            color: var(--white); text-decoration: none; padding: 16px 36px;
            font-size: 15px; font-weight: 700; border-radius: var(--radius);
            box-shadow: 0 4px 14px rgba(27, 67, 50, 0.4); transition: var(--transition);
        }
        .btn-primary:hover {
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(27, 67, 50, 0.6);
        }

        .btn-admin-hero {
            background: linear-gradient(135deg, var(--accent), #d35400) !important;
            box-shadow: 0 4px 14px rgba(230, 126, 34, 0.4) !important;
        }
        .btn-admin-hero:hover {
            box-shadow: 0 6px 20px rgba(230, 126, 34, 0.6) !important;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.15); color: var(--white);
            text-decoration: none; padding: 16px 36px; font-size: 15px;
            font-weight: 600; border-radius: var(--radius);
            border: 1px solid rgba(255, 255, 255, 0.25); transition: var(--transition);
            backdrop-filter: blur(4px);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25); color: var(--white);
        }

        /* === MINIMALIST FOOTER === */
        .footer {
            background: #0b1e14; padding: 60px 0 30px; color: #94a3b8;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .footer-content {
            display: grid; grid-template-columns: 2fr 1fr; gap: 60px;
            padding-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .footer-brand h3 { color: var(--white); font-size: 20px; font-weight: 700; margin-bottom: 12px; }
        .footer-brand p { font-size: 14px; line-height: 1.6; max-width: 400px; }
        
        .footer-team h4 { color: var(--white); font-size: 15px; font-weight: 700; margin-bottom: 16px; }
        .footer-team ul { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .footer-team li { font-size: 13px; font-weight: 500; }

        .footer-bottom { text-align: center; padding-top: 30px; font-size: 13px; color: #475569; }

        @media (max-width: 768px) {
            .hero-content h1 { font-size: 36px; }
            .nav-links { display: none; }
            .footer-content { grid-template-columns: 1fr; gap: 32px; }
        }
    </style>
</head>
<body>

    <!-- Navbar Area -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <?php if(isset($_SESSION['email'])) : ?>
                    
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li><a href="dashboard_admin.php" class="btn-nav-admin"><i class="fa-solid fa-screwdriver-wrench"></i> Dashboard Admin</a></li>
                    <?php else : ?>
                        <li><a href="keunggulan.php">Keunggulan</a></li>
                        <li><a href="katalog.php">Katalog</a></li>
                        <li><a href="riwayat_order.php">Riwayat Order</a></li>
                        <li><a href="profil.php" class="btn-nav-login"><i class="fa-regular fa-user"></i> Profil</a></li>
                    <?php endif; ?>
                    
                    <li><a href="logout.php" class="btn-nav-logout">Logout</a></li>
                <?php else : ?>
                    <li><a href="keunggulan.php">Keunggulan</a></li>
                    <li><a href="katalog.php">Katalog</a></li>
                    <li><a href="login.php" class="btn-nav-login">Login / Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Area Cover -->
    <header class="hero">
        <div class="container">
            <div class="hero-content">
                <span class="badge">🌲 Petualangan Modern Dimulai</span>
                <h1>Sewa Perlengkapan Outdoor Praktis & Efisien</h1>
                <p>Tinggalkan cara manual! Omah Outdoor hadir untuk memudahkan pengalaman sewa alat camping Anda tanpa ribet, tanpa antre, dan bebas double booking.</p>
                
                <div class="hero-buttons">
                    <?php if(isset($_SESSION['email']) && $_SESSION['role'] === 'admin') : ?>
                        <a href="dashboard_admin.php" class="btn-primary btn-admin-hero"><i class="fa-solid fa-gauge"></i> Masuk Dashboard</a>
                    <?php else : ?>
                        <a href="katalog.php" class="btn-primary">Lihat Katalog</a>
                    <?php endif; ?>
                    <a href="keunggulan.php" class="btn-secondary">Cara Kerja</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Footer Area -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>OMAH OUTDOOR</h3>
                    <p>Digitalisasi usaha rental alat outdoor untuk pengalaman petualangan alam yang lebih tertata dan profesional.</p>
                </div>
                <div class="footer-team">
                    <h4>Developed by Kelompok 6</h4>
                    <ul>
                        <li>Zidan Maulana</li>
                        <li>Bintang Putra D</li>
                        <li>M Rasyid Murtado</li>
                        <li>Arif Maulana</li>
                        <li>Gesa Santiko G</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Omah Outdoor - Tugas Kelompok. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>