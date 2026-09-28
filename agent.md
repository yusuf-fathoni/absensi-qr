# AGENT.md

## 1. Project Overview

Project ini adalah website **Absensi Siswa Berbasis QR Code** untuk mencatat kehadiran siswa saat datang ke sekolah pada pagi hari.

Sistem menggunakan:

* PHP Native
* MySQL
* Tailwind CSS
* JavaScript
* HTML
* Composer
* Library QR Code
* QR Code Scanner menggunakan kamera browser

Sistem hanya memiliki **2 role**:

1. Admin
2. Siswa

Tidak ada role guru.

---

# 2. Konsep Sistem

Siswa memiliki **QR Code pribadi**.

Saat siswa datang ke sekolah:

```text
Siswa datang
    ↓
Siswa menunjukkan QR pribadi
    ↓
Admin membuka Scan Absensi
    ↓
Admin scan QR siswa
    ↓
Sistem membaca QR Token
    ↓
Sistem mencari data siswa
    ↓
Cek absensi hari ini
    ↓
Simpan absensi
```

QR Code dibuat secara otomatis oleh sistem ketika data siswa dibuat.

Admin **tidak memiliki tombol Generate QR**.

QR Code hanya merepresentasikan token unik milik siswa.

Database menyimpan:

```text
qr_token
```

bukan gambar QR.

---

# 3. Role dan Hak Akses

## Admin

Admin dapat:

* Login
* Melihat dashboard
* Scan QR siswa
* Melihat data kelas
* CRUD kelas
* Melihat siswa berdasarkan kelas
* CRUD siswa
* Melihat QR siswa
* Melihat absensi hari ini
* Mengubah status absensi menjadi Izin/Sakit/Alpa
* Melihat rekap absensi
* Melihat laporan

## Siswa

Siswa dapat:

* Login
* Melihat dashboard
* Melihat QR pribadi
* Melihat riwayat absensi
* Melihat profil sendiri

Siswa tidak dapat:

* Melihat siswa lain
* Mengubah data siswa
* Mengubah absensi
* Membuat QR
* Menghapus data

---

# 4. Struktur Folder

Gunakan struktur berikut dan jangan mengubah struktur utama tanpa alasan yang jelas.

```text
qr-absensi-sekolah/
│
├── config/
│   └── database.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
│
├── auth/
│   ├── login.php
│   └── logout.php
│
├── admin/
│   ├── dashboard.php
│   │
│   ├── siswa/
│   │   ├── index.php
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   ├── detail.php
│   │   └── hapus.php
│   │
│   ├── kelas/
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   └── hapus.php
│   │
│   ├── absensi/
│   │   ├── scan.php
│   │   ├── proses_scan.php
│   │   ├── hari_ini.php
│   │   ├── ubah_status.php
│   │   └── rekap.php
│   │
│   └── laporan/
│       ├── index.php
│       └── cetak.php
│
├── siswa/
│   ├── dashboard.php
│   ├── qr_saya.php
│   ├── riwayat.php
│   └── profil.php
│
├── includes/
│   ├── auth.php
│   ├── admin_sidebar.php
│   ├── siswa_sidebar.php
│   ├── header.php
│   └── footer.php
│
├── functions/
│   ├── siswa.php
│   ├── kelas.php
│   ├── absensi.php
│   └── qr.php
│
├── vendor/
│
├── index.php
├── composer.json
├── .env
├── database.sql
└── AGENT.md
```

---

# 5. Penjelasan File

## config/database.php

Digunakan untuk koneksi PHP ke MySQL.

Gunakan konfigurasi dari `.env`.

Jangan menuliskan password database langsung di banyak file.

---

## assets/css/style.css

Berisi CSS tambahan yang tidak praktis dibuat menggunakan Tailwind.

Gunakan Tailwind sebagai CSS utama.

Jangan membuat CSS panjang jika dapat diselesaikan menggunakan Tailwind utility class.

---

## assets/js/script.js

Berisi JavaScript umum seperti:

* Konfirmasi hapus
* Toggle sidebar
* Alert
* Interaksi UI sederhana

JavaScript khusus QR Scanner tetap berada di halaman atau file yang berkaitan dengan scanner.

---

# 6. Authentication

## auth/login.php

Digunakan untuk login Admin dan Siswa.

Form:

```text
Username
Password

[ Login ]
```

Setelah login:

```text
Admin
→ admin/dashboard.php

Siswa
→ siswa/dashboard.php
```

Gunakan PHP Session.

---

## auth/logout.php

Menghapus session dan mengarahkan pengguna kembali ke login.

---

# 7. Authorization

File:

```text
includes/auth.php
```

Digunakan untuk memastikan pengguna sudah login.

Role harus diperiksa sebelum membuka halaman.

Contoh konsep:

