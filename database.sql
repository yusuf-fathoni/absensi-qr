CREATE DATABASE IF NOT EXISTS absensi_db;
USE absensi_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'siswa') NOT NULL DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nis VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    kelas_id INT NOT NULL,
    qr_token VARCHAR(64) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam TIME NOT NULL,
    status ENUM('Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa') NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    UNIQUE KEY unique_absensi (siswa_id, tanggal)
) ENGINE=InnoDB;

CREATE TABLE pengajuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jenis ENUM('Izin', 'Sakit') NOT NULL,
    alasan TEXT NOT NULL,
    status ENUM('Menunggu', 'Disetujui', 'Ditolak') NOT NULL DEFAULT 'Menunggu',
    admin_note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_pengajuan (siswa_id, tanggal),
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Data admin default (password: admin123)
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$pAVkQr0t6ySHJ7mdjQwDD.aOTurFjx5XPuGgurFC2Xzn5XFEahV0K', 'admin');

-- Data kelas contoh
INSERT INTO kelas (nama_kelas) VALUES
('X RPL A'),
('X RPL B'),
('XI RPL A'),
('XI RPL B'),
('XII RPL A');

-- Data siswa contoh (akun login siswa, username & password = NIS)
INSERT INTO users (username, password, role) VALUES
('1001', '$2y$10$KNmg9JPQAa3ynssBiP5zWeX.X4Vp2t6X0Di/HByrgeBkDOE.M5X7G', 'siswa'),
('1002', '$2y$10$NTxNQqpJxtFotdXxwLDAH.W7d2QR531xpDiPnGm/BQA7e9HTNEsFO', 'siswa'),
('1003', '$2y$10$XoaIEPx0yMBMm5o4xpxb1.q7oFLjIINNvsF/m1F/0JfekjeKZusoK', 'siswa'),
('1004', '$2y$10$SqoMya0pKG5BDNEE7FJwTeRfdNfXGVxpIfBTxeDzS/QN3gzcJ6yiG', 'siswa'),
('1005', '$2y$10$MLZHvNzQoCC7GUBxiE3qvOgXCnBEx6/Xb3ggvhEuPTSKDXFpS2pPe', 'siswa'),
('1006', '$2y$10$RQTubDsaDzvVFnHOfKQnV.bVGp23HQ8x9gjdTjlwGHSKwHgwfUU4K', 'siswa'),
('1007', '$2y$10$FKkftiqMA43sQcJw30X.hOjfrlaX2Jy2l0eSg1QJ/b.7zwlhDkHU.', 'siswa'),
('1008', '$2y$10$0Sr.6rEi/7EBQ6zmrLHlIO3RncW/mhXgsWONLbA4a3jnVM50TpuX6', 'siswa'),
('1009', '$2y$10$61f6zJtMrncRagEMit.jXuNKidKDWCW07w2mzQBX1jpM13IOP5ds6', 'siswa'),
('1010', '$2y$10$Q9nJ8zyDWENySc6RugVYauVF5pxjFDMJMnz/nFOIjhGpSdEnHFJTi', 'siswa'),
('1011', '$2y$10$IZGOHkN1yWRph3hTc0T62evxxkh.JOsyzDByKK8nA3IkV6694jGgG', 'siswa'),
('1012', '$2y$10$LqqqAtwPpAyf60oHZZoK4eWLtq/A4qYQtzFhD8ZToWje1lzCc5GZW', 'siswa'),
('1013', '$2y$10$Sk8MW5dDu5eq7KD99ikQUeqmo97USNG/eUpIpSwqNG0258VjxU9Xm', 'siswa'),
('1014', '$2y$10$02HrHCaFJ4GCFF4o4wnwhOTE63RR7pqpKsmjywcO3XaCmlIYwyehq', 'siswa'),
('1015', '$2y$10$iegDzgw5nc/VMiTaRm4i0uaKy8lDVQLL8muIZmzFs1qASwmg6tpli', 'siswa');

-- Data siswa (user_id 2-16 mengikuti urutan users di atas)
INSERT INTO siswa (user_id, nis, nama, kelas_id, qr_token) VALUES
(2, '1001', 'Ahmad Fauzan', 1, 'a1f3c8e9d2b44a6f8e1c7d3b5a92f0e6d4c8b1a3f5e2d9c7b6a4f8e1d3c5b7a9'),
(3, '1002', 'Budi Santoso', 1, 'b2e4d9f0a3c55b7f9f2d8e4c6b03a1f7e5d9c2b4a6f8e3d0c9b7a5f2e4c6d8b0'),
(4, '1003', 'Citra Lestari', 1, 'c3f5e0a1b4d66c8g0a3e9f5d7c14b2f8e6d0c3b5a7f9e4d1c0b8a6f3e5d7c9'),
(5, '1004', 'Dewi Anggraini', 2, 'd4a6f1b2c5e77d9h1b4f0a6e8d25c3g9f7e1d4c6b8a0f5e2d1c9b7a4f6e8d0'),
(6, '1005', 'Eko Prasetyo', 2, 'e5b7g2c3d6f88e0i2c5g1b7f9e36d4h0g8f2e5d7c9b1a6f3e2d0c8b5g7f9e1'),
(7, '1006', 'Fitri Ramadhani', 2, 'f6c8h3d4e7g99f1j3d6h2c8g0e47f5i1h9g3f6e8d0c2b7g4f3e1d9c6h8g0f2'),
(8, '1007', 'Galih Nugroho', 3, 'g7d9i4e5f8h00g2k4e7i3d9h1f58g6j2i0h4g7f9e1d3c8h5g4f2e0d7i9h1g3'),
(9, '1008', 'Hana Safitri', 3, 'h8e0j5f6g9i11h3l5f8j4e0i2g69h7k3j1i5h8g0f2e4d9i6h5g3f1e8j0i2h4'),
(10, '1009', 'Indra Wijaya', 3, 'i9f1k6g7h0j22i4m6g9k5f1j3h70i8l4k2j6i9h1g3f5e0j7i6h4g2f9k1j3i5'),
(11, '1010', 'Joko Susilo', 4, 'j0g2l7h8i1k33j5n7h0l6g2k4i81j9m5l3k7j0h2g4f6a1k8j7i5h3g0l2k4j6'),
(12, '1011', 'Kartika Sari', 4, 'k1h3m8i9j2l44k6o8i1m7h3l5j92k0n6m4l8k1i3h5g7b2l9k8j6i4h1m3l5k7'),
(13, '1012', 'Lina Marlina', 4, 'l2i4n9j0k3m55l7p9j2n8i4m6k03l1o7n5m9l2j4i6h8c3m0l9k7j5i2n4m6l8'),
(14, '1013', 'Muhammad Rizky', 5, 'm3j5o0k1l4n66m8q0k3o9j5n7l14m2p8o6n0m3k5j7i9d4n1m0l8k6j3o5n7m9'),
(15, '1014', 'Nurul Handayani', 5, 'n4k6p1l2m5o77n9r1l4p0k6o8m25n3q9p7o1n4l6k8j0e5o2n1m9l7k4p6o8n0'),
(16, '1015', 'Oka Saputra', 5, 'o5l7q2m3n6p88o0s2m5q1l7p9n36o4r0q8p2o5m7l9k1f6p3o2n0m8l5q7p9o1');