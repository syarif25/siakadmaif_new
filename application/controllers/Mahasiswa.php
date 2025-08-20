<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php'; // ⬅️ WAJIB ditambahkan di atas semua

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Mahasiswa extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Mahasiswa_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
        $this->load->helper('tanggal_helper');
        // Cek apakah sudah login
        if (!$this->session->userdata('logged_in')) {
            // Kalau belum login, redirect ke halaman login
            redirect('login');
        }
    }

    public function index()
	{
        $isi['content'] = 'Mahasiswa/Mahasiswa';
        $isi['ajax']    = 'Mahasiswa/Ajax';
        $isi['css']     = 'Mahasiswa/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Mahasiswa_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->nis);
            $row[] = htmlentities($datanya->nim);
            $row[] = htmlentities($datanya->nama_mahasiswa);
            // Format jenis kelamin dengan badge warna
            $jk = htmlentities($datanya->jk);
            if (strtolower($jk) == 'laki-laki') {
                $jk_badge = '<span class="badge bg-primary">Laki-laki</span>';
            } else {
                $jk_badge = '<span class="badge bg-pink text-white">Perempuan</span>';
            }
            $row[] = $jk_badge;

            // Format status dengan badge
            $status = htmlentities($datanya->status);
            $badge_class = 'secondary';
            if ($status == 'Aktif') $badge_class = 'success';
            elseif ($status == 'Cuti') $badge_class = 'warning';
            elseif ($status == 'Non Aktif') $badge_class = 'danger';

            $row[] = '<span class="badge bg-' . $badge_class . '">' . $status . '</span>';

            // Tombol aksi
            $row[] = '
            <a href="' . site_url('mahasiswa/detail/' . $datanya->nis) . '" class="btn btn-sm btn-outline-info">
                <i class="bx bx-search-alt"></i> Detail
             </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    onclick="edit_mahasiswa(\'' . $datanya->nis . '\')">
                    <i class="bx bx-pencil"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" 
                    onclick="delete_mahasiswa(\'' . $datanya->nis . '\')">
                    <i class="bx bx-trash"></i> Hapus
                </button>
            </div>';

            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function detail($nis)
    {
        $data['mahasiswa'] = $this->Mahasiswa_model->get_by_id($nis);

        if (!$data['mahasiswa']) {
            show_404(); // Jika tidak ditemukan
        }

        $data['content'] = 'Mahasiswa/Detail';
        $data['ajax']    = 'Mahasiswa/Ajax';
        $data['css']     = 'Mahasiswa/Css';
        $this->load->view('Template', $data);
    }


    public function import_excel()
    {
        $this->load->library('upload');

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 2048;
        $config['file_name']     = 'import_mahasiswa_' . time();

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_excel')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('mahasiswa');
        } else {
            $file = $this->upload->data();
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file['full_path']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $data_import = [];
            $i = 0;
            foreach ($sheetData as $row) {
                if ($i++ == 0) continue; // Skip header
                $tanggal_excel = $row['E'];
                $tanggal_lahir = is_numeric($tanggal_excel) 
                    ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal_excel)->format('Y-m-d')
                    : date('Y-m-d', strtotime($tanggal_excel));
            
                $data_import[] = [
                    'nis'               => $row['A'],
                    'nim'               => $row['B'],
                    'nama_mahasiswa'    => $row['C'],
                    'tempat_lahir'      => $row['D'],
                    'tanggal_lahir'     => $tanggal_lahir,
                    'nomor_hp'          => $row['F'],
                    'jk'                => $row['G'],
                    'alamat'            => $row['H'],
                    'email'             => $row['I'],
                    'biaya_pendidikan'  => $row['J'],
                    'status'            => $row['K'],
                ];
            }

            unlink($file['full_path']); // Hapus file fisik
            $this->session->set_userdata('preview_data_mahasiswa', $data_import); // simpan di session

            redirect('mahasiswa/preview_import');
        }
    }

    public function preview_import()
    {
        $data['data_mahasiswa'] = $this->session->userdata('preview_data_mahasiswa');
        $this->load->view('mahasiswa/preview_import', $data);
    }

    public function simpan_import()
    {
        $data = $this->session->userdata('preview_data_mahasiswa');
        if (!empty($data)) {
            $this->Mahasiswa_model->import_batch($data);
            $this->session->unset_userdata('preview_data_mahasiswa');
            $this->session->set_flashdata('success', 'Data mahasiswa berhasil diimport.');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diimport.');
        }
        redirect('mahasiswa');
    }

    public function ajax_add()
    {
        $this->_validate();

        $data = array(
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
        );

        $this->Mahasiswa_model->create('mahasiswa', $data);
        echo json_encode(["status" => TRUE]);
    }

    public function ajax_edit($id)
    {
        $data = $this->Mahasiswa_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
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
            'password'          => $this->input->post('password', TRUE),
        );

        // Update password hanya jika diisi, dan simpan dalam bentuk hash
        $password_input = $this->input->post('password', TRUE);
        if (!empty($password_input)) {
            $data['password'] = password_hash((string)$password_input, PASSWORD_DEFAULT);
        }

        $this->Mahasiswa_model->update(['nis' => $this->input->post('nis')], $data);
        echo json_encode(["status" => TRUE]);
    }

    public function delete($id)
    {
        $this->Mahasiswa_model->delete($id);
        echo json_encode(["status" => TRUE]);
    }

    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $is_update = $this->input->post('method') === 'update';
        $nis       = $this->input->post('nis');
        $nim       = $this->input->post('nim');

        if ($is_update) {
            // Validasi NIS (hanya required karena sudah primary key)
            $this->form_validation->set_rules('nis', 'NIS', 'required|trim', [
                'required' => "Kolom {field} wajib diisi.",
            ]);

            // Cek apakah ada NIM lain yang sama
            $this->db->where('nim', $nim);
            $this->db->where('nis !=', $nis); // Hindari diri sendiri
            $exists_nim = $this->db->get('mahasiswa')->num_rows() > 0;

            if ($exists_nim) {
                $this->form_validation->set_rules('nim', 'NIM', 'callback_dummy_rule');
                $this->form_validation->set_message('dummy_rule', 'NIM sudah terdaftar.');
            } else {
                $this->form_validation->set_rules('nim', 'NIM', 'required|trim', [
                    'required' => "Kolom {field} wajib diisi.",
                ]);
            }
        } else {
            // Validasi tambah (nis dan nim harus unik)
            $this->form_validation->set_rules('nis', 'NIS', 'required|trim|is_unique[mahasiswa.nis]', [
                'required'   => "Kolom {field} wajib diisi.",
                'is_unique'  => "{field} sudah terdaftar.",
            ]);
            $this->form_validation->set_rules('nim', 'NIM', 'required|trim|is_unique[mahasiswa.nim]', [
                'required'   => "Kolom {field} wajib diisi.",
                'is_unique'  => "{field} sudah terdaftar.",
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

    // Digunakan saat nim duplikat di update
    public function dummy_rule() {
        return FALSE; // agar form validation memicu pesan dari set_message di atas
    }

    

    public function check_nis($nis, $id_tahun = null)
    {
        $this->load->model('Mahasiswa_model');
        if ($this->Mahasiswa_model->is_duplicate('nis', $nis, $id_tahun)) {
            $this->form_validation->set_message('check_nis', 'NIS sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function check_nim($nim, $id_tahun = null)
    {
        $this->load->model('Mahasiswa_model');
        if ($this->Mahasiswa_model->is_duplicate('nim', $nim, $id_tahun)) {
            $this->form_validation->set_message('check_nim', 'NIM sudah digunakan.');
            return FALSE;
        }
        return TRUE;
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




}
