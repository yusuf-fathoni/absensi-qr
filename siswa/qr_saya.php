<?php
$page_title = 'QR Saya';
$role = 'siswa';
require_once __DIR__ . '/../includes/auth.php';
requireSiswa();
require_once __DIR__ . '/../functions/siswa.php';
require_once __DIR__ . '/../functions/qr.php';

$siswa = getSiswaByUserId($_SESSION['user_id']);
if (!$siswa) {
    die("Data siswa tidak ditemukan");
}

$qrDataUrl = getQRDataUrl($siswa['qr_token']);
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-6 text-center">QR Absensi Saya</h1>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
        <p class="text-gray-500 text-sm mb-4">Tunjukkan QR ini kepada admin saat absensi</p>

        <div class="flex justify-center mb-6">
            <img src="<?= $qrDataUrl ?>" alt="QR Code Absensi" class="border border-gray-100 rounded-xl shadow-sm" width="250" height="250">
        </div>

        <div class="text-left space-y-3 bg-gray-50 rounded-xl p-4 text-sm">
            <div class="flex justify-between gap-4"><span class="text-gray-500">Nama</span><span class="font-semibold text-gray-800"><?= htmlspecialchars($siswa['nama']) ?></span></div>
            <div class="flex justify-between gap-4"><span class="text-gray-500">NIS</span><span class="font-semibold text-gray-800"><?= htmlspecialchars($siswa['nis']) ?></span></div>
            <div class="flex justify-between gap-4"><span class="text-gray-500">Kelas</span><span class="font-semibold text-gray-800"><?= htmlspecialchars($siswa['nama_kelas']) ?></span></div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>