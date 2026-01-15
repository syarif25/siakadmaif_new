<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Penilaian_model');
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
        $isi['kelas'] = $this->db->get('kelas')->result(); // tambahkan ini
        $isi['content'] = 'Penilaian/Penilaian';
        $isi['ajax']    = 'Penilaian/Ajax';
        $isi['css']     = 'Penilaian/Css';
        $this->load->view('Template', $isi);
    }
    

    public function data_list()
    {
        $this->load->helper('url');

        $list = $this->Penilaian_model->get_datatables(); // Pastikan modelnya sesuai
        $no = 1;
        $data = array();

        foreach ($list as $d) {
            $row = array();
            $row[] = $no++;
            $row[] = htmlentities($d->jenjang);
            $row[] = htmlentities($d->nama_kelas);
            $row[] = htmlentities($d->nama_matakuliah);
            $row[] = htmlentities($d->semester);
            $row[] = htmlentities($d->tahun_akademik);
            $row[] = htmlentities($d->jumlah_mahasiswa);
            $status = htmlentities($d->status_nilai);
            $badgeClass = 'secondary';
            if (strtolower($status) == 'sudah') {
                $badgeClass = 'success';
            } elseif (strtolower($status) == 'sebagian') {
                $badgeClass = 'warning';
            } elseif (strtolower($status) == 'belum') {
                $badgeClass = 'danger';
            }
            $row[] = '<span class="badge bg-' . $badgeClass . '">' . $status . '</span>';

            $row[] = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    onclick="isi_nilai(\'' . $d->id_kelas . '\', \'' . $d->id_matkul . '\')">
                    <i class="bx bx-detail"></i> Isi Nilai
                </button>
            </div>';

            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function get_mahasiswa()
    {
        $id_kelas = $this->input->post('id_kelas');
        $id_matkul = $this->input->post('id_matkul');

        $mahasiswa = $this->db->query("
            SELECT krs.id_krs, m.nis, m.nama_mahasiswa, krs.nilai_angka, krs.nilai_revisi
            FROM krs
            JOIN mahasiswa m ON m.nis = krs.nis
            WHERE krs.id_kelas = ? AND krs.id_matkul = ?
        ", [$id_kelas, $id_matkul])->result();

        if (!empty($mahasiswa)) {
            foreach ($mahasiswa as $m) {
                echo '<tr>';
                echo '<td>' . htmlentities($m->nis) . '</td>';
                echo '<td>' . htmlentities($m->nama_mahasiswa) . '</td>';
                echo '<td>
                    <input type="number"
                        name="nilai[' . $m->id_krs . '][nilai_angka]"
                        class="form-control nilai-input"
                        min="0" max="100"
                        value="' . ($m->nilai_angka !== null ? htmlentities($m->nilai_angka) : '') . '"
                        placeholder="Masukkan nilai">
                </td>';

                echo '<td>
                    <input type="number"
                        name="nilai[' . $m->id_krs . '][nilai_revisi]"
                        class="form-control nilai-revisi"
                        min="0" max="100"
                        value="' . ($m->nilai_revisi !== null ? htmlentities($m->nilai_revisi) : '') . '"
                        placeholder="Masukkan nilai revisi">
                </td>';

                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3" class="text-center text-muted">Belum ada data mahasiswa.</td></tr>';
        }
    }


    public function simpan_nilai()
    {
        $nilai = $this->input->post('nilai');


        if (!empty($nilai)) {
            foreach ($nilai as $id_krs => $value) {
                log_message('debug', "Proses update untuk ID KRS: $id_krs, nilai_angka: {$value['nilai_angka']}, nilai_revisi: {$value['nilai_revisi']}");
            
                if (!is_numeric($value['nilai_angka']) || !is_numeric($value['nilai_revisi'])) continue;
            
                $nilai_angka = intval($value['nilai_angka']);
                $nilai_revisi = intval($value['nilai_revisi']);
            
                $this->db->where('id_krs', $id_krs);
                $this->db->update('krs', [
                    'nilai_angka' => $nilai_angka,
                    'nilai_revisi' => $nilai_revisi
                ]);
            
                if ($this->db->affected_rows() == 0) {
                    log_message('error', "Gagal update nilai untuk ID KRS: $id_krs");
                } else {
                    log_message('debug', "BERHASIL update nilai untuk ID KRS: $id_krs");
                }
                     
            }

            echo json_encode(['status' => true, 'message' => 'Nilai berhasil disimpan.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Tidak ada data nilai yang dikirim.']);
        }
    }


}
