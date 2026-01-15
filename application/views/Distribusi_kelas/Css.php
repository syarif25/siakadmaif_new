<!--plugins-->
<link href="<?php echo base_url() ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<!-- loader-->
<link href="<?php echo base_url() ?>assets/css/pace.min.css" rel="stylesheet" />
<script src="<?php echo base_url() ?>assets/js/pace.min.js"></script>
<!-- Bootstrap CSS -->
<link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>assets/css/bootstrap-extended.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>assets/css/app.css" rel="stylesheet" />
<link href="<?php echo base_url() ?>assets/css/icons.css" rel="stylesheet" />
<!-- Theme Style CSS -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/dark-theme.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/semi-dark.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/header-colors.css" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
.badge.bg-pink {
    background-color: #e83e8c;
}
#mahasiswa_select {
    position: relative;
    z-index: 1060; /* lebih besar dari modal */
}

#modal_edit_status .modal-dialog {
    z-index: 2000 !important;
}

/* Fix Select2 dropdown z-index in modal */
.select2-container {
    z-index: 9999 !important;
}

.select2-dropdown {
    z-index: 9999 !important;
}

.select2-dropdown-above-modal {
    z-index: 10000 !important;
}

</style>