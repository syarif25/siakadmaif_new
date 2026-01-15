<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Detail_krs_model extends CI_Model {

    var $column_order = array(null,'jenjang','nama_kelas','semester','tahun_pelajaran','jumlah_mahasiswa','status_krs',null);
    var $column_search = array('jenjang','nama_kelas','semester','tahun_pelajaran','jumlah_mahasiswa','status_krs'); 
    var $order = array('k.id_kelas' => 'desc'); // atau kolom lain yang valid

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->select('
            m.nis,
            m.nama_mahasiswa,
            k.semester,
            kel.jenjang,
            kel.nama_kelas,
            t.tahun_akademik,
            GROUP_CONCAT(mk.nama_matakuliah SEPARATOR ", ") as matakuliah
        ');

        $this->db->from('krs k');
        $this->db->join('mahasiswa m', 'k.nis = m.nis');
        $this->db->join('matakuliah mk', 'k.id_matkul = mk.id_matakuliah');
        $this->db->join('kelas kel', 'k.id_kelas = kel.id_kelas');
        $this->db->join('tahun_akademik t', 'k.id_tahun = t.id_tahun');
        $this->db->group_by('m.nis');

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

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && $_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->db->from('krs k');
        $this->db->join('mahasiswa m', 'k.nis = m.nis');
        $this->db->join('matakuliah mk', 'k.id_matkul = mk.id_matkul');
        $this->db->join('kelas kel', 'k.id_kelas = kel.id_kelas');
        $this->db->join('tahun_akademik t', 'k.id_tahun = t.id_tahun');
        $this->db->where('k.status', 'Aktif');
        return $this->db->count_all_results();
    }
}

