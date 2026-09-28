<?php
$page_title = 'Edit Kelas';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/kelas.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: /admin/siswa/index.php");
    exit();
}

$kelas = getKelasById($id);
if (!$kelas) {
    header("Location: /admin/siswa/index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $namaKelas = trim($_POST['nama_kelas'] ?? '');

    if (empty($namaKelas)) {
        $error = 'Nama kelas wajib diisi';
    } else {
        if (editKelas($id, $namaKelas)) {
            header("Location: /admin/siswa/index.php?edit_berhasil=1");
            exit();
        } else {
            $error = 'Gagal mengubah kelas';
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kelas</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_kelas">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    value="<?= htmlspecialchars($_POST['nama_kelas'] ?? $kelas['nama_kelas']) ?>">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
                <a href="/admin/siswa/index.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>