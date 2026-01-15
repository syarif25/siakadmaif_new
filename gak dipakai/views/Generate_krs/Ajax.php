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

$(document).on('click', '.proses-krs', function () {
    const id_kelas = $(this).data('id');
    const semester = $(this).data('semester');

    Swal.fire({
        title: 'Proses KRS?',
        text: "KRS akan otomatis dibuat untuk seluruh mahasiswa di kelas ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, proses',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= base_url('generate_krs/proses_krs') ?>", {
                id_kelas: id_kelas,
                semester: semester
            }, function (res) {
                const response = JSON.parse(res);
                if (response.status) {
                    table.ajax.reload();
                    Swal.fire('Sukses', response.message, 'success');
                    $('#tabel-krs').DataTable().ajax.reload();
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
