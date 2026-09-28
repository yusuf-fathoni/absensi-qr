<?php
$page_title = 'Tambah Siswa';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/siswa.php';
require_once __DIR__ . '/../../functions/kelas.php';

$kelasList = getAllKelas();
$kelasId = $_GET['kelas_id'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nis = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kelasId = $_POST['kelas_id'] ?? '';

    if (empty($nis) || empty($nama) || empty($kelasId)) {
        $error = 'Semua field wajib diisi';
    } elseif (isNISExists($nis)) {
        $error = 'NIS sudah digunakan';
    } else {
        $userId = generateUserForSiswa($nis);
        if (tambahSiswa($nis, $nama, $kelasId, $userId)) {
            header("Location: /admin/siswa/detail.php?kelas_id=$kelasId&tambah_berhasil=1");
            exit();
        } else {
            $error = 'Gagal menambahkan siswa';
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Siswa</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nis">NIS</label>
                <input type="text" name="nis" id="nis" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Masukkan NIS"
                    value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Masukkan nama lengkap"
                    value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="kelas_id">Kelas</label>
                <select name="kelas_id" id="kelas_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($kelasId == $k['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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