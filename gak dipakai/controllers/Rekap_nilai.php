<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_nilai extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Rekap_nilai_model');
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
        $isi['content'] = 'Rekap_nilai/Rekap_nilai';
        $isi['ajax']    = 'Rekap_nilai/Ajax';
        $isi['css']     = 'Rekap_nilai/Css';
    
        $this->load->view('Template', $isi);
    }
    

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Rekap_nilai_model->get_datatables();
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
                <a type="button" target="_blank" class="btn btn-warning" href="'.base_url('Rekap_nilai/rekap_fullcreen/'.$datanya->id_kelas).'">
                    <i class="bx bx-file"></i> Lihat Rekap Nilai
                </a>
            </div>';
            $data[] = $row;

        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function rekap_fullcreen($id_kelas)
    {
        $mahasiswa = $this->Rekap_nilai_model->get_rekap_nilai_fullcreen($id_kelas);
    
        $mata_kuliah = $this->Rekap_nilai_model->get_mapel_kelas($id_kelas);
    
        $rekap_nilai = [];
    
        foreach ($mahasiswa as $mhs) {
            $row = [
                'nis' => $mhs->nis,
                'nama' => $mhs->nama_mahasiswa,
                'nilai' => [],
                'rata_rata' => 0
            ];
    
            $total_nilai = 0;
            $jumlah_mapel = 0;
    
            foreach ($mata_kuliah as $mapel) {
                $nilai = $this->Rekap_nilai_model->get_nilai_mahasiswa($mhs->nis, $mapel->id_matakuliah);
    
                $angka = ($nilai) ? $nilai->nilai_angka : 0;
                $angka_revisi = ($nilai) ? $nilai->nilai_revisi : 0;
    
                $row['nilai'][$mapel->nama_matakuliah] = $angka;
                $row['nilai_revisi'][$mapel->nama_matakuliah] = $angka_revisi;
                $total_nilai += $angka;
                $jumlah_mapel++;
            }
    
            $row['rata_rata'] = ($jumlah_mapel > 0) ? round($total_nilai / $jumlah_mapel, 2) : 0;
    
            $rekap_nilai[] = $row;
        }
    
        $data = [
            'rekap_nilai' => $rekap_nilai,
            'mata_kuliah' => $mata_kuliah,
            'kelas' => $id_kelas // bisa ambil nama kelas jika perlu
        ];
    
        $this->load->view('Rekap_nilai/Rekap_fullscreen', $data);
    }
    
}
