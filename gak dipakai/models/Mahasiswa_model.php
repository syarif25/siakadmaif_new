<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_model extends CI_Model
{
    protected $table = 'mahasiswa';

    // Urutan kolom untuk DataTables (pastikan sesuai dengan kolom yang ada di DB)
    protected $column_order  = array('nis','nim','nama_mahasiswa','jk','status', null);
    protected $column_search = array('nis','nim','nama_mahasiswa','jk','status');
    protected $order         = array('nis' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ==========================
     * Query DataTables (order whitelist)
     * ========================== */
    protected function _get_datatables_query()
    {
        $this->db->select('*')->from($this->table);

        // Order (whitelist ASC/DESC, map kolom aman)
        if (isset($_POST['order'])) {
            $colIdx = (int) ($_POST['order'][0]['column'] ?? 0);
            $dir    = strtolower($_POST['order'][0]['dir'] ?? 'asc');
            $dir    = in_array($dir, ['asc','desc'], true) ? $dir : 'asc';

            $column = $this->column_order[$colIdx] ?? key($this->order);
            if ($column) {
                $this->db->order_by($column, $dir);
            }
        } else {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // (Opsional) Jika suatu saat ingin aktifkan pencarian server-side:
        // if (!empty($_POST['search']['value'])) {
        //     $search = $_POST['search']['value'];
        //     $this->db->group_start();
        //     foreach ($this->column_search as $i => $item) {
        //         if ($i === 0) $this->db->like($item, $search);
        //         else $this->db->or_like($item, $search);
        //     }
        //     $this->db->group_end();
        // }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->result();
    }

    /* ==========================
     * CRUD Utilities
     * ========================== */
    public function create($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('nis', $id)
            ->get()
            ->row();
    }

    public function delete($id)
    {
        $this->db->where('nis', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    /* ==========================
     * Validasi/Helper Duplikasi
     * ========================== */
    public function is_duplicate($field, $value, $exclude_id = null)
    {
        $this->db->where($field, $value);
        if (!empty($exclude_id)) {
            $this->db->where('nis !=', $exclude_id);
        }
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function exists($nis, $nim)
    {
        return $this->db->where('nis', $nis)
                        ->or_where('nim', $nim)
                        ->get($this->table)
                        ->num_rows() > 0;
    }

    /* ==========================
     * Import Batch
     * ========================== */
    public function import_batch($data)
    {
        if (!empty($data)) {
            $this->db->insert_batch($this->table, $data);
        }
    }
}
