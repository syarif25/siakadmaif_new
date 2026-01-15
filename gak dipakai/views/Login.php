<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- favicon -->
    <link rel="icon" href="<?= base_url('assets/images/logo.jpg') ?>" type="image/png" />

    <!-- plugins css -->
    <link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
    <!-- loader -->
    <link href="<?= base_url('assets/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/js/pace.min.js') ?>"></script>

    <!-- bootstrap & app css -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet" />

    <title>SIAKAD | Ma'had Aly Salafiyah Syafi'iyah Sukorejo</title>
  </head>

  <body class="bg-login">
    <div class="wrapper">
      <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">
        <div class="container-fluid">
          <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
              <div class="card mt-5 mt-lg-0">
                <div class="card-body">
                  <div class="border p-4 rounded">
                    <div class="text-center">
                      <img src="<?= base_url('assets/images/logo.jpg') ?>" alt="Logo Ma'had Aly" width="75" />
                      <br />
                      <h3 class="mt-2">SIAKAD | Ma'had Aly</h3>
                    </div>

                    <div class="login-separater text-center my-4">
                      <span>oOo</span>
                      <hr />
                    </div>

                    <div class="form-body">
                      <?php
                        echo form_open('Login/auth', ['id' => 'loginForm', 'autocomplete' => 'on', 'novalidate' => 'novalidate']);
                      ?>
                        <!-- CSRF explicit -->
                        <input type="hidden"
                               name="<?= $this->security->get_csrf_token_name(); ?>"
                               value="<?= $this->security->get_csrf_hash(); ?>" />

                        <div class="row g-3">
                          <div class="col-12">
                            <label for="username" class="form-label">NIS / Username / Nomor Handphone</label>
                            <input
                              type="text"
                              class="form-control"
                              name="username"
                              id="username"
                              placeholder="NIS / Username / Nomor Handphone"
                              required
                              autocomplete="username"
                              maxlength="50"
                              aria-describedby="usernameHelp" />
                            <small id="usernameHelp" class="text-muted">
                              Gunakan salah satu: NIS (mahasiswa), username (petugas), atau nomor HP terdaftar (dosen).
                            </small>
                          </div>

                          <div class="col-12">
                            <label for="inputChoosePassword" class="form-label">Password</label>
                            <div class="input-group" id="show_hide_password">
                              <input
                                type="password"
                                name="password"
                                class="form-control border-end-0"
                                id="inputChoosePassword"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                                maxlength="128" />
                              <button type="button" class="input-group-text bg-transparent" aria-label="Tampilkan/sembunyikan password">
                                <i class="bx bx-hide" aria-hidden="true"></i>
                              </button>
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="d-grid">
                              <button type="submit" class="btn btn-primary">
                                <i class="bx bxs-lock-open"></i> Sign in
                              </button>
                            </div>
                          </div>
                        </div>
                      <?= form_close(); ?>
                    </div><!--/form-body-->

                  </div>
                </div>
              </div>
            </div>
          </div><!--end row-->
        </div>
      </div>

      <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
        <p class="mb-0">Copyright © 2025. All rights reserved.</p>
      </footer>
    </div><!--end wrapper-->

    <!-- JS: jQuery dulu baru komponen lain -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>

    <!-- SweetAlert2 -->
    <!-- Rekomendasi: host lokal file ini di assets/ agar tidak bergantung CDN di halaman login -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Password show/hide aman (pakai button, bukan anchor) -->
    <script>
      $(function () {
        $("#show_hide_password button").on("click", function (e) {
          e.preventDefault();
          const $input = $("#show_hide_password input");
          const $icon  = $("#show_hide_password i");
          const isText = $input.attr("type") === "text";
          $input.attr("type", isText ? "password" : "text");
          $icon.toggleClass("bx-hide bx-show");
        });
      });
    </script>

    <!-- Flash message yang di-escape aman ke konteks JS -->
    <?php if ($this->session->flashdata('error')): ?>
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: <?= json_encode($this->session->flashdata('error'), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>
        });
      </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: <?= json_encode($this->session->flashdata('success'), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>,
          timer: 2000,
          showConfirmButton: false
        });
      </script>
    <?php endif; ?>

    <script src="<?= base_url('assets/js/app.js') ?>"></script>
  </body>
</html>
