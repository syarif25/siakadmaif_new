
<!--start page wrapper -->
<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Manajemen</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Distribusi Matakuliah</li>
            <li class="breadcrumb-item" aria-current="page">
            <?php
                  // Jalankan query untuk mendapatkan tahun aktif
                  $query = $this->db->get_where('tahun_akademik', array('status' => 'aktif'));
                  $tahun_aktif = $query->row();

                  if ($tahun_aktif) {
                      echo "<span class='text-danger'>Tahun Aktif :  " . $tahun_aktif->tahun_akademik . " - " . $tahun_aktif->semester . "</span>";
                  } else {
                      echo "<span>Tahun Akademik : Tidak ada tahun aktif</span>";
                  }
                  ?>
            </li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <!-- <div class="btn-group">
          <button type="button" class="btn btn-primary">Settings</button>
          <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><span class="visually-hidden">Toggle Dropdown</span></button>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
            <a class="dropdown-item" href="javascript:;">Action</a>
            <a class="dropdown-item" href="javascript:;">Another action</a>
            <a class="dropdown-item" href="javascript:;">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:;">Separated link</a>
          </div>
        </div> -->
      </div>
    </div>
    <!--end breadcrumb-->
    <!-- <h6 class="mb-0 text-uppercase">Data Mahasiswa</h6> -->
    <button class="btn btn-warning radius-30" onclick="add()"><i class="bx bx-user-plus"></i> Distribusi</button>
    <!-- Modal -->
    
    <hr />
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="tabel_view" class="table table-striped table-bordered">
            <thead>
              <tr>
                    <th>No</th>
                    <th>Jenjang</th>
                    <th>Kelas</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Dosen Pengampu</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
                <tr class="filters">
                    <th></th> <!-- No column -->
                    <th><input type="text" class="form-control form-control-sm" placeholder="Cari Jenjang" /></th>
                    <th><input type="text" class="form-control form-control-sm" placeholder="Cari Kelas" /></th>
                    <th><input type="text" class="form-control form-control-sm" placeholder="Cari Matakuliah" /></th>
                    <th><input type="number" class="form-control form-control-sm" placeholder="SKS" /></th>
                    <th><input type="text" class="form-control form-control-sm" placeholder="Cari Dosen" /></th>
                    <th><input type="text" class="form-control form-control-sm" placeholder="Cari Waktu" /></th>
                    <th></th> <!-- Aksi column -->
                </tr>
            </thead>
            <tbody>
              
            </tbody>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Jenjang</th>
                    <th>Kelas</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Dosen Pengampu</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end page wrapper -->
