<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi_model extends CI_Model {

    var $table = 'distribusi_mk';
	var $column_order = array(null,'jenjang','nama_kelas','nama_matakuliah','sks',null);
	var $column_search = array('jenjang','nama_kelas','nama_matakuliah','sks'); 
	var $order = array('id_distribusi' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select('*');
		$this->db->from('distribusi_mk');
		$this->db->join('tahun_akademik', 'distribusi_mk.id_tahun = tahun_akademik.id_tahun');
        $this->db->join('matakuliah', 'distribusi_mk.id_mk = matakuliah.id_matakuliah');
        $this->db->join('dosen', 'distribusi_mk.id_dosen = dosen.id_dosen');
        $this->db->join('kelas', 'distribusi_mk.id_kelas = kelas.id_kelas');
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
		$this->db->update('distribusi_mk', $data, $where);
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
		$this->db->from('distribusi_mk');
        $this->db->where('id_distribusi',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function delete($id)
	{
		$this->db->where('id_distribusi', $id);
		$this->db->delete('distribusi_mk');
		return $this->db->affected_rows();
	}


// Mendapatkan tahun akademik aktif (asumsi ada kolom 'status' = 'aktif')
public function getActiveTahunAkademik()
{
    $query = $this->db->get_where('tahun_akademik', ['status' => 'aktif']);
    return $query->row();
}

public function get_detail_distribusi($id_distribusi)
{
    $this->db->select('d.*, 
        mk.nama_matakuliah, 
        k.nama_kelas, 
        k.semester, 
        ta.tahun_akademik, 
        ta.semester as semester_akademik, 
        u.nama_dosen as nama_dosen');
    $this->db->from('distribusi_mk d');
    $this->db->join('matakuliah mk', 'mk.id_matakuliah = d.id_mk');
    $this->db->join('kelas k', 'k.id_kelas = d.id_kelas');
    $this->db->join('tahun_akademik ta', 'ta.id_tahun = d.id_tahun');
    $this->db->join('dosen u', 'u.id_dosen = d.id_dosen');
    $this->db->where('d.id_distribusi', $id_distribusi);
    return $this->db->get()->row();
}


public function get_mahasiswa_by_distribusi($id_distribusi)
{
    $this->db->select('m.nis, m.nama_mahasiswa, k.nama_kelas');
    $this->db->from('distribusi_mk dm');
    $this->db->join('kelas k', 'dm.id_kelas = k.id_kelas');
    $this->db->join('distribusi_kelas dk', 'k.id_kelas = dk.id_kelas');
    $this->db->join('mahasiswa m', 'dk.nis = m.nis');
    $this->db->where('dm.id_distribusi', $id_distribusi);
    $this->db->order_by('m.nama_mahasiswa', 'ASC');

    return $this->db->get()->result();
}



}
