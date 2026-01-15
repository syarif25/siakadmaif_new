/* ========= CSRF helper ========== */

function meta(name) {
	let el = document.querySelector('meta[name="' + name + '"]');
	return el ? el.content : '';
}
let csrfName = meta('csrf-name'),
	csrfHash = meta('csrf-hash'),
	csrfCookie = meta('csrf-cookie');
function getCookie(name) {
	let m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
	return m ? decodeURIComponent(m[2]) : null;
}
function updateCsrf(newHash) {
	if (newHash) {
		csrfHash = newHash;
		$('input[name="' + csrfName + '"]').val(csrfHash);
	}
}

/* ========= URL mapping ========== */
let app = (() => {
	let el = document.getElementById('app-url');
	return el
		? {
				list: el.dataset.list,
				add: el.dataset.add,
				update: el.dataset.update,
				edit: el.dataset.edit,
				del: el.dataset.delete,
				aktif: el.dataset.aktifkan,
			}
		: {};
})();
console.log('=== DEBUG URL MAPPING ===');
console.log('app:', app);

/* ========= DataTable init ========== */

let table;
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
			error: function (xhr, textStatus, err) {
				Swal.fire(
					'Gagal memuat data',
					'Status ' +
						xhr.status +
						' — ' +
						(xhr.responseText?.substring(0, 200) || ''),
					'error'
				);
				console.error(
					'DT AJAX error:',
					xhr.status,
					textStatus,
					err,
					xhr.responseText
				);
			},
		},
		columnDefs: [{ targets: [-1], orderable: false }],
		paging: true,
		searching: true,
		ordering: true,
	});

	console.log('app.list =', app.list);
	$('#tabel_view').on('xhr.dt', function (e, settings, json, xhr) {
		console.log('DT xhr status:', xhr.status, 'json:', json);
	});
});

function reload_table() {
	table?.ajax.reload(null, false);
}

$(document).on('click', '#btnSave', function () {
	save();
});

/* ========= CRUD ========== */
let save_method = 'add';
function add() {
	$('#form')[0].reset();
	$('.is-invalid').removeClass('is-invalid');
	$('.invalid-feedback').empty();
	save_method = 'add';
	$('#modal_tademik').modal('show');
}
function save() {
	$('#btnSave').text('Menyimpan...').prop('disabled', true);
	let url = save_method === 'add' ? app.add : app.update;
	let pesan =
		save_method === 'add' ? 'Berhasil menambah data' : 'Berhasil mengubah data';
	let fd = new FormData($('#form')[0]);
	fd.set(csrfName, csrfHash || getCookie(csrfCookie));
	$.ajax({
		url,
		type: 'POST',
		data: fd,
		contentType: false,
		processData: false,
		dataType: 'json',
		success: (r) => {
			updateCsrf(r?.csrf);
			if (r?.status) {
				$('#modal_tademik').modal('hide');
				reload_table();
				Swal.fire({
					icon: 'success',
					title: 'Sukses',
					text: pesan,
					timer: 1500,
					showConfirmButton: false,
				});
			} else {
				$('.is-invalid').removeClass('is-invalid');
				$('.invalid-feedback').empty();
				(r?.inputerror || []).forEach((n, i) => {
					$('[name="' + n + '"]').addClass('is-invalid');
					$('[name="' + n + '"]')
						.closest('.text-secondary')
						.find('.invalid-feedback')
						.text(r.error_string[i]);
				});
			}
		},
		error: (x) => {
			Swal.fire('Error', 'Gagal menyimpan data', 'error');
		},
		complete: () => $('#btnSave').text('Save').prop('disabled', false),
	});
}
function edit_tahun(id) {
	save_method = 'update';
	$('#form')[0].reset();
	$('.is-invalid').removeClass('is-invalid');
	$('.invalid-feedback').empty();
	$.ajax({
		url: app.edit + '/' + id,
		type: 'GET',
		dataType: 'json',
		success: (d) => {
			if (!d) {
				Swal.fire('Error', 'Data tidak ditemukan', 'error');
				return;
			}
			$('[name="id_tahun"]').val(d.id_tahun);
			$('[name="tahun_akademik"]').val(d.tahun_akademik);
			$('[name="semester"]').val(d.semester);
			$('[name="tanggal_mulai"]').val(d.tanggal_mulai);
			$('[name="tanggal_selesai"]').val(d.tanggal_selesai);
			$('#modal_tademik').modal('show');
		},
		error: () => Swal.fire('Error', 'Gagal mengambil data', 'error'),
	});
}
function delete_tahun(id) {
	Swal.fire({
		title: 'Yakin hapus?',
		text: 'Data tidak bisa dikembalikan',
		icon: 'warning',
		showCancelButton: true,
	}).then((r) => {
		if (!r.isConfirmed) return;
		let d = {};
		d[csrfName] = csrfHash || getCookie(csrfCookie);
		$.ajax({
			url: app.del + '/' + id,
			type: 'POST',
			dataType: 'json',
			data: d,
			success: (r) => {
				updateCsrf(r?.csrf);
				if (r?.status) {
					reload_table();
					Swal.fire('Deleted!', 'Data dihapus.', 'success');
				} else Swal.fire('Gagal', 'Tidak bisa menghapus', 'error');
			},
			error: () => Swal.fire('Error', 'Gagal menghapus', 'error'),
		});
	});
}
function konfirmasiAktifkan(id) {
	Swal.fire({
		title: 'Aktifkan tahun ini?',
		text: 'Tahun lain akan dinonaktifkan',
		icon: 'warning',
		showCancelButton: true,
	}).then((r) => {
		if (!r.isConfirmed) return;
		let d = {};
		d[csrfName] = csrfHash || getCookie(csrfCookie);
		$.ajax({
			url: app.aktif + '/' + id,
			type: 'POST',
			dataType: 'json',
			data: d,
			success: (r) => {
				updateCsrf(r?.csrf);
				if (r?.status) {
					reload_table();
					Swal.fire('Berhasil', 'Tahun aktif diperbarui', 'success');
				} else Swal.fire('Gagal', 'Tidak bisa mengaktifkan', 'error');
			},
			error: () => Swal.fire('Error', 'Server tidak merespons', 'error'),
		});
	});
}

$(document).on('click', '#btnAddTahun', function () {
	add();
});

/* ========= Event delegation (tanpa inline onclick) ========== */
$(document).on('click', '.btn-edit', (e) =>
	edit_tahun($(e.currentTarget).data('id'))
);
$(document).on('click', '.btn-delete', (e) =>
	delete_tahun($(e.currentTarget).data('id'))
);
$(document).on('click', '.btn-aktifkan', (e) =>
	konfirmasiAktifkan($(e.currentTarget).data('id'))
);
