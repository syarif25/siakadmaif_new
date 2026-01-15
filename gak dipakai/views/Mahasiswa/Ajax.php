<!-- ===== CSRF meta (untuk JS membaca & menyegarkan token) ===== -->
<meta name="csrf-name"   content="<?= $this->security->get_csrf_token_name(); ?>">
<meta name="csrf-hash"   content="<?= $this->security->get_csrf_hash(); ?>">
<meta name="csrf-cookie" content="<?= $this->config->item('csrf_cookie_name'); ?>">

<!-- ===== URL mapping untuk JS modul ===== -->
<div id="app-url"
  data-list="<?= site_url('Mahasiswa/data_list') ?>"
  data-add="<?= site_url('Mahasiswa/ajax_add') ?>"
  data-update="<?= site_url('Mahasiswa/ajax_update') ?>"
  data-edit="<?= site_url('Mahasiswa/ajax_edit') ?>"
  data-delete="<?= site_url('Mahasiswa/delete') ?>">
</div>

<!-- ===== Vendor JS (urutannya penting) ===== -->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>

<!-- (Opsional) DataTables Buttons via CDN -->
<!--
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
-->

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- App umum -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<!-- ===== Modul JS Mahasiswa (tanpa inline, patuh CSP) ===== -->
<script src="<?= base_url('assets/js/mahasiswa.js') ?>"></script>

<!-- =========================
     Modal Import Excel
     ========================= -->
<div class="modal fade" id="modal_import" tabindex="-1" aria-labelledby="labelImport" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= site_url('mahasiswa/import_excel') ?>" method="post" enctype="multipart/form-data">
      <!-- CSRF hidden agar POST sah di permintaan pertama -->
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="labelImport">Import Mahasiswa dari Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="file" name="file_excel" class="form-control" required accept=".xls,.xlsx">
          <small class="text-muted d-block mt-2">
            Gunakan template yang tersedia agar urutan kolom sesuai.
          </small>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-upload"></i> Import
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- =========================
     Modal Form Mahasiswa (Add/Update)
     ========================= -->
<div class="modal fade" id="modal_mahasiswa" tabindex="-1" aria-labelledby="labelMhs" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title modal-title" id="labelMhs">Data Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <form id="form" method="post" autocomplete="off">
            <!-- CSRF hidden agar POST awal sah; token akan disegarkan via JSON -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="nis" class="mb-0">NIS</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="nis" id="nis" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="nim" class="mb-0">NIM</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="nim" id="nim" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="nama_lengkap" class="mb-0">Nama Lengkap</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" required minlength="3">
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="tempat_lahir" class="mb-0">Tempat Lahir</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="tanggal_lahir" class="mb-0">Tanggal Lahir</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="no_hp" class="mb-0">Nomor HP</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="no_hp" id="no_hp" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="jenis_kelamin" class="mb-0">Jenis Kelamin</label></div>
              <div class="col-sm-9 text-secondary">
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                  <option value="">Pilih Jenis Kelamin</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="alamat" class="mb-0">Alamat</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="alamat" id="alamat" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="email" class="mb-0">Email</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="email" class="form-control" name="email" id="email" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="biaya_pendidikan" class="mb-0">Biaya Pendidikan</label></div>
              <div class="col-sm-9 text-secondary">
                <select name="biaya_pendidikan" id="biaya_pendidikan" class="form-control" required>
                  <option value="">.:: Pilih .::</option>
                  <option>Mandiri</option>
                  <option>Beasiswa LPPD</option>
                  <option>Beasiswa PBSB</option>
                  <option>Beasiswa Baznas</option>
                  <option>Beasiswa Pesantren</option>
                  <option>Beasiswa Lainnya</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="status" class="mb-0">Status</label></div>
              <div class="col-sm-9 text-secondary">
                <select name="status" id="status" class="form-control" required>
                  <option value="">.:: Pilih .::</option>
                  <option value="Aktif">Aktif</option>
                  <option value="Cuti">Cuti</option>
                  <option value="Non Aktif">Non Aktif</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="form-group row mb-1">
              <div class="col-sm-3"><label for="password" class="mb-0">Password</label></div>
              <div class="col-sm-9 text-secondary">
                <code id="password-info" class="d-block mb-1"></code>
                <input type="text" class="form-control" name="password" id="password">
                <div class="invalid-feedback"></div>
              </div>
            </div>

          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="btnSave">Save</button>
      </div>
    </div>
  </div>
</div>
