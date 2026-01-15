<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekap Kehadiran Mahasiswa</title>
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
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;
    }
    thead tr:first-child th {
      background-color: #e9ecef;
    }
    .table-responsive {
    overflow-x: auto;
    }
    thead th {
    min-width: 80px;
    }
    tbody td {
    min-width: 60px;
    }

  </style>
</head>
<body>

<div class="container-fluid">
  <h4 class="title">Rekapitulasi Kehadiran Mahasiswa</h4>

  <!-- Info -->
  <div class="row mb-3">
    <div class="col-md-4">
      <div class="info-card">
        <p><strong>Kelas:</strong> M1 - A1</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-card">
        <p><strong>Tahun Akademik:</strong> 2024/2025</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-card">
        <p><strong>Semester:</strong> Genap</p>
      </div>
    </div>
  </div>

  <!-- Tabel Kehadiran -->
  <div class="table-responsive">
  <table id="rekapKehadiran" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">Nama Mahasiswa</th>
                <?php foreach ($mapel as $mp): ?>
                <th colspan="4"><?= htmlentities($mp->nama_matakuliah) ?></th>
                <?php endforeach; ?>
                <th colspan="4">Total</th>
                <th rowspan="2">Rata-rata</th>
            </tr>
            <tr>
                <?php foreach ($mapel as $mp): ?>
                <th class="text-success">H</th><th class="text-success">A</th><th class="text-success">I</th><th class="text-success">S</th>
                <?php endforeach; ?>
                <th>H</th><th>A</th><th>I</th><th>S</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1; foreach ($rekap_presensi as $mhs): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $mhs['nis'] ?></td>
                    <td><?= htmlentities($mhs['nama_mahasiswa']) ?></td>

                    <?php foreach ($mapel as $mp):
                        $presensi = $mhs['presensi'][$mp->id_matakuliah] ?? ['h'=>0,'a'=>0,'i'=>0,'s'=>0];
                    ?>
                        <td><?= $presensi['h'] ?></td>
                        <td><?= $presensi['a'] ?></td>
                        <td><?= $presensi['i'] ?></td>
                        <td><?= $presensi['s'] ?></td>
                    <?php endforeach; ?>

                    <?php
                        $total_hadir = $mhs['total']['h'];
                        $total_alpa  = $mhs['total']['a'];
                        $total_izin  = $mhs['total']['i'];
                        $total_sakit = $mhs['total']['s'];

                        $total_pertemuan  = $total_hadir + $total_alpa + $total_izin + $total_sakit;
                        $jumlah_kehadiran = $total_pertemuan - ($total_alpa + 0.5 * $total_izin + 0.5 * $total_sakit);
                        $persentase       = ($total_pertemuan > 0) ? round(($jumlah_kehadiran / $total_pertemuan) * 100, 2) : 0;
                    ?>

                <td class="table-warning"><?= $total_hadir ?></td>
                <td class="table-warning"><?= $total_alpa ?></td>
                <td class="table-warning"><?= $total_izin ?></td>
                <td class="table-warning"><?= $total_sakit ?></td>
                <td class="table-warning"><strong><?= $persentase ?>%</strong></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


<script>
  $(document).ready(function () {
  $('#rekapKehadiran').DataTable({
    paging: false,
    searching: false,
    info: false,
    scrollX: true,
    fixedHeader: true,
    dom: 'Bfrtip', // Tambahkan tombol di atas tabel
    buttons: [
      {
        extend: 'excelHtml5',
        text: 'Download Excel',
        className: 'btn btn-success btn-sm'
      },
      {
        extend: 'pdfHtml5',
        text: ' Download PDF',
        className: 'btn btn-danger btn-sm',
        orientation: 'landscape',
        pageSize: 'A4'
      }
    ]
  });
});
</script>

</body>
</html>