```text
Admin
→ hanya halaman admin

Siswa
→ hanya halaman siswa
```

Jangan hanya menyembunyikan menu menggunakan HTML.

Backend PHP juga harus memeriksa role.

---

# 8. Menu Admin

Sidebar Admin:

```text
Dashboard
Scan Absensi
Data Siswa
Absensi
Laporan
Logout
```

## Dashboard

File:

```text
admin/dashboard.php
```

Tampilkan informasi ringkas seperti:

```text
Total Siswa
Total Kelas
Hadir Hari Ini
Terlambat
Izin
Sakit
Alpa
```

Dashboard harus sederhana dan mudah dibaca.

---

# 9. Data Siswa

Menu:

```text
Data Siswa
```

Data kelas dan siswa tetap berada dalam satu bagian.

## Halaman utama

```text
admin/siswa/index.php
```

Halaman menampilkan daftar kelas.

Contoh:

```text
X RPL A
30 Siswa
[Lihat Siswa]

X RPL B
28 Siswa
[Lihat Siswa]

XI RPL A
30 Siswa
[Lihat Siswa]
```

Admin tidak langsung melihat seluruh siswa dari semua kelas.

Admin memilih kelas terlebih dahulu.

Flow:

```text
Data Siswa
    ↓
Daftar Kelas
    ↓
Pilih Kelas
    ↓
Daftar Siswa dalam Kelas
```

---

# 10. CRUD Kelas

Kelas memiliki CRUD lengkap.

File:

```text
admin/kelas/tambah.php
admin/kelas/edit.php
admin/kelas/hapus.php
```

Data kelas:

```text
id
nama_kelas
```

Contoh:

```text
X RPL A
X RPL B
XI RPL A
XI RPL B
XII RPL A
```

Admin dapat:

* Tambah kelas
* Edit kelas
* Hapus kelas

Sebelum menghapus kelas, cek apakah masih terdapat siswa di kelas tersebut.

Jangan menghapus kelas secara sembarangan jika masih digunakan oleh data siswa.

---

# 11. CRUD Siswa

File:

```text
admin/siswa/tambah.php
admin/siswa/edit.php
admin/siswa/detail.php
admin/siswa/hapus.php
```

Form tambah siswa:

```text
NIS
Nama
Kelas
```

NIS diinput oleh admin berdasarkan data resmi sekolah.

NIS tidak dibuat secara random oleh sistem.

Kelas dipilih dari tabel `kelas`.

QR Token dibuat otomatis oleh sistem.

Contoh:

```text
NIS
12345678

Nama
Ahmad Fauzan

Kelas
XI RPL A

QR Token
dibuat otomatis
```

---

# 12. QR Code

File:

```text
functions/qr.php
```

Digunakan untuk fungsi yang berkaitan dengan QR.

Setiap siswa memiliki satu QR unik.

Ketika siswa dibuat:

```text
Generate random unique token
        ↓
Simpan ke siswa.qr_token
        ↓
QR ditampilkan berdasarkan token
```

QR tidak perlu disimpan sebagai file gambar di server jika library yang digunakan dapat melakukan rendering secara langsung.

QR harus menggunakan token yang sulit ditebak.

Jangan menggunakan:

```text
qr = NIS
```

Gunakan token unik/random.

---

# 13. QR Siswa

File:

```text
siswa/qr_saya.php
```

Halaman menampilkan:

```text
QR ABSENSI SAYA

[ QR CODE ]

Nama:
Ahmad Fauzan

NIS:
12345678

Kelas:
XI RPL A
```

QR hanya bisa digunakan untuk identitas siswa tersebut.

---

# 14. Scan Absensi

File:

```text
admin/absensi/scan.php
```

Admin menggunakan kamera perangkat untuk melakukan scan QR.

Flow:

```text
Admin buka Scan Absensi
        ↓
Aktifkan kamera
        ↓
Scan QR siswa
        ↓
Ambil QR Token
        ↓
Kirim token ke PHP
        ↓
Cari siswa
        ↓
Cek absensi hari ini
        ↓
Simpan absensi
```

Scanner harus memberikan feedback yang jelas:

### Berhasil

```text
Absensi berhasil

Ahmad Fauzan
XI RPL A
Hadir
07:12
```

### Sudah absen

```text
Siswa sudah melakukan absensi hari ini.
```

### QR tidak valid

```text
QR Code tidak valid.
```

---

# 15. Status Absensi

Gunakan status:

```text
Hadir
Terlambat
Izin
Sakit
Alpa
```

Contoh aturan waktu:

```text
06:00 - 07:30
Hadir

07:31 - 08:00
Terlambat

Setelah batas waktu
Belum Hadir / kemudian Alpa
```

Waktu dan batas keterlambatan harus dibuat sebagai konfigurasi yang mudah diubah jika diperlukan.

