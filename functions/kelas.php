<?php
require_once __DIR__ . '/../config/database.php';

function getAllKelas() {
    $db = getDBConnection();
    $stmt = $db->query("SELECT k.*, COUNT(s.id) as jumlah_siswa FROM kelas k LEFT JOIN siswa s ON k.id = s.kelas_id GROUP BY k.id ORDER BY k.nama_kelas");
    return $stmt->fetchAll();
}

function getKelasById($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM kelas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function tambahKelas($namaKelas) {
    $db = getDBConnection();
    $stmt = $db->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
    return $stmt->execute([$namaKelas]);
}

function editKelas($id, $namaKelas) {
    $db = getDBConnection();
    $stmt = $db->prepare("UPDATE kelas SET nama_kelas = ? WHERE id = ?");
    return $stmt->execute([$namaKelas, $id]);
}

function hapusKelas($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("DELETE FROM kelas WHERE id = ?");
    return $stmt->execute([$id]);
}

function isKelasHasSiswa($id) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM siswa WHERE kelas_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch()['total'] > 0;
}

function getTotalKelas() {
    $db = getDBConnection();
    return $db->query("SELECT COUNT(*) as total FROM kelas")->fetch()['total'];
}