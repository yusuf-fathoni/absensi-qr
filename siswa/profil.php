<?php
$page_title = 'Profil';
$role = 'siswa';
require_once __DIR__ . '/../includes/auth.php';
requireSiswa();
require_once __DIR__ . '/../functions/siswa.php';

$siswa = getSiswaByUserId($_SESSION['user_id']);
if (!$siswa) {
    die("Data siswa tidak ditemukan");
}

$inisial = '';
foreach (explode(' ', $siswa['nama']) as $kata) {
    if ($kata !== '') {
        $inisial .= strtoupper(substr($kata, 0, 1));
    }
}
$inisial = substr($inisial, 0, 2);

$errorPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $lama = $_POST['password_lama'] ?? '';
    $baru = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($lama === '' || $baru === '' || $konfirmasi === '') {
        $errorPassword = 'Semua field wajib diisi.';
    } elseif (!verifikasiPasswordUser($_SESSION['user_id'], $lama)) {
        $errorPassword = 'Password lama tidak sesuai.';
    } elseif (strlen($baru) < 6) {
        $errorPassword = 'Password baru minimal 6 karakter.';
    } elseif ($baru === $lama) {
        $errorPassword = 'Password baru tidak boleh sama dengan password lama.';
    } elseif ($baru !== $konfirmasi) {
        $errorPassword = 'Konfirmasi password tidak cocok.';
    } elseif (ubahPasswordUser($_SESSION['user_id'], $baru)) {
        header("Location: /siswa/profil.php?sukses=1");
        exit();
    } else {
        $errorPassword = 'Gagal mengubah password. Coba lagi.';
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-6">Profil Saya</h1>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-ok">
            Password berhasil diubah.
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold flex-shrink-0">
                <?= htmlspecialchars($inisial) ?>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($siswa['nama']) ?></h2>
                <p class="text-gray-500">NIS <?= htmlspecialchars($siswa['nis']) ?> &bull; <?= htmlspecialchars($siswa['nama_kelas']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">NIS</label>
                <p class="text-lg text-gray-800"><?= htmlspecialchars($siswa['nis']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Nama</label>
                <p class="text-lg text-gray-800"><?= htmlspecialchars($siswa['nama']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Kelas</label>
                <p class="text-lg text-gray-800"><?= htmlspecialchars($siswa['nama_kelas']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Username</label>
                <p class="text-lg text-gray-800"><?= htmlspecialchars($_SESSION['username']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Keamanan Akun</h2>
            <p class="text-sm text-gray-500">Ganti password akun kamu</p>
        </div>
        <button type="button" id="btnUbahPassword"
            class="btn btn-primary">
            Ubah Password
        </button>
    </div>
</div>

<div id="modalUbahPassword" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 <?= $errorPassword ? '' : 'hidden' ?>">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Ubah Password</h2>
            <button type="button" id="btnTutupModal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <?php if ($errorPassword): ?>
            <div class="alert alert-err">
                <?= htmlspecialchars($errorPassword) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="label" for="password_lama">Password Lama</label>
                <input type="password" name="password_lama" id="password_lama" required
                    class="input"
                    placeholder="Masukkan password lama">
            </div>
            <div class="mb-4">
                <label class="label" for="password_baru">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru" required minlength="6"
                    class="input"
                    placeholder="Minimal 6 karakter">
            </div>
            <div class="mb-6">
                <label class="label" for="konfirmasi">Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi" id="konfirmasi" required minlength="6"
                    class="input"
                    placeholder="Ulangi password baru">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Password
                </button>
                <button type="button" id="btnBatalModal" class="btn btn-secondary">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalUbahPassword');
    document.getElementById('btnUbahPassword').addEventListener('click', () => modal.classList.remove('hidden'));
    ['btnTutupModal', 'btnBatalModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', () => modal.classList.add('hidden'));
    });
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') modal.classList.add('hidden');
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
