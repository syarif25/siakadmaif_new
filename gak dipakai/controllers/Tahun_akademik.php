<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_akademik extends MY_Controller
{
    // Aturan akses per-method (multi-role)
    protected $role_map = [
        'index'      => ['petugas','dosen'],    // list/halaman utama
        'data_list'  => ['petugas','dosen'],    // data untuk tabel
        'ajax_add'   => ['petugas'],            // CRUD khusus petugas
        'ajax_edit'  => ['petugas'],
        'ajax_update'=> ['petugas'],
        'delete'     => ['petugas'],
        'aktifkan'   => ['petugas'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tahun_akademik_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form','Tanggal_helper']);
        // Tidak perlu cek logged_in di sini; MY_Controller sudah handle
    }

    public function index()
    {
        $isi['content'] = 'Tahun_akademik/Tahun_akademik';
        $isi['ajax']    = 'Tahun_akademik/Ajax';
        $isi['css']     = 'Tahun_akademik/Css';
        $this->load->view('Template',$isi);
    }

    public function data_list()
{
    // Selalu balas JSON
    $this->output->set_content_type('application/json');

    // Wajib POST (tapi tetap balas JSON agar DataTables tidak error)
    if (strtolower($this->input->method()) !== 'post') {
        echo json_encode([
            'data' => [],
            'csrf' => $this->security->get_csrf_hash(),
            'info' => ['error' => 'METHOD_NOT_ALLOWED', 'method' => $this->input->method()]
        ]);
        return;
    }

    $role = strtolower((string) $this->session->userdata('role'));
    $list = $this->Tahun_akademik_model->get_datatables();

    $no   = 1;
    $rows = [];
    foreach ($list as $row) {
        $aksi = [];
        $aksi[] = '<button type="button" class="btn btn-outline-primary btn-sm btn-edit" data-id="'.
                  htmlentities($row->id_tahun, ENT_QUOTES, 'UTF-8').
                  '"><i class="bx bx-edit mr-1"></i> Edit</button>';

        if ($role === 'petugas' && $row->status !== 'Aktif') {
            $aksi[] = '<button type="button" class="btn btn-outline-danger btn-sm btn-delete" data-id="'.
                      htmlentities($row->id_tahun, ENT_QUOTES, 'UTF-8').
                      '"><i class="bx bx-trash mr-1"></i> Delete</button>';
            $aksi[] = '<button type="button" class="btn btn-outline-info btn-sm btn-aktifkan" data-id="'.
                      htmlentities($row->id_tahun, ENT_QUOTES, 'UTF-8').
                      '"><i class="bx bx-check-circle mr-1"></i> Aktifkan</button>';
        }

        $rows[] = [
            $no++,
            htmlentities($row->tahun_akademik, ENT_QUOTES, 'UTF-8'),
            format_tanggal_indonesia($row->tanggal_mulai).' - '.format_tanggal_indonesia($row->tanggal_selesai),
            ($row->status === 'Aktif'
                ? '<span class="badge bg-success text-white">Aktif</span>'
                : '<span class="badge bg-danger text-white">Tidak Aktif</span>'),
            implode(' ', $aksi),
        ];
    }

    echo json_encode([
        'data' => $rows,
        'csrf' => $this->security->get_csrf_hash(),
        'info' => [
            'count' => count($rows),
            'role'  => $role
        ]
    ]);
}

public function ajax_add()
{
    // Wajib POST
    if (strtolower($this->input->method()) !== 'post') {
        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => FALSE,
                'csrf'   => $this->security->get_csrf_hash(),
                'error'  => 'METHOD_NOT_ALLOWED'
            ]));
    }

    $this->_validate(); // termasuk validasi tanggal

    $data = [
        'tahun_akademik'  => trim($this->input->post('tahun_akademik', TRUE)),
        'semester'        => $this->input->post('semester', TRUE),
        'tanggal_mulai'   => $this->input->post('tanggal_mulai', TRUE),
        'tanggal_selesai' => $this->input->post('tanggal_selesai', TRUE),
        'status'          => 'Tidak Aktif',
    ];

    // CATATAN: sesuaikan dengan signature model kamu (create($data) atau create($table,$data))
    $this->Tahun_akademik_model->create($data);

    return $this->output->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => TRUE,
            'csrf'   => $this->security->get_csrf_hash()
        ]));
}

