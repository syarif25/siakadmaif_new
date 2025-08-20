<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matakuliah extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Matakuliah_model');
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
        $isi['content'] = 'Matakuliah/Matakuliah';
        $isi['ajax']    = 'Matakuliah/Ajax';
        $isi['css']     = 'Matakuliah/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Matakuliah_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->kode_matakuliah);
            $row[] = htmlentities($datanya->nama_matakuliah);
            $row[] = htmlentities($datanya->sks);
            // // Format jenis kelamin dengan badge warna
            // $jk = htmlentities($datanya->jenjang);
            if ($datanya->jenjang == 'M1') {
                $row[] = '<span class="badge bg-primary">Marhalah Ula</span>';
            } else {
                $row[] = '<span class="badge bg-success text-white">Marhalah Tsani</span>';
            }
            // $row[] = $jk_badge;
            $row[] = htmlentities($datanya->semester);
            // Tombol aksi
            $row[] = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    onclick="edit_matakuliah(\'' . $datanya->id_matakuliah . '\')">
                    <i class="bx bx-edit"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" 
                    onclick="delete_matakuliah(\'' . $datanya->id_matakuliah . '\')">
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

        // Handle file upload jika ada
        $silabus = '';
        if (!empty($_FILES['silabus']['name'])) {
            $config['upload_path']   = './uploads/silabus/';
            $config['allowed_types'] = 'pdf|doc|docx';
            $config['max_size']      = 2048; // dalam KB
            $config['file_name']     = uniqid('silabus_');

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('silabus')) {
                $silabus = $this->upload->data('file_name');
            } else {
                echo json_encode([
                    'inputerror'   => ['silabus'],
                    'error_string' => [$this->upload->display_errors()],
                    'status'       => FALSE
                ]);
                return;
            }
        }

        $data = array(
            'kode_matakuliah'           => $this->input->post('kode', TRUE),
            'nama_matakuliah'=> $this->input->post('nama_matakuliah', TRUE),
            'sks'            => $this->input->post('sks', TRUE),
            'jenjang'        => $this->input->post('jenjang', TRUE),
            'semester'       => $this->input->post('semester', TRUE),
            // 'silabus'        => $silabus,
        );

        $this->Matakuliah_model->create('matakuliah', $data);
        echo json_encode(["status" => TRUE]);
    }

    public function ajax_edit($id)
    {
        $data = $this->Matakuliah_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
            'kode_matakuliah'           => $this->input->post('kode', TRUE),
            'nama_matakuliah'=> $this->input->post('nama_matakuliah', TRUE),
            'sks'            => $this->input->post('sks', TRUE),
            'jenjang'        => $this->input->post('jenjang', TRUE),
            'semester'       => $this->input->post('semester', TRUE),
            // tidak update silabus di sini, kecuali Anda ingin menambahkan fitur update file
        );

        $this->Matakuliah_model->update(['id_matakuliah' => $this->input->post('id_matakuliah')], $data);
        echo json_encode(["status" => TRUE]);
    }

    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
    
        $rules = [
            ['kode', 'Kode', 'required|trim'],
            ['nama_matakuliah', 'Nama Matakuliah', 'required|trim'],
            ['sks', 'SKS', 'required|numeric'],
            ['jenjang', 'Jenjang', 'required|in_list[M1,M2]'],
            ['semester', 'Semester', 'required|numeric'],
        ];
    
        foreach ($rules as $rule) {
            $this->form_validation->set_rules($rule[0], $rule[1], $rule[2], [
                'required'    => "Kolom {field} wajib diisi.",
                'numeric'     => "{field} harus berupa angka.",
                'in_list'     => "Pilih opsi yang valid untuk {field}.",
            ]);
        }
    
        // Jalankan form validation terlebih dahulu
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
    
        // Baru cek duplikat setelah validasi form berhasil
        $kode = $this->input->post('kode', TRUE);
        $id   = $this->input->post('id_matakuliah');
        $existing = $this->Matakuliah_model->cek_kode($kode, $id);
        if ($existing) {
            echo json_encode([
                'inputerror'   => ['kode'],
                'error_string' => ['Kode sudah digunakan. Gunakan kode lain.'],
                'status'       => FALSE
            ]);
            exit;
        }
    }
    
    
    public function delete($id)
    {
        $this->Matakuliah_model->delete($id);
        echo json_encode(["status" => TRUE]);
    }

}
