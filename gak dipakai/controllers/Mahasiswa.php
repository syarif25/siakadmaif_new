<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Mahasiswa extends MY_Controller
{
    // Atur role yang boleh mengakses modul ini
    protected $allowed_roles = ['petugas', 'dosen'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa_model');
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form', 'security', 'Tanggal_helper']);
    }

    /* ==========================
     * View
     * ========================== */
    public function index()
    {
        $isi['content'] = 'Mahasiswa/Mahasiswa';
        $isi['ajax']    = 'Mahasiswa/Ajax';
        $isi['css']     = 'Mahasiswa/Css';
        $this->load->view('Template', $isi);
    }

    public function detail($nis)
    {
        $data['mahasiswa'] = $this->Mahasiswa_model->get_by_id($nis);
        if (!$data['mahasiswa']) {
            show_404();
        }

        $data['content'] = 'Mahasiswa/Detail';
        $data['ajax']    = 'Mahasiswa/Ajax';
        $data['css']     = 'Mahasiswa/Css';
        $this->load->view('Template', $data);
    }

    /* ==========================
     * DataTables source (POST only)
     * ========================== */
    public function data_list()
    {
        $this->output->set_content_type('application/json');

        if (strtolower($this->input->method()) !== 'post') {
            return $this->_json([
                'data' => [],
                'info' => ['error' => 'METHOD_NOT_ALLOWED', 'method' => $this->input->method()]
            ]);
        }

        $list = $this->Mahasiswa_model->get_datatables();
        $no   = 1;
        $data = [];

        foreach ($list as $r) {
            $nis   = html_escape($r->nis);
            $nim   = html_escape($r->nim);
            $nama  = html_escape($r->nama_mahasiswa);

            // JK badge
            $jkRaw = strtolower((string)$r->jk);
            $jkBadge = ($jkRaw === 'laki-laki')
                ? '<span class="badge bg-primary">Laki-laki</span>'
                : '<span class="badge bg-pink text-white">Perempuan</span>';

            // Status badge
            $status = (string)$r->status;
            $badgeClass = ($status === 'Aktif') ? 'success' : (($status === 'Cuti') ? 'warning' : 'danger');

            // Tombol aksi tanpa inline handler; gunakan class + data-id
            $aksi = '
            <a href="' . site_url('mahasiswa/detail/' . rawurlencode($nis)) . '" class="btn btn-sm btn-outline-info">
                <i class="bx bx-search-alt"></i> Detail
            </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary btn-edit" data-id="' . $nis . '">
                    <i class="bx bx-pencil"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="' . $nis . '">
                    <i class="bx bx-trash"></i> Hapus
                </button>
            </div>';

            $data[] = [
                $no++,
                $nis,
                $nim,
                $nama,
                $jkBadge,
                '<span class="badge bg-' . $badgeClass . '">' . html_escape($status) . '</span>',
                $aksi
            ];
        }

        return $this->_json(['data' => $data]);
    }

    /* ==========================
     * Import Excel
     * ========================== */
    public function import_excel()
    {
        $config = [
            'upload_path'   => './uploads/',
            'allowed_types' => 'xls|xlsx',
            'max_size'      => 2048,
            'file_name'     => 'import_mahasiswa_' . time(),
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_excel')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('mahasiswa');
            return;
        }

        $file = $this->upload->data();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file['full_path']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $data_import = [];
        $i = 0;
        foreach ($sheetData as $row) {
            if ($i++ == 0) continue; // skip header

            $tanggal_excel = $row['E'];
            $tanggal_lahir = is_numeric($tanggal_excel)
                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal_excel)->format('Y-m-d')
                : date('Y-m-d', strtotime($tanggal_excel));

            $data_import[] = [
                'nis'               => trim((string)$row['A']),
                'nim'               => trim((string)$row['B']),
                'nama_mahasiswa'    => trim((string)$row['C']),
                'tempat_lahir'      => trim((string)$row['D']),
                'tanggal_lahir'     => $tanggal_lahir,
                'nomor_hp'          => trim((string)$row['F']),
                'jk'                => trim((string)$row['G']),
                'alamat'            => trim((string)$row['H']),
                'email'             => trim((string)$row['I']),
                'biaya_pendidikan'  => trim((string)$row['J']),
                'status'            => trim((string)$row['K']),
            ];
        }

        @unlink($file['full_path']); // hapus file upload
        $this->session->set_userdata('preview_data_mahasiswa', $data_import);
        redirect('mahasiswa/preview_import');
    }

    public function preview_import()
    {
        $data['data_mahasiswa'] = $this->session->userdata('preview_data_mahasiswa');
        $this->load->view('mahasiswa/preview_import', $data);
    }

    public function simpan_import()
    {
        $data = $this->session->userdata('preview_data_mahasiswa');

        if (empty($data)) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diimport atau session sudah habis.');
            redirect('mahasiswa');
            return;
        }

        $valid = [];
        $dupe  = [];

        foreach ($data as $row) {
            $exists = $this->Mahasiswa_model->exists($row['nis'], $row['nim']);
            if ($exists) $dupe[] = $row; else $valid[] = $row;
        }

        if (!empty($valid)) {
            $this->Mahasiswa_model->import_batch($valid);
            $msg = count($valid) . ' data berhasil diimport.';
            if (!empty($dupe)) $msg .= ' ' . count($dupe) . ' data dilewati karena duplikat.';
            $this->session->set_flashdata('success', $msg);
        } else {
            $this->session->set_flashdata('error', 'Semua data duplikat, tidak ada yang disimpan.');
        }

        $this->session->unset_userdata('preview_data_mahasiswa');
        redirect('mahasiswa');
    }

    /* ==========================
     * CRUD (JSON + CSRF refresh)
     * ========================== */
    public function ajax_add()
    {
        $this->_validate();

        $payload = [
            'nis'               => $this->input->post('nis', TRUE),
            'nim'               => $this->input->post('nim', TRUE),
            'nama_mahasiswa'    => $this->input->post('nama_lengkap', TRUE),
            'tempat_lahir'      => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'     => $this->input->post('tanggal_lahir', TRUE),
            'nomor_hp'          => $this->input->post('no_hp', TRUE),
            'jk'                => $this->input->post('jenis_kelamin', TRUE),
            'alamat'            => $this->input->post('alamat', TRUE),
            'email'             => $this->input->post('email', TRUE),
            'biaya_pendidikan'  => $this->input->post('biaya_pendidikan', TRUE),
            'status'            => $this->input->post('status', TRUE),
            'password'          => password_hash((string)$this->input->post('password', TRUE), PASSWORD_DEFAULT),
        ];

        $this->Mahasiswa_model->create('mahasiswa', $payload);
        return $this->_json(['status' => TRUE]);
    }

    public function ajax_edit($id)
    {
        $row = $this->Mahasiswa_model->get_by_id($id);
        if (!$row) {
            return $this->_json(['error' => 'NOT_FOUND']);
        }
        // kirimkan baris + csrf baru
        return $this->_json((array)$row);
    }

    public function ajax_update()
    {
        $this->_validate();

        $payload = [
            'nim'               => $this->input->post('nim', TRUE),
            'nama_mahasiswa'    => $this->input->post('nama_lengkap', TRUE),
            'tempat_lahir'      => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'     => $this->input->post('tanggal_lahir', TRUE),
            'nomor_hp'          => $this->input->post('no_hp', TRUE),
            'jk'                => $this->input->post('jenis_kelamin', TRUE),
            'alamat'            => $this->input->post('alamat', TRUE),
            'email'             => $this->input->post('email', TRUE),
            'biaya_pendidikan'  => $this->input->post('biaya_pendidikan', TRUE),
            'status'            => $this->input->post('status', TRUE),
        ];

        // Update password hanya jika diisi
        $password_input = $this->input->post('password', TRUE);
        if (!empty($password_input)) {
            $payload['password'] = password_hash((string)$password_input, PASSWORD_DEFAULT);
        }

        $this->Mahasiswa_model->update(['nis' => $this->input->post('nis', TRUE)], $payload);
        return $this->_json(['status' => TRUE]);
    }

    public function delete($id)
    {
        // (opsional) batasi hanya petugas yang boleh hapus:
        // if (strtolower($this->session->userdata('role')) !== 'petugas') {
        //     return $this->_json(['status' => FALSE, 'error' => 'FORBIDDEN'], 403);
        // }

        $ok = $this->Mahasiswa_model->delete($id) > 0;
        return $this->_json(['status' => (bool) $ok]);
    }

    /* ==========================
     * Validasi & helper
     * ========================== */
    private function _validate()
    {
        $this->form_validation->set_error_delimiters('', '');

        $is_update = $this->input->post('method') === 'update';
        $nis       = trim((string)$this->input->post('nis', TRUE));
        $nim       = trim((string)$this->input->post('nim', TRUE));

        if ($is_update) {
            // NIS tetap wajib (primary key)
            $this->form_validation->set_rules('nis', 'NIS', 'required|trim');

            // NIM harus unik terhadap baris lain
            $exists_nim = $this->Mahasiswa_model->is_duplicate('nim', $nim, $nis);
            if ($exists_nim) {
                $this->form_validation->set_rules('nim', 'NIM', 'callback_dummy_rule');
                $this->form_validation->set_message('dummy_rule', 'NIM sudah terdaftar.');
            } else {
                $this->form_validation->set_rules('nim', 'NIM', 'required|trim');
            }
        } else {
            // Saat tambah, NIS & NIM unik
            $this->form_validation->set_rules('nis', 'NIS', 'required|trim|is_unique[mahasiswa.nis]', [
                'is_unique' => '{field} sudah terdaftar.'
            ]);
            $this->form_validation->set_rules('nim', 'NIM', 'required|trim|is_unique[mahasiswa.nim]', [
                'is_unique' => '{field} sudah terdaftar.'
            ]);
        }

        // Validasi umum
        $fields = [
            ['nama_lengkap', 'Nama Lengkap', 'required|trim|min_length[3]'],
            ['tempat_lahir', 'Tempat Lahir', 'required|trim'],
            ['tanggal_lahir', 'Tanggal Lahir', 'required|callback_valid_date'],
            ['no_hp', 'Nomor HP', 'required|trim|numeric|min_length[10]|max_length[15]'],
            ['jenis_kelamin', 'Jenis Kelamin', 'required|in_list[Laki-laki,Perempuan]'],
            ['alamat', 'Alamat', 'required|trim'],
            ['email', 'Email', 'required|trim|valid_email'],
            ['biaya_pendidikan', 'Biaya Pendidikan', 'required|trim'],
            ['status', 'Status', 'required|in_list[Aktif,Cuti,Non Aktif]'],
        ];

        foreach ($fields as $f) {
            $this->form_validation->set_rules($f[0], $f[1], $f[2], [
                'required'    => "Kolom {field} wajib diisi.",
                'valid_email' => "Format {field} tidak valid.",
                'numeric'     => "{field} harus berupa angka.",
                'in_list'     => "Pilih opsi yang valid untuk {field}.",
            ]);
        }

        if ($this->form_validation->run() === FALSE) {
            return $this->_json_validation_errors();
        }
    }

    // Digunakan saat nim duplikat di update
    public function dummy_rule() { return FALSE; }

    // Validasi tanggal
    public function valid_date($date)
    {
        if (DateTime::createFromFormat('Y-m-d', $date) !== FALSE) {
            return TRUE;
        }
        $this->form_validation->set_message('valid_date', '{field} tidak valid.');
        return FALSE;
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
