<?php
$page_title = 'Edit Pengajuan';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/absensi.php';
require_once __DIR__ . '/../../functions/pengajuan.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: /admin/absensi/pengajuan.php");
    exit();
}

$pengajuan = getPengajuanById($id);
if (!$pengajuan) {
    header("Location: /admin/absensi/pengajuan.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $tanggal = $_POST['tanggal'] ?? '';
    $jenis = $_POST['jenis'] ?? '';
    $alasan = trim($_POST['alasan'] ?? '');
    $adminNote = trim($_POST['admin_note'] ?? '');

    list($ok, $pesan) = editPengajuan($id, $tanggal, $jenis, $alasan, $adminNote !== '' ? $adminNote : null);
    if ($ok) {
        header("Location: /admin/absensi/pengajuan.php?sukses=" . urlencode($pesan));
        exit();
    }
    $error = $pesan;
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-6">Edit Pengajuan</h1>

    <?php if ($error): ?>
        <div class="alert alert-err">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="mb-4 bg-gray-50 rounded-lg p-4">
            <p><span class="font-medium">Nama:</span> <?= htmlspecialchars($pengajuan['nama']) ?></p>
            <p><span class="font-medium">NIS:</span> <?= htmlspecialchars($pengajuan['nis']) ?></p>
            <p><span class="font-medium">Kelas:</span> <?= htmlspecialchars($pengajuan['nama_kelas']) ?></p>
            <p><span class="font-medium">Status:</span> <?= $pengajuan['status'] ?></p>
            <?php if ($pengajuan['status'] === 'Disetujui'): ?>
                <p class="text-sm text-gray-500 mt-1">Perubahan jenis/alasan/tanggal akan ikut menyesuaikan data absensi terkait.</p>
            <?php endif; ?>
        </div>

        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="label" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($pengajuan['tanggal']) ?>" max="<?= date('Y-m-d') ?>" required
                    class="input">
            </div>

            <div class="mb-4">
                <label class="label">Jenis</label>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach (['Izin', 'Sakit'] as $j): ?>
                        <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 <?= $pengajuan['jenis'] === $j ? 'border-blue-500 bg-blue-50' : '' ?>">
                            <input type="radio" name="jenis" value="<?= $j ?>" <?= $pengajuan['jenis'] === $j ? 'checked' : '' ?> required class="mr-2"
                                onchange="this.closest('label').classList.toggle('border-blue-500', this.checked); this.closest('label').classList.toggle('bg-blue-50', this.checked);">
                            <?= $j ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="label" for="alasan">Alasan</label>
                <textarea name="alasan" id="alasan" rows="3" required
                    class="input"
                    placeholder="Tulis alasan..."><?= htmlspecialchars($_POST['alasan'] ?? $pengajuan['alasan']) ?></textarea>
            </div>

            <div class="mb-6">
                <label class="label" for="admin_note">Catatan Admin</label>
                <textarea name="admin_note" id="admin_note" rows="2"
                    class="input"
                    placeholder="Opsional..."><?= htmlspecialchars($_POST['admin_note'] ?? ($pengajuan['admin_note'] ?? '')) ?></textarea>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="/admin/absensi/pengajuan.php" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
