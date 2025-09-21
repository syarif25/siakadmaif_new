<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Selamat Datang <b>Salafus Sobirin</b></div>
        <div class="ps-3"></div>
        <div class="ms-auto">
            <div class="btn-group"></div>
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
                        <p class="text-secondary mb-1">Jabatan Akademik : Asisten Ahli</p>
                        <button class="btn btn-warning">Dosen Tetap</button>
                        <button class="btn btn-outline-primary">Marhalah Ula</button>
                    </div>
                    </div>
                    <hr class="my-4" />
                </div>
                </div>
            </div>

            <div class="col-xl-8 d-flex">
                <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-1">Jadwal Perkuliahan Semester ini</h5>
                        <p class="mb-0 font-13 text-secondary"><i class="bx bxs-calendar"></i>5 Matakuliah</p>
                    </div>
                    <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i></div>
                    </div>
                    <div class="table-responsive mt-4">
                    <table class="table align-middle mb-0 table-hover" id="Transaction-History">
                        <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>kelas/Semester</th>
                            <th>Nama Matakuliah</th>
                            <th>SKS</th>
                            <th>Hari - Waktu</th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>M2 - Smt 3</td>
                            <td>Bahasa Arab</td>
                            <td>2 sks</td>
                            <td class="text-success">Sabtu 08.00 - 09.30</td>
                            <td>
                            <button class="btn btn-danger">Lihat Kelas</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>M2 - Smt 3</td>
                            <td>Ilmu Nahwu 1</td>
                            <td>3 sks</td>
                            <td class="text-success">Sabtu 15.00 - 14.30</td>
                            <td>
                            <button class="btn btn-danger">Lihat Kelas</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>M2 - Smt 3</td>
                            <td>Ilmu Nahwu</td>
                            <td>3 sks</td>
                            <td class="text-success">Ahad 09.30 - 11.00</td>
                            <td>
                            <button class="btn btn-danger">Lihat Kelas</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>M2 - Smt 3</td>
                            <td>Ilmu Sharraf 1</td>
                            <td>2 sks</td>
                            <td class="text-success">Senin 13.30 - 15.00</td>
                            <td>
                            <button class="btn btn-danger">Lihat Kelas</button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>M2 - Smt 3</td>
                            <td>Ilmu Sharraf</td>
                            <td>2 sks</td>
                            <td class="text-success">Selasa 12.30 - 14.00</td>
                            <td>
                            <button class="btn btn-danger">Lihat Kelas</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!-- row -->
        </div>
        </div>

        <div class="row row-cols-1 row-cols-lg-3 row-cols-xl-3">
        <div class="col d-flex">
            <div class="card radius-10 w-100">
            <div class="card-body">
                <div id="chart8"></div>
            </div>
            </div>
        </div>
        <div class="col d-flex">
            <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-0">Statistik Nilai</h5>
                </div>
                <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i></div>
                </div>
                <div class="mt-4" id="chart9"></div>
            </div>
            </div>
        </div>
        <div class="col d-flex">
            <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <h5 class="mb-0 font-weight-bold">Nilai Tertinggi</h5>
                <p class="mb-0 ms-auto"><i class="bx bx-dots-horizontal-rounded float-right font-22"></i></p>
                </div>
                <div class="d-flex mt-2 mb-4">
                <h2 class="mb-0 font-weight-bold">64</h2>
                <p class="mb-0 ms-1 font-14 align-self-end text-secondary">Sks yang telah ditempuh</p>
                </div>
                <div class="progress radius-10 h-10">
                <div class="progress-bar bg-primary w-25" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-danger w-50" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-info w-25" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-warning w-25" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="table-responsive mt-4">
                <table class="table mb-0">
                    <tbody>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <div><i class="bx bxs-checkbox me-2 font-22 text-primary"></i></div>
                            <div>Fikih Puasa</div>
                        </div>
                        </td>
                        <td>2 Sks</td>
                        <td class="px-0 text-right">4 (<b>A</b>)</td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <div><i class="bx bxs-checkbox me-2 font-22 text-danger"></i></div>
                            <div>Studi Hadis</div>
                        </div>
                        </td>
                        <td>2 Sks</td>
                        <td class="px-0 text-right">4 (<b>A</b>)</td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <div><i class="bx bxs-checkbox me-2 font-22 text-info"></i></div>
                            <div>Ushul Fikih - Ijtihad</div>
                        </div>
                        </td>
                        <td>3 Sks</td>
                        <td class="px-0 text-right">3.5 (<b>B+</b>)</td>
                    </tr>
                    <tr>
                        <td class="px-0">
                        <div class="d-flex align-items-center">
                            <div><i class="bx bxs-checkbox me-2 font-22 text-warning"></i></div>
                            <div>Qawaidul Kulliyyah Ku..</div>
                        </div>
                        </td>
                        <td>3 Sks</td>
                        <td class="px-0 text-right">3.75 (<b>B+</b>)</td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
        </div>

        <div class="row">
        <div class="col-xl-8 d-flex">
            <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-1">History Matakuliah</h5>
                    <p class="mb-0 font-13 text-secondary"><i class="bx bxs-calendar"></i>Semester sebelumnya</p>
                </div>
                <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i></div>
                </div>
                <div class="table-responsive mt-4">
                <table class="table align-middle mb-0 table-hover" id="Transaction-History">
                    <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Matakuliah</th>
                        <th>SKS</th>
                        <th>Nilai</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Bahasa Arab</td>
                        <td>2 sks</td>
                        <td>2,75 (<b>B-</b>)</td>
                        <td>
                        <div class="badge rounded-pill bg-success w-100">Lulus</div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ilmu Nahwu 1</td>
                        <td>3 sks</td>
                        <td>3 (<b>B</b>)</td>
                        <td>
                        <div class="badge rounded-pill bg-success w-100">Lulus</div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Ilmu Nahwu</td>
                        <td>3 sks</td>
                        <td>3,5 (<b>B+</b>)</td>
                        <td>
                        <div class="badge rounded-pill bg-success w-100">Lulus</div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Ilmu Sharraf 1</td>
                        <td>2 sks</td>
                        <td>3 (<b>B</b>)</td>
                        <td>
                        <div class="badge rounded-pill bg-success w-100">Lulus</div>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Ilmu Sharraf</td>
                        <td>2 sks</td>
                        <td>2.75 (<b>B-</b>)</td>
                        <td>
                        <div class="badge rounded-pill bg-success w-100">Lulus</div>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Tasawwuf</td>
                        <td>2 sks</td>
                        <td>1 (<b>D</b>)</td>
                        <td>
                        <div class="badge rounded-pill bg-danger w-100">Tidak Lulus</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
<!--end page wrapper -->