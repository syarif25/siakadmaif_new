<!-- JS dependencies -->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="app-url"
  data-list="<?= site_url('Tahun_akademik/data_list') ?>"
  data-add="<?= site_url('Tahun_akademik/ajax_add') ?>"
  data-update="<?= site_url('Tahun_akademik/ajax_update') ?>"
  data-edit="<?= site_url('Tahun_akademik/ajax_edit') ?>"
  data-delete="<?= site_url('Tahun_akademik/delete') ?>"
  data-aktifkan="<?= site_url('Tahun_akademik/aktifkan') ?>">
</div>

<script src="<?= base_url('assets/js/tahun_akademik.js') ?>"></script>


<!-- meta tags untuk CSRF -->
<meta name="csrf-name" content="<?= $this->security->get_csrf_token_name(); ?>">
<meta name="csrf-hash" content="<?= $this->security->get_csrf_hash(); ?>">
<meta name="csrf-cookie" content="<?= $this->config->item('csrf_cookie_name'); ?>">



<!-- ===== Modal Tahun Akademik ===== -->
<div class="modal fade" id="modal_tademik" tabindex="-1" aria-labelledby="labelTademik" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelTademik">Tahun Akademik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form id="form" autocomplete="off">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="id_tahun">
          <div class="row mb-3">
            <div class="col-sm-3"><label for="tahun_akademik">Tahun Akademik</label></div>
            <div class="col-sm-9 text-secondary">
              <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" maxlength="30" required>
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><label for="semester">Semester</label></div>
            <div class="col-sm-9 text-secondary">
              <select name="semester" id="semester" class="form-control" required>
                <option value="">-- pilih --</option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
              </select>
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><label for="tanggal_mulai">Tanggal Mulai</label></div>
            <div class="col-sm-9 text-secondary">
              <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-3"><label for="tanggal_selesai">Tanggal Selesai</label></div>
            <div class="col-sm-9 text-secondary">
              <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
              <span class="invalid-feedback"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="btnSave">Save</button>
      </div>
    </div>
  </div>
</div>
