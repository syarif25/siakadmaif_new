<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_perdosen_model extends CI_Model {

    var $table = 'dosen';
	var $column_order = array(null,'nama_dosen','jk','bidang_keahlian',null);
	var $column_search = array('nama_dosen','jk','bidang_keahlian'); 
	var $order = array('dosen.id_dosen' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('dosen.id_dosen, dosen.nama_dosen, dosen.jk, dosen.bidang_keahlian');
		$this->db->from('distribusi_mk');
		$this->db->join('tahun_akademik', 'distribusi_mk.id_tahun = tahun_akademik.id_tahun');
        $this->db->join('dosen', 'distribusi_mk.id_dosen = dosen.id_dosen');
        $this->db->where('tahun_akademik.status','Aktif');
        $this->db->group_by('dosen.id_dosen');

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

    function get_jadwal_by_dosen($id_dosen)
    {
        $this->db->select('
            mk.nama_matakuliah,
            mk.jenjang,
            kelas.nama_kelas,
            distribusi_mk.hari,
            distribusi_mk.jam_mulai,
            distribusi_mk.jam_selesai,
            tahun_akademik.tahun_akademik
        ');
        $this->db->from('distribusi_mk');
        $this->db->join('matakuliah mk', 'distribusi_mk.id_mk = mk.id_matakuliah');
        $this->db->join('kelas', 'distribusi_mk.id_kelas = kelas.id_kelas');
        $this->db->join('tahun_akademik', 'distribusi_mk.id_tahun = tahun_akademik.id_tahun');
        $this->db->where('distribusi_mk.id_dosen', $id_dosen);
        $this->db->where('tahun_akademik.status', 'Aktif');
        $this->db->order_by('distribusi_mk.hari, distribusi_mk.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

	

}
