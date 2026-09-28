<?php
require_once __DIR__ . '/../../includes/session.php';
security_bootstrap();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/absensi/pengajuan.php");
    exit();
}

require_once __DIR__ . '/../../includes/csrf.php';
verify_csrf();

require_once __DIR__ . '/../../functions/siswa.php';
require_once __DIR__ . '/../../functions/absensi.php';
require_once __DIR__ . '/../../functions/pengajuan.php';

$aksi = $_POST['aksi'] ?? '';

function redirectPengajuan($sukses = null, $gagal = null) {
    $url = '/admin/absensi/pengajuan.php';
    if ($sukses) {
        $url .= '?sukses=' . urlencode($sukses);
    } elseif ($gagal) {
        $url .= '?gagal=' . urlencode($gagal);
    }
    header("Location: $url");
    exit();
}

switch ($aksi) {
    case 'setujui':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            redirectPengajuan(null, 'Pengajuan tidak valid.');
        }
        list($ok, $pesan) = setujuiPengajuan($id);
        $ok ? redirectPengajuan($pesan) : redirectPengajuan(null, $pesan);
        break;

    case 'tolak':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            redirectPengajuan(null, 'Pengajuan tidak valid.');
        }
        $adminNote = trim($_POST['admin_note'] ?? '');
        list($ok, $pesan) = tolakPengajuan($id, $adminNote !== '' ? $adminNote : null);
        $ok ? redirectPengajuan($pesan) : redirectPengajuan(null, $pesan);
        break;

    case 'manual':
        $siswaId = $_POST['siswa_id'] ?? null;
        $tanggal = $_POST['tanggal'] ?? '';
        $jenis = $_POST['jenis'] ?? '';
        $keterangan = trim($_POST['keterangan'] ?? '');

        if (!$siswaId || !getSiswaById($siswaId)) {
            redirectPengajuan(null, 'Siswa tidak valid.');
        }
        if (!$tanggal || $tanggal > date('Y-m-d')) {
            redirectPengajuan(null, 'Tanggal tidak valid.');
        }
        if (!in_array($jenis, ['Izin', 'Sakit'])) {
            redirectPengajuan(null, 'Jenis harus Izin atau Sakit.');
        }
        if ($keterangan === '') {
            redirectPengajuan(null, 'Keterangan wajib diisi.');
        }
        if (cekAbsensiHari($siswaId, $tanggal)) {
            redirectPengajuan(null, 'Siswa sudah memiliki data absensi pada tanggal tersebut.');
        }
        if (simpanAbsensi($siswaId, $tanggal, date('H:i:s'), $jenis, $keterangan)) {
            redirectPengajuan("Absensi $jenis berhasil disimpan.");
        }
        redirectPengajuan(null, 'Gagal menyimpan data absensi.');
        break;

    case 'hapus':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            redirectPengajuan(null, 'Pengajuan tidak valid.');
        }
        list($ok, $pesan) = hapusPengajuan($id);
        $ok ? redirectPengajuan($pesan) : redirectPengajuan(null, $pesan);
        break;

    default:
        redirectPengajuan(null, 'Aksi tidak dikenal.');
}
