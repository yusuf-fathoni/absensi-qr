<?php
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../functions/absensi.php';

$tanggalMulai = $_GET['tanggal_mulai'] ?? date('Y-m-01');
$tanggalAkhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');
$kelasId = $_GET['kelas_id'] ?? null;
$siswaId = $_GET['siswa_id'] ?? null;
$status = $_GET['status'] ?? null;

$rekapList = getRekapAbsensi($tanggalMulai, $tanggalAkhir, $kelasId, $siswaId, $status);
$rekap = hitungRekap($tanggalMulai, $tanggalAkhir, $kelasId);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
    <style>
        @page { size: A4 portrait; margin: 12mm; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; color: #1f2937; }
        h1 { text-align: center; font-size: 17px; margin: 0 0 4px; }
        h2 { text-align: center; font-size: 13px; font-weight: normal; color: #6b7280; margin: 0 0 18px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #d1d5db; padding: 7px 8px; word-wrap: break-word; overflow-wrap: break-word; }
        th { background: #e5e7eb; font-size: 11px; text-transform: uppercase; text-align: center; }
        td { font-size: 11.5px; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        th.center, td.center { text-align: center; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        .summary { display: flex; justify-content: center; gap: 10px; margin-bottom: 18px; }
        .summary-item { border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb; padding: 7px 16px; text-align: center; min-width: 78px; font-size: 11px; color: #6b7280; }
        .summary-item strong { display: block; font-size: 19px; margin-bottom: 2px; }
        .s-hadir strong { color: #16a34a; }
        .s-terlambat strong { color: #ea580c; }
        .s-izin strong { color: #4f46e5; }
        .s-sakit strong { color: #9333ea; }
        .s-alpa strong { color: #dc2626; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10.5px; font-weight: bold; }
        .b-hadir { background: #dcfce7; color: #166534; }
        .b-terlambat { background: #ffedd5; color: #9a3412; }
        .b-izin { background: #e0e7ff; color: #3730a3; }
        .b-sakit { background: #f3e8ff; color: #6b21a8; }
        .b-alpa { background: #fee2e2; color: #991b1b; }
        .empty { text-align: center; color: #9ca3af; padding: 30px 0; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <h1>REKAP ABSENSI SISWA</h1>
    <h2>Periode: <?= date('d M Y', strtotime($tanggalMulai)) ?> - <?= date('d M Y', strtotime($tanggalAkhir)) ?></h2>

    <div class="summary">
        <div class="summary-item s-hadir"><strong><?= $rekap['Hadir'] ?? 0 ?></strong>Hadir</div>
        <div class="summary-item s-terlambat"><strong><?= $rekap['Terlambat'] ?? 0 ?></strong>Terlambat</div>
        <div class="summary-item s-izin"><strong><?= $rekap['Izin'] ?? 0 ?></strong>Izin</div>
        <div class="summary-item s-sakit"><strong><?= $rekap['Sakit'] ?? 0 ?></strong>Sakit</div>
        <div class="summary-item s-alpa"><strong><?= $rekap['Alpa'] ?? 0 ?></strong>Alpa</div>
    </div>

    <?php if (empty($rekapList)): ?>
        <p class="empty">Tidak ada data absensi untuk periode ini.</p>
    <?php else: ?>
        <table>
            <colgroup>
                <col style="width:6%">
                <col style="width:17%">
                <col style="width:10%">
                <col style="width:23%">
                <col style="width:17%">
                <col style="width:10%">
                <col style="width:17%">
            </colgroup>
            <thead>
                <tr>
                    <th class="center">No</th>
                    <th class="center">Tanggal</th>
                    <th class="center">NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th class="center">Jam</th>
                    <th class="center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $badgeClass = [
                    'Hadir' => 'b-hadir',
                    'Terlambat' => 'b-terlambat',
                    'Izin' => 'b-izin',
                    'Sakit' => 'b-sakit',
                    'Alpa' => 'b-alpa',
                ];
                ?>
                <?php foreach ($rekapList as $i => $a): ?>
                    <tr>
                        <td class="center"><?= $i + 1 ?></td>
                        <td class="center"><?= date('d M Y', strtotime($a['tanggal'])) ?></td>
                        <td class="center"><?= htmlspecialchars($a['nis']) ?></td>
                        <td><?= htmlspecialchars($a['nama']) ?></td>
                        <td><?= htmlspecialchars($a['nama_kelas']) ?></td>
                        <td class="center"><?= date('H:i', strtotime($a['jam'])) ?></td>
                        <td class="center"><span class="badge <?= $badgeClass[$a['status']] ?? '' ?>"><?= $a['status'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>window.onload = function() { window.print(); };</script>
</body>
</html>
