<?php
$page_title = 'Daftar Siswa';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/siswa.php';
require_once __DIR__ . '/../../functions/kelas.php';

$kelasId = $_GET['kelas_id'] ?? null;
if (!$kelasId) {
    header("Location: /admin/siswa/index.php");
    exit();
}

$kelas = getKelasById($kelasId);
if (!$kelas) {
    header("Location: /admin/siswa/index.php");
    exit();
}

$siswaList = getSiswaByKelas($kelasId);

if (isset($_GET['tambah_berhasil'])) {
    $success = 'Siswa berhasil ditambahkan.';
}
if (isset($_GET['edit_berhasil'])) {
    $success = 'Data siswa berhasil diubah.';
}
if (isset($_GET['hapus_berhasil'])) {
    $success = 'Siswa berhasil dihapus.';
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <a href="/admin/siswa/index.php" class="btn btn-secondary mb-2 inline-flex">&larr; Kembali ke Daftar Kelas</a>
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Siswa Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?></h1>
    </div>
    <a href="/admin/siswa/tambah.php?kelas_id=<?= $kelasId ?>" class="btn btn-primary">
        + Tambah Siswa
    </a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-ok" data-auto-hide>
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if (empty($siswaList)): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
        <p class="text-gray-500 mb-4">Belum ada siswa di kelas ini.</p>
        <a href="/admin/siswa/tambah.php?kelas_id=<?= $kelasId ?>" class="text-blue-600 hover:underline">+ Tambah Siswa</a>
    </div>
<?php else: ?>
    <div class="table-card">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($siswaList as $i => $s): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $i + 1 ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($s['nis']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($s['nama']) ?></td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="/admin/siswa/edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="/admin/siswa/hapus.php" class="inline" onsubmit="return confirm('Hapus siswa <?= htmlspecialchars($s['nama']) ?>?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>