<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_nilai_model extends CI_Model {

    var $table = 'rekap_nilai';
	var $column_order = array(null,'jenjang','nama_kelas',null);
	var $column_search = array('jenjang','nama_kelas'); 
	var $order = array('id_krs' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('krs.*, kelas.nama_kelas, tahun_akademik.tahun_akademik, kelas.jenjang, tahun_akademik.semester');
		$this->db->from('krs');
        $this->db->join('tahun_akademik', 'krs.id_tahun = tahun_akademik.id_tahun');
        $this->db->join('kelas', 'krs.id_kelas = kelas.id_kelas');
        $this->db->where('tahun_akademik.status', 'Aktif');
        $this->db->group_by('krs.id_kelas');
		$i = 0;
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->result();
	}

    public function get_rekap_nilai_fullcreen($id_kelas)
    {
        $this->db->select('mahasiswa.nis, mahasiswa.nama_mahasiswa, krs.id_kelas, tahun_akademik.semester, tahun_akademik.tahun_akademik, kelas.nama_kelas');
        $this->db->from('krs');
        $this->db->join('mahasiswa', 'mahasiswa.nis = krs.nis');
        $this->db->join('kelas', 'kelas.id_kelas = krs.id_kelas');
        $this->db->join('tahun_akademik', 'tahun_akademik.id_tahun = krs.id_tahun');
        $this->db->where('krs.id_kelas', $id_kelas);
        $this->db->where('tahun_akademik.status', 'Aktif');
        $this->db->group_by('krs.nis');
        return $this->db->get()->result();
    }

    // Ambil daftar mata kuliah yang diampu kelas ini
    public function get_mapel_kelas($id_kelas)
    {
        $this->db->select('matakuliah.id_matakuliah, matakuliah.nama_matakuliah');
        $this->db->from('krs');
        $this->db->join('matakuliah', 'matakuliah.id_matakuliah = krs.id_matkul');
        $this->db->where('krs.id_kelas', $id_kelas);
        $this->db->group_by('krs.id_matkul');
        return $this->db->get()->result();
    }

    // Ambil nilai angka mahasiswa untuk matkul tertentu
    public function get_nilai_mahasiswa($nis, $id_matakuliah)
    {
        $this->db->select('nilai_angka,nilai_revisi');
        $this->db->from('krs');
        $this->db->where('nis', $nis);
        $this->db->where('id_matkul', $id_matakuliah);
        $query = $this->db->get();

        return $query->row(); // return satu baris (nilai_angka)
    }

    
	
	
}
