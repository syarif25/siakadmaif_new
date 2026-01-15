/* ========= CSRF helper ========= */
function meta(n) {
	var m = document.querySelector('meta[name="' + n + '"]');
	return m ? m.content : '';
}
let csrfName = meta('csrf-name'),
	csrfHash = meta('csrf-hash'),
	csrfCookie = meta('csrf-cookie');
function getCookie(name) {
	var m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
	return m ? decodeURIComponent(m[2]) : null;
}
function updateCsrf(newHash) {
	if (newHash) {
		csrfHash = newHash;
		$('input[name="' + csrfName + '"]').val(csrfHash);
	}
}

/* ========= URL mapping ========= */
let app = (function () {
	let el = document.getElementById('app-url');
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

/* ========= DataTable init ========= */
let table,
	save_method = 'add';

$(function () {
	table = $('#tabel_view').DataTable({
		ajax: {
			url: app.list,
			type: 'POST',
			data: (d) => {
				d[csrfName] = csrfHash || getCookie(csrfCookie);
			},
			dataSrc: (json) => {
				updateCsrf(json?.csrf);
				return json?.data || [];
			},
			error: (xhr, ts, err) => {
				Swal.fire('Gagal memuat data', 'Status ' + xhr.status, 'error');
				console.error('DT error:', xhr.responseText);
			},
		},
		columnDefs: [{ targets: [-1], orderable: false }],
		dom: 'Bfrtip',
		buttons: ['copy', 'excel', 'pdf', 'print'],
		paging: true,
		searching: true,
		ordering: true,
	});

	// tombol-tombol
	$(document).on('click', '#btnAddMK', add);
	$(document).on('click', '#btnSave', save);
	$(document).on('click', '.btn-edit', function () {
		edit($(this).data('id'));
	});
	$(document).on('click', '.btn-delete', function () {
		remove($(this).data('id'));
	});
});

function reload_table() {
	table?.ajax.reload(null, false);
}

/* ========= CRUD ========= */
function add() {
	$('#form')[0].reset();
	$('.is-invalid').removeClass('is-invalid');
	$('.invalid-feedback').empty();
	save_method = 'add';
	$('#modal_matakuliah').modal('show');
}

function save() {
	$('#btnSave').text('Menyimpan...').prop('disabled', true);
	const url = save_method === 'add' ? app.add : app.update;
	const fd = new FormData($('#form')[0]);
	fd.set(csrfName, csrfHash || getCookie(csrfCookie));

	$.ajax({
		url,
		type: 'POST',
		data: fd,
		contentType: false,
		processData: false,
		dataType: 'json',
		success: (res) => {
			updateCsrf(res?.csrf);
			if (res?.status) {
				$('#modal_matakuliah').modal('hide');
				reload_table();
				Swal.fire({
					icon: 'success',
					title: 'Sukses',
					text:
						save_method === 'add'
							? 'Berhasil menambah data'
							: 'Berhasil mengubah data',
					timer: 1500,
					showConfirmButton: false,
				});
			} else if (res?.inputerror) {
				$('.is-invalid').removeClass('is-invalid');
				$('.invalid-feedback').empty();
				res.inputerror.forEach((n, i) => {
					$('[name="' + n + '"]').addClass('is-invalid');
					$('[name="' + n + '"]')
						.closest('.text-secondary, .mb-3, .form-group')
						.find('.invalid-feedback')
						.text(res.error_string[i]);
				});
			} else {
				Swal.fire('Gagal', res?.error || 'Validasi gagal', 'error');
			}
		},
		error: () =>
			Swal.fire('Error', 'Terjadi kesalahan saat menyimpan', 'error'),
		complete: () => $('#btnSave').text('Save').prop('disabled', false),
	});
}

function edit(id) {
	save_method = 'update';
	$('#form')[0].reset();
	$('.is-invalid').removeClass('is-invalid');
	$('.invalid-feedback').empty();
	$.ajax({
		url: app.edit + '/' + id,
		type: 'GET',
		dataType: 'json',
		success: (d) => {
			updateCsrf(d?.csrf);
			if (d?.error) {
				Swal.fire('Error', 'Data tidak ditemukan', 'error');
				return;
			}
			$('[name="id_matakuliah"]').val(d.id_matakuliah);
			$('[name="kode"]').val(d.kode_matakuliah);
			$('[name="nama_matakuliah"]').val(d.nama_matakuliah);
			$('[name="sks"]').val(d.sks);
			$('[name="jenjang"]').val(d.jenjang);
			$('[name="semester"]').val(d.semester);
			$('#modal_matakuliah').modal('show');
		},
		error: () => Swal.fire('Error', 'Gagal mengambil data', 'error'),
	});
}

function remove(id) {
	Swal.fire({
		title: 'Hapus data?',
		text: 'Data yang dihapus tidak bisa dikembalikan.',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya, hapus!',
	}).then((r) => {
		if (!r.isConfirmed) return;
		let d = {};
		d[csrfName] = csrfHash || getCookie(csrfCookie);
		$.ajax({
			url: app.del + '/' + id,
			type: 'POST',
			dataType: 'json',
			data: d,
			success: (res) => {
				updateCsrf(res?.csrf);
				if (res?.status) {
					reload_table();
					Swal.fire('Deleted', 'Data berhasil dihapus', 'success');
				} else {
					Swal.fire('Gagal', 'Tidak bisa menghapus', 'error');
				}
			},
			error: () => Swal.fire('Error', 'Gagal menghubungi server', 'error'),
		});
	});
}
