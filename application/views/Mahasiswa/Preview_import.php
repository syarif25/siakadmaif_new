<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Preview Data Mahasiswa</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons (optional, ganti dengan FontAwesome jika perlu) -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
          <i class="bx bx-table"></i> Preview Data Mahasiswa dari Excel
        </h5>
      </div>
      <div class="card-body">
        <?php if (!empty($data_mahasiswa)): ?>
          <form action="<?= site_url('mahasiswa/simpan_import') ?>" method="post">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                  <tr>
                    <th>NIS</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>HP</th>
                    <th>JK</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Biaya</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data_mahasiswa as $row): ?>
                    <tr>
                      <td><?= htmlspecialchars($row['nis']) ?></td>
                      <td><?= htmlspecialchars($row['nim']) ?></td>
                      <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                      <td><?= htmlspecialchars($row['tempat_lahir']) ?></td>
                      <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                      <td><?= htmlspecialchars($row['nomor_hp']) ?></td>
                      <td><?= htmlspecialchars($row['jk']) ?></td>
                      <td><?= htmlspecialchars($row['alamat']) ?></td>
                      <td><?= htmlspecialchars($row['email']) ?></td>
                      <td><?= htmlspecialchars($row['biaya_pendidikan']) ?></td>
                      <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
            <div class="text-end mt-4">
              <button type="submit" class="btn btn-success me-2">
                <i class="bx bx-save"></i> Simpan ke Database
              </button>
              <a href="<?= site_url('mahasiswa') ?>" class="btn btn-danger">
                <i class="bx bx-x"></i> Batal
              </a>
            </div>
          </form>
        <?php else: ?>
          <div class="alert alert-warning">
            <i class="bx bx-error-circle"></i> Tidak ada data yang ditampilkan.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (optional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
