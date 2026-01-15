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
        "url": "<?php echo site_url('Generate_krs/data_list')?>",
        "type": "POST"
    },
    "columnDefs": [
        { 
            "targets": [ -1, -2 ], // Aksi & Status columns
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
            var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
            
            // Bind input text & number
            $('input', cell).off('keyup change').on('keyup change', function (e) {
                e.stopPropagation();
                var curValue = this.value;
                api.column(colIdx).search(curValue).draw();
            });
        });
    }
});

});

$(document).on('click', '.proses-krs', function () {
    const id_kelas = $(this).data('id');
    const semester = $(this).data('semester');
    const $row = $(this).closest('tr');
    const rowData = table.row($row).data();

    Swal.fire({
        title: 'Proses KRS?',
        html: `<strong>KRS akan otomatis dibuat untuk:</strong><br><br>` +
              `<strong>Kelas:</strong> ${rowData[2]}<br>` +
              `<strong>Semester:</strong> ${rowData[3]}<br>` +
              `<strong>Jumlah Mahasiswa:</strong> ${rowData[5]} mahasiswa<br>` +
              `<strong>Jumlah Matakuliah:</strong> ${rowData[6]} matakuliah<br><br>` +
              `<small class="text-muted">Estimasi: ${rowData[5] * rowData[6]} record KRS akan dibuat</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Proses',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= base_url('generate_krs/proses_krs') ?>", {
                id_kelas: id_kelas,
                semester: semester
            }, function (res) {
                const response = JSON.parse(res);
                if (response.status) {
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        html: response.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            });
        }
    });
});

// Handler for Reset KRS
$(document).on('click', '.reset-krs', function () {
    const id_kelas = $(this).data('id');
    const semester = $(this).data('semester');
    const $row = $(this).closest('tr');
    const rowData = table.row($row).data();

    Swal.fire({
        title: 'Reset KRS?',
        html: `<strong class="text-danger">! PERHATIAN!</strong><br><br>` +
              `<strong>Kelas:</strong> ${rowData[2]}<br>` +
              `<strong>Semester:</strong> ${rowData[3]}<br>` +
              `<strong>Jumlah Mahasiswa:</strong> ${rowData[5]} mahasiswa<br>` +
              `<strong>Jumlah Matakuliah:</strong> ${rowData[6]} matakuliah<br><br>` +
              `<strong class="text-danger">Semua KRS mahasiswa di kelas ini akan DIHAPUS!</strong><br>` +
              `<small class="text-muted">Estimasi: ${rowData[5] * rowData[6]} record akan dihapus</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= base_url('generate_krs/reset_krs') ?>", {
                id_kelas: id_kelas,
                semester: semester
            }, function (res) {
                const response = JSON.parse(res);
                if (response.status) {
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: response.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            });
        }
    });
});

// Handler for Reset ALL KRS
$('#btn-reset-all').on('click', function () {
    Swal.fire({
        title: '! RESET SEMUA KRS?',
        html: `<strong class="text-danger">PERHATIAN! Tindakan ini BERBAHAYA!</strong><br><br>` +
              `Semua KRS untuk <strong>SEMUA KELAS</strong> di tahun akademik aktif akan DIHAPUS PERMANEN!<br><br>` +
              `<strong>Apakah Anda yakin?</strong>`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset Semua',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        showDenyButton: true,
        denyButtonText: 'Tidak, Batalkan!',
        denyButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= base_url('generate_krs/reset_all_krs') ?>", {}, function (res) {
                const response = JSON.parse(res);
                if (response.status) {
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: response.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            });
        }
    });
});



function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>
