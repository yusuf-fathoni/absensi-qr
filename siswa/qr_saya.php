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
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">QR Absensi Saya</h1>

    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500 mb-4">Tunjukkan QR ini kepada admin saat absensi</p>

        <div class="flex justify-center mb-6">
            <img src="<?= $qrDataUrl ?>" alt="QR Code Absensi" class="border rounded-lg" width="250" height="250">
        </div>

        <div class="text-left space-y-2 bg-gray-50 rounded-lg p-4">
            <p><span class="font-medium text-gray-700">Nama:</span> <?= htmlspecialchars($siswa['nama']) ?></p>
            <p><span class="font-medium text-gray-700">NIS:</span> <?= htmlspecialchars($siswa['nis']) ?></p>
            <p><span class="font-medium text-gray-700">Kelas:</span> <?= htmlspecialchars($siswa['nama_kelas']) ?></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>