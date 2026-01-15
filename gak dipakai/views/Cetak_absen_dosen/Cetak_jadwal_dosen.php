<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Jadwal Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 14px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; }
        .table th, .table td { vertical-align: middle; }
        @media print {
            .btn-print { display: none; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
<div class="container mt-3">

    <!-- HEADER -->
    <div class="header">
        <h4 class="fw-bold mb-0">JADWAL MENGAJAR DOSEN</h4>
        <p class="text-muted small">Tahun Akademik Aktif</p>
        <hr>
    </div>

    <!-- DATA DOSEN -->
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nama Dosen :</strong> <?= $dosen->gelar_depan.' '.$dosen->nama_dosen.' '.$dosen->gelar_belakang; ?></p>
                <p><strong>Bidang Keahlian :</strong> <?= $dosen->bidang_keahlian; ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><strong>Jenis Kelamin :</strong> <?= $dosen->jk; ?></p>
            </div>
        </div>
    </div>

    <!-- TABEL JADWAL -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th width="5%">No</th>
                <th>Jenjang</th>
                <th>Mata Kuliah</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Ruang</th>
                <th>Tahun Akademik</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($jadwal)): $no=1; foreach($jadwal as $j): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $j->jenjang; ?></td>
                <td><?= $j->nama_matakuliah; ?></td>
                <td><?= $j->nama_kelas; ?></td>
                <td><?= ucfirst($j->hari); ?></td>
                <td><?= date('H:i', strtotime($j->jam_mulai)).' - '.date('H:i', strtotime($j->jam_selesai)); ?></td>
                <td><?= $j->nama_kelas; ?></td>
                <td><?= $j->tahun_akademik; ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada jadwal ditemukan</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tombol Cetak -->
    <div class="text-center mt-3 btn-print">
        <button class="btn btn-success px-4" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Jadwal
        </button>
        <a href="<?= site_url('Cetak_perdosen') ?>" class="btn btn-secondary px-4 ms-2">
            Kembali
        </a>
    </div>
</div>

<!-- Bootstrap Icon untuk icon printer -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>
