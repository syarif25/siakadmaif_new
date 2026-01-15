<div class="page-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Profil Mahasiswa</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
          <div class="btn-group">
            <button type="button" class="btn btn-primary">Settings</button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><span class="visually-hidden">Toggle Dropdown</span></button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
              <a class="dropdown-item" href="javascript:;">Action</a>
              <a class="dropdown-item" href="javascript:;">Another action</a>
              <a class="dropdown-item" href="javascript:;">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
          </div>
        </div>
      </div>
      <!--end breadcrumb-->
      <div class="container">
        <div class="main-body">
          <div class="row">
            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <?php if ($mahasiswa->jk == 'Laki-laki') { ?>
                      <img src="<?= base_url('assets/images/avatars/a.png') ?>" alt="Admin" class="rounded-circle p-1 bg-primary" width="110" />
                    <?php } else { ?>
                      <img src="<?= base_url('assets/images/avatars/bb.jpg') ?>" alt="Admin" class="rounded-circle p-1 bg-primary" width="110" />
                    <?php } ?>
                    <div class="mt-3">
                      <h4><?= $mahasiswa->nama_mahasiswa ?></h4>
                      <p class="text-secondary mb-1">NIS : <?= $mahasiswa->nis ?> | NPM : <?= $mahasiswa->nim ?></p>
                      <p class="text-muted font-size-sm"><?= $mahasiswa->tempat_lahir ?>, <?= format_tanggal_indonesia($mahasiswa->tanggal_lahir) ?></p>
                      <!-- <button class="btn btn-primary">Follow</button> -->
                      <!-- <button class="btn btn-outline-primary">Marhalah Ula</button> -->
                    </div>
                  </div>
                  <hr class="my-4" />
                  <ul class="list-group list-group-flush"></ul>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="card">
                <div class="card-body">
                  <h4 class="d-flex align-items-center mb-3">Data Diri</h4>
                  <hr />
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">NIS</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->nis ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">NPM</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->nim ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Nama Lengkap</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->nama_mahasiswa ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Tempat Lahir</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->tempat_lahir ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Nomor Handphone</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->nomor_hp ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Jenis Kelamin</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->jk ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Alamat</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->alamat ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->email ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Biaya Pendidikan</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" class="form-control" value="<?= $mahasiswa->biaya_pendidikan ?>" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Status</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" value="<?= $mahasiswa->status ?>" />
                    </div>
                  </div>
                  <!-- <div class="row mb-3">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Foto</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="file" class="form-control" />
                    </div>
                  </div> -->
                  <!-- <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                      <input type="button" class="btn btn-primary px-4" value="Simpan Perubahan" />
                    </div>
                  </div> -->
                </div>
              </div>
              <div class="row">
                <!-- <div class="col-sm-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="d-flex align-items-center mb-3">Project Status</h5>
                      <p>Web Design</p>
                      <div class="progress mb-3 h-5">
                        <div class="progress-bar bg-primary w-75" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <p>Website Markup</p>
                      <div class="progress mb-3 h-5">
                        <div class="progress-bar bg-danger w-25" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <p>One Page</p>
                      <div class="progress mb-3 h-5">
                        <div class="progress-bar bg-success w-50" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <p>Mobile Template</p>
                      <div class="progress mb-3 h-5">
                        <div class="progress-bar bg-warning w-75" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <p>Backend API</p>
                      <div class="progress h-5">
                        <div class="progress-bar bg-info w-25" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>