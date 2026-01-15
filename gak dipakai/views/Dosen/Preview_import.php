<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Preview Data Dosen</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bx bx-table"></i> Preview Data Dosen dari Excel</h5>
      </div>
      <div class="card-body">

        <?php if (!empty($data_dosen)): ?>
          <?php
            // akses model dari view (drop-in; idealnya hitung di controller)
            $CI =& get_instance();
            $CI->load->model('Dosen_model');

            $duplicate_count = 0;
            $total_rows = count($data_dosen);
          ?>

          <form action="<?= site_url('dosen/simpan_import') ?>" method="post">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                  <tr>
                    <th style="width:100px">Validasi</th>
                    <th>NIK</th>
                    <th>Nama Dosen</th>
                    <th>Gelar Depan</th>
                    <th>Gelar Belakang</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Nomor HP</th>
                    <th>JK</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Pendidikan Terakhir</th>
                    <th>Bidang Keahlian</th>
                    <th>Status Kepegawaian</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data_dosen as $row): ?>
                    <?php
                      // Sesuaikan signature exists() di model kamu:
                      // Disarankan: exists_by_nik($nik) atau exists($nik, $email)
                      $is_duplicate = method_exists($CI->Dosen_model, 'exists_by_nik')
                        ? $CI->Dosen_model->exists_by_nik($row['nik'])
                        : $CI->Dosen_model->exists($row['nik'], isset($row['email']) ? $row['email'] : null);

                      if ($is_duplicate) $duplicate_count++;
                    ?>
                    <tr class="<?= $is_duplicate ? 'table-danger' : ''; ?>">
                      <td class="text-center">
                        <?= $is_duplicate
                          ? '<span class="badge bg-danger"><i class="bx bx-error-circle"></i> Duplikat</span>'
                          : '<span class="badge bg-success"><i class="bx bx-check-circle"></i> OK</span>' ?>
                      </td>
                      <td><?= htmlspecialchars($row['nik']) ?></td>
                      <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                      <td><?= htmlspecialchars($row['gelar_depan']) ?></td>
                      <td><?= htmlspecialchars($row['gelar_belakang']) ?></td>
                      <td><?= htmlspecialchars($row['tempat_lahir']) ?></td>
                      <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                      <td><?= htmlspecialchars($row['nomor_hp']) ?></td>
                      <td><?= htmlspecialchars($row['jk']) ?></td>
                      <td><?= htmlspecialchars($row['alamat']) ?></td>
                      <td><?= htmlspecialchars($row['email']) ?></td>
                      <td><?= htmlspecialchars($row['pendidikan_terakhir']) ?></td>
                      <td><?= htmlspecialchars($row['bidang_keahlian']) ?></td>
                      <td><?= htmlspecialchars($row['status_kepegawaian']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <?php $valid_count = $total_rows - $duplicate_count; ?>

            <div class="mt-3">
              <?php if ($valid_count > 0 && $duplicate_count > 0): ?>
                <div class="alert alert-info">
                  <i class="bx bx-info-circle"></i>
                  <?= $valid_count; ?> data akan disimpan. <?= $duplicate_count; ?> data dilewati (duplikat).
                </div>
              <?php elseif ($valid_count === 0): ?>
                <div class="alert alert-warning">
                  <i class="bx bx-error-circle"></i>
                  Semua baris duplikat. Tidak ada data yang akan disimpan.
                </div>
              <?php else: ?>
                <div class="alert alert-success">
                  <i class="bx bx-check-circle"></i>
                  Semua data valid. Siap disimpan.
                </div>
              <?php endif; ?>

              <small class="text-muted d-block">
                <span class="badge bg-success"><i class="bx bx-check-circle"></i> OK</span> = baris akan disimpan.
                <span class="badge bg-danger ms-2"><i class="bx bx-error-circle"></i> Duplikat</span> = baris dilewati.
              </small>
            </div>

            <div class="text-end mt-4">
              <button type="submit" class="btn btn-success me-2" <?= $valid_count === 0 ? 'disabled' : '' ?>>
                <i class="bx bx-save"></i> Simpan ke Database
              </button>
              <a href="<?= site_url('dosen') ?>" class="btn btn-danger">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
