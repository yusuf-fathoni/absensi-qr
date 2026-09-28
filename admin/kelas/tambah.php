<?php
$page_title = 'Tambah Kelas';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/kelas.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $namaKelas = trim($_POST['nama_kelas'] ?? '');

    if (empty($namaKelas)) {
        $error = 'Nama kelas wajib diisi';
    } else {
        if (tambahKelas($namaKelas)) {
            header("Location: /admin/siswa/index.php?tambah_berhasil=1");
            exit();
        } else {
            $error = 'Gagal menambahkan kelas';
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-6">Tambah Kelas</h1>

    <?php if ($error): ?>
        <div class="alert alert-err">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="label" for="nama_kelas">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" required
                    class="input"
                    placeholder="Contoh: X RPL A"
                    value="<?= htmlspecialchars($_POST['nama_kelas'] ?? '') ?>">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="/admin/siswa/index.php" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>