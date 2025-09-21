<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
            'password'          => password_hash((string)$this->input->post('password', TRUE), PASSWORD_DEFAULT),
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
        $nomor_hp   = $this->input->post('nomor_hp'); 

        // Cek apakah nomor HP sudah dipakai dosen lain
        $this->db->where('nomor_hp', $nomor_hp);
        if (!empty($id_dosen)) {
            // Saat update, abaikan nomor dosen sendiri
            $this->db->where('id_dosen !=', $id_dosen);
        }
        $exists_hp = $this->db->get('dosen')->num_rows() > 0;

        $rules = [
            ['nik', 'NIK', 'required|trim'],
            ['nama_dosen', 'Nama Lengkap', 'required|trim|min_length[3]'],
            ['tempat_lahir', 'Tempat Lahir', 'required|trim'],
            ['tanggal_lahir', 'Tanggal Lahir', 'required|callback_valid_date'],
            // nomor_hp dicek unik secara manual
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
