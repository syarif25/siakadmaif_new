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

<script>
$(document).ready(function(){
    table =   $('#tabel_view').DataTable({
        "ajax": {
            "url": "<?php echo site_url('Tahun_akademik/data_list')?>",
            "type": "POST"
        },

        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
        
        ],  

        "paging": true,
        "searching": true,
        "ordering": true,
        scrollY:        false,
        // scrollX:        false,
    });
});


function add() {
    $('#form')[0].reset(); // reset form on modals
    save_method = 'add';
    $('#modal_tademik').modal('show');    
}

function save()
{
    $('#btnSave').text('Proses menyimpan...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    if(save_method == 'add') {
        url = "<?php echo site_url('Tahun_akademik/ajax_add')?>";
        var pesan = 'Berhasil Menambah data';
    } else {
        url = "<?php echo site_url('Tahun_akademik/ajax_update')?>";
        var pesan = 'Berhasil Merubah data';
    }

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if(data.status) {
                $('#modal_tademik').modal('hide');
                reload_table();
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: pesan,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
            }

            $('#btnSave').text('save');
            $('#btnSave').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menyimpan data!'
            });

            $('#btnSave').text('save');
            $('#btnSave').attr('disabled', false);
        }
    }); 
}

function edit_tahun(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Tahun_akademik/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id_tahun"]').val(data.id_tahun);
            $('[name="tahun_akademik"]').val(data.tahun_akademik);
            $('[name="semester"]').val(data.semester);
            $('[name="tanggal_mulai"]').val(data.tanggal_mulai);
            $('[name="tanggal_selesai"]').val(data.tanggal_selesai);
            $('#modal_tademik').modal('show'); // show bootstrap modal when complete loaded
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function delete_tahun(id)
{
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this record!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo site_url('Tahun_akademik/delete')?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    if (data.status) {
                        reload_table();
                        Swal.fire('Deleted!', 'Your record has been deleted.', 'success');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    Swal.fire('Error!', 'There was an error deleting the record.', 'error');
                }
            });
        }
    });
}

function konfirmasiAktifkan(id) {
    Swal.fire({
        title: 'Yakin ingin mengaktifkan tahun ini?',
        text: "Tahun akademik lain akan otomatis dinonaktifkan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, aktifkan!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim request AJAX ke server
            $.ajax({
                url: '<?= site_url("tahun_akademik/aktifkan/") ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data.status) {
                        reload_table();
                        Swal.fire(
                            'Berhasil!',
                            'Tahun akademik berhasil diaktifkan.',
                            'success'
                        );
                        // reload table
                        $('#tabelnya').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat mengaktifkan.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Gagal menghubungi server.',
                        'error'
                    );
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

<div class="modal fade" id="modal_tademik" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tahun Akademik</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                <form action="#" id="form">
                    <hr />
                    <input type="hidden" name="id_tahun" />
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Tahun Akademik</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" />
                        <span class="invalid-feedback"></span>
                        </div>
                        
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Semester</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <select name="semester" id="semester" class="form-control">
                                <option value=""></option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        <span class="invalid-feedback"></span>
                        </div>
                        
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Tanggal Mulai</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" />
                    <span class="invalid-feedback"></span>    
                    </div>
                        
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                        <h6 class="mb-0">Tanggal Selesai</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" />
                    <span class="invalid-feedback"></span>    
                    </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 text-secondary">
                        <!-- <input type="button" class="btn btn-primary px-4" value="Save Changes" /> -->
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