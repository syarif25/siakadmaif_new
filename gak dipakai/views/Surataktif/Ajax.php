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
            "url": "<?php echo site_url('Pelanggaran/data_list')?>",
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
    $('#modal_pelanggaran').modal('show');    
}


$(document).ready(function () {
    $("#nis").on("blur", function () {
        var nis = $(this).val();
        if (nis !== "") {
            $.ajax({
                url: "<?php echo site_url('Pelanggaran/get_mahasiswa')?>", // endpoint untuk ambil data
                type: "GET",
                data: { nis: nis },
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $("#nama_mahasiswa").val(res.nama_mahasiswa).addClass("highlight-success");
                        $("#jenjang").val(res.jenjang).addClass("highlight-success");
                        $("#semester").val(res.semester).addClass("highlight-success");

                        setTimeout(function() {
                            $("#nama_mahasiswa").removeClass("highlight-success");
                            $("#jenjang").removeClass("highlight-success");
                            $("#semester").removeClass("highlight-success");
                        
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data tidak ditemukan',
                            text: 'Mahasiswa dengan NIS ' + nis + ' tidak ada di database!',
                            confirmButtonText: 'OK'
                        });
                        $("#nama_mahasiswa").val("");
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan koneksi ke server.', 'error');
                }
            });
        }
    });
});


let save_method; 
function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    const url = save_method === 'add'
        ? "<?= site_url('pelanggaran/ajax_add') ?>"
        : "<?= site_url('pelanggaran/ajax_update') ?>";

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
                $('#modal_pelanggaran').modal('hide');
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
                    input.next('.invalid-feedback').text(response.error_string[i]);
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


function edit_pelanggaran(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form
    $('.is-invalid').removeClass('is-invalid'); // hapus class error
    $('.invalid-feedback').text(''); // hapus pesan error

    $.ajax({
        url: "<?= site_url('Pelanggaran/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id_pelanggaran"]').val(data.id_pelanggaran);
            $('[name="nis"]').val(data.nis);
            $('[name="nama_mahasiswa"]').val(data.nama_mahasiswa);
            $('[name="jenjang"]').val(data.jenjang);
            $('[name="semester"]').val(data.semester);
            $('[name="jenis_pelanggaran"]').val(data.jenis_pelanggaran);
            $('[name="sanksi"]').val(data.sanksi);
            $('[name="tanggal_pelanggaran"]').val(data.tanggal_pelanggaran);
            $('#modal_pelanggaran').modal('show');
            $('.modal-title').text('Edit Data Matakuliah');
        },
        error: function(xhr, status, error) {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}

function delete_pelanggaran(id) {
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
                url: "<?= site_url('Pelanggaran/delete') ?>/" + id,
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

<div class="modal fade" id="modal_pelanggaran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Data Pelanggaran Mahasiswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form action="" id="form">
                <div class="modal-body">
                    <div class="card-body">
                    <!-- <h4 class="d-flex align-items-center mb-3">Data Diri</h4> -->
                    <hr />
                    <div class="row mb-3">
                        <div class="col-sm-3">
                        <h6 class="mb-0">NIS</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="hidden" name="id_pelanggaran">
                            <input type="text" class="form-control" id="nis" name="nis" />
                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Nama Mahasiswa</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" readonly />
                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Jenjang</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <select name="jenjang" id="jenjang" class="form-control">
                                <option value="">Pilih Jenjang</option>
                                <option>M1</option>
                                <option>M2</option>
                            </select>
                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0"> Semester</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="number" id="semester" class="form-control" name="semester" />
                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Jenis Pelanggaran</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" class="form-control" name="jenis_pelanggaran" />
                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Sanksi</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control" name="sanksi" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Tanggal Pelanggaran</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <input type="date" class="form-control" name="tanggal_pelanggaran" />
                        <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 text-secondary">
                        <!-- <input type="button" class="btn btn-primary px-4" value="Save Changes" /> -->
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">Save</button>
                </div>
            </form>
        </div>
    </div>
    </div>