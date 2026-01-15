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
            "url": "<?php echo site_url('Presensi/data_list')?>",
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

    $('#formPresensi').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo site_url('presensi/simpan_rekap'); ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    $('#kehadiranModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message || 'Presensi berhasil disimpan!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#tabel_view').DataTable().ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Gagal menyimpan presensi!',
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Server',
                    text: 'Terjadi kesalahan pada server.',
                });
            }
        });
    });


});

function inputKehadiran(id_kelas, id_matkul) {
    $('#inputIdKelas').val(id_kelas);
    $('#inputIdMatkul').val(id_matkul);
    $('#kehadiranModalLabel').text('Input Kehadiran');

    $('#tabelRekapPresensi tbody').html('<tr><td colspan="6">Loading...</td></tr>');

    $.ajax({
        url: "<?php echo site_url('presensi/get_mahasiswa_json') ?>",
        method: 'POST',
        dataType: 'json',
        data: { id_kelas: id_kelas, id_matkul: id_matkul },
        success: function(data) {
            let html = '';
            data.forEach(m => {
                html += `
                    <tr>
                    <td>${m.nis}</td>
                    <td>${m.nama_mahasiswa}</td>
                    <td><input type="number" class="form-control" name="hadir[${m.id_krs}]" value="${m.jumlah_hadir}"></td>
                    <td><input type="number" class="form-control" name="izin[${m.id_krs}]" value="${m.jumlah_izin}"></td>
                    <td><input type="number" class="form-control" name="sakit[${m.id_krs}]" value="${m.jumlah_sakit}"></td>
                    <td><input type="number" class="form-control" name="alpha[${m.id_krs}]" value="${m.jumlah_alpha}"></td>
                    </tr>
                `;
            });
            $('#tabelRekapPresensi tbody').html(html);
            $('#kehadiranModal').modal('show');
        }
    });
}



function reload_table()
{
    table.ajax.reload(null,false);
}
</script>

<div class="modal fade" id="kehadiranModal" tabindex="-1" aria-labelledby="kehadiranModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formPresensi">
        <div class="modal-header">
          <h5 class="modal-title" id="kehadiranModalLabel">Input Kehadiran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_kelas" id="inputIdKelas">
          <input type="hidden" name="id_matkul" id="inputIdMatkul">

          <div class="table-responsive">
            <table class="table table-bordered" id="tabelRekapPresensi">
              <thead>
                <tr>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th>Hadir</th>
                  <th>Izin</th>
                  <th>Sakit</th>
                  <th>Alpha</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>
