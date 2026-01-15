
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
            <li class="breadcrumb-item active" aria-current="page">Data Distribusi Mahasiswa Kedalam Setiap  Kelas</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        
      </div>
    </div>
    <!--end breadcrumb-->
    <!-- <h6 class="mb-0 text-uppercase">Data Mahasiswa</h6> -->
    <button class="btn btn-warning radius-30" onclick="add()"><i class="bx bx-user-plus"></i> Tambah Distribusi Kelas</button>
    <!-- Modal -->
    
    <hr />
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="tabel_view" class="table table-striped table-bordered">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Kelas</th>
                  <th>Tahun Akademik</th>
                  <th>NIS</th>
                  <th>Nama Mahasiswa</th>
                  <th>Semester</th>
                  <th>Status</th>
                  <th>Aksi</th>
              </tr>
              <tr class="filters">
                  <th></th> <!-- No column -->
                  <th><input type="text" class="form-control form-control-sm" placeholder="Cari Kelas" /></th>
                  <th><input type="text" class="form-control form-control-sm" placeholder="Cari Tahun" /></th>
                  <th><input type="text" class="form-control form-control-sm" placeholder="Cari NIS" /></th>
                  <th><input type="text" class="form-control form-control-sm" placeholder="Cari Nama" /></th>
                  <th><input type="text" class="form-control form-control-sm" placeholder="Cari Semester" /></th>
                  <th><input type="text" class="form-control form-control-sm" placeholder="Cari Status" /></th>
                  <th></th> <!-- Aksi column -->
              </tr>
            </thead>
            <tbody>
              
            </tbody>
            <tfoot>
              <tr>
                  <th>No</th>
                  <th>Kelas</th>
                  <th>Tahun Akademik</th>
                  <th>NIS</th>
                  <th>Nama Mahasiswa</th>
                  <th>Semester</th>
                  <th>Status</th>
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
