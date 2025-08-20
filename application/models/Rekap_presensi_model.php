<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_presensi_model extends CI_Model {

    var $table = 'rekap_presensi';
	var $column_order = array(null,'jenjang','nama_kelas',null);
	var $column_search = array('jenjang','nama_kelas'); 
	var $order = array('id_rekap' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('rekap_presensi.*, krs.id_kelas, kelas.nama_kelas, tahun_akademik.tahun_akademik, kelas.jenjang, tahun_akademik.semester');
		$this->db->from('rekap_presensi');
        $this->db->join('krs', 'krs.id_krs = rekap_presensi.id_krs');
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

    function get_rekap_presensi_fullcreen($id_kelas)
    {
        $this->db->select('rekap_presensi.*, krs.id_kelas, kelas.nama_kelas, tahun_akademik.tahun_akademik, kelas.jenjang, tahun_akademik.semester, matakuliah.nama_matakuliah,
         mahasiswa.nama_mahasiswa, mahasiswa.nis, rekap_presensi.jumlah_hadir, rekap_presensi.jumlah_alpha, rekap_presensi.jumlah_izin, rekap_presensi.jumlah_sakit, krs.id_matkul');
        $this->db->from('rekap_presensi');
        $this->db->join('krs', 'krs.id_krs = rekap_presensi.id_krs');
        $this->db->join('tahun_akademik', 'krs.id_tahun = tahun_akademik.id_tahun');
        $this->db->join('kelas', 'krs.id_kelas = kelas.id_kelas');
        $this->db->join('matakuliah', 'krs.id_matkul = matakuliah.id_matakuliah');
        $this->db->join('mahasiswa', 'krs.nis = mahasiswa.nis');
        $this->db->where('tahun_akademik.status', 'Aktif');
        $this->db->where('krs.id_kelas', $id_kelas);
        // $this->db->group_by('krs.nis');
        $query = $this->db->get();
        return $query->result();
    }

    function get_mapel_kelas($id_kelas) {
        $this->db->select('matakuliah.id_matakuliah, matakuliah.nama_matakuliah');
        $this->db->from('krs');
        $this->db->join('matakuliah', 'matakuliah.id_matakuliah = krs.id_matkul');
        $this->db->where('krs.id_kelas', $id_kelas);
        $this->db->group_by('krs.id_matkul');
        return $this->db->get()->result();
    }
    
	
	
}
