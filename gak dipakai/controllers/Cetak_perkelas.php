<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_perkelas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Cetak_perkelas_model');
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
        $isi['content'] = 'Cetak_absen_perkelas/Cetak_absen_perkelas';
        $isi['ajax']    = 'Cetak_absen_perkelas/Ajax';
        $isi['css']     = 'Cetak_absen_perkelas/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Cetak_perkelas_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            if ($datanya->jenjang == 'M1') {
                $row[] = '<span class="badge bg-primary">Marhalah Ula</span>';
            } else {
                $row[] = '<span class="badge bg-success text-white">Marhalah Tsani</span>';
            }
            $row[] = htmlentities($datanya->nama_kelas);
            
            // $row[] = htmlentities($datanya->kategori);
            if ($datanya->kategori == 'Putra') {
                $row[] = '<span class="badge bg-primary">Putra</span>';
            } else {
                $row[] = '<span class="badge bg-pink text-white">Putri</span>';
            }
          
            // $row[] = $jk_badge;
            // Tombol aksi
            $row[] = '
                <div class="btn-group" role="group">
                    <a href="'.site_url('Cetak_perkelas/cetak/'.$datanya->id_dosen).'" 
                    target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bx bx-printer"></i> Cetak
                    </a>
                </div>';


            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function cetak($id_kelas)
    {
        // Ambil data dosen
        $data['kelas'] = $this->db->get_where('kelas', ['id_kelas' => $id_kelas])->row();

        // Ambil data jadwal detail
        $data['jadwal'] = $this->Cetak_perkelas_model->get_jadwal_by_kelas($id_kelas);

        // Load view untuk cetak
        $this->load->view('Cetak_absen_perkelas/Cetak_jadwal_kelas', $data);
    }





}
