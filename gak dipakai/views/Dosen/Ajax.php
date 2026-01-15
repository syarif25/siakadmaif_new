 <!-- Bootstrap JS -->
 <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/table-datatable.js"></script>
<!--app JS-->
<script src="<?php echo base_url() ?>assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- JSZip (Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- pdfmake (PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<!-- Buttons HTML5 + Print -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


<script>
$(document).ready(function(){
    table = $('#tabel_view').DataTable({
    "ajax": {
        "url": "<?php echo site_url('Dosen/data_list')?>",
        "type": "POST"
    },
    "columnDefs": [
        { 
            "targets": [ -1 ],
            "orderable": false,
        },
    ],
    "paging": true,
    "searching": true,
    "ordering": true,
    "scrollY": false,

    // Tambahkan dom dan buttons di sini:
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf', 'print']
});

$('#form-import-dosen').off('submit').on('submit', function (e) {
    e.preventDefault();
    ajaxImportDosen(this);
  });

});


function add() {
    $('#form')[0].reset(); // reset form on modals
    save_method = 'add';
    $('#modal_dosen').modal('show');    
}

let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    const url = save_method === 'add'
        ? "<?= site_url('Dosen/ajax_add') ?>"
        : "<?= site_url('Dosen/ajax_update') ?>";

    const pesan = save_method === 'add'
        ? 'Berhasil menambahkan data.'
        : 'Berhasil mengubah data.';

    const form = $('#form')[0];
    const formData = new FormData(form);
    formData.append('method', save_method); // Tambahkan method ke form data

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(response) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            if (response.status) {
                $('#modal_dosen').modal('hide');
                reload_table();
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: pesan,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                // Tampilkan error validasi dari server
                for (let i = 0; i < response.inputerror.length; i++) {
                    const input = $('[name="' + response.inputerror[i] + '"]');
                    input.addClass('is-invalid');
                    input.closest('.form-group').find('.invalid-feedback').text(response.error_string[i]);
                }
            }

            btn.text('Simpan').prop('disabled', false);
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memproses data.'
            });
            btn.text('Simpan').prop('disabled', false);
        }
    });
}


function edit_dosen(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form
    $('.is-invalid').removeClass('is-invalid'); // hapus error class
    $('.invalid-feedback').text(''); // hapus pesan error

    $.ajax({
        url: "<?= site_url('Dosen/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id_dosen"]').val(data.id_dosen);
            $('[name="nik"]').val(data.nik);
            $('[name="nama_dosen"]').val(data.nama_dosen);
            $('[name="tempat_lahir"]').val(data.tempat_lahir);
            $('[name="tanggal_lahir"]').val(data.tanggal_lahir);
            $('[name="nomor_hp"]').val(data.nomor_hp);
            $('[name="jenis_kelamin"]').val(data.jk);
            $('[name="alamat"]').val(data.alamat);
            $('[name="email"]').val(data.email);
            $('[name="pendidikan_terakhir"]').val(data.pendidikan_terakhir);
            $('[name="nama_kampus"]').val(data.nama_kampus);
            $('[name="tahun_lulus"]').val(data.tahun_lulus);
            $('[name="gelar_depan"]').val(data.gelar_depan);
            $('[name="gelar_belakang"]').val(data.gelar_belakang);
            $('[name="bidang_keahlian"]').val(data.bidang_keahlian);
            $('[name="jabatan"]').val(data.jabatan_fungsional);
            $('[name="status_kepegawaian"]').val(data.status_kepegawaian);
            // $('[name="password"]').val(data.password);

            $('#modal_dosen').modal('show');
            $('.modal-title').text('Edit Data Dosen');
        },
        error: function(xhr, status, error) {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}


function delete_dosen(id) {
    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= site_url('Dosen/delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        reload_table();
                        Swal.fire('Berhasil!', 'Data telah dihapus.', 'success');
                    } else {
                        Swal.fire('Gagal!', 'Tidak bisa menghapus data.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            });
        }
    });
}

function ajaxImportDosen(formEl) {
  const btn = $('#btnImport');
  btn.prop('disabled', true).text('Mengunggah...');

  const fd = new FormData(formEl);

  // Jika CSRF aktif di CI3, ikutkan token (opsional, kalau kamu pakai CSRF)
  <?php if (isset($this) && property_exists($this, 'security') && method_exists($this->security, 'get_csrf_token_name')): ?>
  fd.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');
  <?php endif; ?>

  $.ajax({
    url: "<?= site_url('dosen/import_excel_ajax') ?>", // endpoint khusus AJAX
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    dataType: "json"
  })
  .done(function (resp) {
    if (resp && resp.ok) {
      // Optional: tutup modal
      $('#modal_import').modal('hide');
      // Arahkan ke preview
      window.location = resp.redirect;
    } else {
      Swal.fire('Gagal', (resp && resp.error) ? resp.error : 'Terjadi kesalahan saat import.', 'error');
    }
  })
  .fail(function (xhr) {
    Swal.fire('Error', 'Tidak dapat menghubungi server.', 'error');
  })
  .always(function () {
    btn.prop('disabled', false).text('Import');
  });
}


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


<?php if ($this->session->flashdata('success')): ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= $this->session->flashdata('success'); ?>',
    showConfirmButton: false,
    timer: 4000
  });
<?php elseif ($this->session->flashdata('error')): ?>
  Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= $this->session->flashdata('error'); ?>',
    showConfirmButton: false,
    timer: 4000
  });
<?php endif; ?>

</script>

<div class="modal fade" id="modal_dosen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Data Dosen</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">
              <form action="#" id="form">
                <input type="hidden" name="id_dosen" id="id_dosen">
                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">NIK</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="nik" id="nik" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Nama Lengkap</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="nama_dosen" id="nama_dosen" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Gelar Depan</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="gelar_depan" id="gelar_depan" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Gelar Belakang</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="gelar_belakang" id="gelar_belakang" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Tempat Lahir</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Tanggal Lahir</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Nomor Handphone</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="nomor_hp" id="nomor_hp" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Jenis Kelamin</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                      <option value="">Pilih Jenis Kelamin</option>
                      <option value="Laki-laki">Laki-laki</option>
                      <option value="Perempuan">Perempuan</option>
                    </select>
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Alamat</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="alamat" id="alamat" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Email</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="email" class="form-control" name="email" id="email" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>

                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Pendidikan Terakhir</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-control" required>
                      <option value="">-- Jenjang --</option>
                      <!-- <option value="D3">D3</option> -->
                      <option value="S1">S1</option>
                      <option value="S2">S2</option>
                      <option value="S3">S3</option>
                    </select>
                    <span class="invalid-feedback"></span>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Bidang Keahlian</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" name="bidang_keahlian" id="bidang_keahlian" />
                    <span class="invalid-feedback"></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-9 text-secondary">
                    <!-- Tombol aksi bisa ditambahkan di sini -->
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <div class="col-sm-3"><h6 class="mb-0">Password </h6></div>
                      <div class="col-sm-9 text-secondary">
                      <code>Kosongi jika tidak ingin mengubah password</code>
                      <input type="text" name="password" id="password" class="form-control" />
                      <div class="invalid-feedback d-block"></div>
                      </div>
                  </div>
              </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnSave" onclick="save()">Save</button>
        </div>
      </div>
  </div>
</div>

<div class="modal fade" id="modal_import">
  <div class="modal-dialog">
    <form id="form-import-dosen" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="">Import Dosen dari Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="file" name="file_excel" class="form-control" required accept=".xls,.xlsx">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="btnImport">Import</button>
        </div>
      </div>
    </form>
  </div>
</div>
