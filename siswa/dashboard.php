<?php
$page_title = 'Dashboard Siswa';
$role = 'siswa';
require_once __DIR__ . '/../includes/auth.php';
requireSiswa();
require_once __DIR__ . '/../functions/siswa.php';
require_once __DIR__ . '/../functions/absensi.php';

$siswa = getSiswaByUserId($_SESSION['user_id']);
if (!$siswa) {
    die("Data siswa tidak ditemukan");
}

$bulan = date('Y-m');
$absensiBulan = getAbsensiSiswa($siswa['id'], 30);
$rekap = [];
foreach ($absensiBulan as $a) {
    if (substr($a['tanggal'], 0, 7) === $bulan) {
        $rekap[$a['status']] = ($rekap[$a['status']] ?? 0) + 1;
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500">Selamat datang, <?= htmlspecialchars($siswa['nama']) ?></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Data Pribadi</h2>
        <div class="space-y-2">
            <p><span class="font-medium">NIS:</span> <?= htmlspecialchars($siswa['nis']) ?></p>
            <p><span class="font-medium">Nama:</span> <?= htmlspecialchars($siswa['nama']) ?></p>
            <p><span class="font-medium">Kelas:</span> <?= htmlspecialchars($siswa['nama_kelas']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Absensi Bulan Ini</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600"><?= $rekap['Hadir'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Hadir</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600"><?= $rekap['Terlambat'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Terlambat</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600"><?= $rekap['Izin'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Izin</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600"><?= $rekap['Sakit'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Sakit</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="/siswa/qr_saya.php" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <p class="font-medium text-blue-800">QR Saya</p>
            <p class="text-sm text-blue-600">Lihat QR Code absensi</p>
        </a>
        <a href="/siswa/riwayat.php" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <p class="font-medium text-green-800">Riwayat Absensi</p>
            <p class="text-sm text-green-600">Lihat riwayat kehadiran</p>
        </a>
        <a href="/siswa/pengajuan.php" class="block p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
            <p class="font-medium text-yellow-800">Ajukan Izin/Sakit</p>
            <p class="text-sm text-yellow-600">Ajukan izin atau sakit untuk hari ini</p>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>