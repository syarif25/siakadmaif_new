<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generate_krs_model extends CI_Model {

    var $column_order = array(null,'jenjang','nama_kelas','semester','tahun_pelajaran','jumlah_mahasiswa','status_krs',null);
    var $column_search = array('jenjang','nama_kelas','semester','tahun_pelajaran','jumlah_mahasiswa','status_krs'); 
    var $order = array('k.id_kelas' => 'desc'); // atau kolom lain yang valid

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($tahun_pelajaran)
    {
        $this->db->select('
            k.id_kelas,
            k.nama_kelas,
            k.semester,
            k.jenjang,
            k.status,
            t.tahun_akademik,
             COUNT(dk.nis) AS jumlah_mahasiswa,
            (
                SELECT SUM(mk.sks)
                FROM distribusi_mk dm
                JOIN matakuliah mk ON dm.id_mk = mk.id_matakuliah
                WHERE dm.id_kelas = k.id_kelas
            ) AS total_sks,
            COUNT(CASE WHEN dk.status_keanggotaan = "Aktif" THEN dk.nis END) AS jumlah_mahasiswa,
            (
                SELECT COUNT(*) FROM distribusi_mk dm
                WHERE dm.id_kelas = k.id_kelas
            ) AS jumlah_matakuliah,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM krs 
                    WHERE krs.id_kelas = k.id_kelas 
                      AND krs.semester = k.semester 
                      AND krs.id_tahun = t.id_tahun
                    LIMIT 1
                ) THEN "Sudah"
                ELSE "Belum"
            END AS status_krs
        ');
        $this->db->from('distribusi_kelas dk');
        $this->db->join('tahun_akademik t', 'dk.id_tahun = t.id_tahun');
        $this->db->join('kelas k', 'k.id_kelas = dk.id_kelas', 'left');
        $this->db->group_by('k.id_kelas');

        // Urutan kolom jika di DataTables
        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables($tahun_pelajaran)
    {
        $this->_get_datatables_query($tahun_pelajaran);
        if (isset($_POST['length']) && $_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered($tahun_pelajaran)
    {
        $this->_get_datatables_query($tahun_pelajaran);
        return $this->db->get()->num_rows();
    }

    public function count_all($tahun_pelajaran)
    {
        $this->db->from('kelas k');
        $this->db->join('tahun_pelajaran t', 'k.id_tahun = t.id_tahun');
        $this->db->where('k.status', 'Aktif');
        $this->db->where('t.tahun_pelajaran', $tahun_pelajaran);
        return $this->db->count_all_results();
    }
}

