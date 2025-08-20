<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate_krs extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Generate_krs_model');
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
        $isi['content'] = 'Generate_krs/Generate_krs';
        $isi['ajax']    = 'Generate_krs/Ajax';
        $isi['css']     = 'Generate_krs/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        // Ambil tahun akademik aktif dari database
        $tahun_aktif = $this->db->get_where('tahun_akademik', ['status' => 'Aktif'])->row();
        if (!$tahun_aktif) {
            echo json_encode(['data' => [], 'message' => 'Tahun akademik aktif tidak ditemukan']);
            return;
        }

        $tahun_pelajaran = $tahun_aktif->id_tahun;

        $list = $this->Generate_krs_model->get_datatables($tahun_pelajaran);
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->jenjang);
            $row[] = htmlentities($datanya->nama_kelas);
            $row[] = htmlentities($datanya->semester);
            $row[] = htmlentities($datanya->tahun_akademik);
            $row[] = htmlentities($datanya->jumlah_mahasiswa);
            $row[] = htmlentities($datanya->jumlah_matakuliah);
            $row[] = htmlentities($datanya->total_sks !== null ? $datanya->total_sks : '0');
            $row[] = ($datanya->status_krs === 'Belum')
                ? '<span class="badge bg-warning text-dark">Belum</span>'
                : '<span class="badge bg-success">Sudah</span>';

            // Tombol aksi (opsional: sesuaikan jika status_krs = 'Sudah')
            $row[] = ($datanya->status_krs === 'Belum') ?
                '<button class="btn btn-sm btn-danger proses-krs" data-id="'.$datanya->id_kelas.'" data-semester="'.$datanya->semester.'">
                    <i class="bx bx-cog"></i> Proses KRS
                </button>' :
                '<span class="badge bg-success">Sudah diproses</span>';


            $data[] = $row;
        }

        $output = array(
            "data" => $data,
            "tahun_pelajaran" => $tahun_pelajaran
            );
        echo json_encode($output);
    }

    public function proses_krs()
    {
        $id_kelas = $this->input->post('id_kelas');
        $semester = $this->input->post('semester');

        // Ambil tahun akademik aktif
        $tahun = $this->db->get_where('tahun_akademik', ['status' => 'Aktif'])->row();
        if (!$tahun) {
            echo json_encode(['status' => false, 'message' => 'Tahun akademik aktif tidak ditemukan.']);
            return;
        }
        $id_tahun = $tahun->id_tahun;

        // Ambil semua mahasiswa dalam kelas
        $mahasiswa = $this->db->get_where('distribusi_kelas', ['id_kelas' => $id_kelas, 'status_keanggotaan' => 'Aktif'])->result();
        if (!$mahasiswa) {
            echo json_encode(['status' => false, 'message' => 'Tidak ada mahasiswa dalam kelas ini.']);
            return;
        }

        // Ambil distribusi matakuliah untuk kelas ini
        $matkul = $this->db->get_where('distribusi_mk', ['id_kelas' => $id_kelas])->result();
        if (!$matkul) {
            echo json_encode(['status' => false, 'message' => 'Distribusi matakuliah belum dibuat.']);
            return;
        }

        // Simpan ke tabel krs (nis + matkul)
        foreach ($mahasiswa as $m) {
            foreach ($matkul as $mk) {
                $this->db->insert('krs', [
                    'nis' => $m->nis,
                    'id_matkul' => $mk->id_mk,
                    'id_kelas' => $id_kelas,
                    'semester' => $semester,
                    'id_tahun' => $id_tahun
                ]);
            }
        }

        echo json_encode(['status' => true, 'message' => 'KRS berhasil diproses untuk kelas ini.']);
    }



}
