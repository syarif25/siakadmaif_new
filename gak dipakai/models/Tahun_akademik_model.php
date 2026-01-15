<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_akademik_model extends CI_Model
{
    private $table = 'tahun_akademik';

    // Datatables basic (kamu bisa tambahkan pencarian bila perlu)
    private $column_order = ['tahun_akademik','tanggal_mulai','tanggal_selesai', null];
    private $order        = ['tahun_akademik' => 'desc'];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        // Order
        if (isset($_POST['order'])) {
            $col = (int) $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'] === 'asc' ? 'asc' : 'desc';
            // amankan index kolom
            if (isset($this->column_order[$col]) && $this->column_order[$col] !== null) {
                $this->db->order_by($this->column_order[$col], $dir);
            }
        } else {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_tahun' => $id])->row();
    }

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);
        return (bool) $this->db->insert_id();
    }

    public function update(array $where, array $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id_tahun', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Set 1 tahun_akademik menjadi "Aktif" secara atomik:
     * - Semua baris jadi "Tidak Aktif"
     * - Baris dengan id tertentu jadi "Aktif"
     */
    public function set_active($id)
    {
        $this->db->trans_start();

        // Nonaktifkan semua
        $this->db->set('status', 'Tidak Aktif')->update($this->table);

        // Aktifkan satu
        $this->db->where('id_tahun', $id)->set('status', 'Aktif')->update($this->table);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
