// ============================================================
// HAMBURGER MENU — Toggle mobile navigation
// ============================================================
function toggleMenu() {
    const navLinks = document.querySelector('.nav-links');
    const hamburger = document.querySelector('.hamburger');
    if (!navLinks || !hamburger) return;
    navLinks.classList.toggle('open');
    hamburger.classList.toggle('active');
    document.body.classList.toggle('menu-open');
}

// Tutup menu saat klik di luar navbar
document.addEventListener('click', function (e) {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelector('.nav-links');
    const hamburger = document.querySelector('.hamburger');
    if (!navbar || !navLinks || !hamburger) return;
    if (!navbar.contains(e.target)) {
        navLinks.classList.remove('open');
        hamburger.classList.remove('active');
        document.body.classList.remove('menu-open');
    }
});

// Tutup menu saat klik salah satu link
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.nav-links a').forEach(function (link) {
        link.addEventListener('click', function () {
            document.querySelector('.nav-links')?.classList.remove('open');
            document.querySelector('.hamburger')?.classList.remove('active');
            document.body.classList.remove('menu-open');
        });
    });
});

// =========================================
// NAVBAR: Scroll-aware color change
// Khusus index.php: transparan → solid saat scroll
// =========================================
(function () {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    if (navbar.classList.contains('navbar-hero')) {
        window.addEventListener('scroll', function () {
            // Tutup hamburger menu saat user scroll (mencegah menu floating)
            const navLinks = document.querySelector('.nav-links');
            const hamburger = document.querySelector('.hamburger');
            if (navLinks && navLinks.classList.contains('open')) {
                navLinks.classList.remove('open');
                hamburger?.classList.remove('active');
                document.body.classList.remove('menu-open');
            }

            if (window.scrollY > 80) {
                navbar.classList.remove('navbar-hero');
            } else {
                navbar.classList.add('navbar-hero');
            }
        });
    }
})();

// Data konten untuk Modal
const infoDetail = {
    katalog:
        '<h3>📦 Katalog & Stok</h3><p>Data stok kami diperbarui setiap detik. Jika di website tertera "Tersedia", maka barang pasti ada di gudang kami siap untuk Anda sewa.</p>',
    jadwal: '<h3>📅 Booking System</h3><p>Pilih tanggal mulai dan selesai sewa. Sistem akan otomatis memvalidasi jadwal agar tidak terjadi bentrok dengan penyewa lain.</p>',
    automasi:
        '<h3>⚙️ Sistem Otomatis</h3><p>Invoice akan dikirimkan otomatis ke akun Anda. Denda keterlambatan juga akan terhitung secara transparan oleh sistem kami.</p>',
    pickup: '<h3>⚡ Layanan Express</h3><p>Fitur ini memungkinkan Anda melakukan transaksi sepenuhnya secara online. Datang ke toko hanya untuk verifikasi fisik dan serah terima alat.</p>',
};

// Fungsi membuka Modal
function openModal(key) {
    const modal = document.getElementById('featureModal');
    const modalBody = document.getElementById('modal-body');

    if (infoDetail[key]) {
        modalBody.innerHTML = infoDetail[key];
        modal.style.display = 'flex';
    }
}

// Fungsi menutup Modal
function closeModal() {
    document.getElementById('featureModal').style.display = 'none';
}

// Menutup modal jika user klik di luar area konten modal
window.addEventListener('click', function (event) {
    const modal = document.getElementById('featureModal');
    if (event.target == modal) {
        closeModal();
    }
});

// Menutup modal jika tombol Escape ditekan
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

/**
 * Mengatur pergantian antarmuka antara formulir Login dan Registrasi.
 * @param {string} type - Menentukan target form ('login' atau 'register').
 */
function switchForm(type) {
    const loginForm = document.getElementById('form-login');
    const regForm = document.getElementById('form-register');
    const loginTab = document.getElementById('tab-login');
    const regTab = document.getElementById('tab-register');
    const title = document.getElementById('auth-title');
    const desc = document.getElementById('auth-desc');

    // Cek dulu apakah elemen-elemen ini ada di halaman (biar nggak error di halaman lain)
    if (loginForm && regForm) {
        if (type === 'login') {
            loginForm.classList.remove('hidden');
            regForm.classList.add('hidden');
            loginTab.classList.add('active');
            regTab.classList.remove('active');
            title.innerText = 'Selamat Datang Kembali';
            desc.innerText = 'Silakan masuk untuk mulai menyewa perlengkapan.';
        } else {
            loginForm.classList.add('hidden');
            regForm.classList.remove('hidden');
            loginTab.classList.remove('active');
            regTab.classList.add('active');
            title.innerText = 'Gabung Omah Outdoor';
            desc.innerText = 'Daftar sekarang untuk kemudahan booking alat.';
        }
    }
}

// Fungsi Tampilkan/Sembunyikan Password
function lihatPassword(idKotak) {
    var kotakPassword = document.getElementById(idKotak);
    // Cek dulu apakah kotak passwordnya ada
    if (kotakPassword) {
        if (kotakPassword.type === 'password') {
            kotakPassword.type = 'text';
        } else {
            kotakPassword.type = 'password';
        }
    }
}
