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
    // Initialize Select2 for Kelas dropdown with search
    $('#kelas_select').select2({
        placeholder: '-- Pilih Kelas --',
        width: '100%',
        dropdownParent: $('#modal_distribusi')
    });
    
    // Initialize Select2 for Mahasiswa
    $('#mahasiswa_select').select2({
        placeholder: 'Pilih Mahasiswa',
        width: '100%',
        dropdownParent: $('#modal_distribusi')
    });
    
    // Counter untuk mahasiswa terpilih
    $('#mahasiswa_select').on('change', function() {
        var count = $(this).val() ? $(this).val().length : 0;
        $('#selected_count').text(count);
        if (count > 0) {
            $('#selection_info').slideDown();
        } else {
            $('#selection_info').slideUp();
        }
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
                
                // Bind input text
                $('input', cell).off('keyup change').on('keyup change', function (e) {
                    e.stopPropagation();
                    var curValue = this.value;
                    api.column(colIdx).search(curValue).draw();
                });
            });
        }
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
                            // Icon untuk JK
                            var jk_icon = mhs.jk == 'Laki-laki' ? '♂' : '♀';
                            var jk_color = mhs.jk == 'Laki-laki' ? '#007bff' : '#e83e8c';
                            
                            $('#mahasiswa_select').append(
                                '<option value="'+mhs.nis+'">'+
                                ' ' + mhs.nama_mahasiswa + ' (' + mhs.nis + ') | ' + mhs.jk +
                                '</option>'
                            );
                        });
                        
                        // Show info about filtering
                        if (res.jenjang || res.kategori) {
                            console.log('Filter: Jenjang ' + res.jenjang + ', Kategori ' + res.kategori);
                        }
                    } else {
                        $('#mahasiswa_select').append('<option value="">Tidak ada mahasiswa tersedia untuk kelas '+res.kategori+'</option>');
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
    
    // Reset Select2
    $('#kelas_select').val(null).trigger('change');
    $('#mahasiswa_select').val(null).trigger('change');
    
    // Hide selection info
    $('#selection_info').hide();
    $('#selected_count').text('0');
    
    $('#modal_distribusi').modal('show');
    $('.modal-title').text('Tambah Distribusi Mahasiswa');
}


