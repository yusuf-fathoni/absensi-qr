<?php
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/siswa.php';

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

$siswa = getSiswaById($id);
$kelasId = $siswa['kelas_id'] ?? null;

hapusSiswa($id);
header("Location: /admin/siswa/detail.php?kelas_id=$kelasId&hapus_berhasil=1");
exit();