Jangan menanamkan waktu di banyak file.

---

# 16. Absensi Izin dan Sakit

Siswa tidak melakukan scan untuk status Izin atau Sakit.

Admin dapat mengubah status siswa melalui:

```text
admin/absensi/ubah_status.php
```

Contoh:

```text
Nama:
Ahmad Fauzan

Status:
[ Izin ]

Keterangan:
Keperluan keluarga

[ Simpan ]
```

Untuk sakit:

```text
Status:
Sakit

Keterangan:
Demam
```

---

# 17. Alpa

Siswa yang tidak melakukan scan dan tidak memiliki keterangan dapat dianggap Alpa setelah batas waktu absensi selesai.

Jangan langsung menjadikan siswa Alpa ketika pagi baru dimulai.

Konsep:

```text
Pagi
↓
Belum scan
↓
Status belum hadir

Batas absensi selesai
↓
Tidak ada keterangan
↓
Alpa
```

Implementasi Alpa dapat dilakukan saat admin membuka rekap atau menggunakan proses otomatis jika nantinya diperlukan.

---

# 18. Absensi Hari Ini

File:

```text
admin/absensi/hari_ini.php
```

Tampilkan:

```text
Absensi Hari Ini
20 September 2026

Pilih Kelas:
[ XI RPL A ▼ ]

Hadir       26
Terlambat    2
Izin         1
Sakit        1
Alpa         0
```

Tabel:

```text
No
NIS
Nama
Jam
Status
Keterangan
Aksi
```

Admin dapat mengubah status jika diperlukan.

---

# 19. Rekap Absensi

File:

```text
admin/absensi/rekap.php
```

Digunakan untuk melihat riwayat absensi.

Filter:

```text
Tanggal
Kelas
Siswa
Status
```

Contoh:

```text
01 September - 30 September 2026
Kelas: XI RPL A
```

Tampilkan data absensi sesuai filter.

---

# 20. Laporan

File:

```text
admin/laporan/index.php
admin/laporan/cetak.php
```

Laporan dapat difilter berdasarkan:

```text
Periode
Kelas
Siswa
```

Laporan menampilkan jumlah:

```text
Hadir
Terlambat
Izin
Sakit
Alpa
```

Laporan dapat dicetak menggunakan halaman print-friendly.

---

# 21. Database

Gunakan MySQL.

Tabel utama:

```text
users
kelas
siswa
absensi
```

## users

```text
id
username
password
role
created_at
```

Role:

```text
admin
siswa
```

---

## kelas

```text
id
nama_kelas
created_at
updated_at
```

---

## siswa

```text
id
user_id
nis
nama
kelas_id
qr_token
created_at
updated_at
```

Relasi:

```text
users
  ↓
siswa
  ↓
kelas
```

`user_id` digunakan jika siswa memiliki akun login.

`kelas_id` menghubungkan siswa dengan kelas.

`qr_token` digunakan untuk QR pribadi siswa.

---

## absensi

```text
id
siswa_id
tanggal
jam
status
keterangan
created_at
```

Relasi:

```text
siswa
   ↓
absensi
```

Satu siswa dapat memiliki banyak data absensi berdasarkan tanggal.

---

# 22. Aturan Database

NIS harus unik.

QR Token harus unik.

Untuk absensi, satu siswa hanya boleh memiliki satu absensi utama per tanggal.

Contoh:

```text
siswa_id = 10
tanggal = 2026-09-20
```

Tidak boleh membuat data absensi kedua untuk siswa yang sama pada tanggal tersebut kecuali sistem memang dirancang untuk mendukung perubahan status.

Jika admin mengubah status:

```text
Hadir → Izin
```

update data yang sudah ada, bukan membuat data duplikat.

---

# 23. Keamanan

Gunakan:

* `password_hash()` untuk password
* `password_verify()` untuk login
* Prepared Statement / PDO
* Session authentication
* Role authorization
* CSRF protection untuk form penting
* Validasi input
* Escape output menggunakan `htmlspecialchars()`

Jangan:

* Menyimpan password plaintext
* Membuat query SQL dari input user secara langsung
* Mempercayai role dari frontend
* Menggunakan NIS sebagai QR token
* Menaruh password database di source code

---

# 24. Tailwind CSS

Gunakan **Tailwind CSS** sebagai framework UI utama.

Jangan menggunakan Bootstrap.

Prioritaskan utility class Tailwind seperti:

```text
flex
grid
p-4
px-4
py-2
rounded-lg
shadow
border
bg-white
text-gray-700
text-blue-600
```

Gunakan desain yang:

* Bersih
* Modern
* Responsive
* Mudah dibaca
* Tidak terlalu ramai

---

# 25. Desain UI

Gunakan tema utama:

