# Task List - Absensi Siswa Berbasis QR Code

## Tahap 1: Setup Environment
- [✓] Buat `composer.json`
- [✓] Install composer dependencies
- [✓] Setup Tailwind CSS
- [✓] Buat struktur folder & file sesuai agent.md

## Tahap 2: Database
- [✓] Buat database MySQL
- [✓] Buat `database.sql` (tabel users, kelas, siswa, absensi)
- [✓] Isi data awal (admin default)
- [✓] Buat `.env` untuk konfigurasi database
- [✓] Buat `config/database.php`

## Tahap 3: Authentication
- [✓] Buat `auth/login.php`
- [✓] Buat `auth/logout.php`
- [✓] Buat `includes/auth.php` (cek session & role)
- [✓] Implementasi `password_hash()` dan `password_verify()`

## Tahap 4: Layout & Includes
- [✓] Buat `includes/header.php`
- [✓] Buat `includes/footer.php`
- [✓] Buat `includes/admin_sidebar.php`
- [✓] Buat `includes/siswa_sidebar.php`
- [✓] Buat `assets/css/style.css`
- [✓] Buat `assets/js/script.js`

## Tahap 5: CRUD Kelas
- [✓] Buat `functions/kelas.php`
- [✓] Buat form tambah kelas
- [✓] Buat form edit kelas
- [✓] Buat proses hapus kelas (cek siswa dulu)

## Tahap 6: CRUD Siswa
- [✓] Buat `functions/siswa.php`
- [✓] Buat `admin/siswa/index.php` (daftar kelas)
- [✓] Buat `admin/siswa/detail.php` (daftar siswa per kelas)
- [✓] Buat form tambah siswa
- [✓] Buat form edit siswa
- [✓] Buat proses hapus siswa
- [✓] Validasi NIS duplikat

## Tahap 7: QR Token & QR Code
- [✓] Buat `functions/qr.php`
- [✓] Auto-generate random token saat siswa dibuat
- [✓] Install library QR Code (composer)
- [✓] Buat `siswa/qr_saya.php`

## Tahap 8: QR Scanner
- [✓] Buat `admin/absensi/scan.php`
- [✓] Implementasi kamera browser
- [✓] Tampilkan feedback scan

## Tahap 9: Proses Absensi
- [✓] Buat `functions/absensi.php`
- [✓] Buat `admin/absensi/proses_scan.php`
- [✓] Cek absensi duplikat per hari
- [✓] Buat konfigurasi waktu (hadir/terlambat)

## Tahap 10: Absensi Hari Ini
- [✓] Buat `admin/absensi/hari_ini.php`
- [✓] Ringkasan status per kelas
- [✓] Tabel data absensi dengan aksi ubah status

## Tahap 11: Ubah Status (Izin/Sakit/Alpa)
- [✓] Buat `admin/absensi/ubah_status.php`
- [✓] Update data absensi (bukan insert baru)

## Tahap 12: Dashboard
- [✓] Buat `admin/dashboard.php`
- [✓] Buat `siswa/dashboard.php`
- [✓] Buat `index.php` (redirect ke login)

## Tahap 13: Rekap Absensi
- [✓] Buat `admin/absensi/rekap.php`
- [✓] Filter tanggal, kelas, siswa, status

## Tahap 14: Laporan
- [✓] Buat `admin/laporan/index.php`
- [✓] Buat `admin/laporan/cetak.php`
- [✓] Filter periode, kelas, siswa
- [✓] Dihapus, digabung ke Rekap + `admin/absensi/cetak.php`

## Tahap 15: Testing & Responsive
- [✓] Test login (admin, siswa, password salah)
- [✓] Test CRUD kelas & siswa
- [✓] Test scan QR (valid, tidak valid, duplikat)
- [✓] Test absensi (hadir, terlambat, izin, sakit, alpa)
- [✓] Test responsive (desktop, tablet, mobile)

## Tahap 16: Izin/Sakit & Proses Alpa
- [✓] Buat tabel `pengajuan` (database.sql + DB live `absensi_db`)
- [✓] Buat `functions/pengajuan.php` (ajukan, daftar, setujui, tolak)
- [✓] Buat `siswa/pengajuan.php` (form ajukan hari ini + riwayat pengajuan milik siswa)
- [✓] Tambah menu sidebar siswa & kartu akses cepat di dashboard siswa
- [✓] Buat `admin/absensi/pengajuan.php` (list + filter, tombol Setujui/Tolak, form input manual Izin/Sakit)
- [✓] Buat `admin/absensi/proses_pengajuan.php` (handler approve/reject + flash message)
- [✓] Tambah menu sidebar admin (Pengajuan)
- [✓] Tambah `hitungBelumAbsen()` & `prosesAlpa()` di `functions/absensi.php` (idempoten, guard 07:30)
- [✓] Buat `admin/absensi/proses_alpa.php` (handler tombol manual)
- [✓] Tambah tombol "Proses Alpa" di `hari_ini.php` (hari ini) & `rekap.php` (rentang filter)
- [✓] Update `README.md` (daftar fitur)
- [✓] Testing: `php -l` semua file + uji curl e2e (ajukan→approve→riwayat, tolak, input manual, Alpa pagi/berulang/rentang)
- [✓] Buat `admin/absensi/edit_pengajuan.php` + fungsi `editPengajuan()` (sinkron ke absensi jika Disetujui)
- [✓] Tambah aksi Hapus pengajuan (`hapusPengajuan()`, hapus baris absensi jika Disetujui)
- [✓] Testing edit & hapus pengajuan (sinkron absensi, hapus Disetujui, bentrok tanggal)

## Tahap 17: Redesign Profil & Ubah Password
- [✓] Redesign `siswa/profil.php` (avatar inisial, kartu info akun)
- [✓] Fitur ubah password siswa (validasi password lama, min 6, konfirmasi) + `ubahPasswordUser()` / `verifikasiPasswordUser()`
- [✓] Update `README.md` (Profil: data diri & ubah password)
- [✓] Testing: `php -l` + uji curl (tampilan, validasi password, login password baru, restore password)
- [✓] Ubah form password inline jadi tombol + modal
