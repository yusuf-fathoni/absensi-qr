<?php
$page_title = 'Dashboard Admin';
$role = 'admin';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/siswa.php';
require_once __DIR__ . '/../functions/kelas.php';

$db = getDBConnection();
$today = date('Y-m-d');

$totalSiswa = getTotalSiswa();
$totalKelas = getTotalKelas();

$stmt = $db->prepare("SELECT status, COUNT(*) as jumlah FROM absensi WHERE tanggal = ? GROUP BY status");
$stmt->execute([$today]);
$absensiHari = $stmt->fetchAll();
$absensiHari = array_column($absensiHari, 'jumlah', 'status');
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Total Siswa</p>
        <p class="text-2xl font-bold text-gray-800"><?= $totalSiswa ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Total Kelas</p>
        <p class="text-2xl font-bold text-gray-800"><?= $totalKelas ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Hadir Hari Ini</p>
        <p class="text-2xl font-bold text-green-600"><?= $absensiHari['Hadir'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Terlambat</p>
        <p class="text-2xl font-bold text-yellow-600"><?= $absensiHari['Terlambat'] ?? 0 ?></p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Izin</p>
        <p class="text-2xl font-bold text-blue-600"><?= $absensiHari['Izin'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Sakit</p>
        <p class="text-2xl font-bold text-red-600"><?= $absensiHari['Sakit'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-500">Alpa</p>
        <p class="text-2xl font-bold text-gray-600"><?= $absensiHari['Alpa'] ?? 0 ?></p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mt-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="/admin/absensi/scan.php" class="block p-4 bg-cyan-50 rounded-lg hover:bg-cyan-100 transition">
            <p class="font-medium text-cyan-800">Scan Absensi</p>
            <p class="text-sm text-cyan-600">Absensi siswa lewat QR code</p>
        </a>
        <a href="/admin/absensi/hari_ini.php" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <p class="font-medium text-green-800">Kehadiran Hari Ini</p>
            <p class="text-sm text-green-600">Lihat & ubah absensi hari ini</p>
        </a>
        <a href="/admin/absensi/pengajuan.php" class="block p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
            <p class="font-medium text-yellow-800">Pengajuan Izin/Sakit</p>
            <p class="text-sm text-yellow-600">Setujui atau tolak pengajuan siswa</p>
        </a>
        <a href="/admin/absensi/rekap.php" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <p class="font-medium text-blue-800">Rekap Bulanan</p>
            <p class="text-sm text-blue-600">Rekap kehadiran semua siswa</p>
        </a>
        <a href="/admin/siswa/tambah.php" class="block p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
            <p class="font-medium text-purple-800">Tambah Siswa</p>
            <p class="text-sm text-purple-600">Daftarkan siswa baru</p>
        </a>
        <a href="/admin/kelas/tambah.php" class="block p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition">
            <p class="font-medium text-pink-800">Tambah Kelas</p>
            <p class="text-sm text-pink-600">Buat kelas baru</p>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>