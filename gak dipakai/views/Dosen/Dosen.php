
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
            <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
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
    <button class="btn btn-warning radius-30" onclick="add()"><i class="bx bx-user-plus"></i> Tambah Dosen</button>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_import"> <i class="bx bx-upload"></i>
        Import Excel
      </button>
    <a class="btn btn-info" href="<?php echo base_url('assets/template_dosen.xlsx') ?>"><i class="bx bx-download"></i>  Download Template</a>
    <!-- Modal -->
    
    <hr />
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="tabel_view" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Bidang Keahlian</th>
                <th>Nomor HP</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
            <tfoot>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Bidang Keahlian</th>
                <th>Nomor HP</th>
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
