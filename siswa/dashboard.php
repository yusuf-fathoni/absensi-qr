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
$hariNama = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$hariIni = $hariNama[(int) date('w')] . ', ' . date('d M Y');
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard</h1>
    <p class="text-gray-500 text-sm mt-1">Selamat datang, <?= htmlspecialchars($siswa['nama']) ?> &middot; <?= $hariIni ?></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Data Pribadi</h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between gap-4 pb-3 border-b border-gray-100">
                <span class="text-gray-500">NIS</span>
                <span class="font-semibold text-gray-800"><?= htmlspecialchars($siswa['nis']) ?></span>
            </div>
            <div class="flex justify-between gap-4 pb-3 border-b border-gray-100">
                <span class="text-gray-500">Nama</span>
                <span class="font-semibold text-gray-800"><?= htmlspecialchars($siswa['nama']) ?></span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-gray-500">Kelas</span>
                <span class="font-semibold text-gray-800"><?= htmlspecialchars($siswa['nama_kelas']) ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Absensi Bulan Ini</h2>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl bg-green-50 p-4">
                <p class="text-2xl font-bold text-green-600"><?= $rekap['Hadir'] ?? 0 ?></p>
                <p class="text-xs font-medium text-green-700/80 mt-0.5">Hadir</p>
            </div>
            <div class="rounded-xl bg-yellow-50 p-4">
                <p class="text-2xl font-bold text-yellow-600"><?= $rekap['Terlambat'] ?? 0 ?></p>
                <p class="text-xs font-medium text-yellow-700/80 mt-0.5">Terlambat</p>
            </div>
            <div class="rounded-xl bg-blue-50 p-4">
                <p class="text-2xl font-bold text-blue-600"><?= $rekap['Izin'] ?? 0 ?></p>
                <p class="text-xs font-medium text-blue-700/80 mt-0.5">Izin</p>
            </div>
            <div class="rounded-xl bg-red-50 p-4">
                <p class="text-2xl font-bold text-red-600"><?= $rekap['Sakit'] ?? 0 ?></p>
                <p class="text-xs font-medium text-red-700/80 mt-0.5">Sakit</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="/siswa/qr_saya.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">QR Saya</span>
                <span class="block text-xs text-gray-500 mt-0.5">Lihat QR Code absensi</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/siswa/riwayat.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-green-200 hover:shadow-md hover:-translate-y-0.5 transition">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Riwayat Absensi</span>
                <span class="block text-xs text-gray-500 mt-0.5">Lihat riwayat kehadiran</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-green-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <a href="/siswa/pengajuan.php" class="group flex items-center justify-between gap-4 p-5 rounded-2xl border border-gray-100 bg-white hover:border-yellow-200 hover:shadow-md hover:-translate-y-0.5 transition md:col-span-2">
            <span class="min-w-0">
                <span class="block font-semibold text-gray-800 text-sm">Ajukan Izin/Sakit</span>
                <span class="block text-xs text-gray-500 mt-0.5">Ajukan izin atau sakit untuk hari ini</span>
            </span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-yellow-600 group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
