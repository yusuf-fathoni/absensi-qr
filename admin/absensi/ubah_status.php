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
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-6">Ubah Status Absensi</h1>

    <?php if ($error): ?>
        <div class="alert alert-err">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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
                <label class="label">Ubah Status</label>
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
                <label class="label" for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="input"
                    placeholder="Isi keterangan jika perlu..."><?= htmlspecialchars($_POST['keterangan'] ?? $absensi['keterangan'] ?? '') ?></textarea>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="/admin/absensi/hari_ini.php" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>