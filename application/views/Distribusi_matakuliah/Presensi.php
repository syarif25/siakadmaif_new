<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Presensi Kuliah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }
        .header h3 {
            margin: 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 3px 5px;
            vertical-align: top;
        }
        table.presensi {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }
        table.presensi th,
        table.presensi td {
            border: 1px solid #000;
            text-align: center;
            padding: 3px;
        }
        .ttd {
            width: 100%;
            margin-top: 30px;
        }
        .ttd td {
            width: 50%;
            text-align: center;
        }
        .ttd .ttd-nama {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="header">
    <h3>LEMBAR PRESENSI KULIAH</h3>
</div>

<table class="info-table">
    <tr>
        <td><strong>Mata Kuliah</strong></td>
        <td>: <?= $distribusi->nama_matakuliah ?? '-' ?></td>
        <td><strong>Hari/Jam</strong></td>
        <td>: <?= ucfirst($distribusi->hari ?? '-') ?> / <?= substr($distribusi->jam_mulai, 0, 5) ?> - <?= substr($distribusi->jam_selesai, 0, 5) ?> WIB</td>
    </tr>
    <tr>
        <td><strong>Kelas</strong></td>
        <td>: <?= $distribusi->nama_kelas ?? '-' ?></td>
        <td><strong>Semester</strong></td>
        <td>: <?= $distribusi->semester ?? '-' ?></td>
    </tr>
    <tr>
        <td><strong>Dosen</strong></td>
        <td colspan="3">: <?= $distribusi->nama_dosen ?? '-' ?></td>
    </tr>
    <tr>
        <td><strong>Tahun Akademik</strong></td>
        <td colspan="3">: <?= $distribusi->tahun_akademik ?? '-' ?> (<?= ucfirst($distribusi->semester_akademik ?? '-') ?>)</td>
    </tr>
</table>

<table class="presensi">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">NIS</th>
            <th rowspan="2">Nama Mahasiswa</th>
            <th colspan="16">Pertemuan</th>
            <th rowspan="2">Total Hadir</th>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= 16; $i++): ?>
                <th><?= $i ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($mahasiswa as $mhs): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $mhs->nis ?></td>
                <td style="text-align: left;"><?= $mhs->nama_mahasiswa ?></td>
                <?php for ($i = 1; $i <= 16; $i++): ?>
                    <td></td>
                <?php endfor; ?>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="ttd">
    <tr>
        <td></td>
        <td>
            <p><?= date('d F Y') ?><br>Dosen Pengampu,</p>
            <p class="ttd-nama"><?= $distribusi->nama_dosen ?? '-' ?></p>
        </td>
    </tr>
</table>

</body>
</html>
