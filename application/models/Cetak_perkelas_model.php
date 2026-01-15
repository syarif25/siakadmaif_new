<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetak_perkelas_model extends CI_Model {

    var $table = 'kelas';
	var $column_order = array(null,'nama_kelas','semester','kategori','jenjang',null);
	var $column_search = array('nama_kelas','semester','kategori','jenjang'); 
	var $order = array('kelas.id_kelas' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('kelas.id_kelas, kelas.nama_kelas, kelas.jenjang, kelas.kategori');
		$this->db->from('distribusi_mk');
		$this->db->join('tahun_akademik', 'distribusi_mk.id_tahun = tahun_akademik.id_tahun');
        $this->db->join('kelas', 'distribusi_mk.id_kelas = kelas.id_kelas');
        $this->db->where('tahun_akademik.status','Aktif');
        $this->db->group_by(array('kelas.id_kelas', 'kelas.nama_kelas', 'kelas.jenjang', 'kelas.kategori'));

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

    function get_jadwal_by_kelas($id_kelas)
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
        $this->db->where('distribusi_mk.id_kelas', $id_kelas);
        $this->db->where('tahun_akademik.status', 'Aktif');
        $this->db->order_by('distribusi_mk.hari, distribusi_mk.jam_mulai', 'ASC');
        
        return $this->db->get()->result();
    }

    

	

}
