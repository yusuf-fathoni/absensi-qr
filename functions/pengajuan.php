<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/absensi.php';

function ajukanPengajuan($siswaId, $tanggal, $jenis, $alasan) {
    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO pengajuan (siswa_id, tanggal, jenis, alasan) VALUES (?, ?, ?, ?)");
    try {
        return $stmt->execute([$siswaId, $tanggal, $jenis, $alasan]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return false;
        }
        throw $e;
    }
}

function getPengajuanById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT p.*, s.nis, s.nama, k.nama_kelas FROM pengajuan p JOIN siswa s ON p.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getPengajuanSiswa($siswaId) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM pengajuan WHERE siswa_id = ? ORDER BY tanggal DESC, created_at DESC");
    $stmt->execute([$siswaId]);
    return $stmt->fetchAll();
}

function getPengajuanSiswaTanggal($siswaId, $tanggal) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM pengajuan WHERE siswa_id = ? AND tanggal = ?");
    $stmt->execute([$siswaId, $tanggal]);
    return $stmt->fetch();
}

function getAllPengajuan($status = null) {
    $db = getDBConnection();
    if ($status) {
        $stmt = $db->prepare("SELECT p.*, s.nis, s.nama, k.nama_kelas FROM pengajuan p JOIN siswa s ON p.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id WHERE p.status = ? ORDER BY p.created_at DESC");
        $stmt->execute([$status]);
    } else {
        $stmt = $db->query("SELECT p.*, s.nis, s.nama, k.nama_kelas FROM pengajuan p JOIN siswa s ON p.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id ORDER BY p.created_at DESC");
    }
    return $stmt->fetchAll();
}

function hitungPengajuanMenunggu() {
    $db = getDBConnection();
    $stmt = $db->query("SELECT COUNT(*) FROM pengajuan WHERE status = 'Menunggu'");
    return (int)$stmt->fetchColumn();
}

function setujuiPengajuan($id, $adminNote = null) {
    $pengajuan = getPengajuanById($id);
    if (!$pengajuan || $pengajuan['status'] !== 'Menunggu') {
        return [false, 'Pengajuan tidak ditemukan atau sudah diproses.'];
    }
    if (cekAbsensiHari($pengajuan['siswa_id'], $pengajuan['tanggal'])) {
        return [false, 'Siswa sudah memiliki data absensi pada tanggal tersebut.'];
    }
    if (!simpanAbsensi($pengajuan['siswa_id'], $pengajuan['tanggal'], date('H:i:s'), $pengajuan['jenis'], $pengajuan['alasan'])) {
        return [false, 'Gagal menyimpan data absensi.'];
    }
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE pengajuan SET status = 'Disetujui', admin_note = ? WHERE id = ?");
    $stmt->execute([$adminNote, $id]);
    return [true, 'Pengajuan disetujui.'];
}

function tolakPengajuan($id, $adminNote = null) {
    $pengajuan = getPengajuanById($id);
    if (!$pengajuan || $pengajuan['status'] !== 'Menunggu') {
        return [false, 'Pengajuan tidak ditemukan atau sudah diproses.'];
    }
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE pengajuan SET status = 'Ditolak', admin_note = ? WHERE id = ?");
    $stmt->execute([$adminNote, $id]);
    return [true, 'Pengajuan ditolak.'];
}

function editPengajuan($id, $tanggal, $jenis, $alasan, $adminNote = null) {
    $pengajuan = getPengajuanById($id);
    if (!$pengajuan) {
        return [false, 'Pengajuan tidak ditemukan.'];
    }
    if (!in_array($jenis, ['Izin', 'Sakit'])) {
        return [false, 'Jenis harus Izin atau Sakit.'];
    }
    $alasan = trim($alasan);
    if ($alasan === '') {
        return [false, 'Alasan wajib diisi.'];
    }
    if ($tanggal > date('Y-m-d')) {
        return [false, 'Tanggal tidak boleh melebihi hari ini.'];
    }

    $db = getDBConnection();

    $stmt = $db->prepare("SELECT id FROM pengajuan WHERE siswa_id = ? AND tanggal = ? AND id != ?");
    $stmt->execute([$pengajuan['siswa_id'], $tanggal, $id]);
    if ($stmt->fetch()) {
        return [false, 'Siswa sudah memiliki pengajuan pada tanggal tersebut.'];
    }

    $tanggalLama = $pengajuan['tanggal'];

    if ($pengajuan['status'] === 'Disetujui') {
        $absensi = cekAbsensiHari($pengajuan['siswa_id'], $tanggalLama);
        if ($absensi) {
            if ($tanggal !== $tanggalLama) {
                $stmt = $db->prepare("SELECT id FROM absensi WHERE siswa_id = ? AND tanggal = ? AND id != ?");
                $stmt->execute([$pengajuan['siswa_id'], $tanggal, $absensi['id']]);
                if ($stmt->fetch()) {
                    return [false, 'Siswa sudah memiliki data absensi pada tanggal baru.'];
                }
            }
            $stmt = $db->prepare("UPDATE absensi SET tanggal = ?, status = ?, keterangan = ? WHERE id = ?");
            $stmt->execute([$tanggal, $jenis, $alasan, $absensi['id']]);
        }
    }

    $stmt = $db->prepare("UPDATE pengajuan SET tanggal = ?, jenis = ?, alasan = ?, admin_note = ? WHERE id = ?");
    $stmt->execute([$tanggal, $jenis, $alasan, $adminNote, $id]);
    return [true, 'Pengajuan berhasil diperbarui.'];
}

function hapusPengajuan($id) {
    $pengajuan = getPengajuanById($id);
    if (!$pengajuan) {
        return [false, 'Pengajuan tidak ditemukan.'];
    }
    $db = getDBConnection();
    if ($pengajuan['status'] === 'Disetujui') {
        $stmt = $db->prepare("DELETE FROM absensi WHERE siswa_id = ? AND tanggal = ?");
        $stmt->execute([$pengajuan['siswa_id'], $pengajuan['tanggal']]);
    }
    $stmt = $db->prepare("DELETE FROM pengajuan WHERE id = ?");
    $stmt->execute([$id]);
    return [true, 'Pengajuan dihapus.'];
}
