<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen_model extends CI_Model {

    var $table = 'dosen';
	var $column_order = array(null,'nik','nama_dosen','jk','bidang_keahlian','nomor_hp',null);
	var $column_search = array('nik','nama_dosen','jk','bidang_keahlian','nomor_hp'); 
	var $order = array('id_dosen' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('dosen');
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
		$this->db->update('dosen', $data, $where);
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
		$this->db->from('dosen');
        $this->db->where('id_dosen',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_dosen', $id);
		$this->db->delete('dosen');
		return $this->db->affected_rows();
	}

	public function is_duplicate($field, $value, $exclude_id = null)
	{
		$this->db->where($field, $value);
		if ($exclude_id) {
			$this->db->where('id_dosen !=', $exclude_id);
		}
		return $this->db->get('dosen')->num_rows() > 0;
	}

	public function import_batch($data)
	{
		$this->db->insert_batch('dosen', $data);
	}

	public function exists($nis, $nim) {
		return $this->db->where('nik', $nis)
						->get('dosen')
						->num_rows() > 0;
	}

}