function pindah(id) {
    save_method = 'update';
    $('#form_edit')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    $.ajax({
        url: "<?= site_url('distribusi_kelas/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#edit_id_distribusi_kelas').val(data.id_distribusi_kelas);
            $('#edit_nis').val(data.nis);
            $('#display_nis').val(data.nis);
            $('#edit_nama_mahasiswa').val(data.nama_mahasiswa);
            $('#edit_status_keanggotaan').val(data.status_keanggotaan);
            $('#edit_id_kelas').val(data.id_kelas);
            
            // Store old values for comparison
            $('#old_id_kelas').val(data.id_kelas);
            $('#old_status').val(data.status_keanggotaan);
            $('#mahasiswa_jk').val(data.jk);
            
            // Display current class
            var current_class_info = data.kelas_sekarang;
            if (data.jenjang && data.kategori) {
                current_class_info += ' | ' + (data.jenjang == 'M1' ? 'Marhalah Ula' : 'Marhalah Tsaniya') + ' | ' + data.kategori;
            }
            $('#current_kelas').val(current_class_info);
            
            // Initialize Select2 for edit kelas
            $('#edit_id_kelas').select2({
                placeholder: '-- Pilih Kelas --',
                width: '100%',
                dropdownParent: $('#modal_edit_status'),
                dropdownCssClass: 'select2-dropdown-above-modal'
            });
            
            // Filter kelas options based on gender
            filterKelasByGender(data.jk);
            
            $('#modal_edit_status').modal('show');
            $('.modal-title').text('Edit Status & Kelas Mahasiswa');
        },
        error: function() {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}

// Filter kelas by gender
function filterKelasByGender(jk) {
    var targetKategori = jk == 'Laki-laki' ? 'Putra' : 'Putri';
    
    $('#edit_id_kelas option').each(function() {
        var kategori = $(this).data('kategori');
        if (kategori && kategori != targetKategori) {
            $(this).prop('disabled', true).hide();
        } else {
            $(this).prop('disabled', false).show();
        }
    });
}



let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    
    // Check for dangerous status changes
    if (save_method === 'update') {
        var newStatus = $('#edit_status_keanggotaan').val();
        var oldStatus = $('#old_status').val();
        
        if ((newStatus == 'Dikeluarkan' || newStatus == 'Keluar') && newStatus != oldStatus) {
            Swal.fire({
                title: 'Konfirmasi Status Berbahaya',
                html: `Anda akan mengubah status menjadi <strong>${newStatus}</strong>.<br>Status ini akan tersinkron ke data master mahasiswa.<br><br>Yakin melanjutkan?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Ubah Status',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    performSave(btn);
                }
            });
            return;
        }
    }
    
    performSave(btn);
}

function performSave(btn) {
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
                
                // Use custom message if available
                var successMessage = response.message || pesan;
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    html: successMessage,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
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
                                    <?php foreach ($kelas as $k): 
                                        $jenjang_text = $k->jenjang == 'M1' ? 'Marhalah Ula' : 'Marhalah Tsaniya';
                                        $status_class = $k->status == 'Aktif' ? 'text-success' : 'text-secondary';
                                    ?>
                                        <option value="<?= $k->id_kelas ?>" 
                                                data-jenjang="<?= $k->jenjang ?>" 
                                                data-kategori="<?= $k->kategori ?>">
                                            <?= $k->nama_kelas ?> | <?= $jenjang_text ?> | <?= $k->kategori ?> | Sem <?= $k->semester ?> | <?= $k->status ?>
                                        </option>
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
                                <small class="text-muted d-block mt-1">Pilih satu atau lebih mahasiswa yang akan dimasukkan ke kelas.</small>
                                <div class="alert alert-info mt-2" id="selection_info" style="display:none;">
                                    <strong> Terpilih: <span id="selected_count">0</span> mahasiswa</strong>
                                </div>
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
                        <input type="hidden" name="edit_nis" id="edit_nis" />
                        <input type="hidden" name="old_id_kelas" id="old_id_kelas" />
                        <input type="hidden" name="old_status" id="old_status" />
                        <input type="hidden" name="mahasiswa_jk" id="mahasiswa_jk" />

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">NIS</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="display_nis" id="display_nis" class="form-control" readonly />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Nama Mahasiswa</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="edit_nama_mahasiswa" id="edit_nama_mahasiswa" class="form-control" readonly />
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Kelas Sekarang</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="current_kelas" id="current_kelas" class="form-control" readonly style="background-color: #e9ecef; font-weight: bold;" />
                                <small class="text-muted">Kelas yang sedang ditempati mahasiswa</small>
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
                                    <option value="Aktif"><i class="bx bx-check-circle"></i> Aktif</option>
                                    <option value="Cuti"><i class="bx bx-pause-circle"></i> Cuti</option>
                                    <option value="Lulus"><i class="bx bxs-graduation"></i> Lulus</option>
                                    <option value="Dikeluarkan"><i class="bx bx-error-circle"></i> Dikeluarkan</option>
                                    <option value="Keluar"><i class="bx bx-log-out-circle"></i> Keluar</option>
                                </select>
                                <small class="text-muted">Status mahasiswa akan tersinkron di data master</small>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Pindah Ke Kelas</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <select class="form-control" name="edit_id_kelas" id="edit_id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelas as $k): 
                                        $jenjang_text = $k->jenjang == 'M1' ? 'Marhalah Ula' : 'Marhalah Tsaniya';
                                    ?>
                                        <option value="<?= $k->id_kelas ?>" 
                                                data-jenjang="<?= $k->jenjang ?>" 
                                                data-kategori="<?= $k->kategori ?>">
                                            <?= $k->nama_kelas ?> | <?= $jenjang_text ?> | <?= $k->kategori ?> | Sem <?= $k->semester ?> | <?= $k->status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Hanya kelas dengan gender yang sesuai yang bisa dipilih</small>
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

