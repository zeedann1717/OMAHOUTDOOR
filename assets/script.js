// Data konten untuk Modal
const infoDetail = {
    'katalog': '<h3>📦 Katalog & Stok</h3><p>Data stok kami diperbarui setiap detik. Jika di website tertera "Tersedia", maka barang pasti ada di gudang kami siap untuk Anda sewa.</p>',
    'jadwal': '<h3>📅 Booking System</h3><p>Pilih tanggal mulai dan selesai sewa. Sistem akan otomatis memvalidasi jadwal agar tidak terjadi bentrok dengan penyewa lain.</p>',
    'automasi': '<h3>⚙️ Sistem Otomatis</h3><p>Invoice akan dikirimkan otomatis ke akun Anda. Denda keterlambatan juga akan terhitung secara transparan oleh sistem kami.</p>',
    'pickup': '<h3>⚡ Layanan Express</h3><p>Fitur ini memungkinkan Anda melakukan transaksi sepenuhnya secara online. Datang ke toko hanya untuk verifikasi fisik dan serah terima alat.</p>'
};

// Fungsi membuka Modal
function openModal(key) {
    const modal = document.getElementById('featureModal');
    const modalBody = document.getElementById('modal-body');
    
    if (infoDetail[key]) {
        modalBody.innerHTML = infoDetail[key];
        modal.style.display = "flex";
    }
}

// Fungsi menutup Modal
function closeModal() {
    document.getElementById('featureModal').style.display = "none";
}

// Menutup modal jika user klik di luar area konten modal
window.onclick = function(event) {
    const modal = document.getElementById('featureModal');
    if (event.target == modal) {
        closeModal();
    }
};