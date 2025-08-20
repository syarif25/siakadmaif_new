<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_akademik extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Tahun_akademik_model');
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
        $isi['content'] = 'Tahun_akademik/Tahun_akademik';
        $isi['ajax']    = 'Tahun_akademik/Ajax';
        $isi['css']     = 'Tahun_akademik/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
	{
		$this->load->helper('url');

		$list = $this->Tahun_akademik_model->get_datatables();
		$no =1;
		$data = array();
		foreach ($list as $datanya) {
			
			$row = array();
			$row[] = $no++;
			$row[] = htmlentities($datanya->tahun_akademik);
            $row[] = format_tanggal_indonesia($datanya->tanggal_mulai).' - '.format_tanggal_indonesia($datanya->tanggal_selesai);
            // $row[] = htmlentities($datanya->status);
            if ($datanya->status == 'Aktif') {
                $row[] = '<span class="badge bg-success text-white">Aktif</span>';
                $row[] = '<a type="button" class="btn btn-outline-primary btn-sm" href="#" 
                            title="Edit Tahun" onclick="edit_tahun('."'".$datanya->id_tahun."'".')"><i class="bx bx-edit mr-1" ></i> Edit</a>';
            } else {
                $row[] = '<span class="badge bg-danger text-white">Tidak Aktif</span>';
                $row[] = '<a type="button" class="btn btn-outline-primary btn-sm" href="#" 
                        title="Edit Tahun" onclick="edit_tahun('."'".$datanya->id_tahun."'".')"><i class="bx bx-edit mr-1" ></i> Edit</a> - 
                        <a type="button" class="btn btn-outline-danger btn-sm" href="#" 
                        title="Delete Tahun" onclick="delete_tahun('."'".$datanya->id_tahun."'".')"><i class="bx bx-trash mr-1" ></i> Delete</a> -
                        <a href="javascript:void(0)" 
                            class="btn btn-outline-info btn-sm" 
                            onclick="konfirmasiAktifkan('."'".$datanya->id_tahun."'".')"
                            title="Aktifkan Tahun">
                            <i class="bx bx-check-circle mr-1"></i> Aktifkan
                        </a>';

            }                         
			//add html for action
			$row[] = '<a type="button" class="btn btn-outline-danger btn-sm" href="#" 
			title="Edit Tahun" onclick="edit_tahun('."'".$datanya->id_tahun."'".')"><i class="bx bx-edit mr-1" ></i> Edit</a>';
		$data[] = $row;
		}
			$output = array("data" => $data);
		echo json_encode($output);
	}

    public function ajax_add()
	{
        $this->_validate();
		$data = array(
				'tahun_akademik' 	=> $this->input->post('tahun_akademik'),
                'semester' 	        => $this->input->post('semester'),
                'tanggal_mulai' 	=> $this->input->post('tanggal_mulai'),
                'tanggal_selesai' 	=> $this->input->post('tanggal_selesai'),
				'status' 		=> 'Tidak Aktif',
		);
	    $simpan = $this->Tahun_akademik_model->create('tahun_akademik',$data);
		echo json_encode(array("status" => TRUE));
	}

    public function ajax_edit($id)
	{
		$data = $this->Tahun_akademik_model->get_by_id($id);
		echo json_encode($data);
	}

    public function ajax_update()
	{
        $this->_validate();
	    $data = array(
            'tahun_akademik' 	=> $this->input->post('tahun_akademik'),
            'semester' 	        => $this->input->post('semester'),
            'tanggal_mulai' 	=> $this->input->post('tanggal_mulai'),
            'tanggal_selesai' 	=> $this->input->post('tanggal_selesai'),
            'status' 		=> 'Tidak Aktif',
        );
				
		$this->Tahun_akademik_model->update(array('id_tahun' => $this->input->post('id_tahun')), $data);
		echo json_encode(array("status" => TRUE));
	}

    public function delete($id)
    {
        $this->Tahun_akademik_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
	
	public function aktifkan($id)
	{
		$this->db->update('tahun_akademik',array('status' => 'Tidak Aktif'));
		$this->db->where('id_tahun',$id);
		$this->db->update('tahun_akademik',array('status' => 'Aktif'));
		echo json_encode(array("status" => TRUE));
	}

    private function _validate()
    {
        $this->load->library('form_validation');

        // Mengatur delimiters error menjadi kosong agar tidak ada tag <p>
        $this->form_validation->set_error_delimiters('', '');

        // Menentukan aturan validasi dan pesan kesalahan khusus untuk setiap field
        $this->form_validation->set_rules(
            'tahun_akademik', 
            'Tahun Akademik', 
            'required|trim', 
            [
                'required' => 'Kolom {field} wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'semester', 
            'Semester', 
            'required|in_list[Ganjil,Genap]', 
            [
                'required' => 'Kolom {field} tidak boleh kosong.',
                'in_list' => 'Pilih salah satu dari Ganjil atau Genap untuk {field}.'
            ]
        );

        $this->form_validation->set_rules(
            'tanggal_mulai', 
            'Tanggal Mulai', 
            'required', 
            [
                'required' => '{field} harus diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'tanggal_selesai', 
            'Tanggal Selesai', 
            'required', 
            [
                'required' => '{field} harus diisi.'
            ]
        );

        // Menjalankan validasi, jika ada error
        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'inputerror'   => array(),
                'error_string' => array(),
                'status'       => FALSE
            );

            // Menangani error per field dan mengembalikan ke AJAX response
            foreach ($_POST as $key => $value) {
                if (form_error($key)) {
                    $errors['inputerror'][]   = $key;
                    $errors['error_string'][] = form_error($key);
                }
            }

            echo json_encode($errors);
            exit;
        }
    }


}
