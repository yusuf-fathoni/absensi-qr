<?php
require_once __DIR__ . '/../../includes/session.php';
security_bootstrap();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/absensi/hari_ini.php");
    exit();
}

require_once __DIR__ . '/../../includes/csrf.php';
verify_csrf();

require_once __DIR__ . '/../../functions/absensi.php';

$sumber = $_POST['sumber'] ?? 'hari_ini';
$kelasId = !empty($_POST['kelas_id']) ? $_POST['kelas_id'] : null;
$siswaId = !empty($_POST['siswa_id']) ? $_POST['siswa_id'] : null;

if ($sumber === 'rekap') {
    $params = [];
    foreach (['tanggal_mulai', 'tanggal_akhir', 'kelas_id', 'siswa_id', 'status'] as $f) {
        if (!empty($_POST[$f])) {
            $params[$f] = $_POST[$f];
        }
    }
    $redirect = '/admin/absensi/rekap.php?' . http_build_query($params);
    $tanggalMulai = $_POST['tanggal_mulai'] ?? '';
    $tanggalAkhir = $_POST['tanggal_akhir'] ?? '';
} else {
    $redirect = '/admin/absensi/hari_ini.php';
    $tanggalMulai = $_POST['tanggal'] ?? date('Y-m-d');
    $tanggalAkhir = $tanggalMulai;
    $kelasId = null;
    $siswaId = null;
}

if (!$tanggalMulai || !$tanggalAkhir || $tanggalMulai > $tanggalAkhir) {
    header("Location: $redirect" . (strpos($redirect, '?') !== false ? '&' : '?') . "alpa_gagal=" . urlencode('Tanggal tidak valid.'));
    exit();
}

list($ok, $pesan) = prosesAlpa($tanggalMulai, $tanggalAkhir, $kelasId, $siswaId);
$param = $ok ? 'alpa_sukses' : 'alpa_gagal';
header("Location: $redirect" . (strpos($redirect, '?') !== false ? '&' : '?') . "$param=" . urlencode($pesan));
exit();
