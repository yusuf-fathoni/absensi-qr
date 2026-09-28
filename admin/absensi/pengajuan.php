<?php
$page_title = 'Pengajuan Izin/Sakit';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/siswa.php';
require_once __DIR__ . '/../../functions/pengajuan.php';

$statusFilter = $_GET['status'] ?? '';
$validFilter = ['', 'Menunggu', 'Disetujui', 'Ditolak'];
if (!in_array($statusFilter, $validFilter)) {
    $statusFilter = '';
}

$daftarPengajuan = getAllPengajuan($statusFilter ?: null);
$siswaList = getAllSiswa();
$menungguCount = hitungPengajuanMenunggu();
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="mb-6 flex items-center justify-between flex-wrap gap-2">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengajuan Izin/Sakit</h1>
        <p class="text-gray-500"><?= $menungguCount ?> pengajuan menunggu persetujuan</p>
    </div>
</div>

<?php if (isset($_GET['sukses'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_GET['sukses']) ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['gagal'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_GET['gagal']) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Status:</label>
        <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            <option value="">Semua</option>
            <?php foreach (['Menunggu', 'Disetujui', 'Ditolak'] as $s): ?>
                <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto mb-6">
    <?php if (empty($daftarPengajuan)): ?>
        <div class="p-8 text-center">
            <p class="text-gray-500">Tidak ada pengajuan<?= $statusFilter ? ' dengan status ' . htmlspecialchars($statusFilter) : '' ?>.</p>
        </div>
    <?php else: ?>
        <table class="w-full stack-mobile">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">NIS</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Kelas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Catatan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($daftarPengajuan as $i => $p): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 hidden md:table-cell" data-label="No"><?= $i + 1 ?></td>
                        <td class="px-4 py-3" data-label="Tanggal"><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                        <td class="px-4 py-3 hidden md:table-cell" data-label="NIS"><?= htmlspecialchars($p['nis']) ?></td>
                        <td class="px-4 py-3" data-label="Nama"><?= htmlspecialchars($p['nama']) ?></td>
                        <td class="px-4 py-3 hidden md:table-cell" data-label="Kelas"><?= htmlspecialchars($p['nama_kelas']) ?></td>
                        <td class="px-4 py-3 hidden md:table-cell" data-label="Jenis"><?= $p['jenis'] ?></td>
                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs break-words" data-label="Alasan"><?= htmlspecialchars($p['alasan']) ?></td>
                        <td class="px-4 py-3" data-label="Status">
                            <?php
                            $colors = ['Menunggu' => 'yellow', 'Disetujui' => 'green', 'Ditolak' => 'red'];
                            $c = $colors[$p['status']] ?? 'gray';
                            ?>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-<?= $c ?>-100 text-<?= $c ?>-700"><?= $p['status'] ?></span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell" data-label="Catatan"><?= htmlspecialchars($p['admin_note'] ?? '-') ?></td>
                        <td class="px-4 py-3 aksi-cell" data-label="Aksi">
                            <div class="grid grid-cols-2 gap-4 sm:flex sm:items-center sm:flex-wrap sm:gap-4">
                                <?php if ($p['status'] === 'Menunggu'): ?>
                                    <form method="POST" action="/admin/absensi/proses_pengajuan.php">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="aksi" value="setujui">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-3 py-2 sm:py-1 rounded text-sm text-center hover:bg-green-700 transition">Setujui</button>
                                    </form>
                                    <form method="POST" action="/admin/absensi/proses_pengajuan.php" class="sm:flex sm:items-center sm:gap-1">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="aksi" value="tolak">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <input type="text" name="admin_note" placeholder="Catatan (opsional)"
                                            class="hidden sm:block px-2 py-1 border border-gray-300 rounded text-sm w-28 md:w-36 focus:outline-none focus:border-blue-500">
                                        <button type="submit" class="w-full sm:w-auto bg-red-600 text-white px-3 py-2 sm:py-1 rounded text-sm text-center hover:bg-red-700 transition">Tolak</button>
                                    </form>
                                <?php endif; ?>
                                <a href="/admin/absensi/edit_pengajuan.php?id=<?= $p['id'] ?>"
                                    class="block col-span-2 w-full sm:w-auto text-center bg-blue-600 text-white px-3 py-2 sm:py-1 rounded text-sm hover:bg-blue-700 transition">Edit</a>
                                <form method="POST" action="/admin/absensi/proses_pengajuan.php" class="col-span-2"
                                    onsubmit="return confirm('Hapus pengajuan <?= htmlspecialchars($p['nama']) ?> (<?= date('d M Y', strtotime($p['tanggal'])) ?>)?<?= $p['status'] === 'Disetujui' ? ' Data absensi terkait ikut dihapus.' : ''; ?>');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="w-full sm:w-auto bg-red-700 text-white px-3 py-2 sm:py-1 rounded text-sm text-center hover:bg-red-800 transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Input Manual Izin/Sakit</h2>
    <form method="POST" action="/admin/absensi/proses_pengajuan.php">
        <?= csrf_field() ?>
        <input type="hidden" name="aksi" value="manual">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="siswa_id">Siswa</label>
                <select name="siswa_id" id="siswa_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($siswaList as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama'] . ' - ' . $s['nis'] . ' (' . $s['nama_kelas'] . ')') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Jenis</label>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach (['Izin', 'Sakit'] as $j): ?>
                        <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="jenis" value="<?= $j ?>" <?= $j === 'Izin' ? 'checked' : '' ?> required class="mr-2"
                                onchange="this.closest('label').classList.toggle('border-blue-500', this.checked); this.closest('label').classList.toggle('bg-blue-50', this.checked);">
                            <?= $j ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Isi keterangan..."></textarea>
            </div>
            <div class="flex md:justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full md:w-auto">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
