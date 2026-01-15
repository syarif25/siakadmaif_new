<!-- ===== CSRF meta (untuk JS membaca token) ===== -->
<meta name="csrf-name"   content="<?= $this->security->get_csrf_token_name(); ?>">
<meta name="csrf-hash"   content="<?= $this->security->get_csrf_hash(); ?>">
<meta name="csrf-cookie" content="<?= $this->config->item('csrf_cookie_name'); ?>">

<!-- ===== URL mapping untuk JS tanpa inline ===== -->
<div id="app-url"
  data-list="<?= site_url('Matakuliah/data_list') ?>"
  data-add="<?= site_url('Matakuliah/ajax_add') ?>"
  data-update="<?= site_url('Matakuliah/ajax_update') ?>"
  data-edit="<?= site_url('Matakuliah/ajax_edit') ?>"
  data-delete="<?= site_url('Matakuliah/delete') ?>">
</div>

<!-- ===== JS dependencies (jQuery duluan) ===== -->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>

<!-- DataTables + Bootstrap 5 -->
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>

<!-- Buttons (via CDN aman) -->
<!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script> -->

<!-- SweetAlert2 (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- App umum -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<!-- ===== JS modul tanpa inline (patuh CSP) ===== -->
<script src="<?= base_url('assets/js/matakuliah.js') ?>"></script>

<!-- ===== Modal Matakuliah ===== -->
<div class="modal fade" id="modal_matakuliah" tabindex="-1" aria-labelledby="labelMK" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelMK">Data Matakuliah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <form id="form" method="post" enctype="multipart/form-data" autocomplete="off">
            <!-- CSRF hidden di form agar POST awal sah -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id_matakuliah" id="id_matakuliah">

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="kode" class="mb-0">Kode</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="kode" id="kode" maxlength="20" required>
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="nama_matakuliah" class="mb-0">Nama Matakuliah</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="nama_matakuliah" id="nama_matakuliah" maxlength="50" required>
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="sks" class="mb-0">SKS</label></div>
              <div class="col-sm-9 text-secondary">
                <input type="number" class="form-control" name="sks" id="sks" min="1" max="10" required>
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="jenjang" class="mb-0">Jenjang</label></div>
              <div class="col-sm-9 text-secondary">
                <select name="jenjang" id="jenjang" class="form-control" required>
                  <option value="">-- Pilih Jenjang --</option>
                  <option value="M1">Marhalah Ula</option>
                  <option value="M2">Marhalah Tsaniya</option>
                </select>
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3"><label for="semester" class="mb-0">Semester</label></div>
              <div class="col-sm-9 text-secondary">
                <select class="form-control" name="semester" id="semester" required>
                  <option value="">-- Pilih Semester --</option>
                  <option>1</option><option>2</option><option>3</option><option>4</option>
                  <option>5</option><option>6</option><option>7</option><option>8</option>
                </select>
                <span class="invalid-feedback"></span>
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
