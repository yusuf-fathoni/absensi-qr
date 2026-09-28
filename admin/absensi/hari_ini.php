<?php
$page_title = 'Absensi Hari Ini';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/absensi.php';
require_once __DIR__ . '/../../functions/kelas.php';

$today = date('Y-m-d');
$kelasList = getAllKelas();
$kelasId = $_GET['kelas_id'] ?? null;

$absensiList = getAbsensiHari($today, $kelasId);
$belumAbsen = hitungBelumAbsen($today);

$rekap = [];
foreach ($absensiList as $a) {
    $rekap[$a['status']] = ($rekap[$a['status']] ?? 0) + 1;
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Absensi Hari Ini</h1>
    <p class="text-gray-500"><?= date('d F Y', strtotime($today)) ?></p>
</div>

<?php if (isset($_GET['alpa_sukses'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_GET['alpa_sukses']) ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['alpa_gagal'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_GET['alpa_gagal']) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow p-4 mb-6 flex items-center justify-between flex-wrap gap-4">
    <form method="GET" class="flex items-center space-x-4">
        <label class="text-sm font-medium text-gray-700">Pilih Kelas:</label>
        <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            <option value="">Semua Kelas</option>
            <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($kelasId == $k['id']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div class="flex items-center space-x-3">
        <span class="text-sm text-gray-500"><?= $belumAbsen ?> siswa belum absen</span>
        <?php if ($belumAbsen > 0): ?>
            <form method="POST" action="/admin/absensi/proses_alpa.php" onsubmit="return confirm('Tandai <?= $belumAbsen ?> siswa yang belum absen sebagai Alpa hari ini?');">
                <?= csrf_field() ?>
                <input type="hidden" name="sumber" value="hari_ini">
                <input type="hidden" name="tanggal" value="<?= $today ?>">
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm whitespace-nowrap">
                    Proses Alpa
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-green-600"><?= $rekap['Hadir'] ?? 0 ?></p>
        <p class="text-sm text-gray-500">Hadir</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600"><?= $rekap['Terlambat'] ?? 0 ?></p>
        <p class="text-sm text-gray-500">Terlambat</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-blue-600"><?= $rekap['Izin'] ?? 0 ?></p>
        <p class="text-sm text-gray-500">Izin</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-red-600"><?= $rekap['Sakit'] ?? 0 ?></p>
        <p class="text-sm text-gray-500">Sakit</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-gray-600"><?= $rekap['Alpa'] ?? 0 ?></p>
        <p class="text-sm text-gray-500">Alpa</p>
    </div>
</div>

<?php if (empty($absensiList)): ?>
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500">Belum ada data absensi hari ini<?= $kelasId ? ' untuk kelas ini' : '' ?>.</p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full stack-mobile">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($absensiList as $i => $a): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3" data-label="No"><?= $i + 1 ?></td>
                        <td class="px-4 py-3" data-label="NIS"><?= htmlspecialchars($a['nis']) ?></td>
                        <td class="px-4 py-3" data-label="Nama"><?= htmlspecialchars($a['nama']) ?></td>
                        <td class="px-4 py-3" data-label="Kelas"><?= htmlspecialchars($a['nama_kelas']) ?></td>
                        <td class="px-4 py-3" data-label="Jam"><?= date('H:i', strtotime($a['jam'])) ?></td>
                        <td class="px-4 py-3" data-label="Status">
                            <?php
                            $colors = ['Hadir' => 'green', 'Terlambat' => 'yellow', 'Izin' => 'blue', 'Sakit' => 'red', 'Alpa' => 'gray'];
                            $c = $colors[$a['status']] ?? 'gray';
                            ?>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?= $c ?>-100 text-<?= $c ?>-700"><?= $a['status'] ?></span>
                        </td>
                        <td class="px-4 py-3" data-label="Aksi">
                            <a href="/admin/absensi/ubah_status.php?id=<?= $a['id'] ?>" class="text-blue-600 hover:underline text-sm">Ubah</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>