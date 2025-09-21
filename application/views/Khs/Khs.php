 <!--start page wrapper -->
 <div class="page-wrapper">
        <div class="page-content">
          <!--breadcrumb-->
          <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Akademik</div>
            <div class="ps-3">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                  <li class="breadcrumb-item">
                    <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                  </li>
                  <li class="breadcrumb-item active" aria-current="page">Kartu Hasil Studi</li>
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
          <div class="container">
            <div class="main-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex flex-column align-items-center text-center">
                        <img src="<?php echo base_url() ?>assets/images/avatars/a.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110" />
                        <div class="mt-3">
                          <h4><?php echo $this->session->userdata('username')?></h4>
                          <p class="text-secondary mb-1" style="text-align: left">NIS : 2019.01.1234 | NPM : 12346783</p>
                          <p class="text-secondary mb-1" style="text-align: left">Takhassus &nbsp; : Fiqh dan Ushul Fiqh</p>
                          <p class="text-secondary mb-3" style="text-align: left">Konsentrasi : Fiqh Ekonomi Keummatan</p>
                          <button class="btn btn-warning">Semester 3</button>
                          <button class="btn btn-outline-primary">Marhalah Ula</button>
                        </div>
                      </div>
                      <hr class="my-4" />
                    </div>
                  </div>
                </div>

                <div class="col-xl-8">
                  <div class="row"></div>

                  <div class="row">
                    <div class="col">
                      <div class="card radius-10">
                        <div class="card-body">
                          <div class="text-center">
                            <!-- <div class="widgets-icons rounded-circle mx-auto bg-light-info text-info mb-3"><i class="bx bxl-linkedin-square"></i>
                            </div> -->
                            <h4 class="my-1 mb-2">Pilih Tahun Akademik</h4>
                            <select name="" id="" class="form-control">
                              <option value="">.:: Pilih Tahun ::.</option>
                              <option value="">2023/2024</option>
                              <option value="">2024/2025</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col">
                      <div class="card radius-10">
                        <div class="card-body">
                          <div class="text-center">
                            <!-- <div class="widgets-icons rounded-circle mx-auto bg-light-warning text-warning mb-3"><i class="bx bxl-dropbox"></i>
                            </div> -->
                            <h4 class="my-1 mb-2">Pilih Semester</h4>
                            <select name="" id="" class="form-control">
                              <option value="">.:: Pilih Semester ::.</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col">
                        <div class="card">
                          <a href="<?php echo base_url() ?>Khs_mahasiswa/Detail_khs" class="btn btn-success"><i class="bx bx-window-alt"></i> Tampilkan KHS</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3"></div>
              </div>

              <!-- row -->
            </div>
          </div>
        </div>
      </div>
      <!--end page wrapper -->