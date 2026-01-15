<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    var $table = 'kelas';
	var $column_order = array(null,'tahun_akademik.tahun_akademik','nama_kelas','semester','kategori','jenjang','status',null);
	var $column_search = array('tahun_akademik.tahun_akademik','nama_kelas','semester','kategori','jenjang','status'); 
	var $order = array('id_kelas' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*, kelas.semester as smt, kelas.status as sts');
		$this->db->from('kelas');
		$this->db->join('tahun_akademik', 'kelas.id_tahun = tahun_akademik.id_tahun');
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
		$this->db->from('kelas');
        $this->db->where('id_kelas',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_kelas', $id);
		$this->db->delete('kelas');
		return $this->db->affected_rows();
	}

	public function cek_duplikat_kelas($nama_kelas, $id_tahun, $id_kelas = null)
	{
		$this->db->where('nama_kelas', $nama_kelas);
		$this->db->where('id_tahun', $id_tahun);
		if ($id_kelas !== null) {
			$this->db->where('id_kelas !=', $id_kelas);
		}
		return $this->db->get('kelas')->row();
	}



}
