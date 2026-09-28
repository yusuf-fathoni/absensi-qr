<?php
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/kelas.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/siswa/index.php");
    exit();
}
verify_csrf();

$id = $_POST['id'] ?? null;
if (!$id) {
    header("Location: /admin/siswa/index.php");
    exit();
}

if (isKelasHasSiswa($id)) {
    header("Location: /admin/siswa/index.php?error=kelas_masih_ada_siswa");
    exit();
}

hapusKelas($id);
header("Location: /admin/siswa/index.php?hapus_berhasil=1");
exit();