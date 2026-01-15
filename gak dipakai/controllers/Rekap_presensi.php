<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_presensi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Rekap_presensi_model');
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
      
        $isi['content'] = 'Rekap_presensi/Rekap_presensi';
        $isi['ajax']    = 'Rekap_presensi/Ajax';
        $isi['css']     = 'Rekap_presensi/Css';
    
        $this->load->view('Template', $isi);
    }
    

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Rekap_presensi_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->tahun_akademik).' - '.$datanya->semester;
            $row[] = htmlentities($datanya->jenjang);
            $row[] = htmlentities($datanya->nama_kelas);
            
            // Tombol aksi
            $row[] = '
            <div class="btn-group" role="group" aria-label="Aksi">
                <a type="button" target="_blank" class="btn btn-success" href="'.base_url('Rekap_presensi/rekap_fullcreen/'.$datanya->id_kelas).'">
                    <i class="bx bx-file"></i> Lihat Rekap Presensi
                </a>
            </div>';
            $data[] = $row;

        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function rekap_fullcreen($id_kelas)
    {
        $result = $this->Rekap_presensi_model->get_rekap_presensi_fullcreen($id_kelas);
        $mapel = $this->Rekap_presensi_model->get_mapel_kelas($id_kelas);

        // Struktur ulang data: kelompokkan per mahasiswa
        $rekap = [];
        foreach ($result as $row) {
            $nis = $row->nis;
            if (!isset($rekap[$nis])) {
                $rekap[$nis] = [
                    'nis' => $row->nis,
                    'nama_mahasiswa' => $row->nama_mahasiswa,
                    'presensi' => [],
                    'total' => ['h' => 0, 'a' => 0, 'i' => 0, 's' => 0]
                ];
            }

            $rekap[$nis]['presensi'][$row->id_matkul] = [
                'h' => (int)$row->jumlah_hadir,
                'a' => (int)$row->jumlah_alpha,
                'i' => (int)$row->jumlah_izin,
                's' => (int)$row->jumlah_sakit
            ];

            // Totalkan
            $rekap[$nis]['total']['h'] += (int)$row->jumlah_hadir;
            $rekap[$nis]['total']['a'] += (int)$row->jumlah_alpha;
            $rekap[$nis]['total']['i'] += (int)$row->jumlah_izin;
            $rekap[$nis]['total']['s'] += (int)$row->jumlah_sakit;
        }

        $isi['mapel'] = $mapel;
        $isi['rekap_presensi'] = $rekap;
        $this->load->view('Rekap_presensi/Rekap_fullscreen', $isi);
    }




}
