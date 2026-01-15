    // Add new method to get room schedule
    public function get_jadwal_ruangan()
    {
        $id_kelas = $this->input->post('id_kelas');
        $hari = $this->input->post('hari');
        $id_distribusi = $this->input->post('id_distribusi'); // untuk exclude saat edit
        
        $this->db->select('distribusi_mk.*, matakuliah.nama_matakuliah, dosen.nama_dosen');
        $this->db->from('distribusi_mk');
        $this->db->join('matakuliah', 'distribusi_mk.id_mk = matakuliah.id_matakuliah');
        $this->db->join('dosen', 'distribusi_mk.id_dosen = dosen.id_dosen');
        $this->db->where('distribusi_mk.id_kelas', $id_kelas);
        $this->db->where('distribusi_mk.hari', $hari);
        
        if ($id_distribusi) {
            $this->db->where('distribusi_mk.id_distribusi !=', $id_distribusi);
        }
        
        $this->db->order_by('distribusi_mk.jam_mulai', 'ASC');
        $jadwal = $this->db->get()->result();
        
        echo json_encode(['status' => true, 'jadwal' => $jadwal]);
    }
    
    // Add new method to get teacher schedule
    public function get_jadwal_dosen()
    {
        $id_dosen = $this->input->post('id_dosen');
        $hari = $this->input->post('hari');
        $id_distribusi = $this->input->post('id_distribusi'); // untuk exclude saat edit
        
        $this->db->select('distribusi_mk.*, matakuliah.nama_matakuliah, kelas.nama_kelas');
        $this->db->from('distribusi_mk');
        $this->db->join('matakuliah', 'distribusi_mk.id_mk = matakuliah.id_matakuliah');
        $this->db->join('kelas', 'distribusi_mk.id_kelas = kelas.id_kelas');
        $this->db->where('distribusi_mk.id_dosen', $id_dosen);
        $this->db->where('distribusi_mk.hari', $hari);
        
        if ($id_distribusi) {
            $this->db->where('distribusi_mk.id_distribusi !=', $id_distribusi);
        }
        
        $this->db->order_by('distribusi_mk.jam_mulai', 'ASC');
        $jadwal = $this->db->get()->result();
        
        echo json_encode(['status' => true, 'jadwal' => $jadwal]);
    }
