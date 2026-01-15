<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Khs_mahasiswa extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
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
        $isi['content'] = 'Khs/Khs';
        $isi['ajax']    = 'Khs/Ajax';
        $isi['css']     = 'Khs/Css';
        $this->load->view('Template',$isi);
	}

    public function Detail_khs()
	{
        $isi['content'] = 'Khs/Detail_khs';
        $isi['ajax']    = 'Khs/Ajax';
        $isi['css']     = 'Khs/Css';
        $this->load->view('Template',$isi);
	}


}
