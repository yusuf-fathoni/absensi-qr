<?php
$page_title = 'Ubah Status Absensi';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/absensi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: /admin/absensi/hari_ini.php");
    exit();
}

$absensi = getAbsensiById($id);
if (!$absensi) {
    header("Location: /admin/absensi/hari_ini.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $status = $_POST['status'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    $validStatus = ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa'];
    if (!in_array($status, $validStatus)) {
        $error = 'Status tidak valid';
    } else {
        if (ubahStatusAbsensi($id, $status, $keterangan)) {
            header("Location: /admin/absensi/hari_ini.php?ubah_berhasil=1");
            exit();
        } else {
            $error = 'Gagal mengubah status';
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ubah Status Absensi</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4 bg-gray-50 rounded-lg p-4">
            <p><span class="font-medium">Nama:</span> <?= htmlspecialchars($absensi['nama']) ?></p>
            <p><span class="font-medium">NIS:</span> <?= htmlspecialchars($absensi['nis']) ?></p>
            <p><span class="font-medium">Kelas:</span> <?= htmlspecialchars($absensi['nama_kelas']) ?></p>
            <p><span class="font-medium">Tanggal:</span> <?= date('d F Y', strtotime($absensi['tanggal'])) ?></p>
            <p><span class="font-medium">Jam:</span> <?= date('H:i', strtotime($absensi['jam'])) ?></p>
            <p><span class="font-medium">Status:</span> <?= $absensi['status'] ?></p>
        </div>

        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ubah Status</label>
                <div class="grid grid-cols-5 gap-2">
                    <?php foreach (['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa'] as $s): ?>
                        <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 <?= ($absensi['status'] === $s) ? 'border-blue-500 bg-blue-50' : '' ?>">
                            <input type="radio" name="status" value="<?= $s ?>" <?= ($absensi['status'] === $s) ? 'checked' : '' ?> class="hidden" onchange="this.closest('label').classList.toggle('border-blue-500', this.checked); this.closest('label').classList.toggle('bg-blue-50', this.checked);">
                            <?= $s ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Isi keterangan jika perlu..."><?= htmlspecialchars($_POST['keterangan'] ?? $absensi['keterangan'] ?? '') ?></textarea>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
                <a href="/admin/absensi/hari_ini.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>