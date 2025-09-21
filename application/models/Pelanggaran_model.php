<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggaran_model extends CI_Model {

    var $table = 'pelanggaran';
	var $column_order = array(null,'nis','nama_mahasiswa','jenjang','semester','pelanggaran','sanksi',null);
	var $column_search = array('nis','nama_mahasiswa','jenjang','semester','pelanggaran','sanksi'); 
	var $order = array('id_pelanggaran' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('pelanggaran');
		$this->db->join('mahasiswa', 'pelanggaran.nis = mahasiswa.nis');

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
		$this->db->update('pelanggaran', $data, $where);
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
		$this->db->from('pelanggaran');
		$this->db->join('mahasiswa', 'pelanggaran.nis = mahasiswa.nis');
        $this->db->where('id_pelanggaran',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_pelanggaran', $id);
		$this->db->delete('pelanggaran');
		return $this->db->affected_rows();
	}

	public function get_by_nis($nis)
    {
        $this->db->select('*');
		$this->db->from('mahasiswa');
		$this->db->join('distribusi_kelas','mahasiswa.nis = distribusi_kelas.nis');
		$this->db->join('kelas','distribusi_kelas.id_kelas = kelas.id_kelas');
        $this->db->where('mahasiswa.nis',$nis);
		$query = $this->db->get();

		return $query->row();
    }


}
