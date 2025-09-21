<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_kelas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('distribusi_kelas_model');
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
        $isi['tahun_akademik']  = $this->db->get('tahun_akademik')->result();
        $isi['kelas']           = $this->db->get('kelas')->result();
        $isi['mahasiswa']       = $this->db->get('mahasiswa')->result();
        $isi['content']         = 'Distribusi_kelas/Distribusi_kelas';
        $isi['ajax']            = 'Distribusi_kelas/Ajax';
        $isi['css']             = 'Distribusi_kelas/Css';
        $this->load->view('Template',$isi);
	}

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->distribusi_kelas_model->get_datatables();
        $no = 1;
        $data = array();

        foreach ($list as $datanya) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($datanya->nama_kelas).' - <b><span style="color: '.($datanya->jenjang == 'M1' ? 'green' : ($datanya->jenjang == 'M2' ? 'blue' : 'black')).'">'.htmlentities($datanya->jenjang).'</span></b>';
            $row[] = htmlentities($datanya->tahun_akademik);
            $row[] = htmlentities($datanya->nis);
            $row[] = htmlentities($datanya->nama_mahasiswa);
            $row[] = htmlentities($datanya->semester_kelas);
            $status = htmlentities($datanya->status_keanggotaan);
            $badgeClass = 'secondary'; // default

            switch (strtolower($status)) {
                case 'aktif':
                    $badgeClass = 'success';
                    break;
                case 'cuti':
                    $badgeClass = 'secondary';
                    break;
                case 'lulus':
                    $badgeClass = 'primary';
                    break;
                case 'berhenti':
                    $badgeClass = 'danger';
                    break;
                case 'dikeluarkan':
                    $badgeClass = 'danger';
                    break;
                case 'keluar':
                    $badgeClass = 'danger';
                    break;
            }

            $row[] = '<span class="badge bg-' . $badgeClass . '">' . $status . '</span>';

            // Tombol aksi
            $row[] = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" 
                        onclick="pindah(\'' . $datanya->id_distribusi_kelas . '\')">
                        <i class="bx bx-edit"></i> Edit
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
    
        $id_kelas = $this->input->post('id_kelas', TRUE);
        $mahasiswa = $this->input->post('nis', TRUE); // array of NIS 
        $semester = $this->input->post('semester', TRUE);
        $semester_aktif = $this->input->post('semester', TRUE);
        $id_tahun = $this->input->post('id_tahun', TRUE);
    
        $inserted = 0;
        $duplikat = [];
    
        foreach ($mahasiswa as $nis) {
            // Cek apakah data sudah ada
            $exists = $this->db->get_where('distribusi_kelas', [
                'nis' => $nis,
                'id_kelas' => $id_kelas,
                'semester_masuk' => $semester
            ])->num_rows();
    
            if ($exists === 0) {
                // Jika belum ada, insert
                $data = [
                    'nis'                => $nis,
                    'id_kelas'           => $id_kelas,
                    'id_tahun'           => $id_tahun,
                    'status_keanggotaan' => 'Aktif',
                    'semester_masuk'     => $semester,
                    'semester_aktif'     => $semester_aktif,
                    'created_at'         => date('Y-m-d H:i:s')
                ];
                $this->distribusi_kelas_model->create('distribusi_kelas', $data);
                $inserted++;
            } else {
                // Tambahkan ke list duplikat
                $duplikat[] = $nis;
            }
        }
    
        // Beri respon JSON sesuai hasil
        if ($inserted > 0) {
            $response = ['status' => TRUE];
            if (count($duplikat) > 0) {
                $response['message'] = 'Sebagian data berhasil ditambahkan. Beberapa mahasiswa sudah pernah terdistribusi.';
                $response['duplikat'] = $duplikat;
            }
        } else {
            $response = [
                'status' => FALSE,
                'message' => 'Semua mahasiswa sudah terdistribusi ke kelas dan semester tersebut.',
                'duplikat' => $duplikat
            ];
        }
    
        echo json_encode($response);
    }
    
    public function ajax_update()
    {
        $id       = $this->input->post('edit_id_distribusi_kelas');
        $nis      = $this->input->post('edit_nis');
        $status   = $this->input->post('edit_status_keanggotaan');
        $id_kelas = $this->input->post('edit_id_kelas');

        // Validasi sederhana
        if (empty($id) || empty($status) || empty($nis)) {
            echo json_encode([
                'status'      => false,
                'inputerror'  => [],
                'error_string'=> ['Data tidak lengkap']
            ]);
            return;
        }

        date_default_timezone_set('Asia/Jakarta');

        // Update tabel distribusi_kelas
        $this->db->where('id_distribusi_kelas', $id);
        $updateDistribusi = $this->db->update('distribusi_kelas', [
            'status_keanggotaan' => $status,
            'id_kelas'           => $id_kelas,
            'created_at'         => date('Y-m-d H:i:s') 
        ]);

        if ($updateDistribusi) {
            // Update juga tabel mahasiswa berdasarkan NIS
            $this->db->where('nis', $nis);
            $this->db->update('mahasiswa', [
                'status'     => $status
            ]);

            echo json_encode(['status' => true]);
        } else {
            echo json_encode([
                'status'      => false,
                'inputerror'  => [],
                'error_string'=> ['Gagal update data']
            ]);
        }
    }

     
    private function _validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $rules = [
            ['id_kelas', 'Kelas', 'required|numeric'],
            ['nis[]', 'Mahasiswa', 'required']
        ];

        foreach ($rules as $rule) {
            $this->form_validation->set_rules($rule[0], $rule[1], $rule[2], [
                'required'    => "Kolom {field} wajib diisi.",
                'numeric'     => "{field} harus berupa angka."
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
    }

    public function ajax_edit($id)
    {
        $this->db->select('dk.*, m.nama_mahasiswa');
        $this->db->from('distribusi_kelas dk');
        $this->db->join('mahasiswa m', 'm.nis = dk.nis');
        $this->db->where('dk.id_distribusi_kelas', $id);
        $query = $this->db->get();
    
        echo json_encode($query->row());
    }
    
    public function get_info_kelas($id_kelas)
    {
        $this->db->select('kelas.semester, kelas.id_tahun, tahun_akademik.tahun_akademik');
        $this->db->from('kelas');
        $this->db->join('tahun_akademik', 'kelas.id_tahun = tahun_akademik.id_tahun');
        $this->db->where('kelas.id_kelas', $id_kelas);
        $data = $this->db->get()->row();
    
        if ($data) {
            echo json_encode([
                'status' => true,
                'semester' => $data->semester,
                'tahun_akademik' => $data->tahun_akademik,
                'id_tahun' => $data->id_tahun
            ]);
        } else {
            echo json_encode(['status' => false]);
        }
    }

    public function get_mahasiswa_belum_terdistribusi()
    {
        $id_kelas = $this->input->post('id_kelas');
    
        // Ambil data kelas untuk dapatkan semester dan id_tahun
        $kelas = $this->db->get_where('kelas', ['id_kelas' => $id_kelas])->row();
        if (!$kelas) {
            echo json_encode(['status' => false, 'data' => [], 'message' => 'Kelas tidak ditemukan']);
            return;
        }
    
        $semester = $kelas->semester;
        $id_tahun = $kelas->id_tahun;
    
        // Buat subquery untuk NIS yang sudah terdaftar di kelas manapun pada tahun yang sama
        $subquery = "SELECT dk.nis FROM distribusi_kelas dk 
                     JOIN kelas k ON dk.id_kelas = k.id_kelas";
    
        // Ambil mahasiswa yang TIDAK ada di subquery tersebut
        $this->db->select('m.nis, m.nama_mahasiswa');
        $this->db->from('mahasiswa m');
        $this->db->where("m.nis NOT IN ($subquery)", NULL, FALSE);
        $this->db->where('m.status', 'Aktif');
    
        $query = $this->db->get();
        $mahasiswa = $query->result();
    
        if ($mahasiswa) {
            echo json_encode(['status' => true, 'data' => $mahasiswa]);
        } else {
            echo json_encode(['status' => false, 'data' => []]);
        }
    }
    
}
