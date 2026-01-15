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
        "url": "<?php echo site_url('Kelas/data_list')?>",
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

});


function add() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('#form').show();      // pastikan form muncul
    $('#list_mahasiswa').html(''); // hilangkan list mahasiswa kalau ada
    $('#modal_kelas').modal('show');    
}


let save_method; // global variable

function save() {
    const btn = $('#btnSave');
    btn.text('Menyimpan...').prop('disabled', true);

    const url = save_method === 'add'
        ? "<?= site_url('Kelas/ajax_add') ?>"
        : "<?= site_url('Kelas/ajax_update') ?>";

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
                $('#modal_kelas').modal('hide');
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


function edit_kelas(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form
    $('.is-invalid').removeClass('is-invalid'); // hapus class error
    $('.invalid-feedback').text(''); // hapus pesan error

    $.ajax({
        url: "<?= site_url('Kelas/ajax_edit') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id_kelas"]').val(data.id_kelas);
            $('[name="angkatan"]').val(data.id_tahun);
            $('[name="nama_kelas"]').val(data.nama_kelas);
            $('[name="semester"]').val(data.semester);
            $('[name="jenjang"]').val(data.jenjang);
            $('[name="kategori"]').val(data.kategori);
            $('[name="status"]').val(data.status);
            $('#modal_kelas').modal('show');
            $('.modal-title').text('Edit Data Kelas');
        },
        error: function(xhr, status, error) {
            Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
    });
}

function naikkan_semester(id_kelas) {
    Swal.fire({
        title: 'Naikkan Semester?',
        text: 'Semester kelas akan dinaikkan ke semester berikutnya.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, naikkan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= site_url("kelas/naikkan_semester") ?>/' + id_kelas,
                type: 'POST',
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        reload_table();
                        Swal.fire('Berhasil!', response.msg, 'success');
                    } else {
                        Swal.fire('Gagal!', response.msg || 'Tidak bisa menaikkan semester.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menaikkan semester.', 'error');
                }
            });
        }
    });
}



function delete_kelas(id) {
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
                url: "<?= site_url('Kelas/delete') ?>/" + id,
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

let mahasiswaTable;

function lihatMahasiswa(id_kelas) {
    // Hapus instance DataTables sebelumnya (jika ada)
    if ($.fn.DataTable.isDataTable('#tableListMahasiswa')) {
        $('#tableListMahasiswa').DataTable().clear().destroy();
    }

    $('#listMahasiswaBody').html('<tr><td colspan="5" class="text-center">Memuat data mahasiswa...</td></tr>');

    $.ajax({
        url: '<?= site_url("kelas/get_mahasiswa_by_kelas") ?>',
        type: 'POST',
        data: { id_kelas: id_kelas },
        dataType: 'json',
        success: function(response) {
    if (response.status && response.data.length > 0) {
        let html = '';
        response.data.forEach(function(mhs, index) {
            html += `<tr>
                        <td>${index + 1}</td>
                        <td>${mhs.nis}</td>
                        <td>${mhs.nama_mahasiswa}</td>
                        <td>
                            ${(() => {
                                let badgeClass = '';
                                switch(mhs.status_keanggotaan.toLowerCase()) {
                                    case 'aktif':
                                        badgeClass = 'bg-success';
                                        break;
                                    case 'cuti':
                                        badgeClass = 'bg-warning';
                                        break;
                                    case 'dikeluarkan':
                                    case 'keluar':
                                        badgeClass = 'bg-danger';
                                        break;
                                    default:
                                        badgeClass = 'bg-secondary';
                                }
                                return `<span class="badge ${badgeClass}">${mhs.status_keanggotaan}</span>`;
                            })()}
                        </td>
                     </tr>`;
        });
        $('#listMahasiswaBody').html(html);

        // Jalankan DataTable hanya jika data ada
        mahasiswaTable = $('#tableListMahasiswa').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                zeroRecords: "Tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Tidak ada data tersedia",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });

    } else {
        // Jika data kosong, tampilkan pesan tanpa memanggil DataTables
        $('#listMahasiswaBody').html('<tr><td colspan="5" class="text-center">Tidak ada data mahasiswa.</td></tr>');
    }
},
        error: function() {
            $('#listMahasiswaBody').html('<tr><td colspan="5" class="text-danger text-center">Terjadi kesalahan saat memuat data.</td></tr>');
        }
    });

    const myModal = new bootstrap.Modal(document.getElementById('modalListMahasiswa'));
    myModal.show();
}


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>


<!-- Modal List Mahasiswa -->
<div class="modal fade" id="modalListMahasiswa" tabindex="-1" aria-labelledby="modalListMahasiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalListMahasiswaLabel">List Mahasiswa di Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped" id="tableListMahasiswa">
          <thead>
            <tr>
              <th>No</th>
              <th>NIS</th>
              <th>Nama Mahasiswa</th>
              <th>Status Keanggotaan</th>
              <!-- <th>Semester Masuk</th> -->
            </tr>
          </thead>
          <tbody id="listMahasiswaBody">
            <!-- Data AJAX akan masuk sini -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal_kelas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Kelas & Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="card-body">
          <form action="#" id="form" method="post">
            <input type="hidden" name="id_kelas" id="id_kelas">

            <div class="form-group row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Tahun Angkatan</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <select class="form-control" name="angkatan" id="angkatan">
                  <option value="">-- Pilih Tahun --</option>
                  <?php foreach ($tahun_akademik as $ta): ?>
                    <option value="<?= $ta->id_tahun ?>">
                      <?= $ta->tahun_akademik ?> 
                    </option>
                  <?php endforeach; ?>
                </select>
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Nama Kelas</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <input type="text" class="form-control" name="nama_kelas" id="nama_kelas">
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Semester</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <select class="form-control" name="semester" id="semester">
                  <option value="">-- Pilih Semester --</option>
                  <?php for ($i=1; $i<=8; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                  <?php endfor; ?>
                </select>
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
                <h6 class="mb-0">Kategori</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <select name="kategori" id="kategori" class="form-control">
                  <option value="Putra">Putra</option>
                  <option value="Putri">Putri</option>
                </select>
                <span class="invalid-feedback"></span>
              </div>
            </div>

            <div class="form-group row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Status</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <select name="status" id="status" class="form-control">
                  <option value="">-- Pilih Status --</option>
                  <option value="Aktif">Aktif</option>
                  <option value="Tidak Aktif">Tidak Aktif</option>
                  <option value="Lulus">Lulus</option>
                </select>
                <span class="invalid-feedback"></span>
              </div>
            </div>
          </form>

          <hr>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btnSave" onclick="save()">Save</button>
      </div>
    </div>
  </div>
</div>




