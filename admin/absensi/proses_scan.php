<?php
require_once __DIR__ . '/../../includes/session.php';
security_bootstrap();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../includes/csrf.php';
$csrf = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['csrf_token']) || !is_string($csrf) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman scan.']);
    exit();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/siswa.php';
require_once __DIR__ . '/../../functions/absensi.php';

$token = trim($_POST['token'] ?? '');

if (empty($token)) {
    echo json_encode(['success' => false, 'message' => 'QR Code tidak valid.']);
    exit();
}

$siswa = getSiswaByQRToken($token);
if (!$siswa) {
    echo json_encode(['success' => false, 'message' => 'QR Code tidak valid.']);
    exit();
}

$today = date('Y-m-d');
$jam = date('H:i:s');

$existing = cekAbsensiHari($siswa['id'], $today);
if ($existing) {
    echo json_encode(['success' => false, 'message' => 'Siswa sudah melakukan absensi hari ini.']);
    exit();
}

$batasTerlambat = '07:30:00';
$status = ($jam > $batasTerlambat) ? 'Terlambat' : 'Hadir';

simpanAbsensi($siswa['id'], $today, $jam, $status);

echo json_encode([
    'success' => true,
    'nama' => $siswa['nama'],
    'kelas' => $siswa['nama_kelas'],
    'status' => $status,
    'jam' => date('H:i', strtotime($jam))
]);