<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_model extends CI_Model {

    var $table = 'mahasiswa';
	var $column_order = array('nis','nim','nama','jk','status',null);
	var $column_search = array('nis','nim','nama','jk','status'); 
	var $order = array('nis' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('mahasiswa');
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
		$this->db->update('mahasiswa', $data, $where);
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
		$this->db->from('mahasiswa');
        $this->db->where('nis',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('nis', $id);
		$this->db->delete('mahasiswa');
		return $this->db->affected_rows();
	}

	public function is_duplicate($field, $value, $exclude_id = null)
	{
		$this->db->where($field, $value);
		if ($exclude_id) {
			$this->db->where('nis !=', $exclude_id);
		}
		return $this->db->get('mahasiswa')->num_rows() > 0;
	}

	public function import_batch($data)
	{
		$this->db->insert_batch('mahasiswa', $data);
	}


}
