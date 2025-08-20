<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_perdosen extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Cetak_perdosen_model');
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
        $isi['content'] = 'Cetak_absen_dosen/Cetak_absen_dosen';
        $isi['ajax']    = 'Cetak_absen_dosen/Ajax';
        $isi['css']     = 'Cetak_absen_dosen/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Cetak_perdosen_model->get_datatables();
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
            // Tombol aksi
            $row[] = '
                <div class="btn-group" role="group">
                    <a href="'.site_url('Cetak_perdosen/cetak/'.$datanya->id_dosen).'" 
                    target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bx bx-printer"></i> Cetak
                    </a>
                </div>';


            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function cetak($id_dosen)
    {
        // Ambil data dosen
        $data['dosen'] = $this->db->get_where('dosen', ['id_dosen' => $id_dosen])->row();

        // Ambil data jadwal detail
        $data['jadwal'] = $this->Cetak_perdosen_model->get_jadwal_by_dosen($id_dosen);

        // Load view untuk cetak
        $this->load->view('Cetak_absen_dosen/Cetak_jadwal_dosen', $data);
    }





}
