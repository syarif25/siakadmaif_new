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
                        <img src="../assets/images/avatars/a.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110" />
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
                  <div class="row">
                    <div class="col">
                      <div class="card radius-10">
                        <div class="card-body">
                          <div class="d-flex align-items-center">
                            <div>
                              <p class="mb-0 text-secondary">Jumlah SKS </p>
                              <h4 class="my-1 text-bold">22</h4>
                            </div>
                            <div class="text-primary ms-auto font-35"><i class="bx bx-book"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col">
                      <div class="card radius-10">
                        <div class="card-body">
                          <div class="d-flex align-items-center">
                            <div>
                              <p class="mb-0 text-secondary">Jumlah Matakuliah </p>
                              <h4 class="my-1">6</h4>
                            </div>
                            <div class="text-primary ms-auto font-35"><i class="bx bx-bookmark-minus "></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="card radius-10">
                        <div class="card-body">
                          <div class="text-center">
                            <!-- <div class="widgets-icons rounded-circle mx-auto bg-light-info text-info mb-3"><i class="bx bxl-linkedin-square"></i>
                            </div> -->
                            <h4 class="my-1 text-success">3,5</h4>
                            <p class="mb-0 text-secondary">IPK (Indeks Prestasi Kumulatif)</p>
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
                            <h4 class="my-1 text-primary">JAYYID</h4>
                            <p class="mb-0 text-secondary">Predikat</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
               </div>
               <div class="row row-cols-1 row-cols-md-3">
                
                
              </div>
              </div>
              <div class="col-xl-12 d-flex">
                <div class="card radius-10 w-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div>
                        <h5 class="mb-1">Transkrip Nilai</h5>
                        <p class="mb-0 font-13 text-secondary"><i class="bx bxs-calendar"></i></p>
                      </div>
                      <div class="font-22 ms-auto"><button class="btn btn-success">Cetak</button></div>
                    </div>
                    <div class="table-responsive mt-4">
                      <table class="table align-middle mb-0 table-hover" id="Transaction-History">
                        <thead class="table-light">
                          <tr>
                            <th>No</th>
                            <th>Nama Matakuliah</th>
                            <th>SKS</th>
                            <th>Nilai</th>
                            <th>SKS x Nilai</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td>Bahasa Arab</td>
                            <td>2 sks</td>
                            <td class="text-success">B- (2,75)</td>
                            <td class="text-warning">
                              5,5
                            </td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>Ilmu Nahwu 1</td>
                            <td>3 sks</td>
                            <td class="text-success">B (3)</td>
                            <td class="text-warning">
                              9
                            </td>
                          </tr>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>Ilmu Nahwu</td>
                            <td>3 sks</td>
                            <td class="text-success">B+ (3,5)</td>
                            <td class="text-warning">
                              10,5
                            </td>
                          </tr>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td>Ilmu Sharraf 1</td>
                            <td>2 sks</td>
                            <td class="text-primary">A- (3,75)</td>
                            <td class="text-warning">
                              7,5
                            </td>
                          </tr>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td>Ilmu Sharraf</td>
                            <td>2 sks</td>
                            <td class="text-success">B- (2,75)</td>
                            <td class="text-warning">
                              5,5
                            </td>
                          </tr>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td>Tasawwuf</td>
                            <td>2 sks</td>
                            <td class="text-success">B- (2,75)</td>
                            <td class="text-warning">
                              5,5
                            </td>
                          </tr>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- row -->
               
            </div>
          </div>
        </div>
      </div>
      <!--end page wrapper -->