
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--favicon-->
    <link rel="icon" href="<?php echo base_url() ?>assets/images/logo.jpg" type="image/png" />
    <!--plugins-->
    <link href="<?php echo base_url() ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?php echo base_url() ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?php echo base_url() ?>assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/css/app.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/css/icons.css" rel="stylesheet" />
    <title>Sistem Informasi Akadmik | Ma'had Aly Salafiyah Syafi'iyah Sukorejo</title>
  </head>

  <body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
      <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">
        <div class="container-fluid">
          <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
              <div class="card mt-5 mt-lg-0">
                <div class="card-body">
                  <div class="border p-4 rounded">
                    <div class="text-center">
                      <img src="<?php echo base_url() ?>assets/images/logo.jpg" alt="" width="75" />
                      <BR></BR>
                      <h3 class="">SIAKAD | Ma'had Aly</h3>
                    </div>
                    <div class="d-grid"></div>
                    <div class="login-separater text-center mb-4">
                      <span> oOo</span>
                      <hr />
                    </div>
                    <div class="form-body">
                        <?php echo form_open('Login/auth'); ?>
                        <div class="row g-3">
                                <div class="col-12">
                                    <label for="username" class="form-label">NIS / Username / Nomor Handphone</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="NPM / Username / Nomor Handphone" required />
                                </div>
                                <div class="col-12">
                                    <label for="inputChoosePassword" class="form-label"> Password</label>
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password" required />
                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                              
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--end row-->
        </div>
      </div>
      <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
        <p class="mb-0">Copyright © 2025. All right reserved.</p>
      </footer>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Password show & hide js -->
    <script>
      $(document).ready(function () {
        $("#show_hide_password a").on("click", function (event) {
          event.preventDefault();
          if ($("#show_hide_password input").attr("type") == "text") {
            $("#show_hide_password input").attr("type", "password");
            $("#show_hide_password i").addClass("bx-hide");
            $("#show_hide_password i").removeClass("bx-show");
          } else if ($("#show_hide_password input").attr("type") == "password") {
            $("#show_hide_password input").attr("type", "text");
            $("#show_hide_password i").removeClass("bx-hide");
            $("#show_hide_password i").addClass("bx-show");
          }
        });
      });
    </script>
    <?php if ($this->session->flashdata('error')): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?= $this->session->flashdata("error") ?>'
    });
    </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $this->session->flashdata("success") ?>',
        timer: 2000,
        showConfirmButton: false
    });
    </script>
    <?php endif; ?>


    <!--app JS-->
    <script src="<?php echo base_url() ?>assets/js/app.js"></script>
  </body>
</html>
