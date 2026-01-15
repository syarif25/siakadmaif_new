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



<script>
$(document).ready(function(){
    table = $('#tabel_view').DataTable({
    "ajax": {
        "url": "<?php echo site_url('Penilaian/data_list')?>",
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

function isi_nilai(id_kelas, id_matkul) {
    $('#inputKelas').val(id_kelas);
    $('#inputMatkul').val(id_matkul);

    $('#nilaiContent').html('<p class="text-muted">Memuat data mahasiswa...</p>');

    $.ajax({
        url: "<?php echo site_url('penilaian/get_mahasiswa')?>",
        method: 'POST',
        data: { id_kelas: id_kelas, id_matkul: id_matkul },
        success: function(response) {
            $('#nilaiContent').html(response);
            $('#modalNilai').modal('show');
        }
    });
}

$(document).on('submit', '#formNilai', function(e) {
    e.preventDefault();
    console.log('Form disubmit');

    $.ajax({
        url: "<?php echo site_url('penilaian/simpan_nilai') ?>",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            if(response.status === true){
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message
                });
                $('#modalNilai').modal('hide');
                reload_table(); // untuk refresh DataTable
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Terjadi kesalahan saat menyimpan nilai.'
            });
        }
    });
});


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>

<!-- Modal Input Nilai Mahasiswa -->
<div class="modal fade" id="modalNilai" tabindex="-1" aria-labelledby="modalNilaiLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formNilai">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNilaiLabel">Input Nilai Mahasiswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Hidden input untuk konteks -->
          <input type="hidden" name="id_kelas" id="inputKelas">
          <input type="hidden" name="id_matkul" id="inputMatkul">

          <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width: 20%;">NIS</th>
                  <th style="width: 50%;">Nama Mahasiswa</th>
                  <th style="width: 30%;">Nilai Awal (0–100)</th>
                  <th style="width: 30%;">Nilai Revisi (0–100)</th>
                </tr>
              </thead>
              <tbody id="nilaiContent">
                <!-- ISI AJAX AKAN MASUK DI SINI -->
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Nilai</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>
