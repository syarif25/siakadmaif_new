<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }


    public function find_identity($identifier) {
        $idnorm = strtolower(trim($identifier));
        $hits = [];

        // 1) Petugas: username
        $u = $this->db->select('
                p.id_pengguna AS id,
                LOWER(p.username) AS username,
                p.username AS display_name,
                p.password AS password_hash,
                IFNULL(p.is_active, 1) AS is_active,
                "petugas" AS role
            ', FALSE)
            ->from('pengguna p')
            ->where('LOWER(p.username)', $idnorm)
            ->limit(1)->get()->row();
        if ($u) $hits[] = $u;

        // 2) Mahasiswa: nis (gunakan status untuk fallback is_active bila null)
        $m = $this->db->select('
                m.nis AS id,
                LOWER(m.nis) AS username,
                m.nama_mahasiswa AS display_name,
                m.password AS password_hash,
                IFNULL(m.is_active, (CASE WHEN LOWER(m.status) = "aktif" THEN 1 ELSE 0 END)) AS is_active,
                "mahasiswa" AS role
            ', FALSE)
            ->from('mahasiswa m')
            ->where('LOWER(m.nis)', $idnorm)
            ->limit(1)->get()->row();
        if ($m) $hits[] = $m;

        // 3) Dosen: nomor_hp (fallback dari status_kepegawaian)
        $d = $this->db->select('
                d.id_dosen AS id,
                LOWER(d.nomor_hp) AS username,
                d.nama_dosen AS display_name,
                d.password AS password_hash,
                IFNULL(d.is_active, (CASE WHEN LOWER(d.status_kepegawaian) = "aktif" THEN 1 ELSE 0 END)) AS is_active,
                "dosen" AS role
            ', FALSE)
            ->from('dosen d')
            ->where('LOWER(d.nomor_hp)', $idnorm)
            ->limit(1)->get()->row();
        if ($d) $hits[] = $d;

        // Jika lebih dari satu tabel match → konflik → fail closed
        if (count($hits) > 1) {
            log_message('error', 'Login identifier conflict for "'.$identifier.'" across multiple tables.');
            return null;
        }

        return $hits[0] ?? null;
    }

    /**
     * Update hash password setelah password_needs_rehash() terpenuhi.
     */
    public function update_password_hash($id, $role, $newhash) {
        switch (strtolower($role)) {
            case 'petugas':
                $this->db->where('id_pengguna', $id)->update('pengguna', ['password' => $newhash]);
                break;
            case 'mahasiswa':
                $this->db->where('nis', $id)->update('mahasiswa', ['password' => $newhash]);
                break;
            case 'dosen':
                $this->db->where('id_dosen', $id)->update('dosen', ['password' => $newhash]);
                break;
            default:
                return FALSE;
        }
        return $this->db->affected_rows() > 0;
    }

    /**
     * Update audit login terakhir (waktu & IP).
     */
    public function update_last_login($role, $id, $datetime, $ip) {
        $data = ['last_login_at' => $datetime, 'last_login_ip' => $ip];
        switch (strtolower($role)) {
            case 'petugas':
                $this->db->where('id_pengguna', $id)->update('pengguna', $data);
                break;
            case 'mahasiswa':
                $this->db->where('nis', $id)->update('mahasiswa', $data);
                break;
            case 'dosen':
                $this->db->where('id_dosen', $id)->update('dosen', $data);
                break;
        }
    }


    public function record_login_attempt($key, $success, $meta = []) {
        if (!$this->db->table_exists('login_attempts')) return;
        $data = [
            'key'        => substr($key, 0, 191),
            'success'    => $success ? 1 : 0,
            'metadata'   => json_encode($meta),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('login_attempts', $data);
    }
}
