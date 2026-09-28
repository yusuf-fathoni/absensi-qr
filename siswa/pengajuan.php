<?php
$page_title = 'Ajukan Izin/Sakit';
$role = 'siswa';
require_once __DIR__ . '/../includes/auth.php';
requireSiswa();
require_once __DIR__ . '/../functions/siswa.php';
require_once __DIR__ . '/../functions/absensi.php';
require_once __DIR__ . '/../functions/pengajuan.php';

$siswa = getSiswaByUserId($_SESSION['user_id']);
if (!$siswa) {
    die("Data siswa tidak ditemukan");
}

$today = date('Y-m-d');
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $jenis = $_POST['jenis'] ?? '';
    $alasan = trim($_POST['alasan'] ?? '');

    if (!in_array($jenis, ['Izin', 'Sakit'])) {
        $error = 'Pilih jenis pengajuan Izin atau Sakit.';
    } elseif ($alasan === '') {
        $error = 'Alasan wajib diisi.';
    } elseif (cekAbsensiHari($siswa['id'], $today)) {
        $error = 'Anda sudah melakukan absensi hari ini.';
    } elseif (getPengajuanSiswaTanggal($siswa['id'], $today)) {
        $error = 'Anda sudah mengajukan izin/sakit hari ini.';
    } elseif (ajukanPengajuan($siswa['id'], $today, $jenis, $alasan)) {
        header("Location: /siswa/pengajuan.php?sukses=1");
        exit();
    } else {
        $error = 'Gagal mengajukan. Coba lagi.';
    }
}

$absensiHariIni = cekAbsensiHari($siswa['id'], $today);
$pengajuanHariIni = getPengajuanSiswaTanggal($siswa['id'], $today);
$daftarPengajuan = getPengajuanSiswa($siswa['id']);
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Ajukan Izin/Sakit</h1>
    <p class="text-gray-500 text-sm mt-1"><?= date('d F Y', strtotime($today)) ?></p>
</div>

<?php if (isset($_GET['sukses'])): ?>
    <div class="alert alert-ok">
        Pengajuan berhasil dikirim. Menunggu persetujuan admin.
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-err">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Formulir Pengajuan Hari Ini</h2>

    <?php if ($absensiHariIni): ?>
        <p class="text-gray-500">Anda sudah melakukan absensi hari ini (status: <strong><?= $absensiHariIni['status'] ?></strong>), sehingga tidak bisa mengajukan izin/sakit.</p>
    <?php elseif ($pengajuanHariIni): ?>
        <p class="text-gray-500">Pengajuan hari ini sudah dikirim dengan status <strong><?= $pengajuanHariIni['status'] ?></strong>.</p>
    <?php else: ?>
        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="label">Jenis</label>
                <div class="grid grid-cols-2 gap-2 max-w-full sm:max-w-sm">
                    <?php foreach (['Izin', 'Sakit'] as $j): ?>
                        <label class="flex items-center justify-center p-4 sm:p-3 border rounded-xl cursor-pointer hover:bg-gray-50 <?= (($_POST['jenis'] ?? '') === $j) ? 'border-blue-500 bg-blue-50' : '' ?>">
                            <input type="radio" name="jenis" value="<?= $j ?>" <?= (($_POST['jenis'] ?? '') === $j) ? 'checked' : '' ?> required class="mr-2"
                                onchange="this.closest('label').classList.toggle('border-blue-500', this.checked); this.closest('label').classList.toggle('bg-blue-50', this.checked);">
                            <?= $j ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-6">
                <label class="label" for="alasan">Alasan</label>
                <textarea name="alasan" id="alasan" rows="3" required
                    class="input"
                    placeholder="Tulis alasan..."><?= htmlspecialchars($_POST['alasan'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full sm:w-auto">
                Kirim Pengajuan
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pengajuan</h2>

    <?php if (empty($daftarPengajuan)): ?>
        <p class="text-gray-500">Belum ada pengajuan.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($daftarPengajuan as $i => $p): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 hidden sm:table-cell"><?= $i + 1 ?></td>
                            <td class="px-4 py-3"><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                            <td class="px-4 py-3"><?= $p['jenis'] ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500 max-w-[160px] sm:max-w-xs break-words"><?= htmlspecialchars($p['alasan']) ?></td>
                            <td class="px-4 py-3">
                                <?php
                                $colors = ['Menunggu' => 'yellow', 'Disetujui' => 'green', 'Ditolak' => 'red'];
                                $c = $colors[$p['status']] ?? 'gray';
                                ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?= $c ?>-100 text-<?= $c ?>-700"><?= $p['status'] ?></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 max-w-[160px] sm:max-w-xs break-words"><?= htmlspecialchars($p['admin_note'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
