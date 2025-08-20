<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php'; // <- Tambahkan ini di atas
use Dompdf\Dompdf; // <- lalu import class-nya

class Presensi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Presensi_model');
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
        $isi['tahun_akademik'] = $this->db->get('tahun_akademik')->result();
        $isi['ruangan']        = $this->db->get('kelas')->result();
        $isi['matakuliah']     = $this->db->get('matakuliah')->result();
        $isi['dosen']          = $this->db->get('dosen')->result();
    
        $isi['content'] = 'Presensi/Presensi';
        $isi['ajax']    = 'Presensi/Ajax';
        $isi['css']     = 'Presensi/Css';
    
        $this->load->view('Template', $isi);
    }
    

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Presensi_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->jenjang);
            $row[] = htmlentities($datanya->nama_kelas);
            $row[] = htmlentities($datanya->nama_matakuliah);
            $row[] = htmlentities($datanya->nama_dosen);
            $row[] = htmlentities($datanya->hari) . ' ' . htmlentities($datanya->jam_mulai) . ' - ' . htmlentities($datanya->jam_selesai);
            // $status = htmlentities($d->status_nilai);
            // $badgeClass = 'secondary';
            // if (strtolower($status) == 'sudah') {
            //     $badgeClass = 'success';
            // } elseif (strtolower($status) == 'sebagian') {
            //     $badgeClass = 'warning';
            // } elseif (strtolower($status) == 'belum') {
            //     $badgeClass = 'danger';
            // }
            // $row[] = '<span class="badge bg-' . $badgeClass . '">' . $status . '</span>';
            // $row[] = '';

            // Tombol aksi
            $row[] = '
            <div class="btn-group" role="group" aria-label="Aksi">
                <button type="button" class="btn btn-info" onclick="inputKehadiran(\'' . $datanya->id_kelas . '\', \'' . $datanya->id_mk . '\')">
                    <i class="bx bx-check-square"></i> Input Kehadiran
                </button>
            </div>';
            $data[] = $row;

        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function get_mahasiswa_json()
    {
        $id_kelas   = $this->input->post('id_kelas');
        $id_matkul  = $this->input->post('id_matkul');
    
        $mahasiswa = $this->db->query("
            SELECT 
                krs.id_krs, m.nis, m.nama_mahasiswa,
                IFNULL(rp.jumlah_hadir, 0) AS jumlah_hadir,
                IFNULL(rp.jumlah_izin, 0) AS jumlah_izin,
                IFNULL(rp.jumlah_sakit, 0) AS jumlah_sakit,
                IFNULL(rp.jumlah_alpha, 0) AS jumlah_alpha
            FROM krs
            JOIN mahasiswa m ON m.nis = krs.nis
            LEFT JOIN rekap_presensi rp ON rp.id_krs = krs.id_krs
            WHERE krs.id_kelas = ? AND krs.id_matkul = ?
        ", [$id_kelas, $id_matkul])->result();
    
        echo json_encode($mahasiswa);
    }
    

public function simpan_rekap()
{
    $hadir = $this->input->post('hadir');
    $izin  = $this->input->post('izin');
    $sakit = $this->input->post('sakit');
    $alpha = $this->input->post('alpha');

    if (!$hadir) {
        echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
        return;
    }

    foreach ($hadir as $id_krs => $jml_hadir) {
        $data = [
            'id_krs'        => $id_krs,
            'jumlah_hadir'  => (int)$jml_hadir,
            'jumlah_izin'   => isset($izin[$id_krs])  ? (int)$izin[$id_krs]  : 0,
            'jumlah_sakit'  => isset($sakit[$id_krs]) ? (int)$sakit[$id_krs] : 0,
            'jumlah_alpha'  => isset($alpha[$id_krs]) ? (int)$alpha[$id_krs] : 0,
            'uploaded_at'   => date('Y-m-d H:i:s')
        ];

        // REPLACE akan insert baru atau update jika id_krs sudah ada (karena sudah UNIQUE)
        $this->db->replace('rekap_presensi', $data);
    }

    echo json_encode(['status' => true, 'message' => 'Presensi berhasil disimpan']);
}

}
