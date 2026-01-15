<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matakuliah extends MY_Controller
{
    // petugas & dosen boleh mengakses modul ini
    protected $allowed_roles = ['petugas', 'dosen'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Matakuliah_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form', 'security']);

        // Jika belum login, lempar ke login
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('login');
        }
    }

    /* ==========================
     * View
     * ========================== */
    public function index()
    {
        $isi['content'] = 'Matakuliah/Matakuliah';
        $isi['ajax']    = 'Matakuliah/Ajax';
        $isi['css']     = 'Matakuliah/Css';
        $this->load->view('Template', $isi);
    }

    /* ==========================
     * DataTables source
     * ========================== */
    public function data_list()
    {
        $this->output->set_content_type('application/json');

        // Wajib POST untuk sinkron CSRF
        if (strtolower($this->input->method()) !== 'post') {
            return $this->_json([
                'data' => [],
                'info' => ['error' => 'METHOD_NOT_ALLOWED', 'method' => $this->input->method()]
            ]);
        }

        $list = $this->Matakuliah_model->get_datatables();
        $no   = 1;
        $data = [];

        foreach ($list as $r) {
            $kode   = htmlentities($r->kode_matakuliah, ENT_QUOTES, 'UTF-8');
            $nama   = htmlentities($r->nama_matakuliah, ENT_QUOTES, 'UTF-8');
            $sks    = (int) $r->sks;
            $jenjangBadge = ($r->jenjang === 'M1')
                ? '<span class="badge bg-primary">Marhalah Ula</span>'
                : '<span class="badge bg-success text-white">Marhalah Tsani</span>';
            $semester = (int) $r->semester;

            // Tombol aksi TANPA inline handler; gunakan class + data-id (ditangani di assets/js/matakuliah.js)
            $aksi = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary btn-edit" data-id="' . htmlentities($r->id_matakuliah, ENT_QUOTES, 'UTF-8') . '">
                    <i class="bx bx-edit"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="' . htmlentities($r->id_matakuliah, ENT_QUOTES, 'UTF-8') . '">
                    <i class="bx bx-trash"></i> Hapus
                </button>
            </div>';

            $data[] = [
                $no++,
                $kode,
                $nama,
                $sks,
                $jenjangBadge,
                $semester,
                $aksi
            ];
        }

        return $this->_json(['data' => $data]);
    }

    /* ==========================
     * CRUD
     * ========================== */

    public function ajax_add()
    {
        $this->_validate(); // termasuk cek duplikat kode

        $payload = [
            'kode_matakuliah'  => trim($this->input->post('kode', TRUE)),
            'nama_matakuliah'  => trim($this->input->post('nama_matakuliah', TRUE)),
            'sks'              => (int) $this->input->post('sks', TRUE),
            'jenjang'          => $this->input->post('jenjang', TRUE),
            'semester'         => (int) $this->input->post('semester', TRUE),
        ];

        $this->Matakuliah_model->create('matakuliah', $payload);

        return $this->_json(['status' => TRUE]);
    }

    public function ajax_edit($id)
    {
        $row = $this->Matakuliah_model->get_by_id($id);
        if (!$row) {
            return $this->_json(['error' => 'NOT_FOUND']);
        }
        // kirimkan baris + csrf baru
        $out = (array) $row;
        return $this->_json($out);
    }

    public function ajax_update()
    {
        $this->_validate();

        $payload = [
            'kode_matakuliah'  => trim($this->input->post('kode', TRUE)),
            'nama_matakuliah'  => trim($this->input->post('nama_matakuliah', TRUE)),
            'sks'              => (int) $this->input->post('sks', TRUE),
            'jenjang'          => $this->input->post('jenjang', TRUE),
            'semester'         => (int) $this->input->post('semester', TRUE),
        ];

        $this->Matakuliah_model->update(
            ['id_matakuliah' => $this->input->post('id_matakuliah', TRUE)],
            $payload
        );

        return $this->_json(['status' => TRUE]);
    }

    public function delete($id)
    {
        // (opsional) batasi hanya petugas yang boleh hapus:
        // if (strtolower($this->session->userdata('role')) !== 'petugas') {
        //     return $this->_json(['status' => FALSE, 'error' => 'FORBIDDEN'], 403);
        // }

        $ok = $this->Matakuliah_model->delete($id) > 0;
        return $this->_json(['status' => (bool) $ok]);
    }

    /* ==========================
     * Validasi & helper
     * ========================== */

    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');

        $rules = [
            ['kode',            'Kode',            'required|trim|max_length[20]'],
            ['nama_matakuliah', 'Nama Matakuliah', 'required|trim|max_length[50]'],
            ['sks',             'SKS',             'required|numeric|greater_than_equal_to[1]|less_than_equal_to[10]'],
            ['jenjang',         'Jenjang',         'required|in_list[M1,M2]'],
            ['semester',        'Semester',        'required|integer|greater_than_equal_to[1]|less_than_equal_to[8]'],
        ];

        foreach ($rules as $r) {
            $this->form_validation->set_rules($r[0], $r[1], $r[2], [
                'required'  => "Kolom {field} wajib diisi.",
                'numeric'   => "{field} harus berupa angka.",
                'integer'   => "{field} harus berupa bilangan bulat.",
                'in_list'   => "Pilih opsi yang valid untuk {field}.",
                'max_length'=> "{field} terlalu panjang.",
                'greater_than_equal_to' => "{field} minimal {param}.",
                'less_than_equal_to'    => "{field} maksimal {param}.",
            ]);
        }

        if ($this->form_validation->run() === FALSE) {
            return $this->_json_validation_errors();
        }

        // Cek duplikat kode (exclude id saat edit)
        $kode = $this->input->post('kode', TRUE);
        $id   = $this->input->post('id_matakuliah', TRUE);
        if ($this->Matakuliah_model->cek_kode($kode, $id)) {
            return $this->_json([
                'status'       => FALSE,
                'inputerror'   => ['kode'],
                'error_string' => ['Kode sudah digunakan. Gunakan kode lain.'],
            ]);
        }
    }

    private function _json_validation_errors()
    {
        $errors = ['inputerror' => [], 'error_string' => [], 'status' => FALSE];
        foreach ($_POST as $key => $value) {
            if (form_error($key)) {
                $errors['inputerror'][]   = $key;
                $errors['error_string'][] = form_error($key);
            }
        }
        return $this->_json($errors);
    }

    /**
     * Mengirim JSON + selalu menyertakan CSRF baru.
     * @param array $payload
     * @param int $status_code
     */
    private function _json(array $payload, int $status_code = 200)
    {
        $payload['csrf'] = $this->security->get_csrf_hash();
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
}
