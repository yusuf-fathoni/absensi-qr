<?php
$page_title = 'Rekap Absensi';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/absensi.php';
require_once __DIR__ . '/../../functions/kelas.php';
require_once __DIR__ . '/../../functions/siswa.php';

$kelasList = getAllKelas();
$siswaList = getAllSiswa();

$tanggalMulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggalAkhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');
$kelasId = $_GET['kelas_id'] ?? null;
$siswaId = $_GET['siswa_id'] ?? null;
$status = $_GET['status'] ?? null;

$rekapList = getRekapAbsensi($tanggalMulai, $tanggalAkhir, $kelasId, $siswaId, $status);
$rekap = hitungRekap($tanggalMulai, $tanggalAkhir, $kelasId);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Rekap Absensi</h1>
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

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" value="<?= htmlspecialchars($tanggalMulai) ?>" class="w-full px-3 py-2 border rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggalAkhir) ?>" class="w-full px-3 py-2 border rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-2 border rounded-lg text-sm">
                <option value="">Semua</option>
                <?php foreach ($kelasList as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($kelasId == $k['id']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm">
                <option value="">Semua</option>
                <?php foreach (['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($status === $s) ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                Filter
            </button>
            <?php if (!empty($rekapList)): ?>
                <a href="cetak.php?tanggal_mulai=<?= urlencode($tanggalMulai) ?>&tanggal_akhir=<?= urlencode($tanggalAkhir) ?>&kelas_id=<?= urlencode((string)($kelasId ?? '')) ?>&status=<?= urlencode((string)($status ?? '')) ?>" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm whitespace-nowrap">
                    Cetak
                </a>
            <?php endif; ?>
        </div>
    </form>
    <div class="mt-4 flex items-center justify-end space-x-3 border-t border-gray-100 pt-4">
        <span class="text-sm text-gray-500">Tandai siswa yang belum absen pada rentang filter sebagai Alpa</span>
        <form method="POST" action="/admin/absensi/proses_alpa.php" onsubmit="return confirm('Proses Alpa untuk rentang <?= htmlspecialchars(date('d M Y', strtotime($tanggalMulai)) . ' - ' . date('d M Y', strtotime($tanggalAkhir))) ?>?');">
            <?= csrf_field() ?>
            <input type="hidden" name="sumber" value="rekap">
            <input type="hidden" name="tanggal_mulai" value="<?= htmlspecialchars($tanggalMulai) ?>">
            <input type="hidden" name="tanggal_akhir" value="<?= htmlspecialchars($tanggalAkhir) ?>">
            <input type="hidden" name="kelas_id" value="<?= htmlspecialchars((string)($kelasId ?? '')) ?>">
            <input type="hidden" name="siswa_id" value="<?= htmlspecialchars((string)($siswaId ?? '')) ?>">
            <input type="hidden" name="status" value="<?= htmlspecialchars((string)($status ?? '')) ?>">
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm whitespace-nowrap">
                Proses Alpa (rentang ini)
            </button>
        </form>
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

<?php if (empty($rekapList)): ?>
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500">Tidak ada data absensi untuk filter ini.</p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full stack-mobile">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($rekapList as $i => $a): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3" data-label="No"><?= $i + 1 ?></td>
                        <td class="px-4 py-3" data-label="Tanggal"><?= date('d M Y', strtotime($a['tanggal'])) ?></td>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>