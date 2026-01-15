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

$(document).ready(function(){
    table = $('#tabel_view').DataTable({
        "ajax": {
            "url": "<?php echo site_url('Rekap_nilai/data_list')?>",
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

</script>
