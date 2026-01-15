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
        "url": "<?php echo site_url('Matakuliah/data_list')?>",
        "type": "POST"
    },
    "columnDefs": [
        { 
            "targets": [ -1 ],
            "orderable": false,
        },
        {
            "targets": [ 0 ], // No column
            "orderable": false,
            "searchable": false
        },
    ],
    "paging": true,
    "searching": true,
    "ordering": true,
    "scrollY": false,
    "orderCellsTop": true, // Use first row for ordering
    "fixedHeader": true,

    // Tambahkan dom dan buttons di sini:
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf', 'print'],
    
    // Initialize column filters
    initComplete: function () {
        var api = this.api();
        
        // Setup filters di baris kedua thead
        api.columns().eq(0).each(function (colIdx) {
            // Ambil cell di baris kedua untuk kolom ini
            var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
            
            // Bind input text & number
            $('input', cell).off('keyup change').on('keyup change', function (e) {
                e.stopPropagation();
                var curValue = this.value;
                api.column(colIdx).search(curValue).draw();
            });
            
            // Bind select dropdown
            $('select', cell).off('change').on('change', function (e) {
                e.stopPropagation();
                var val = $(this).val();
                
                // Custom search untuk jenjang column (menggunakan data-search attribute)
                if (val) {
                    api.column(colIdx).search(val, true, false).draw();
                } else {
                    api.column(colIdx).search('').draw();
                }
            });
        });
    }
});

});


function add() {
    $('#form')[0].reset(); // reset form on modals
    save_method = 'add';
    $('#modal_matakuliah').modal('show');    
}

let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    const url = save_method === 'add'
        ? "<?= site_url('Matakuliah/ajax_add') ?>"
        : "<?= site_url('Matakuliah/ajax_update') ?>";

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
                $('#modal_matakuliah').modal('hide');
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


function edit_matakuliah(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form
    $('.is-invalid').removeClass('is-invalid'); // hapus class error
    $('.invalid-feedback').text(''); // hapus pesan error

    $.ajax({
        url: "<?= site_url('Matakuliah/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id_matakuliah"]').val(data.id_matakuliah);
            $('[name="kode"]').val(data.kode_matakuliah);
            $('[name="nama_matakuliah"]').val(data.nama_matakuliah);
            $('[name="sks"]').val(data.sks);
            $('[name="jenjang"]').val(data.jenjang);
            $('[name="semester"]').val(data.semester);
            $('#modal_matakuliah').modal('show');
            $('.modal-title').text('Edit Data Matakuliah');
        },
        error: function(xhr, status, error) {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}



function delete_matakuliah(id) {
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
                url: "<?= site_url('Matakuliah/delete') ?>/" + id,
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

<div class="modal fade" id="modal_matakuliah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Data Dosen</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                <form action="#" id="form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_matakuliah" id="id_matakuliah">
                    <div class="form-group row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Kode</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" class="form-control" name="kode" id="kode">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Nama Matakuliah</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" class="form-control" name="nama_matakuliah" id="nama_matakuliah">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">SKS</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="number" class="form-control" name="sks" id="sks">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Jenjang</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <select name="jenjang" id="jenjang" class="form-control">
                                <option value="">-- Pilih Jenjang --</option>
                                <option value="M1">Marhalah Ula</option>
                                <option value="M2">Marhalah Tsaniya</option>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Semester</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <select class="form-control" name="semester" id="semester">
                                <option value="">-- Pilih Semester  --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <!-- <div class="form-group row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Silabus</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="file" class="form-control" name="silabus" id="silabus">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSave" onclick="save()">Save</button>
            </div>
    </div>
</div>