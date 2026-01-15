<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian_model extends CI_Model {

   var $column_order = array(null,'jenjang','nama_kelas','nama_matakuliah','sks','semester',null);
	var $column_search = array('jenjang','nama_kelas','nama_matakuliah','sks','semester'); 
	var $order = array('id_krs' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function _get_datatables_query()
	{
		$this->db->select("
            krs.id_kelas,
            krs.id_matkul,
            k.nama_kelas,
            k.semester,
            k.jenjang,
            mk.nama_matakuliah,
            t.tahun_akademik,
            COUNT(krs.id_krs) AS jumlah_mahasiswa,
            SUM(CASE WHEN krs.nilai_angka IS NOT NULL AND krs.nilai_angka != '' THEN 1 ELSE 0 END) AS jumlah_terisi,
            CASE 
                WHEN SUM(CASE WHEN krs.nilai_angka IS NOT NULL AND krs.nilai_angka != '' THEN 1 ELSE 0 END) = 0 THEN 'Belum'
                WHEN SUM(CASE WHEN krs.nilai_angka IS NOT NULL AND krs.nilai_angka != '' THEN 1 ELSE 0 END) < COUNT(krs.id_krs) THEN 'Sebagian'
                ELSE 'Sudah'
            END AS status_nilai
        ");
        $this->db->from('krs');
        $this->db->join('kelas k', 'k.id_kelas = krs.id_kelas');
        $this->db->join('matakuliah mk', 'mk.id_matakuliah = krs.id_matkul');
        $this->db->join('tahun_akademik t', 't.id_tahun = krs.id_tahun');
        $this->db->group_by(['krs.id_kelas', 'krs.id_matkul']);
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
	


}
