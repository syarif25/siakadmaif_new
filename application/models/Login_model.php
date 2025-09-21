<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_user($username) {
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get('pengguna');
        return $query->row(); 
    }

    public function get_mahasiswa($nis) {
        $this->db->where('nis', $nis);
        $this->db->limit(1);
        $query = $this->db->get('mahasiswa');
        return $query->row();  
    }

    public function get_dosen($no_hp) {
        $this->db->where('nomor_hp', $no_hp);
        $this->db->limit(1);
        $query = $this->db->get('dosen');
        return $query->row();  
    }
}
