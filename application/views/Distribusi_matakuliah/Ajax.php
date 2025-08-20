<!-- jQuery harus dipanggil dulu sebelum plugin lain yg pakai jQuery -->
<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>

<!-- Bootstrap 5 Bundle (Bootstrap JS + Popper.js) -->
<script src="<?php echo base_url() ?>assets/js/bootstrap.bundle.min.js"></script>

<!-- plugins yang lain, DataTables, dll -->
<script src="<?php echo base_url() ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/table-datatable.js"></script>

<!-- app JS -->
<script src="<?php echo base_url() ?>assets/js/app.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables Buttons dan dependensinya -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script>
$(document).ready(function() {
$('.select2').select2({
    dropdownParent: $('#modal_distribusi'), // ganti dengan id modal jika perlu
    width: '100%' 
});
});
$(document).ready(function(){
    table = $('#tabel_view').DataTable({
    "ajax": {
        "url": "<?php echo site_url('Distribusi_matkul/data_list')?>",
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
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('#modal_distribusi').modal('show');    
}


let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    const url = save_method === 'add'
        ? "<?= site_url('distribusi_matkul/ajax_add') ?>"
        : "<?= site_url('distribusi_matkul/ajax_update') ?>";

    const pesan = save_method === 'add'
        ? 'Berhasil menambahkan data.'
        : 'Berhasil mengubah data.';

    const form = $('#form')[0];
    const formData = new FormData(form);
    formData.append('method', save_method);

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
                $('#form')[0].reset();
                $('#form').find('.select2').val('').trigger('change');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#modal_distribusi').modal('hide');
                
                reload_table();
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: pesan,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                for (let i = 0; i < response.inputerror.length; i++) {
                    const input = $('[name="' + response.inputerror[i] + '"]');
                    input.addClass('is-invalid');
                    input.closest('div.col-sm-9').find('.invalid-feedback').text(response.error_string[i]);
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

function edit_distribusi(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form
    $('.is-invalid').removeClass('is-invalid'); // hapus class error
    $('.invalid-feedback').text(''); // hapus pesan error

    $.ajax({
        url: "<?= site_url('distribusi_matkul/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id_distribusi_matakuliah"]').val(data.id_distribusi);
            $('[name="id_kelas"]').val(data.id_kelas).trigger('change');       // sesuai name="kelas"
            $('[name="id_mk"]').val(data.id_mk).trigger('change');           // sesuai name="id_mk"
            $('[name="id_dosen"]').val(data.id_dosen).trigger('change');     // sesuai name="id_dosen"
            $('[name="hari"]').val(data.hari.toLowerCase()).trigger('change');
            $('[name="jam_mulai"]').val(data.jam_mulai);                     // name="jam_mulai"
            $('[name="jam_selesai"]').val(data.jam_selesai);                 // name="jam_selesai"

            $('#modal_distribusi').modal('show');
            $('.modal-title').text('Edit Data Distribusi Matakuliah');
        },
        error: function(xhr, status, error) {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}

function hapus_distribusi(id) {
    Swal.fire({
        title: 'Yakin hapus distribusi ini?',
        text: "Data tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= site_url('distribusi_matkul/delete/') ?>" + id,
                type: "POST",
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        reload_table();
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                    } else {
                        Swal.fire('Gagal!', 'Tidak dapat menghapus data.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
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

<div class="modal fade" id="modal_distribusi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Distribusi Matakuliah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="card-body">
            <form action="#" id="form" method="post">
                <input type="hidden" name="id_distribusi_matakuliah" id="id_distribusi_matakuliah">

                <!-- Kelas --> 
                <div class="row mb-3">
                    <div class="col-sm-3"><h6 class="mb-0">Nama Kelas</h6></div>
                    <div class="form-group col-sm-9 text-secondary">
                        <select name="id_kelas" id="id_kelas" class="form-control select2">
                            <option value="">.:: Pilih Kelas ::.</option>
                            <?php foreach($ruangan as $r): ?>
                                <option value="<?= $r->id_kelas ?>"><?= $r->jenjang.' - ' . $r->nama_kelas ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <!-- Matakuliah -->
                <div class="row mb-3">
                    <div class="col-sm-3"><h6 class="mb-0">Matakuliah</h6></div>
                    <div class="form-group col-sm-9 text-secondary">
                        <select name="id_mk" id="id_mk" class="form-control select2">
                            <option value="">.:: Pilih Matakuliah ::.</option>
                            <?php foreach($matakuliah as $mk): ?>
                                <option value="<?= $mk->id_matakuliah ?>"><?= $mk->nama_matakuliah ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <!-- Dosen -->
                <div class="row mb-3">
                    <div class="col-sm-3"><h6 class="mb-0">Dosen Pengampu</h6></div>
                    <div class="form-group col-sm-9 text-secondary">
                        <select name="id_dosen" id="id_dosen" class="form-control select2">
                            <option value="">.:: Pilih Dosen ::.</option>
                            <?php foreach($dosen as $d): ?>
                                <option value="<?= $d->id_dosen ?>"><?= $d->nama_dosen ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <!-- Hari -->
                <div class="row mb-3">
                    <div class="col-sm-3"><h6 class="mb-0">Hari</h6></div>
                    <div class="form-group col-sm-9 text-secondary">
                        <select name="hari" id="hari" class="form-control select2">
                            <option value="">.:: Pilih Hari ::.</option>
                            <?php
                            $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                            foreach ($hari as $h): ?>
                                <option value="<?= $h ?>"><?= ucfirst($h) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <!-- Jam Mulai -->
                <div class="row mb-3">
                    <div class="col-sm-3"><h6 class="mb-0">Jam Mulai</h6></div>
                    <div class="form-group col-sm-9 text-secondary">
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" />
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <!-- Jam Selesai -->
                <div class="row mb-3">
                    <div class="col-sm-3"><h6 class="mb-0">Selesai</h6></div>
                    <div class="form-group col-sm-9 text-secondary">
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" />
                        <span class="invalid-feedback"></span>
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