```text
Putih
Abu-abu
Biru
```

Gunakan layout dashboard:

```text
┌──────────────┬──────────────────────────┐
│              │                          │
│   Sidebar    │       Content            │
│              │                          │
│ Dashboard    │       Dashboard          │
│ Scan QR      │                          │
│ Data Siswa   │       Cards              │
│ Absensi      │                          │
│ Laporan      │       Table              │
│              │                          │
└──────────────┴──────────────────────────┘
```

Sidebar harus responsive.

Pada mobile, sidebar dapat menjadi drawer atau menu toggle.

---

# 26. Komponen UI yang Konsisten

Gunakan pola yang konsisten untuk:

### Button

```text
Primary
Secondary
Danger
```

### Form

Setiap input memiliki:

```text
Label
Input
Error message
```

### Table

Gunakan:

```text
Header
Rows
Empty state
Pagination jika diperlukan
```

### Modal

Gunakan untuk aksi sederhana seperti:

```text
Konfirmasi hapus
Ubah status absensi
```

---

# 27. Empty State

Jangan menampilkan halaman kosong.

Jika tidak ada siswa:

```text
Belum ada siswa pada kelas ini.
[ + Tambah Siswa ]
```

Jika belum ada absensi:

```text
Belum ada data absensi hari ini.
```

---

# 28. Validasi

Validasi dilakukan di frontend dan backend.

Contoh tambah siswa:

```text
NIS wajib diisi
Nama wajib diisi
Kelas wajib dipilih
NIS tidak boleh duplikat
```

Backend tetap wajib melakukan validasi meskipun frontend sudah melakukan validasi.

---

# 29. Prinsip Coding

Gunakan kode yang:

* Sederhana
* Mudah dibaca
* Mudah dipelihara
* Tidak duplikasi berlebihan
* Memisahkan logic dan tampilan sebisa mungkin

Gunakan file:

```text
functions/
```

untuk fungsi yang digunakan berulang.

Gunakan:

```text
includes/
```

untuk bagian layout dan authentication yang digunakan berulang.

---

# 30. Jangan Membuat Fitur yang Tidak Diminta

Jangan menambahkan:

* Guru
* Jadwal pelajaran
* Absensi per mata pelajaran
* Absensi masuk kelas
* Absensi pulang
* QR guru
* QR kelas
* Generate QR manual oleh admin
* Sistem pembayaran
* Sistem nilai

Fokus project hanya pada:

```text
Absensi kedatangan siswa pada pagi hari
menggunakan QR pribadi siswa
yang dipindai oleh admin.
```

---

# 31. Urutan Pengerjaan

Kerjakan project secara bertahap.

### Tahap 1

Setup:

```text
PHP
MySQL
Tailwind CSS
Composer
```

### Tahap 2

Buat database:

```text
users
kelas
siswa
absensi
```

### Tahap 3

Buat authentication:

```text
Login
Logout
Session
Role
```

### Tahap 4

Buat CRUD kelas.

### Tahap 5

Buat CRUD siswa.

### Tahap 6

Tambahkan QR Token otomatis saat siswa dibuat.

### Tahap 7

Buat halaman QR siswa.

### Tahap 8

Buat QR Scanner admin.

### Tahap 9

Buat proses absensi.

### Tahap 10

Buat absensi hari ini.

### Tahap 11

Buat Izin/Sakit/Alpa.

### Tahap 12

Buat rekap.

### Tahap 13

Buat laporan.

### Tahap 14

Buat dashboard.

### Tahap 15

Testing dan perbaikan responsive UI.

---

# 32. Testing Minimum

Sebelum project dianggap selesai, test:

### Login

* Login admin
* Login siswa
* Password salah
* User belum login membuka halaman protected

### Siswa

* Tambah siswa
* Edit siswa
* Hapus siswa
* NIS duplikat
* Pilih kelas
* QR otomatis dibuat

### Kelas

* Tambah kelas
* Edit kelas
* Hapus kelas
* Kelas yang masih memiliki siswa

### QR

* Scan QR valid
* Scan QR tidak valid
* Scan QR siswa yang sudah absen
* Scan QR menggunakan kamera

### Absensi

* Hadir
* Terlambat
* Izin
* Sakit
* Alpa
* Satu siswa tidak memiliki absensi duplikat dalam satu hari

### Responsive

Test minimal:

```text
Desktop
Tablet
Mobile
```

---

# 33. Prinsip Utama Project

Prioritas utama:

```text
Fungsional
    ↓
Aman
    ↓
Mudah digunakan
    ↓
Responsive
    ↓
Tampilan rapi
```

Jangan mengorbankan fungsi dan keamanan hanya demi tampilan.

Setiap fitur harus mengikuti konsep utama project dan struktur folder yang telah ditentukan.
