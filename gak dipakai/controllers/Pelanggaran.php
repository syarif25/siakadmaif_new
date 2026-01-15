<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggaran extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pelanggaran_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form','Tanggal_helper'));
        // Cek apakah sudah login
        if (!$this->session->userdata('logged_in')) {
            // Kalau belum login, redirect ke halaman login
            redirect('login');
        }
    }

    public function index()
	{
        $isi['content'] = 'Pelanggaran/Pelanggaran';
        $isi['ajax']    = 'Pelanggaran/Ajax';
        $isi['css']     = 'Pelanggaran/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Pelanggaran_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->nis);
            $row[] = htmlentities($datanya->nama_mahasiswa);
            $row[] = htmlentities($datanya->jenjang);
            $row[] = htmlentities($datanya->semester);
            $row[] = htmlentities($datanya->jenis_pelanggaran);
            $row[] = htmlentities($datanya->sanksi);
            $row[] = format_tanggal_indonesia($datanya->tanggal_pelanggaran);
            
            // Tombol aksi
            $row[] = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    onclick="edit_pelanggaran(\'' . $datanya->id_pelanggaran . '\')">
                    <i class="bx bx-edit"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" 
                    onclick="delete_pelanggaran(\'' . $datanya->id_pelanggaran . '\')">
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
            'nis'           => $this->input->post('nis', TRUE),
            'jenjang'       => $this->input->post('jenjang', TRUE),
            'semester'             => $this->input->post('semester', TRUE),
            'jenis_pelanggaran'    => $this->input->post('jenis_pelanggaran', TRUE),
            'sanksi'               => $this->input->post('sanksi', TRUE),
            'tanggal_pelanggaran'  => $this->input->post('tanggal_pelanggaran', TRUE),
        );

        $this->Pelanggaran_model->create('pelanggaran', $data);
        echo json_encode(["status" => TRUE]);
    }

    public function ajax_edit($id)
    {
        $data = $this->Pelanggaran_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();
        $data = array(
            'nis'           => $this->input->post('nis', TRUE),
            'jenjang'       => $this->input->post('jenjang', TRUE),
            'semester'             => $this->input->post('semester', TRUE),
            'jenis_pelanggaran'    => $this->input->post('jenis_pelanggaran', TRUE),
            'sanksi'               => $this->input->post('sanksi', TRUE),
            'tanggal_pelanggaran'  => $this->input->post('tanggal_pelanggaran', TRUE),
            
        );

        $this->Pelanggaran_model->update(['id_pelanggaran' => $this->input->post('id_pelanggaran')], $data);
        echo json_encode(["status" => TRUE]);
    }

    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $rules = [
            ['nis', 'NIS', 'required|trim'],
            ['jenjang', 'Jenjang', 'required|in_list[M1,M2]'],
            ['semester', 'Semester', 'required|numeric'],
            ['jenis_pelanggaran', 'Jenis Pelanggaran', 'required|trim'],
            ['sanksi', 'Sanksi', 'required|trim'],
            ['tanggal_pelanggaran', 'Tanggal Pelanggaran', 'required|callback_valid_date'],
        ];

        foreach ($rules as $rule) {
            $this->form_validation->set_rules($rule[0], $rule[1], $rule[2], [
                'required' => "Kolom {field} wajib diisi.",
                'numeric'  => "{field} harus berupa angka.",
                'in_list'  => "Pilih opsi yang valid untuk {field}.",
            ]);
        }

        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'inputerror'   => [],
                'error_string' => [],
                'status'       => FALSE
            ];

            foreach ($rules as $rule) {
                $field = $rule[0];
                if (form_error($field)) {
                    $errors['inputerror'][]   = $field;
                    $errors['error_string'][] = form_error($field);
                }
            }

            echo json_encode($errors);
            exit;
        }
    }


    public function valid_date($str)
    {
        if (DateTime::createFromFormat('Y-m-d', $str) !== FALSE) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_date', '{field} tidak valid (format harus YYYY-MM-DD).');
            return FALSE;
        }
    }
    
    public function delete($id)
    {
        $this->Pelanggaran_model->delete($id);
        echo json_encode(["status" => TRUE]);
    }

    public function get_mahasiswa()
    {
        $nis = $this->input->get('nis');
        $response = ['success' => false];

        if ($nis) {
            $data = $this->Pelanggaran_model->get_by_nis($nis);
            if ($data) {
                $response = [
                    'success' => true,
                    'nama_mahasiswa' => $data->nama_mahasiswa,
                    'jenjang' => $data->jenjang,
                    'semester' => $data->semester,
                    
                ];
            }
        }

        echo json_encode($response);
    }

    function cetak()
    {
        require_once FCPATH . 'vendor/autoload.php';

        // $this->load->model('Mahasiswa_model');
        // $data['mahasiswa'] = $this->Mahasiswa_model->get_by_id($id);

        $html = $this->load->view('suket_aktif', '$data', TRUE);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output("suket_aktif.pdf", "I"); // I = inline, D = download
    }

}
