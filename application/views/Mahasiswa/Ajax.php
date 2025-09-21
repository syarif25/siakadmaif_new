
<!--plugins-->
<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/table-datatable.js"></script>
 <!-- Bootstrap JS -->
 <script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
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
        "url": "<?php echo site_url('Mahasiswa/data_list')?>",
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

});


function add() {
    $('#form')[0].reset(); // reset form on modals
    save_method = 'add';
    $('.modal-title').text('Tambah Data Mahasiswa');
    $('#modal_mahasiswa').modal('show');    
}

let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    const url = save_method === 'add'
        ? "<?= site_url('Mahasiswa/ajax_add') ?>"
        : "<?= site_url('Mahasiswa/ajax_update') ?>";

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
                $('#modal_mahasiswa').modal('hide');
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


function edit_mahasiswa(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form
    $('.is-invalid').removeClass('is-invalid'); // hapus error class
    $('.invalid-feedback').text(''); // hapus pesan error

    $.ajax({
        url: "<?= site_url('Mahasiswa/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="nis"]').val(data.nis);
            $('[name="nim"]').val(data.nim);
            $('[name="nama_lengkap"]').val(data.nama_mahasiswa);
            $('[name="tempat_lahir"]').val(data.tempat_lahir);
            $('[name="tanggal_lahir"]').val(data.tanggal_lahir);
            $('[name="no_hp"]').val(data.nomor_hp);
            $('[name="jenis_kelamin"]').val(data.jk);
            $('[name="alamat"]').val(data.alamat);
            $('[name="email"]').val(data.email);
            $('[name="biaya_pendidikan"]').val(data.biaya_pendidikan);
            $('[name="status"]').val(data.status);
            $('#password-info').text('Kosongi jika tidak ingin mengubah password');

            $('#modal_mahasiswa').modal('show');
            $('.modal-title').text('Edit Data Mahasiswa');
        },
        error: function(xhr, status, error) {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}


function delete_mahasiswa(id) {
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
                url: "<?= site_url('Mahasiswa/delete') ?>/" + id,
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


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>
<!-- Modal Upload -->
<div class="modal fade" id="modal_import">
  <div class="modal-dialog">
    <form action="<?= site_url('mahasiswa/import_excel') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="">Import Mahasiswa dari Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="file" name="file_excel" class="form-control" required accept=".xls,.xlsx">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="modal_mahasiswa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Data Mahasiswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                <form action="#" id="form">
                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">NIS</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" name="nis" id="nis" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">NIM</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" name="nim" id="nim" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Nama Lengkap</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Tempat Lahir</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Tanggal Lahir</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Nomor Handphone</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" name="no_hp" id="no_hp" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Jenis Kelamin</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Alamat</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" name="alamat" id="alamat" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Email</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <input type="email" name="email" id="email" class="form-control" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Biaya Pendidikan</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <select name="biaya_pendidikan" id="biaya_pendidikan" class="form-control">
                          <option value="">.:: Pilih .::</option>
                          <option>Mandiri</option>
                          <option>Beasiswa LPPD</option>
                          <option>Beasiswa PBSB</option>
                          <option>Beasiswa Baznas</option>
                          <option>Beasiswa Pesantren</option>
                          <option>Beasiswa Lainnya</option>
                        </select>
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Status</h6></div>
                        <div class="col-sm-9 text-secondary">
                        <select name="status" id="status" class="form-control">
                          <option value="">.:: Pilih .::</option>
                          <option value="Aktif">Aktif</option>
                          <option value="Cuti">Cuti</option>
                          <option value="Non Aktif">Non Aktif</option>
                        </select>
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3"><h6 class="mb-0">Password </h6></div>
                        <div class="col-sm-9 text-secondary">
                        <code id="password-info"></code>
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

