<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Mahasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            padding: 30px;
            background-color: #f9f9f9;
        }

        .info-card {
            background-color: #ffffff;
            border-left: 5px solid #0d6efd;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .info-card p {
            margin: 0;
            font-weight: 500;
        }

        h4.title {
            margin-bottom: 25px;
            text-align: center;
        }

        th, td {
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }

        thead th {
            background-color: #e9ecef;
        }

        .info-card p.text-muted {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .info-card h5 {
            font-weight: 600;
        }

    </style>
</head>
<body>

<div class="container-fluid">
    <h4 class="title">
        <i class="bx bx-clipboard"></i> 
        Rekapitulasi Nilai Mahasiswa
    </h4>

    <!-- Informasi kelas -->
    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="info-card shadow-sm">
                <p class="text-muted mb-1">Kelas</p>
                <h5 class="text-primary mb-0">M1 - A1</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card shadow-sm">
                <p class="text-muted mb-1">Tahun Akademik</p>
                <h5 class="text-primary mb-0">2024/2025</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card shadow-sm">
                <p class="text-muted mb-1">Semester</p>
                <h5 class="text-primary mb-0">Genap</h5>
            </div>
        </div>
    </div>


    <!-- Tabel nilai -->
    <div class="table-responsive">
        <table id="rekapTable" class="table table-bordered table-striped dt-responsive nowrap w-100">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Mahasiswa</th>
                <?php foreach ($mata_kuliah as $mapel): ?>
                    <th><?= $mapel->nama_matakuliah ?></th>
                <?php endforeach; ?>
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($rekap_nilai as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nis'] ?></td>
                <td><?= $row['nama'] ?></td>
                <?php foreach ($mata_kuliah as $mapel): ?>
                    <!-- <td><?= $row['nilai'][$mapel->nama_matakuliah] ?></td> -->


                    <?php
                   $nilai = $row['nilai'][$mapel->nama_matakuliah];
                   $nilai_revisi = $row['nilai_revisi'][$mapel->nama_matakuliah];
                   
                   // Tentukan nilai yang akan ditampilkan
                   $tampil = (!empty($nilai_revisi) && $nilai_revisi > $nilai) ? $nilai_revisi : $nilai;
                    ?>

                <td class="<?= ($tampil < 70) ? 'text-danger' : 'text-success' ?>">
                    <?= $tampil !== null && $tampil !== '' ? $tampil : '-' ?>
                </td>



                <?php endforeach; ?>
                <td><?= $row['rata_rata'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

        </table>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#rekapTable').DataTable({
            paging: false,
            searching: false,
            info: false,
            scrollX: true
        });
    });
</script>

</body>
</html>
