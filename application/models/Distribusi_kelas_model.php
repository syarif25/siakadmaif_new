<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_kelas_model extends CI_Model {

    var $table = 'kelas';
	var $column_order = array(null,'nama_kelas','semester','kategori','jenjang',null);
	var $column_search = array('nama_kelas','semester','kategori','jenjang'); 
	var $order = array('id_distribusi_kelas' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*, kelas.id_kelas as id_kl, kelas.semester as semester_kelas');
		$this->db->from('kelas');
		$this->db->join('tahun_akademik', 'kelas.id_tahun = tahun_akademik.id_tahun');
        $this->db->join('distribusi_kelas', 'distribusi_kelas.id_kelas = kelas.id_kelas');
        $this->db->join('mahasiswa', 'distribusi_kelas.nis = mahasiswa.nis');

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
	
	public function update($where, $data)
	{
		$this->db->update('kelas', $data, $where);
		return $this->db->affected_rows();
	}

	public function create($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

	public function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('distribusi_kelas');
		$this->db->join('mahasiswa', 'distribusi_kelas.nis = mahasiswa.nis');
        $this->db->where('id_distribusi_kelas',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_kelas', $id);
		$this->db->delete('kelas');
		return $this->db->affected_rows();
	}


}
