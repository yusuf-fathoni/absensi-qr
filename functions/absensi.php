<?php
require_once __DIR__ . '/../config/database.php';

function getAbsensiHari($tanggal, $kelasId = null) {
    $db = getDBConnection();
    if ($kelasId) {
        $stmt = $db->prepare("SELECT a.*, s.nis, s.nama, k.nama_kelas FROM absensi a JOIN siswa s ON a.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id WHERE a.tanggal = ? AND s.kelas_id = ? ORDER BY s.nama");
        $stmt->execute([$tanggal, $kelasId]);
    } else {
        $stmt = $db->prepare("SELECT a.*, s.nis, s.nama, k.nama_kelas FROM absensi a JOIN siswa s ON a.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id WHERE a.tanggal = ? ORDER BY k.nama_kelas, s.nama");
        $stmt->execute([$tanggal]);
    }
    return $stmt->fetchAll();
}

function cekAbsensiHari($siswaId, $tanggal) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM absensi WHERE siswa_id = ? AND tanggal = ?");
    $stmt->execute([$siswaId, $tanggal]);
    return $stmt->fetch();
}

function simpanAbsensi($siswaId, $tanggal, $jam, $status, $keterangan = null) {
    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO absensi (siswa_id, tanggal, jam, status, keterangan) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$siswaId, $tanggal, $jam, $status, $keterangan]);
}

function ubahStatusAbsensi($id, $status, $keterangan = null) {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE absensi SET status = ?, keterangan = ? WHERE id = ?");
    return $stmt->execute([$status, $keterangan, $id]);
}

function getRekapAbsensi($tanggalMulai, $tanggalAkhir, $kelasId = null, $siswaId = null, $status = null) {
    $db = getDBConnection();
    $sql = "SELECT a.*, s.nis, s.nama, k.nama_kelas FROM absensi a JOIN siswa s ON a.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id WHERE a.tanggal BETWEEN ? AND ?";
    $params = [$tanggalMulai, $tanggalAkhir];

    if ($kelasId) { $sql .= " AND s.kelas_id = ?"; $params[] = $kelasId; }
    if ($siswaId) { $sql .= " AND a.siswa_id = ?"; $params[] = $siswaId; }
    if ($status) { $sql .= " AND a.status = ?"; $params[] = $status; }

    $sql .= " ORDER BY a.tanggal DESC, k.nama_kelas, s.nama";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function hitungRekap($tanggalMulai, $tanggalAkhir, $kelasId = null) {
    $db = getDBConnection();
    $sql = "SELECT a.status, COUNT(*) as jumlah FROM absensi a JOIN siswa s ON a.siswa_id = s.id WHERE a.tanggal BETWEEN ? AND ?";
    $params = [$tanggalMulai, $tanggalAkhir];

    if ($kelasId) { $sql .= " AND s.kelas_id = ?"; $params[] = $kelasId; }

    $sql .= " GROUP BY a.status";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return array_column($result, 'jumlah', 'status');
}

function getAbsensiSiswa($siswaId, $limit = null) {
    $db = getDBConnection();
    $sql = "SELECT * FROM absensi WHERE siswa_id = ? ORDER BY tanggal DESC";
    $params = [$siswaId];
    if ($limit) { $sql .= " LIMIT ?"; $params[] = $limit; }
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getAbsensiById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT a.*, s.nis, s.nama, k.nama_kelas FROM absensi a JOIN siswa s ON a.siswa_id = s.id JOIN kelas k ON s.kelas_id = k.id WHERE a.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function hitungBelumAbsen($tanggal, $kelasId = null, $siswaId = null) {
    $db = getDBConnection();
    $sql = "SELECT COUNT(*) FROM siswa s WHERE NOT EXISTS (SELECT 1 FROM absensi a WHERE a.siswa_id = s.id AND a.tanggal = ?)";
    $params = [$tanggal];
    if ($kelasId) {
        $sql .= " AND s.kelas_id = ?";
        $params[] = $kelasId;
    }
    if ($siswaId) {
        $sql .= " AND s.id = ?";
        $params[] = $siswaId;
    }
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

function prosesAlpa($tanggalMulai, $tanggalAkhir, $kelasId = null, $siswaId = null) {
    $db = getDBConnection();
    $batasTerlambat = '07:30:00';
    $today = date('Y-m-d');
    $total = 0;

    if ($tanggalMulai > $today || ($tanggalAkhir >= $today && date('H:i:s') < $batasTerlambat)) {
        return [false, 'Proses Alpa hanya bisa untuk hari ini setelah pukul 07:30, atau untuk tanggal sebelumnya.'];
    }

    $tanggalAkhir = min($tanggalAkhir, $today);

    $sql = "SELECT s.id FROM siswa s WHERE NOT EXISTS (SELECT 1 FROM absensi a WHERE a.siswa_id = s.id AND a.tanggal = ?)";
    $filterParams = [];
    if ($kelasId) {
        $sql .= " AND s.kelas_id = ?";
        $filterParams[] = $kelasId;
    }
    if ($siswaId) {
        $sql .= " AND s.id = ?";
        $filterParams[] = $siswaId;
    }
    $selectSiswa = $db->prepare($sql);
    $insert = $db->prepare("INSERT INTO absensi (siswa_id, tanggal, jam, status, keterangan) VALUES (?, ?, ?, 'Alpa', 'Ditandai otomatis via Proses Alpa')");

    $tanggal = $tanggalMulai;
    while ($tanggal <= $tanggalAkhir) {
        $selectSiswa->execute(array_merge([$tanggal], $filterParams));
        $belumAbsen = $selectSiswa->fetchAll(PDO::FETCH_COLUMN);
        foreach ($belumAbsen as $id) {
            if ($insert->execute([$id, $tanggal, $batasTerlambat])) {
                $total++;
            }
        }
        $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 day'));
    }

    return [true, $total > 0 ? "$total siswa ditandai Alpa." : 'Tidak ada siswa yang perlu ditandai Alpa.'];
}