public function ajax_edit($id)
{
    // GET diperbolehkan untuk ambil satu baris
    $row = $this->Tahun_akademik_model->get_by_id($id);

    // Sertakan CSRF baru juga supaya form berikutnya aman
    return $this->output->set_content_type('application/json')
        ->set_output(json_encode($row ? array_merge((array)$row, [
            'csrf' => $this->security->get_csrf_hash()
        ]) : [
            'csrf'  => $this->security->get_csrf_hash(),
            'error' => 'NOT_FOUND'
        ]));
}

public function ajax_update()
{
    if (strtolower($this->input->method()) !== 'post') {
        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => FALSE,
                'csrf'   => $this->security->get_csrf_hash(),
                'error'  => 'METHOD_NOT_ALLOWED'
            ]));
    }

    $this->_validate();

    $payload = [
        'tahun_akademik'  => trim($this->input->post('tahun_akademik', TRUE)),
        'semester'        => $this->input->post('semester', TRUE),
        'tanggal_mulai'   => $this->input->post('tanggal_mulai', TRUE),
        'tanggal_selesai' => $this->input->post('tanggal_selesai', TRUE),
        'status'          => 'Tidak Aktif', // aktivasi pakai endpoint khusus
    ];

    $this->Tahun_akademik_model->update(
        ['id_tahun' => $this->input->post('id_tahun', TRUE)],
        $payload
    );

    return $this->output->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => TRUE,
            'csrf'   => $this->security->get_csrf_hash()
        ]));
}

public function delete($id)
{
    if (strtolower($this->input->method()) !== 'post') {
        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => FALSE,
                'csrf'   => $this->security->get_csrf_hash(),
                'error'  => 'METHOD_NOT_ALLOWED'
            ]));
    }

    $ok = $this->Tahun_akademik_model->delete($id) > 0;

    return $this->output->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => $ok,
            'csrf'   => $this->security->get_csrf_hash()
        ]));
}

public function aktifkan($id)
{
    if (strtolower($this->input->method()) !== 'post') {
        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => FALSE,
                'csrf'   => $this->security->get_csrf_hash(),
                'error'  => 'METHOD_NOT_ALLOWED'
            ]));
    }

    // Transaksi: nonaktifkan semua lalu aktifkan satu
    $this->db->trans_start();
    $this->db->update('tahun_akademik', ['status' => 'Tidak Aktif']);
    $this->db->where('id_tahun', $id)->update('tahun_akademik', ['status' => 'Aktif']);
    $this->db->trans_complete();
    $ok = $this->db->trans_status();

    return $this->output->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => (bool)$ok,
            'csrf'   => $this->security->get_csrf_hash()
        ]));
}


    /* ===================== Validation ===================== */

    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules(
            'tahun_akademik', 'Tahun Akademik',
            'required|trim|min_length[4]|max_length[30]',
            ['required' => 'Kolom {field} wajib diisi.']
        );

        $this->form_validation->set_rules(
            'semester', 'Semester',
            'required|in_list[Ganjil,Genap]',
            [
                'required' => 'Kolom {field} tidak boleh kosong.',
                'in_list'  => 'Pilih salah satu dari Ganjil atau Genap untuk {field}.'
            ]
        );

        $this->form_validation->set_rules(
            'tanggal_mulai', 'Tanggal Mulai',
            'required|callback__valid_date'
        );

        $this->form_validation->set_rules(
            'tanggal_selesai', 'Tanggal Selesai',
            'required|callback__valid_date'
        );

        if ($this->form_validation->run() === FALSE) {
            $errors = ['inputerror' => [], 'error_string' => [], 'status' => FALSE];

            foreach ($_POST as $key => $value) {
                if (form_error($key)) {
                    $errors['inputerror'][]   = $key;
                    $errors['error_string'][] = form_error($key);
                }
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($errors));
            exit;
        }

        // Validasi logis: tanggal_mulai <= tanggal_selesai
        $mulai   = $this->input->post('tanggal_mulai', TRUE);
        $selesai = $this->input->post('tanggal_selesai', TRUE);

        if (strtotime($mulai) === false || strtotime($selesai) === false || strtotime($mulai) > strtotime($selesai)) {
            $errors = [
                'inputerror'   => ['tanggal_mulai','tanggal_selesai'],
                'error_string' => ['Tanggal tidak valid.', 'Tanggal tidak valid.'],
                'status'       => FALSE
            ];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($errors));
            exit;
        }
    }

    // Validator format tanggal sederhana (YYYY-mm-dd)
    public function _valid_date($date)
    {
        if (!$date) return FALSE;
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
