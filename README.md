# Absensi Siswa Berbasis QR Code

Sistem pencatatan kehadiran siswa saat datang ke sekolah menggunakan QR Code.

## Tech Stack

- PHP Native
- MySQL
- Tailwind CSS
- JavaScript
- Composer (bacon/bacon-qr-code)

## Fitur

### Admin

- **Login/Logout** - Autentikasi dengan session
- **Dashboard** - Statistik kehadiran (total siswa, kelas, hadir, terlambat, izin, sakit, alpa)
- **Scan QR Code** - Pindai QR siswa menggunakan kamera browser untuk mencatat kehadiran
- **Data Kelas** - CRUD kelas (tambah, edit, hapus)
- **Data Siswa** - CRUD siswa (tambah, edit, hapus) dengan QR token otomatis
- **Absensi Hari Ini** - Lihat daftar kehadiran siswa hari ini per kelas
- **Pengajuan Izin/Sakit** - Setujui/tolak, edit, hapus pengajuan siswa, plus input manual Izin/Sakit
- **Proses Alpa** - Tandai Alpa manual untuk siswa yang belum absen (hari ini / rentang rekap)
- **Ubah Status** - Ubah status absensi ke Izin/Sakit/Alpa dengan keterangan
- **Rekap Absensi** - Filter riwayat absensi berdasarkan tanggal, kelas, siswa, status, cetak print-friendly

### Siswa

- **Login/Logout** - Autentikasi dengan session
- **Dashboard** - Ringkasan absensi bulan ini
- **QR Saya** - Tampilkan QR Code pribadi untuk absensi
- **Riwayat Absensi** - Lihat riwayat kehadiran
- **Ajukan Izin/Sakit** - Ajukan izin/sakit untuk hari ini (menunggu persetujuan admin)
- **Profil** - Lihat data diri & ubah password

## Alur Kerja

```
Siswa datang ke sekolah
        ↓
Siswa menunjukkan QR Code pribadi
        ↓
Admin membuka menu Scan Absensi
        ↓
Admin memindai QR Code siswa
        ↓
Sistem membaca token dari QR
        ↓
Sistem mencari data siswa berdasarkan token
        ↓
Sistem mengecek apakah sudah absen hari ini
        ↓
Jika belum: simpan absensi (Hadir/Terlambat)
Jika sudah: tampilkan pesan "sudah absen"
```

## Status Absensi

| Status | Keterangan |
|--------|------------|
| Hadir | Scan sebelum batas waktu |
| Terlambat | Scan setelah batas waktu |
| Izin | Pengajuan siswa disetujui admin, atau input manual admin |
| Sakit | Pengajuan siswa disetujui admin, atau input manual admin |
| Alpa | Tidak absen, ditandai via tombol Proses Alpa |

## Struktur Database

- **users** - Akun login (admin & siswa)
- **kelas** - Data kelas
- **siswa** - Data siswa (terhubung ke users & kelas)
- **absensi** - Data kehadiran (terhubung ke siswa)
- **pengajuan** - Pengajuan izin/sakit siswa (terhubung ke siswa)

## Relasi

```
users → siswa → absensi
              ← kelas
      → pengajuan
```

- Satu user memiliki satu siswa
- Satu siswa memiliki satu kelas
- Satu siswa memiliki banyak data absensi
- Satu siswa hanya boleh memiliki satu absensi per hari
- Satu siswa hanya boleh mengajukan satu pengajuan per hari

## Instalasi

1. Clone atau download project ini
2. Buat database MySQL, import file `database.sql`
3. Rename `.env.example` ke `.env` (atau edit `.env` yang ada), sesuaikan konfigurasi database
4. Jalankan `composer install` di terminal
5. Buka browser, akses `index.php`

## Akun Default

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | Admin |
