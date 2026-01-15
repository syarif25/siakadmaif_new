<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form', 'security']);
        // Cache driver untuk throttling sederhana
        $this->load->driver('cache', ['adapter' => 'file']);
    }

    public function index() {
        // Jika sudah login, arahkan ke dashboard sesuai role
        if ($this->session->userdata('logged_in') === TRUE) {
            return $this->_redirect_by_role($this->session->userdata('role'));
        }
        $this->load->view('Login');
    }

    public function auth() {
        if ($this->_is_rate_limited()) {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            return redirect('login');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|max_length[64]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[128]');

        if ($this->form_validation->run() === FALSE) {
            $this->_record_failed_attempt();
            $this->session->set_flashdata('error', 'Username atau password salah.');
            return redirect('login');
        }

        $identifier_raw = $this->input->post('username', TRUE); // XSS filter untuk string biasa
        $password       = $this->input->post('password');       // jangan xss_clean password
        $identifier     = strtolower(trim($identifier_raw));    // normalisasi

        $identity = $this->Login_model->find_identity($identifier);
        if (!$identity) {
            $this->_record_failed_attempt();
            usleep(250000); // delay kecil anti brute force
            $this->session->set_flashdata('error', 'Username atau password salah.');
            return redirect('login');
        }

        if (empty($identity->is_active)) {
            $this->_record_failed_attempt();
            $this->session->set_flashdata('error', 'Akun Anda tidak aktif.');
            return redirect('login');
        }

        if (!password_verify($password, $identity->password_hash)) {
            $this->_record_failed_attempt();
            usleep(250000);
            $this->session->set_flashdata('error', 'Username atau password salah.');
            return redirect('login');
        }

        //  Rehash jika perlu ----
        if (password_needs_rehash($identity->password_hash, PASSWORD_DEFAULT)) {
            $newhash = password_hash($password, PASSWORD_DEFAULT);
            $this->Login_model->update_password_hash($identity->id, $identity->role, $newhash);
        }

        // ---- 8) Regenerasi session id (anti session fixation) ----
        $this->session->sess_regenerate(TRUE);

        // ---- 9) Set session data minimal & konsisten ----
        $this->session->set_userdata([
            'user_id'      => $identity->id,
            'username'     => $identity->username,                 // normalized
            'display_name' => $identity->display_name ?: $identity->username,
            'role'         => strtolower($identity->role),         // petugas|mahasiswa|dosen
            'logged_in'    => TRUE,
        ]);

        // ---- 10) Audit login sukses & reset throttle ----
        $this->Login_model->update_last_login($identity->role, $identity->id, date('Y-m-d H:i:s'), $this->input->ip_address());
        $this->_clear_failed_attempts();

        // ---- 11) Sukses ----
        $this->session->set_flashdata('success', 'Login berhasil.');
        return $this->_redirect_by_role(strtolower($identity->role));
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->session->sess_regenerate(TRUE);
        $this->session->set_flashdata('success', 'Anda berhasil logout.');
        return redirect('login');
    }

    /* ----------------- Helpers ----------------- */

    private function _redirect_by_role($role) {
        switch (strtolower($role)) {
            case 'petugas':   return redirect('dashboard_admin');
            case 'mahasiswa': return redirect('dashboard_mahasiswa');
            case 'dosen':     return redirect('dashboard_dosen');
            default:          return redirect('login');
        }
    }

    // Key rate limit berbasis IP
    private function _rate_key() {
        return 'login_attempts_' . md5($this->input->ip_address());
    }

    private function _is_rate_limited() {
        $key = $this->_rate_key();
        $data = $this->cache->get($key);
        if (!$data) return FALSE;

        $max = 5;           // percobaan
        $window = 10 * 60;  // detik
        $attempts = array_filter($data, function($t) use ($window) {
            return $t >= (time() - $window);
        });
        return count($attempts) >= $max;
    }

    private function _record_failed_attempt() {
        $key = $this->_rate_key();
        $data = $this->cache->get($key) ?: [];
        $data[] = time();
        // simpan 10 menit
        $this->cache->save($key, $data, 10 * 60);

        // Audit opsional di DB (abaikan jika tabel login_attempts tidak ada)
        $this->Login_model->record_login_attempt('ip:' . $this->input->ip_address(), FALSE, [
            'ua' => $this->input->user_agent(),
        ]);
    }

    private function _clear_failed_attempts() {
        $this->cache->delete($this->_rate_key());
        // Audit login sukses opsional
        $this->Login_model->record_login_attempt('ip:' . $this->input->ip_address(), TRUE, [
            'ua' => $this->input->user_agent(),
        ]);
    }
}
