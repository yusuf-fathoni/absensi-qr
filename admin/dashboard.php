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

$totalRekam = (int) $db->query("SELECT COUNT(*) FROM absensi")->fetchColumn();
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard</h1>
    <p class="text-gray-500 text-sm mt-1">Ringkasan kehadiran hari ini</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Siswa</p>
        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $totalSiswa ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Kelas</p>
        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $totalKelas ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Hadir Hari Ini</p>
        <p class="text-3xl font-bold text-green-600 mt-1"><?= $absensiHari['Hadir'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Terlambat</p>
        <p class="text-3xl font-bold text-yellow-600 mt-1"><?= $absensiHari['Terlambat'] ?? 0 ?></p>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Izin</p>
        <p class="text-3xl font-bold text-blue-600 mt-1"><?= $absensiHari['Izin'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Sakit</p>
        <p class="text-3xl font-bold text-red-600 mt-1"><?= $absensiHari['Sakit'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Alpa</p>
        <p class="text-3xl font-bold text-gray-600 mt-1"><?= $absensiHari['Alpa'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs text-gray-500">Total Rekam</p>
        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $totalRekam ?></p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="/admin/absensi/scan.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Scan Absensi</span>
                <span class="block text-xs text-gray-500 mt-0.5">Absensi siswa lewat QR code</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/admin/absensi/hari_ini.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-green-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Kehadiran Hari Ini</span>
                <span class="block text-xs text-gray-500 mt-0.5">Lihat &amp; ubah absensi hari ini</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-green-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/admin/absensi/pengajuan.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-yellow-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Pengajuan Izin/Sakit</span>
                <span class="block text-xs text-gray-500 mt-0.5">Setujui atau tolak pengajuan siswa</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-yellow-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/admin/absensi/rekap.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Rekap Bulanan</span>
                <span class="block text-xs text-gray-500 mt-0.5">Rekap kehadiran semua siswa</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/admin/siswa/tambah.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-purple-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Tambah Siswa</span>
                <span class="block text-xs text-gray-500 mt-0.5">Daftarkan siswa baru</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-purple-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/admin/kelas/tambah.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-pink-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Tambah Kelas</span>
                <span class="block text-xs text-gray-500 mt-0.5">Buat kelas baru</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-pink-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
