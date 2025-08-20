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
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>


$(document).ready(function(){
    $('#mahasiswa_select').select2({
        placeholder: 'Pilih Mahasiswa',
        width: '100%',
        dropdownParent: $('#modal_distribusi')
    });
    table = $('#tabel_view').DataTable({
        "ajax": {
            "url": "<?php echo site_url('distribusi_kelas/data_list')?>",
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

    $('#kelas_select').on('change', function () {
            let id_kelas = $(this).val();
            if (id_kelas) {
                $.ajax({
                    url: '<?= site_url("distribusi_kelas/get_info_kelas/") ?>' + id_kelas,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.status) {
                            $('#tahun_akademik').val(response.tahun_akademik);
                            $('#semester').val(response.semester);
                            $('#id_tahun').val(response.id_tahun);

                            // Panggil fungsi untuk load mahasiswa yang belum terdistribusi
                            loadMahasiswa(id_kelas, response.semester);
                        } else {
                            $('#tahun_akademik').val('');
                            $('#semester').val('');
                            $('#mahasiswa_select').empty();
                        }
                    }
                });
            } else {
                $('#tahun_akademik').val('');
                $('#semester').val('');
                $('#mahasiswa_select').empty();
            }
        });

        function loadMahasiswa(id_kelas, semester) {
            $.ajax({
                url: '<?= site_url("distribusi_kelas/get_mahasiswa_belum_terdistribusi") ?>',
                type: 'POST',
                data: {id_kelas: id_kelas, semester: semester},
                dataType: 'JSON',
                success: function(res) {
                    $('#mahasiswa_select').empty();
                    if(res.status) {
                        $.each(res.data, function(i, mhs) {
                            $('#mahasiswa_select').append('<option value="'+mhs.nis+'">'+mhs.nama_mahasiswa+' ('+mhs.nis+')</option>');
                        });
                    } else {
                        $('#mahasiswa_select').append('<option value="">Tidak ada mahasiswa tersedia</option>');
                    }
                }
            });
        }




});


function add() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('#modal_distribusi').modal('show');
    $('.modal-title').text('Tambah Distribusi Mahasiswa');
}


function pindah(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    $.ajax({
        url: "<?= site_url('distribusi_kelas/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#edit_id_distribusi_kelas').val(data.id_distribusi_kelas);
            $('#edit_nama_mahasiswa').val(data.nama_mahasiswa);
            $('#edit_status_keanggotaan').val(data.status_keanggotaan);
            $('#edit_id_kelas').val(data.id_kelas);

            // Tampilkan nama dan NIS
            $('#info_mahasiswa_text').text(`${data.nis} - ${data.nama_mahasiswa}`);
            
            
            $('#modal_edit_status').modal('show');
            $('.modal-title').text('Edit Status Keanggotaan');
        },
        error: function() {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}



let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    
    const url = save_method === 'add'
        ? "<?= site_url('distribusi_kelas/ajax_add') ?>"
        : "<?= site_url('distribusi_kelas/ajax_update') ?>";

    const pesan = save_method === 'add'
        ? 'Berhasil menambahkan data.'
        : 'Berhasil mengubah data.';

    
    // Ambil form sesuai metode simpan
    const form = save_method === 'add' ? $('#form')[0] : $('#form_edit')[0];

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
                $('#modal_distribusi').modal('hide');
                $('#modal_edit_status').modal('hide');
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





function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>

    <div class="modal fade" id="modal_distribusi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Distribusi </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form action="#" id="form" method="post">
                        <input type="hidden" name="id_distribusi_kelas" id="id_distribusi_kelas" />
                    
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Kelas</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <select name="id_kelas" class="form-control" id="kelas_select">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelas as $k): ?>
                                        <option value="<?= $k->id_kelas ?>"><?= $k->nama_kelas ?> (Semester <?= $k->semester ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Tahun Akademik</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" readonly />
                                <input type="hidden" name="id_tahun" id="id_tahun" />
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Semester</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="semester" id="semester" class="form-control" readonly />
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Mahasiswa</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                            <select name="nis[]" id="mahasiswa_select" class="form-control" multiple style="height: 200px;">
                                    <!-- Diisi via AJAX -->
                                </select>
                                <small class="text-muted">Pilih satu atau lebih mahasiswa yang akan dimasukkan ke kelas.</small>
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

<div class="modal fade" id="modal_edit_status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Distribusi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card-body">
                    <form action="#" id="form_edit" method="post">
                        <input type="hidden" name="edit_id_distribusi_kelas" id="edit_id_distribusi_kelas" />

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Nama Mahasiswa</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="edit_nama_mahasiswa" id="edit_nama_mahasiswa" class="form-control" readonly />
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>


                        <!-- Status Keanggotaan -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Status Keanggotaan</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <select class="form-control" name="edit_status_keanggotaan" id="edit_status_keanggotaan" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Dikeluarkan">Dikeluarkan</option>
                                    <option value="Keluar">Keluar</option>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Kelas</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <select class="form-control" name="edit_id_kelas" id="edit_id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelas as $k): ?>
                                        <option value="<?= $k->id_kelas ?>"><?= $k->nama_kelas ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" id="btnSave" onclick="save()">Simpan</button>
            </div>
        </div>
    </div>
</div>

