<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_krs extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Detail_krs_model');
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
        $isi['content'] = 'Detail_krs/Detail_krs';
        $isi['ajax']    = 'Detail_krs/Ajax';
        $isi['css']     = 'Detail_krs/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Detail_krs_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->nis);
            $row[] = htmlentities($datanya->nama_mahasiswa);
            $row[] = htmlentities($datanya->nama_kelas);
            $row[] = htmlentities($datanya->matakuliah);
            $row[] = htmlentities($datanya->semester);
            $row[] = htmlentities($datanya->jenjang);
            
            

            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

}
