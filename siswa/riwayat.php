<?php
$page_title = 'Riwayat Absensi';
$role = 'siswa';
require_once __DIR__ . '/../includes/auth.php';
requireSiswa();
require_once __DIR__ . '/../functions/siswa.php';
require_once __DIR__ . '/../functions/absensi.php';

$siswa = getSiswaByUserId($_SESSION['user_id']);
$riwayat = getAbsensiSiswa($siswa['id']);
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Absensi</h1>
</div>

<?php if (empty($riwayat)): ?>
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500">Belum ada riwayat absensi.</p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($riwayat as $i => $a): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3"><?= $i + 1 ?></td>
                        <td class="px-4 py-3"><?= date('d M Y', strtotime($a['tanggal'])) ?></td>
                        <td class="px-4 py-3"><?= date('H:i', strtotime($a['jam'])) ?></td>
                        <td class="px-4 py-3">
                            <?php
                            $colors = ['Hadir' => 'green', 'Terlambat' => 'yellow', 'Izin' => 'blue', 'Sakit' => 'red', 'Alpa' => 'gray'];
                            $c = $colors[$a['status']] ?? 'gray';
                            ?>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?= $c ?>-100 text-<?= $c ?>-700"><?= $a['status'] ?></span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500"><?= htmlspecialchars($a['keterangan'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>