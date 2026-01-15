/**
 * Mahasiswa Module - DataTables + CRUD + Import
 * Versi selaras Matakuliah (CSP-friendly, CSRF refresh, Bootstrap 5 Modal API)
 */
(function ($) {
	'use strict';

	/* ==============================
	 * 🔒 CSRF helpers
	 * ============================== */
	function meta(n) {
		var m = document.querySelector('meta[name="' + n + '"]');
		return m ? m.content : '';
	}
	var csrfName = meta('csrf-name');
	var csrfHash = meta('csrf-hash');
	var csrfCookie = meta('csrf-cookie');

	function getCookie(name) {
		var m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
		return m ? decodeURIComponent(m[2]) : null;
	}
	function token() {
		return csrfHash || getCookie(csrfCookie);
	}
	function updateCsrf(newHash) {
		if (!newHash) return;
		csrfHash = newHash;
		var metaEl = document.querySelector('meta[name="csrf-hash"]');
		if (metaEl) metaEl.setAttribute('content', newHash);
		// update hidden input pada form jika ada
		var hidden = document.querySelector('input[name="' + csrfName + '"]');
		if (hidden) hidden.value = newHash;
	}

	/* ==============================
	 * 🌐 URL mapping dari #app-url
	 * ============================== */
	var app = (function () {
		var el = document.getElementById('app-url');
		return el
			? {
					list: el.dataset.list,
					add: el.dataset.add,
					update: el.dataset.update,
					edit: el.dataset.edit,
					del: el.dataset.delete,
				}
			: {};
	})();

	/* ==============================
	 * 🪟 Bootstrap 5 Modal helpers
	 * ============================== */
	function showModal(id) {
		var el = document.getElementById(id);
		var m = bootstrap.Modal.getOrCreateInstance(el);
		m.show();
		return m;
	}
	function hideModal(id) {
		var el = document.getElementById(id);
		var m = bootstrap.Modal.getOrCreateInstance(el);
		m.hide();
	}

	/* ==============================
	 * 📊 DataTables
	 * ============================== */
	var table;
	var save_method = 'add';

	$(function () {
		table = $('#tabel_view').DataTable({
			ajax: {
				url: app.list,
				type: 'POST',
				data: function (d) {
					d[csrfName] = token();
				},
				dataSrc: function (json) {
					updateCsrf(json && json.csrf);
					return (json && json.data) || [];
				},
				error: function (xhr) {
					if (xhr && xhr.status === 403) {
						Swal.fire('Sesi habis', 'Silakan login kembali.', 'warning').then(
							function () {
								window.location.href = 'login';
							}
						);
					} else {
						Swal.fire(
							'Gagal memuat data',
							'Status ' + (xhr && xhr.status),
							'error'
						);
						console.error('DT error:', xhr && xhr.responseText);
					}
				},
			},
			columnDefs: [{ targets: [-1], orderable: false }],
			responsive: false, // jaga kolom Aksi tetap tampil
			autoWidth: false,
			scrollX: true, // munculkan horizontal scroll jika perlu
			dom: 'Bfrtip',
			buttons: ['copy', 'excel', 'pdf', 'print'],
			paging: true,
			searching: true,
			ordering: true,
			// ,language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json' }
		});

		/* ==============================
		 * 🎛️ Events (tanpa inline)
		 * ============================== */
		// Tambah
		$(document).on('click', '#btnAdd', onAdd);
		// Simpan
		$(document).on('click', '#btnSave', onSave);
		// Edit
		$(document).on('click', '.btn-edit', function () {
			onEdit($(this).data('id'));
		});
		// Hapus
		$(document).on('click', '.btn-delete', function () {
			onRemove($(this).data('id'));
		});
	});

	function reload_table() {
		if (table) table.ajax.reload(null, false);
	}

	/* ==============================
	 * ➕ Tambah
	 * ============================== */
	function onAdd() {
		save_method = 'add';
		var f = $('#form')[0];
		if (f) f.reset();
		$('.is-invalid').removeClass('is-invalid');
		$('.invalid-feedback').text('');
		$('#password-info').text(''); // info password kosong saat tambah
		showModal('modal_mahasiswa');
		$('.modal-title').text('Tambah Data Mahasiswa');
	}

	/* ==============================
	 * ✏️ Edit
	 * ============================== */
	function onEdit(id) {
		save_method = 'update';
		var f = $('#form')[0];
		if (f) f.reset();
		$('.is-invalid').removeClass('is-invalid');
		$('.invalid-feedback').text('');

		$.ajax({
			url: app.edit + '/' + encodeURIComponent(id),
			type: 'GET',
			dataType: 'json',
			success: function (res) {
				updateCsrf(res && res.csrf);
				if (res && res.error) {
					Swal.fire('Error', 'Data tidak ditemukan', 'error');
					return;
				}
				// Controller mengirimkan langsung field baris (array cast)
				var d = res || {};
				$('[name="nis"]').val(d.nis);
				$('[name="nim"]').val(d.nim);
				$('[name="nama_lengkap"]').val(d.nama_mahasiswa);
				$('[name="tempat_lahir"]').val(d.tempat_lahir);
				$('[name="tanggal_lahir"]').val(d.tanggal_lahir);
				$('[name="no_hp"]').val(d.nomor_hp);
				$('[name="jenis_kelamin"]').val(d.jk);
				$('[name="alamat"]').val(d.alamat);
				$('[name="email"]').val(d.email);
				$('[name="biaya_pendidikan"]').val(d.biaya_pendidikan);
				$('[name="status"]').val(d.status);
				$('#password-info').text('Kosongi jika tidak ingin mengubah password.');

				$('.modal-title').text('Edit Data Mahasiswa');
				showModal('modal_mahasiswa');
			},
			error: function () {
				Swal.fire('Error', 'Gagal mengambil data', 'error');
			},
		});
	}

	/* ==============================
	 * 💾 Simpan (Add/Update)
	 * ============================== */
	function onSave() {
		var btn = $('#btnSave');
		btn.text('Menyimpan...').prop('disabled', true);

		var url = save_method === 'add' ? app.add : app.update;
		var fd = new FormData($('#form')[0]);

		// kirimkan penanda method utk validasi update di server
		fd.set('method', save_method);
		// sertakan CSRF terbaru
		fd.set(csrfName, token());

		$.ajax({
			url: url,
			type: 'POST',
			data: fd,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (res) {
				updateCsrf(res && res.csrf);
				$('.is-invalid').removeClass('is-invalid');
				$('.invalid-feedback').text('');

				if (res && res.status) {
					hideModal('modal_mahasiswa');
					reload_table();
					Swal.fire(
						'Sukses',
						save_method === 'add'
							? 'Berhasil menambahkan data'
							: 'Berhasil mengubah data',
						'success'
					);
				} else if (res && res.inputerror) {
					// tampilkan pesan validasi
					for (var i = 0; i < res.inputerror.length; i++) {
						var field = res.inputerror[i];
						var msg = res.error_string[i];
						var $el = $('[name="' + field + '"]');
						$el.addClass('is-invalid');
						$el
							.closest('.form-group, .mb-3, .text-secondary')
							.find('.invalid-feedback')
							.text(msg);
					}
				} else {
					Swal.fire('Gagal', (res && res.error) || 'Validasi gagal', 'error');
				}
			},
			error: function (xhr) {
				if (xhr && xhr.status === 403) {
					Swal.fire('Sesi habis', 'Silakan login ulang.', 'warning').then(
						function () {
							location.reload();
						}
					);
				} else {
					Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
				}
			},
			complete: function () {
				btn.text('Save').prop('disabled', false);
			},
		});
	}

	/* ==============================
	 * 🗑️ Hapus
	 * ============================== */
	function onRemove(id) {
		Swal.fire({
			title: 'Hapus data?',
			text: 'Data yang dihapus tidak dapat dikembalikan!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus!',
			cancelButtonText: 'Batal',
			reverseButtons: true,
		}).then(function (r) {
			if (!r.isConfirmed) return;

			var data = {};
			data[csrfName] = token();

			$.ajax({
				url: app.del + '/' + encodeURIComponent(id),
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (res) {
					updateCsrf(res && res.csrf);
					if (res && res.status) {
						reload_table();
						Swal.fire('Berhasil', 'Data telah dihapus.', 'success');
					} else {
						Swal.fire('Gagal', 'Tidak bisa menghapus data.', 'error');
					}
				},
				error: function (xhr) {
					if (xhr && xhr.status === 403) {
						Swal.fire('Sesi habis', 'Silakan login ulang.', 'warning').then(
							function () {
								location.reload();
							}
						);
					} else {
						Swal.fire(
							'Error',
							'Terjadi kesalahan saat menghapus data.',
							'error'
						);
					}
				},
			});
		});
	}
})(jQuery);
