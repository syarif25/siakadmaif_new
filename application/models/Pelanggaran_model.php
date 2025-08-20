<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggaran_model extends CI_Model {

    var $table = 'matakuliah';
	var $column_order = array(null,'nama_matakuliah','sks','jenjang','semester',null);
	var $column_search = array('nama_matakuliah','sks','jenjang','semester'); 
	var $order = array('id_matakuliah' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('matakuliah');
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
		$this->db->update('matakuliah', $data, $where);
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
		$this->db->from('matakuliah');
        $this->db->where('id_matakuliah',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_matakuliah', $id);
		$this->db->delete('matakuliah');
		return $this->db->affected_rows();
	}

	public function cek_kode($kode, $exclude_id = null)
    {
        $this->db->where('kode_matakuliah', $kode);
        if ($exclude_id !== null && $exclude_id !== '') {
            $this->db->where('id_matakuliah !=', $exclude_id);
        }
        return $this->db->get('matakuliah')->row();
    }


}
