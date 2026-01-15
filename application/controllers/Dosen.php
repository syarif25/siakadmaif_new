<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH . '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class Dosen extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Dosen_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
        // Cek apakah sudah login
        if (!$this->session->userdata('logged_in')) {
            // Kalau belum login, redirect ke halaman login
            redirect('login');
        }
        
        // Cek apakah user adalah Petugas
        if ($this->session->userdata('role') != 'Petugas') {
            // Kalau bukan Petugas, redirect ke dashboard sesuai role
            if ($this->session->userdata('role') == 'Mahasiswa') {
                redirect('Dashboard_mahasiswa');
            } elseif ($this->session->userdata('role') == 'Dosen') {
                redirect('Dashboard_dosen');
            } else {
                redirect('login');
            }
        }
    }

    public function index()
	{
        $isi['content'] = 'Dosen/Dosen';
        $isi['ajax']    = 'Dosen/Ajax';
        $isi['css']     = 'Dosen/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Dosen_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->nik); // NIK kolom baru
            $row[] = htmlentities($datanya->nama_dosen);
            
            // Format jenis kelamin dengan badge warna
            $jk = htmlentities($datanya->jk);
            if (strtolower($jk) == 'laki-laki') {
                $jk_badge = '<span class="badge bg-primary">Laki-laki</span>';
            } else {
                $jk_badge = '<span class="badge bg-pink text-white">Perempuan</span>';
            }
            
            $row[] = $jk_badge;
            $row[] = htmlentities($datanya->bidang_keahlian);
            $row[] = htmlentities($datanya->nomor_hp);
            // Tombol aksi
            $row[] = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    onclick="edit_dosen(\'' . $datanya->id_dosen . '\')">
                    <i class="bx bx-edit"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" 
                    onclick="delete_dosen(\'' . $datanya->id_dosen . '\')">
                    <i class="bx bx-trash"></i> Hapus
                </button>
            </div>';

            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }


    public function ajax_add()
    {
        $this->_validate();

        // Auto password = "123456" untuk dosen baru
        $default_password = "123456";
        
        $data = array(
            'id_dosen'           => "",
            'nik'               => $this->input->post('nik', TRUE),
            'nama_dosen'         => $this->input->post('nama_dosen', TRUE),
            'tempat_lahir'       => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'      => $this->input->post('tanggal_lahir', TRUE),
            'nomor_hp'           => $this->input->post('nomor_hp', TRUE),
            'jk'                 => $this->input->post('jenis_kelamin', TRUE),
            'alamat'             => $this->input->post('alamat', TRUE),
            'email'              => $this->input->post('email', TRUE),
            'pendidikan_terakhir' => $this->input->post('pendidikan_terakhir', TRUE),
            'gelar_depan'           => $this->input->post('gelar_depan', TRUE),
            'gelar_belakang'        => $this->input->post('gelar_belakang', TRUE),
            'bidang_keahlian'           => $this->input->post('bidang_keahlian', TRUE),
            'password'          => password_hash($default_password, PASSWORD_DEFAULT), // Auto password
        );

        $this->Dosen_model->create('dosen', $data);
        echo json_encode(["status" => TRUE]);
    }

    public function ajax_edit($id)
    {
        $data = $this->Dosen_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
            'nik'                   => $this->input->post('nik', TRUE),
            'nama_dosen'            => $this->input->post('nama_dosen', TRUE),
            'tempat_lahir'          => $this->input->post('tempat_lahir', TRUE),
            'tanggal_lahir'         => $this->input->post('tanggal_lahir', TRUE),
            'nomor_hp'              => $this->input->post('nomor_hp', TRUE),
            'jk'                    => $this->input->post('jenis_kelamin', TRUE),
            'alamat'                => $this->input->post('alamat', TRUE),
            'email'                 => $this->input->post('email', TRUE),
            'pendidikan_terakhir'   => $this->input->post('pendidikan_terakhir', TRUE),
            'gelar_depan'           => $this->input->post('gelar_depan', TRUE),
            'gelar_belakang'        => $this->input->post('gelar_belakang', TRUE),
            'bidang_keahlian'       => $this->input->post('bidang_keahlian', TRUE),
        );

        // Update password hanya jika diisi, dan simpan dalam bentuk hash
        $password_input = $this->input->post('password', TRUE);
        if (!empty($password_input)) {
            $data['password'] = password_hash((string)$password_input, PASSWORD_DEFAULT);
        }

        $this->Dosen_model->update(['id_dosen' => $this->input->post('id_dosen')], $data);
        echo json_encode(["status" => TRUE]);
    }

    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $id_dosen   = $this->input->post('id_dosen'); // id dosen dari form
        $nik        = $this->input->post('nik');
        $nomor_hp   = $this->input->post('nomor_hp'); 

        // Cek apakah NIK sudah dipakai dosen lain
        $this->db->where('nik', $nik);
        if (!empty($id_dosen)) {
            // Saat update, abaikan NIK dosen sendiri
            $this->db->where('id_dosen !=', $id_dosen);
        }
        $exists_nik = $this->db->get('dosen')->num_rows() > 0;

        // Cek apakah nomor HP sudah dipakai dosen lain
        $this->db->where('nomor_hp', $nomor_hp);
        if (!empty($id_dosen)) {
            // Saat update, abaikan nomor dosen sendiri
            $this->db->where('id_dosen !=', $id_dosen);
        }
        $exists_hp = $this->db->get('dosen')->num_rows() > 0;

        $rules = [
            // NIK dan nomor_hp dicek unik secara manual
            ['nama_dosen', 'Nama Lengkap', 'required|trim|min_length[3]'],
            ['tempat_lahir', 'Tempat Lahir', 'required|trim'],
            ['tanggal_lahir', 'Tanggal Lahir', 'required|callback_valid_date'],
            ['jenis_kelamin', 'Jenis Kelamin', 'required|in_list[Laki-laki,Perempuan]'],
            ['alamat', 'Alamat', 'required|trim'],
            ['email', 'Email', 'required|trim|valid_email'],
            ['pendidikan_terakhir', 'Jenjang Pendidikan', 'required|trim'],
            ['gelar_depan', 'Gelar Depan', 'trim'],
            ['gelar_belakang', 'Gelar Belakang', 'trim'],
            ['bidang_keahlian', 'Bidang Keahlian', 'trim'],
        ];

        foreach ($rules as $rule) {
            $this->form_validation->set_rules($rule[0], $rule[1], $rule[2], [
                'required'    => "Kolom {field} wajib diisi.",
                'valid_email' => "Format {field} tidak valid.",
                'numeric'     => "{field} harus berupa angka.",
                'min_length'  => "{field} minimal {param} karakter.",
                'max_length'  => "{field} maksimal {param} karakter.",
                'in_list'     => "Pilih opsi yang valid untuk {field}.",
            ]);
        }

        // Tambahkan aturan khusus untuk NIK unik
        if ($exists_nik) {
            $this->form_validation->set_rules('nik', 'NIK', 'callback_dummy_rule_nik');
            $this->form_validation->set_message('dummy_rule_nik', 'NIK sudah terdaftar.');
        } else {
            $this->form_validation->set_rules('nik', 'NIK', 'required|trim', [
                'required' => "Kolom {field} wajib diisi.",
            ]);
        }

        // Tambahkan aturan khusus untuk nomor HP unik
        if ($exists_hp) {
            $this->form_validation->set_rules('nomor_hp', 'Nomor Handphone', 'callback_dummy_rule');
            $this->form_validation->set_message('dummy_rule', 'Nomor Handphone sudah terdaftar.');
        } else {
            $this->form_validation->set_rules('nomor_hp', 'Nomor Handphone', 'required|trim|numeric|min_length[10]|max_length[15]', [
                'required'   => "Kolom {field} wajib diisi.",
                'numeric'    => "{field} harus berupa angka.",
                'min_length' => "{field} minimal {param} karakter.",
                'max_length' => "{field} maksimal {param} karakter.",
            ]);
        }

        // Validasi password hanya saat update (jika diisi)
        $password_input = $this->input->post('password');
        if (!empty($id_dosen) && !empty($password_input)) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]', [
                'min_length' => '{field} minimal {param} karakter.'
            ]);
        }

        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'inputerror'   => [],
                'error_string' => [],
                'status'       => FALSE
            ];

            foreach ($_POST as $key => $val) {
                if (form_error($key)) {
                    $errors['inputerror'][]   = $key;
                    $errors['error_string'][] = form_error($key);
                }
            }

            echo json_encode($errors);
            exit;
        }
    }

    public function delete($id)
    {
        $this->Dosen_model->delete($id);
        echo json_encode(["status" => TRUE]);
    }

    // Digunakan saat nim duplikat di update
    public function dummy_rule() {
        return FALSE; // agar form validation memicu pesan dari set_message di atas
    }

    // Digunakan saat NIK duplikat
    public function dummy_rule_nik() {
        return FALSE; // agar form validation memicu pesan dari set_message di atas
    }

    
    // Optional: validasi tanggal
    public function valid_date($date)
    {
        if (DateTime::createFromFormat('Y-m-d', $date) !== FALSE) {
            return TRUE;
        }
        $this->form_validation->set_message('valid_date', '{field} tidak valid.');
        return FALSE;
    }

    public function import_excel_ajax()
    {
        // Hanya izinkan AJAX (opsional tapi bagus)
        if (!$this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['ok' => false, 'error' => 'Akses tidak valid.']));
            return;
        }

        $this->load->library('upload');

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 2048; // KB
        $config['file_name']     = 'import_dosen_' . time();

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_excel')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['ok' => false, 'error' => strip_tags($this->upload->display_errors())]));
            return;
        }

        $file = $this->upload->data();

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file['full_path']);
            $spreadsheet = $reader->load($file['full_path']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            @unlink($file['full_path']);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['ok' => false, 'error' => 'File tidak dapat dibaca: ' . $e->getMessage()]));
            return;
        }

        $data_import = [];
        $i = 0;

        foreach ($sheetData as $row) {
            if ($i++ == 0) continue; // header
            $nik = trim((string)($row['A'] ?? ''));
            if ($nik === '') continue; // Skip jika NIK kosong
            
            $nomor_hp = trim((string)($row['G'] ?? ''));
            if ($nomor_hp === '') continue; // Skip jika Nomor HP kosong (karena login pakai HP)

            $nama_dosen          = trim((string)($row['B'] ?? ''));
            $gelar_depan         = trim((string)($row['C'] ?? ''));
            $gelar_belakang      = trim((string)($row['D'] ?? ''));
            $tempat_lahir        = trim((string)($row['E'] ?? ''));
            $tgl_excel           = $row['F'] ?? '';
            $nomor_hp            = trim((string)($row['G'] ?? ''));
            $jk_raw              = trim((string)($row['H'] ?? ''));
            $alamat              = trim((string)($row['I'] ?? ''));
            $email               = trim((string)($row['J'] ?? ''));
            $pendidikan_terakhir = trim((string)($row['K'] ?? ''));
            $bidang_keahlian     = trim((string)($row['L'] ?? ''));
            $status_raw          = trim((string)($row['M'] ?? ''));

            // Tanggal
            if (is_numeric($tgl_excel)) {
                $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_excel)->format('Y-m-d');
            } elseif (!empty($tgl_excel) && strtotime($tgl_excel) !== false) {
                $tanggal_lahir = date('Y-m-d', strtotime($tgl_excel));
            } else {
                $tanggal_lahir = null;
            }

            // JK
            $jk_lower = strtolower($jk_raw);
            if (in_array($jk_lower, ['l','laki-laki','male','m'], true)) {
                $jk = 'Laki-laki';
            } elseif (in_array($jk_lower, ['p','perempuan','female','f'], true)) {
                $jk = 'Perempuan';
            } else {
                $jk = $jk_raw ?: '';
            }

            $status_kepegawaian = $status_raw;

            $data_import[] = [
                'nik'                 => $nik,
                'nama_dosen'          => $nama_dosen,
                'gelar_depan'         => $gelar_depan,
                'gelar_belakang'      => $gelar_belakang,
                'tempat_lahir'        => $tempat_lahir,
                'tanggal_lahir'       => $tanggal_lahir,
                'nomor_hp'            => $nomor_hp,
                'jk'                  => $jk,
                'alamat'              => $alamat,
                'email'               => $email,
                'pendidikan_terakhir' => $pendidikan_terakhir,
                'bidang_keahlian'     => $bidang_keahlian,
                'status_kepegawaian'  => $status_kepegawaian,
                'password'            => password_hash('123456', PASSWORD_DEFAULT), // Auto password
            ];
        }

        @unlink($file['full_path']);

        if (empty($data_import)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['ok' => false, 'error' => 'Tidak ada baris data yang valid pada file Excel.']));
            return;
        }

        // simpan ke session untuk preview
        $this->session->set_userdata('preview_data_dosen', $data_import);

        // balas JSON berisi URL tujuan
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'ok' => true,
                'redirect' => site_url('dosen/preview_import')
            ]));
    }



    public function import_excel()
    {
        $this->load->library('upload');
    
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 2048; // KB
        $config['file_name']     = 'import_dosen_' . time();
    
        $this->upload->initialize($config);
    
        if (!$this->upload->do_upload('file_excel')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('dosen');
            return;
        }
    
        $file = $this->upload->data();
    
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file['full_path']);
            $spreadsheet = $reader->load($file['full_path']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            @unlink($file['full_path']);
            $this->session->set_flashdata('error', 'File tidak dapat dibaca: ' . $e->getMessage());
            redirect('dosen');
            return;
        }
    
        $data_import = [];
        $i = 0;
    
        foreach ($sheetData as $row) {
            if ($i++ == 0) continue; // header
    
            // Skip kalau NIK kosong
            $nik = trim((string)($row['A'] ?? ''));
            if ($nik === '') continue;
            
            $nomor_hp = trim((string)($row['G'] ?? ''));
            if ($nomor_hp === '') continue; // Skip jika Nomor HP kosong (karena login pakai HP)
    
            $nama_dosen          = trim((string)($row['B'] ?? ''));
            $gelar_depan         = trim((string)($row['C'] ?? ''));
            $gelar_belakang      = trim((string)($row['D'] ?? ''));
            $tempat_lahir        = trim((string)($row['E'] ?? ''));
            $tgl_excel           = $row['F'] ?? '';
            $nomor_hp            = trim((string)($row['G'] ?? ''));
            $jk_raw              = trim((string)($row['H'] ?? ''));
            $alamat              = trim((string)($row['I'] ?? ''));
            $email               = trim((string)($row['J'] ?? ''));
            $pendidikan_terakhir = trim((string)($row['K'] ?? ''));
            $bidang_keahlian     = trim((string)($row['L'] ?? ''));
            $status_raw          = trim((string)($row['M'] ?? ''));
    
            // Tanggal
            if (is_numeric($tgl_excel)) {
                $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_excel)->format('Y-m-d');
            } elseif (!empty($tgl_excel) && strtotime($tgl_excel) !== false) {
                $tanggal_lahir = date('Y-m-d', strtotime($tgl_excel));
            } else {
                $tanggal_lahir = null; // atau ''
            }
    
            // JK
            $jk_lower = strtolower($jk_raw);
            if (in_array($jk_lower, ['l','laki-laki','male','m'], true)) {
                $jk = 'Laki-laki';
            } elseif (in_array($jk_lower, ['p','perempuan','female','f'], true)) {
                $jk = 'Perempuan';
            } else {
                $jk = $jk_raw ?: '';
            }
    
            // Status (opsional: mapping eksplisit)
            $status_kepegawaian = $status_raw; // atau mapping seperti catatan #6
    
            $data_import[] = [
                'nik'                 => $nik,
                'nama_dosen'          => $nama_dosen,
                'gelar_depan'         => $gelar_depan,
                'gelar_belakang'      => $gelar_belakang,
                'tempat_lahir'        => $tempat_lahir,
                'tanggal_lahir'       => $tanggal_lahir,
                'nomor_hp'            => $nomor_hp,
                'jk'                  => $jk,
                'alamat'              => $alamat,
                'email'               => $email,
                'pendidikan_terakhir' => $pendidikan_terakhir,
                'bidang_keahlian'     => $bidang_keahlian,
                'status_kepegawaian'  => $status_kepegawaian,
                'password'            => password_hash('123456', PASSWORD_DEFAULT), // Auto password
            ];
        }
    
        @unlink($file['full_path']);
    
        if (empty($data_import)) {
            $this->session->set_flashdata('error', 'Tidak ada baris data yang valid pada file Excel.');
            redirect('dosen');
            return;
        }
    
        $this->session->set_userdata('preview_data_dosen', $data_import);
        redirect('dosen/preview_import'); // lowercase
    }
    
    public function preview_import()
    {
        $data['data_dosen'] = $this->session->userdata('preview_data_dosen');
        $this->load->view('dosen/preview_import', $data);
    }


    public function simpan_import()
    {
        $data = $this->session->userdata('preview_data_dosen');
        
        if (empty($data)) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diimport atau session sudah habis.');
            redirect('dosen');
        }

        $valid_data = [];
        $duplicate_nik = [];
        $duplicate_hp = [];
        $empty_hp = [];

        foreach ($data as $row) {
            // Skip jika nomor HP kosong (karena login pakai HP)
            if (empty($row['nomor_hp'])) {
                $empty_hp[] = $row;
                continue;
            }

            // Cek apakah NIK sudah ada di database
            $exists_nik = $this->db->where('nik', $row['nik'])
                            ->get('dosen')
                            ->num_rows() > 0;

            // Cek apakah Nomor HP sudah ada di database
            $exists_hp = $this->db->where('nomor_hp', $row['nomor_hp'])
                            ->get('dosen')
                            ->num_rows() > 0;

            if ($exists_nik) {
                $duplicate_nik[] = $row;
            } elseif ($exists_hp) {
                $duplicate_hp[] = $row;
            } else {
                $valid_data[] = $row;
            }
        }

        // Jika ada data valid, simpan ke DB
        if (!empty($valid_data)) {
            $this->Dosen_model->import_batch($valid_data);
            $msg = count($valid_data) . ' data berhasil diimport.';
            
            // Tambahkan info data yang dilewati
            $skipped_info = [];
            if (!empty($duplicate_nik)) {
                $skipped_info[] = count($duplicate_nik) . ' data duplikat NIK';
            }
            if (!empty($duplicate_hp)) {
                $skipped_info[] = count($duplicate_hp) . ' data duplikat Nomor HP';
            }
            if (!empty($empty_hp)) {
                $skipped_info[] = count($empty_hp) . ' data tanpa Nomor HP';
            }
            
            if (!empty($skipped_info)) {
                $msg .= ' Dilewati: ' . implode(', ', $skipped_info) . '.';
            }
            
            $this->session->set_flashdata('success', $msg);
        } else {
            // Buat pesan error detail
            $error_msg = 'Tidak ada data yang dapat diimport. ';
            $reasons = [];
            if (!empty($duplicate_nik)) {
                $reasons[] = count($duplicate_nik) . ' duplikat NIK';
            }
            if (!empty($duplicate_hp)) {
                $reasons[] = count($duplicate_hp) . ' duplikat Nomor HP';
            }
            if (!empty($empty_hp)) {
                $reasons[] = count($empty_hp) . ' tanpa Nomor HP';
            }
            if (!empty($reasons)) {
                $error_msg .= 'Alasan: ' . implode(', ', $reasons) . '.';
            }
            
            $this->session->set_flashdata('error', $error_msg);
        }

        // Hapus session preview
        $this->session->unset_userdata('preview_data_dosen');
        redirect('dosen');
    }




}
