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
        
        // Cek apakah user adalah Petugas
        if ($this->session->userdata('role') != 'Petugas') {
            // Kalau bukan Petugas, redirect ke dashboard sesuai role
            if ($this->session->userdata('role') == 'Mahasiswa') {
                redirect('Dashboard_mahasiswa');
            } elseif ($this->session->userdata('role') == 'Dosen') {
                redirect('Dashboard_dosen');
            } else {
                redirect('login');
            }
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

            // Tombol aksi
            $row[] = ($datanya->status_krs === 'Belum') ?
                '<button class="btn btn-sm btn-primary proses-krs" data-id="'.$datanya->id_kelas.'" data-semester="'.$datanya->semester.'">
                    <i class="bx bx-cog"></i> Proses KRS
                </button>' :
                '<div class="btn-group" role="group">
                    <button class="btn btn-sm btn-danger reset-krs" data-id="'.$datanya->id_kelas.'" data-semester="'.$datanya->semester.'">
                        <i class="bx bx-undo"></i> Reset KRS
                    </button>
                </div>';


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

        // Ambil info kelas
        $kelas = $this->db->get_where('kelas', ['id_kelas' => $id_kelas])->row();
        
        // Ambil semua mahasiswa dalam kelas
        $mahasiswa = $this->db->get_where('distribusi_kelas', ['id_kelas' => $id_kelas, 'status_keanggotaan' => 'Aktif'])->result();
        if (!$mahasiswa || count($mahasiswa) == 0) {
            echo json_encode(['status' => false, 'message' => 'Tidak ada mahasiswa aktif dalam kelas ini.']);
            return;
        }

        // Ambil distribusi matakuliah untuk kelas ini (DISTINCT matakuliah only)
        // Fix duplicate KRS entries if multiple schedules exist for the same subject
        $this->db->select('id_mk');
        $this->db->distinct();
        $this->db->where('id_kelas', $id_kelas);
        $matkul = $this->db->get('distribusi_mk')->result();

        if (!$matkul || count($matkul) == 0) {
            echo json_encode(['status' => false, 'message' => 'Distribusi matakuliah belum dibuat untuk kelas ini.']);
            return;
        }

        // Cek apakah KRS sudah pernah diproses
        $existing = $this->db->get_where('krs', [
            'id_kelas' => $id_kelas,
            'semester' => $semester,
            'id_tahun' => $id_tahun
        ])->num_rows();
        
        if ($existing > 0) {
            echo json_encode(['status' => false, 'message' => 'KRS untuk kelas ini sudah pernah diproses. Gunakan fitur Reset KRS jika ingin memproses ulang.']);
            return;
        }

        // Simpan ke tabel krs (nis + matkul)
        $inserted = 0;
        foreach ($mahasiswa as $m) {
            foreach ($matkul as $mk) {
                $this->db->insert('krs', [
                    'nis' => $m->nis,
                    'id_matkul' => $mk->id_mk,
                    'id_kelas' => $id_kelas,
                    'semester' => $semester,
                    'id_tahun' => $id_tahun
                ]);
                $inserted++;
            }
        }
        
        // Build detailed success message
        $jumlah_mahasiswa = count($mahasiswa);
        $jumlah_matakuliah = count($matkul);
        
        $message = "<strong>KRS Berhasil Diproses!</strong><br><br>" .
                   "<strong>Kelas:</strong> {$kelas->nama_kelas}<br>" .
                   "<strong>Semester:</strong> {$semester}<br>" .
                   "<strong>Tahun Akademik:</strong> {$tahun->tahun_akademik}<br><br>" .
                   "<strong> Statistik:</strong><br>" .
                   "• Jumlah Mahasiswa: {$jumlah_mahasiswa} mahasiswa<br>" .
                   "• Jumlah Matakuliah: {$jumlah_matakuliah} matakuliah<br>" .
                   "• Total Record KRS: {$inserted} record";

        echo json_encode(['status' => true, 'message' => $message]);
    }

    public function reset_krs()
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
        
        // Ambil info kelas
        $kelas = $this->db->get_where('kelas', ['id_kelas' => $id_kelas])->row();

        // Hapus KRS untuk kelas ini
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('semester', $semester);
        $this->db->where('id_tahun', $id_tahun);
        $deleted = $this->db->delete('krs');
        
        $affected = $this->db->affected_rows();

        if ($deleted) {
            $message = "<strong>KRS Berhasil Direset!</strong><br><br>" .
                       "<strong>Kelas:</strong> {$kelas->nama_kelas}<br>" .
                       "<strong>Semester:</strong> {$semester}<br>" .
                       "<strong>Tahun Akademik:</strong> {$tahun->tahun_akademik}<br><br>" .
                       "<strong> Data yang dihapus:</strong> {$affected} record KRS<br><br>" .
                       "<small class='text-muted'>✓ Anda bisa memproses ulang KRS untuk kelas ini</small>";
            
            echo json_encode(['status' => true, 'message' => $message]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal mereset KRS.']);
        }
    }

    public function reset_all_krs()
    {
        // Ambil tahun akademik aktif
        $tahun = $this->db->get_where('tahun_akademik', ['status' => 'Aktif'])->row();
        if (!$tahun) {
            echo json_encode(['status' => false, 'message' => 'Tahun akademik aktif tidak ditemukan.']);
            return;
        }
        $id_tahun = $tahun->id_tahun;

        // Hapus SEMUA KRS untuk tahun akademik aktif
        $this->db->where('id_tahun', $id_tahun);
        $deleted = $this->db->delete('krs');
        
        $affected = $this->db->affected_rows();

        if ($deleted || $affected >= 0) {
            $message = "<strong>Semua KRS Berhasil Direset!</strong><br><br>" .
                       "<strong>Tahun Akademik:</strong> {$tahun->tahun_akademik}<br>" .
                       "<strong>Semester:</strong> {$tahun->semester}<br><br>" .
                       "<strong>Data yang dihapus:</strong> {$affected} record KRS<br><br>" .
                       "<small class='text-muted'>✓ Semua kelas bisa diproses ulang</small>";
            
            echo json_encode(['status' => true, 'message' => $message]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal mereset KRS.']);
        }
    }


}
