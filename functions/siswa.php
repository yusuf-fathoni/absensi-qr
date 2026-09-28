<?php
require_once __DIR__ . '/../config/database.php';

function getAllSiswa() {
    $db = getDBConnection();
    $stmt = $db->query("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.kelas_id = k.id ORDER BY s.nama");
    return $stmt->fetchAll();
}

function getSiswaById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.kelas_id = k.id WHERE s.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getSiswaByKelas($kelasId) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.kelas_id = k.id WHERE s.kelas_id = ? ORDER BY s.nama");
    $stmt->execute([$kelasId]);
    return $stmt->fetchAll();
}

function getSiswaByUserId($userId) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.kelas_id = k.id WHERE s.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function tambahSiswa($nis, $nama, $kelasId, $userId = null) {
    $db = getDBConnection();
    $qrToken = bin2hex(random_bytes(32));
    $stmt = $db->prepare("INSERT INTO siswa (user_id, nis, nama, kelas_id, qr_token) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $nis, $nama, $kelasId, $qrToken]);
}

function editSiswa($id, $nis, $nama, $kelasId) {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE siswa SET nis = ?, nama = ?, kelas_id = ? WHERE id = ?");
    return $stmt->execute([$nis, $nama, $kelasId, $id]);
}

function hapusSiswa($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT user_id FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    $siswa = $stmt->fetch();
    $userId = $siswa['user_id'] ?? null;

    $stmt = $db->prepare("DELETE FROM siswa WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result && $userId) {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'siswa'");
        $stmt->execute([$userId]);
    }

    return $result;
}

function isNISExists($nis, $excludeId = null) {
    $db = getDBConnection();
    if ($excludeId) {
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM siswa WHERE nis = ? AND id != ?");
        $stmt->execute([$nis, $excludeId]);
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM siswa WHERE nis = ?");
        $stmt->execute([$nis]);
    }
    return $stmt->fetch()['total'] > 0;
}

function getSiswaByQRToken($token) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.kelas_id = k.id WHERE s.qr_token = ?");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

function getTotalSiswa() {
    $db = getDBConnection();
    return $db->query("SELECT COUNT(*) as total FROM siswa")->fetch()['total'];
}

function generateUserForSiswa($nis) {
    $db = getDBConnection();
    $username = $nis;
    $password = password_hash($nis, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'siswa')");
    $stmt->execute([$username, $password]);
    return $db->lastInsertId();
}

function verifikasiPasswordUser($userId, $passwordLama) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $hash = $stmt->fetchColumn();
    return $hash ? password_verify($passwordLama, $hash) : false;
}

function ubahPasswordUser($userId, $passwordBaru) {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([password_hash($passwordBaru, PASSWORD_DEFAULT), $userId]);
}