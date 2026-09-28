<?php
$page_title = 'Data Siswa';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/kelas.php';

$kelasList = getAllKelas();

if (isset($_GET['hapus_berhasil'])) {
    $success = 'Kelas berhasil dihapus.';
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Data Siswa</h1>
    <a href="/admin/kelas/tambah.php" class="btn btn-primary">
        + Tambah Kelas
    </a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-ok" data-auto-hide>
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if (empty($kelasList)): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
        <p class="text-gray-500 mb-4">Belum ada kelas.</p>
        <a href="/admin/kelas/tambah.php" class="text-blue-600 hover:underline">+ Tambah Kelas</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($kelasList as $k): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($k['nama_kelas']) ?></h2>
                <p class="text-gray-500 mb-4 text-sm"><?= $k['jumlah_siswa'] ?> Siswa</p>
                <div class="flex space-x-2">
                    <a href="/admin/siswa/detail.php?kelas_id=<?= $k['id'] ?>" class="btn btn-sm btn-success">
                        Lihat Siswa
                    </a>
                    <a href="/admin/kelas/edit.php?id=<?= $k['id'] ?>" class="btn btn-sm btn-secondary">
                        Edit
                    </a>
                    <form method="POST" action="/admin/kelas/hapus.php" class="inline" onsubmit="return confirm('Hapus kelas <?= htmlspecialchars($k['nama_kelas']) ?>? Siswa di kelas ini juga akan dihapus.');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>