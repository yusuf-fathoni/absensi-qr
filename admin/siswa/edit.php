<?php
$page_title = 'Edit Siswa';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/siswa.php';
require_once __DIR__ . '/../../functions/kelas.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: /admin/siswa/index.php");
    exit();
}

$siswa = getSiswaById($id);
if (!$siswa) {
    header("Location: /admin/siswa/index.php");
    exit();
}

$kelasList = getAllKelas();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nis = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kelasId = $_POST['kelas_id'] ?? '';

    if (empty($nis) || empty($nama) || empty($kelasId)) {
        $error = 'Semua field wajib diisi';
    } elseif (isNISExists($nis, $id)) {
        $error = 'NIS sudah digunakan oleh siswa lain';
    } else {
        if (editSiswa($id, $nis, $nama, $kelasId)) {
            header("Location: /admin/siswa/detail.php?kelas_id=$kelasId&edit_berhasil=1");
            exit();
        } else {
            $error = 'Gagal mengubah data siswa';
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-6">Edit Siswa</h1>

    <?php if ($error): ?>
        <div class="alert alert-err">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="label" for="nis">NIS</label>
                <input type="text" name="nis" id="nis" required
                    class="input"
                    value="<?= htmlspecialchars($_POST['nis'] ?? $siswa['nis']) ?>">
            </div>
            <div class="mb-4">
                <label class="label" for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required
                    class="input"
                    value="<?= htmlspecialchars($_POST['nama'] ?? $siswa['nama']) ?>">
            </div>
            <div class="mb-6">
                <label class="label" for="kelas_id">Kelas</label>
                <select name="kelas_id" id="kelas_id" required
                    class="input">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($siswa['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
                <a href="/admin/siswa/detail.php?kelas_id=<?= $siswa['kelas_id'] ?>" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>