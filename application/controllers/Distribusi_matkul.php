<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php'; // <- Tambahkan ini di atas
use Dompdf\Dompdf; // <- lalu import class-nya

class Distribusi_matkul extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Distribusi_matkul_model');
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
    
        $isi['content'] = 'Distribusi_matakuliah/Distribusi_matakuliah';
        $isi['ajax']    = 'Distribusi_matakuliah/Ajax';
        $isi['css']     = 'Distribusi_matakuliah/Css';
    
        $this->load->view('Template', $isi);
    }
    

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Distribusi_matkul_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->jenjang);
            $row[] = htmlentities($datanya->nama_kelas);
            $row[] = htmlentities($datanya->nama_matakuliah);
            $row[] = htmlentities($datanya->sks);
            $row[] = htmlentities($datanya->nama_dosen);
            $row[] = htmlentities($datanya->hari) . ' ' . htmlentities($datanya->jam_mulai) . ' - ' . htmlentities($datanya->jam_selesai);

            // Tombol aksi
            $row[] = '
                    <div class="btn-group" role="group" aria-label="Aksi">
                        <button type="button" class="btn btn-info" onclick="edit_distribusi(\'' . $datanya->id_distribusi . '\')">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button type="button" class="btn btn-warning" onclick="hapus_distribusi(\'' . $datanya->id_distribusi . '\')">
                            <i class="bx bx-trash"></i>
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Cetak
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="' . site_url('distribusi_matkul/cetak_presensi/' . $datanya->id_distribusi) . '" target="_blank">
                                        Presensi
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#">Jurnal</a></li>
                            </ul>
                        </div>
                    </div>';


            $data[] = $row;

        }

        $output = array("data" => $data);
        echo json_encode($output);
    }


    public function ajax_add()
    {
        $this->_validate();
        $tahun_akademik_aktif = $this->db->get_where('tahun_akademik', ['status' => 'aktif'])->row();

        $data = [
            'id_tahun'    => $tahun_akademik_aktif->id_tahun,
            'id_kelas'    => $this->input->post('id_kelas', TRUE),
            'id_mk' => $this->input->post('id_mk', TRUE),
            'id_dosen'    => $this->input->post('id_dosen', TRUE),
            'hari'        => $this->input->post('hari', TRUE),
            'jam_mulai'   => $this->input->post('jam_mulai', TRUE),
            'jam_selesai' => $this->input->post('jam_selesai', TRUE),
        ];

        $this->Distribusi_matkul_model->create('distribusi_mk',$data); // Pastikan modelnya sesuai
        echo json_encode(["status" => TRUE]);
    }


    public function ajax_edit($id)
    {
        $data = $this->Distribusi_matkul_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $id = $this->input->post('id_distribusi_matakuliah');

        $data = [
            'id_kelas'    => $this->input->post('id_kelas', TRUE),
            'id_mk'       => $this->input->post('id_mk', TRUE), // diperbaiki
            'id_dosen'    => $this->input->post('id_dosen', TRUE),
            'hari'        => $this->input->post('hari', TRUE),
            'jam_mulai'   => $this->input->post('jam_mulai', TRUE),
            'jam_selesai' => $this->input->post('jam_selesai', TRUE),
        ];

        $this->Distribusi_matkul_model->update(['id_distribusi' => $id], $data);

        echo json_encode(["status" => TRUE]);
    }


    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        // 1. Validasi wajib (required) sudah kamu buat, saya ulang dan sesuaikan:
        $rules = [
            ['id_kelas', 'Kelas', 'required|integer'],
            ['id_mk', 'Matakuliah', 'required|integer'],
            ['id_dosen', 'Dosen Pengampu', 'required|integer'],
            ['hari', 'Hari', 'required|in_list[senin,selasa,rabu,kamis,jumat,sabtu,minggu]'],
            ['jam_mulai', 'Jam Mulai', 'required'],
            ['jam_selesai', 'Jam Selesai', 'required'],
        ];

        foreach ($rules as $rule) {
            $this->form_validation->set_rules($rule[0], $rule[1], $rule[2], [
                'required' => "Kolom {field} wajib diisi.",
                'integer'  => "{field} harus berupa angka.",
                'in_list'  => "Kolom {field} harus dipilih dari daftar yang valid.",
            ]);
        }

        if ($this->form_validation->run() == FALSE) {
            $errors = [
                'inputerror'   => [],
                'error_string' => [],
                'status'       => FALSE
            ];

            foreach ($_POST as $key => $val) {
                if (form_error($key)) {
                    $errors['inputerror'][]   = $key;
                    $errors['error_string'][] = form_error($key);
                }
            }

            echo json_encode($errors);
            exit;
        }

        // 2. Validasi jam_mulai dan jam_selesai (format dan logika waktu)
        $jam_mulai = $this->input->post('jam_mulai');
        $jam_selesai = $this->input->post('jam_selesai');

        if (strtotime($jam_selesai) <= strtotime($jam_mulai)) {
            echo json_encode([
                'inputerror'   => ['jam_selesai'],
                'error_string' => ['Jam selesai harus lebih besar dari jam mulai.'],
                'status'       => FALSE
            ]);
            exit;
        }

        // 3. Validasi bentrok jadwal ruangan (kelas)
        $id_kelas = $this->input->post('id_kelas');
        $hari = $this->input->post('hari');
        $id_distribusi = $this->input->post('id_distribusi_matakuliah'); // kalau update, agar tidak cek sendiri

        if ($this->Distribusi_matkul_model->checkConflictRoom($id_kelas, $hari, $jam_mulai, $jam_selesai, $id_distribusi)) {
            echo json_encode([
                'inputerror'   => ['id_kelas', 'hari', 'jam_mulai', 'jam_selesai'],
                'error_string' => ['Jadwal ruangan bentrok dengan jadwal lain pada waktu yang sama.'],
                'status'       => FALSE
            ]);
            exit;
        }

        // 4. Validasi bentrok jadwal dosen
        $id_dosen = $this->input->post('id_dosen');
        if ($this->Distribusi_matkul_model->checkConflictDosen($id_dosen, $hari, $jam_mulai, $jam_selesai, $id_distribusi)) {
            echo json_encode([
                'inputerror'   => ['id_dosen', 'hari', 'jam_mulai', 'jam_selesai'],
                'error_string' => ['Jadwal dosen bentrok dengan jadwal lain pada waktu yang sama.'],
                'status'       => FALSE
            ]);
            exit;
        }

        // 5. Validasi duplikasi data distribusi
        $id_mk = $this->input->post('id_mk');
        if ($this->Distribusi_matkul_model->checkDuplicateDistribusi($id_kelas, $id_mk, $id_dosen, $hari, $jam_mulai, $jam_selesai, $id_distribusi)) {
            echo json_encode([
                'inputerror'   => ['id_kelas', 'id_mk', 'id_dosen', 'hari', 'jam_mulai', 'jam_selesai'],
                'error_string' => ['Data distribusi dengan kombinasi tersebut sudah ada.'],
                'status'       => FALSE
            ]);
            exit;
        }

        // 6. Validasi tahun akademik aktif
        $tahun_aktif = $this->Distribusi_matkul_model->getActiveTahunAkademik();
        if (!$tahun_aktif) {
            echo json_encode([
                'inputerror'   => [],
                'error_string' => ['Tidak ditemukan tahun akademik aktif.'],
                'status'       => FALSE
            ]);
            exit;
        }
    }

    public function delete($id)
    {
        $this->Distribusi_matkul_model->delete($id);
        echo json_encode(["status" => TRUE]);
    }

    public function cetak_presensi($id_distribusi)
    {
        $data['distribusi'] = $this->Distribusi_matkul_model->get_detail_distribusi($id_distribusi);
        $data['mahasiswa']  = $this->Distribusi_matkul_model->get_mahasiswa_by_distribusi($id_distribusi);
    
        $html = $this->load->view('distribusi_matakuliah/presensi', $data, true);
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("presensi_{$id_distribusi}.pdf", array("Attachment" => false));
        exit;
    }
    


   
}
