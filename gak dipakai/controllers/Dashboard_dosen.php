<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_dosen extends CI_Controller {
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
        $isi['content'] = 'Dashboard_dosen';
        $isi['ajax']    = 'Ajax';
        $isi['css']     = 'Css';
        $this->load->view('Template',$isi);
	}

}
