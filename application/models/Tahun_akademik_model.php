<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_akademik_model extends CI_Model {

    var $table = 'tahun_akademik';
	var $column_order = array('tahun_akademik','tanggal_mulai','tanggal_selesai',null);
	var $column_search = array('tahun_akademik','tanggal_mulai','tanggal_selesai'); 
	var $order = array('tahun_akademik' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('tahun_akademik');
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
		$this->db->update('tahun_akademik', $data, $where);
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
		$this->db->from('tahun_akademik');
        $this->db->where('id_tahun',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_tahun', $id);
		$this->db->delete('tahun_akademik');
		return $this->db->affected_rows();
	}


}
