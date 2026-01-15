<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Kelas_model');
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
        $isi['content'] = 'Kelas/Kelas';
        $isi['ajax']    = 'Kelas/Ajax';
        $isi['css']     = 'Kelas/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Kelas_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->tahun_akademik);
            $row[] = htmlentities($datanya->nama_kelas);
            $row[] = htmlentities($datanya->smt);
            // $row[] = htmlentities($datanya->kategori);
            if ($datanya->kategori == 'Putra') {
                $row[] = '<span class="badge bg-primary">Putra</span>';
            } else {
                $row[] = '<span class="badge bg-pink text-white">Putri</span>';
            }
            if ($datanya->jenjang == 'M1') {
                $row[] = '<span class="badge bg-primary">Marhalah Ula</span>';
            } else {
                $row[] = '<span class="badge bg-success text-white">Marhalah Tsani</span>';
            }
            // $row[] = $jk_badge;
            $row[] = htmlentities($datanya->sts);
            // Tombol aksi
            $row[] = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" 
                        onclick="edit_kelas(\'' . $datanya->id_kelas . '\')">
                        <i class="bx bx-edit"></i> Edit
                    </button>&nbsp;
                    <button type="button" class="btn btn-outline-primary" 
                        onclick="naikkan_semester(\'' . $datanya->id_kelas . '\')">
                        <i class="bx bx-arrow-to-right"></i> Naikkan
                    </button>&nbsp;
                    <button type="button" class="btn btn-outline-success" 
                        onclick="lihatMahasiswa(' . $datanya->id_kelas . ')">
                        <i class="bx bx-group"></i> List Mahasiswa
                    </button>&nbsp;
                     <button type="button" class="btn btn-sm btn-outline-danger" 
                        onclick="delete_kelas(\'' . $datanya->id_kelas . '\')">
                        <i class="bx bx-trash"></i> Hapus
                    </button>&nbsp;
                </div>';

            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }


    public function ajax_add()
    {
        $this->_validate();

        $data = [
            'id_tahun'   => $this->input->post('angkatan', TRUE),
            'nama_kelas' => $this->input->post('nama_kelas', TRUE),
            'semester'   => $this->input->post('semester', TRUE),
            'jenjang'    => $this->input->post('jenjang', TRUE),
            'kategori'   => $this->input->post('kategori', TRUE),
            'status'     => $this->input->post('status', TRUE),
        ];

        $this->Kelas_model->create('kelas', $data);
        echo json_encode(["status" => TRUE]);
    }

    public function ajax_edit($id)
    {
        $data = $this->Kelas_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = [
            'id_tahun'   => $this->input->post('angkatan', TRUE),
            'nama_kelas' => $this->input->post('nama_kelas', TRUE),
            'semester'   => $this->input->post('semester', TRUE),
            'jenjang'    => $this->input->post('jenjang', TRUE),
            'kategori'   => $this->input->post('kategori', TRUE),
            'status'     => $this->input->post('status', TRUE),
        ];

        $this->Kelas_model->update(['id_kelas' => $this->input->post('id_kelas')], $data);
        echo json_encode(["status" => TRUE]);
    }

    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $rules = [
            ['angkatan', 'Tahun Angkatan', 'required|numeric'],
            ['nama_kelas', 'Nama Kelas', 'required|trim'],
            ['semester', 'Semester', 'required|numeric'],
            ['jenjang', 'Jenjang', 'required|in_list[M1,M2]'],
            ['kategori', 'Kategori', 'required|in_list[Putra,Putri]'],
            ['status', 'Status', 'required|in_list[Aktif,Tidak Aktif,Lulus]'],
        ];

        foreach ($rules as $rule) {
            $this->form_validation->set_rules($rule[0], $rule[1], $rule[2], [
                'required' => "Kolom {field} wajib diisi.",
                'numeric'  => "{field} harus berupa angka.",
                'in_list'  => "Pilih opsi yang valid untuk {field}.",
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

        // Contoh validasi duplikat nama_kelas + id_tahun (optional)
        $nama_kelas = $this->input->post('nama_kelas', TRUE);
        $id_tahun   = $this->input->post('angkatan', TRUE);
        $id         = $this->input->post('id_kelas');

        $existing = $this->Kelas_model->cek_duplikat_kelas($nama_kelas, $id_tahun, $id);
        if ($existing) {
            echo json_encode([
                'inputerror'   => ['nama_kelas'],
                'error_string' => ['Nama kelas sudah ada pada tahun angkatan tersebut.'],
                'status'       => FALSE
            ]);
            exit;
        }
    }

    public function naikkan_semester($id_kelas)
    {
        $kelas = $this->Kelas_model->get_by_id($id_kelas);

        if (!$kelas) {
            echo json_encode(['status' => FALSE, 'msg' => 'Kelas tidak ditemukan.']);
            return;
        }

        if ($kelas->status != 'Aktif') {
            echo json_encode(['status' => FALSE, 'msg' => 'Kelas tidak aktif, tidak bisa dinaikkan.']);
            return;
        }

        if ($kelas->semester >= 8) {
            echo json_encode(['status' => FALSE, 'msg' => 'Semester sudah maksimal, tidak bisa dinaikkan lagi.']);
            return;
        }

        // 1. Ambil semua mahasiswa dari distribusi_kelas
        $mahasiswa = $this->db->get_where('distribusi_kelas', ['id_kelas' => $id_kelas])->result();

        // 2. Simpan ke riwayat_semester
        $tahun_pelajaran = date('Y') . '/' . (date('Y') + 1); // Bisa disesuaikan logikanya
        $data_riwayat = [];

        foreach ($mahasiswa as $mhs) {
            $data_riwayat[] = [
                'nis' => $mhs->nis,
                'id_kelas' => $mhs->id_kelas,
                'semester' => $kelas->semester, // sebelum dinaikkan
                'id_tahun' => $mhs->id_tahun,
                'status' => $mhs->status_keanggotaan,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($data_riwayat)) {
            $this->db->insert_batch('riwayat_semester', $data_riwayat);
        }

        // 3. Naikkan semester kelas
        $this->db->set('semester', 'semester+1', FALSE);
        $this->db->where('id_kelas', $id_kelas);
        $this->db->update('kelas');

        // Ambil data kelas terbaru setelah update
        $kelas_baru = $this->Kelas_model->get_by_id($id_kelas);
        $msg = 'Kelas <strong style="color:#007bff;">' . $kelas_baru->nama_kelas . '</strong> berhasil dinaikkan ke semester <strong style="color:#28a745;">' . $kelas_baru->semester . '</strong>';
        echo json_encode([
            'status'       => TRUE,
            'msg'          => $msg,
            'nama_kelas'   => $kelas_baru->nama_kelas,
            'semester'     => $kelas_baru->semester,
        ]);
    }

    
    public function delete($id)
    {
        $this->Kelas_model->delete($id);
        echo json_encode(["status" => TRUE]);
    }

    public function get_mahasiswa_by_kelas()
    {
        $id_kelas = $this->input->post('id_kelas');

        if (!$id_kelas) {
            echo json_encode(['status' => false, 'data' => [], 'message' => 'ID Kelas tidak ditemukan']);
            return;
        }

        // Query mahasiswa yang ada di kelas ini
        $this->db->select('m.nis, m.nama_mahasiswa, dk.status_keanggotaan, dk.semester_masuk');
        $this->db->from('distribusi_kelas dk');
        $this->db->join('mahasiswa m', 'm.nis = dk.nis');
        $this->db->where('dk.id_kelas', $id_kelas);
        $this->db->order_by('dk.status_keanggotaan', 'ASC');
        $this->db->order_by('m.nama_mahasiswa', 'ASC');
        $query = $this->db->get();
        $mahasiswa = $query->result();

        echo json_encode(['status' => true, 'data' => $mahasiswa]);
    }



}
