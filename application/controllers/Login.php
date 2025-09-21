<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->library('session');
        $this->load->helper(array('url', 'form'));
    }

    public function index() {
        // Jika sudah login sebagai petugas
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'petugas') {
            redirect('Dashboard_admin');
        }
    
        // Jika sudah login sebagai mahasiswa
        if ($this->session->userdata('logged_in') && $this->session->userdata('role') == 'mahasiswa') {
            redirect('Dashboard_mahasiswa');
        }
    
        // Jika belum login
        $this->load->view('Login');
    }
    

	public function auth() {
        $username = trim($this->input->post('username')); // buang spasi
        $password = $this->input->post('password');
    
        // Cek di tabel pengguna (Petugas)
        $user = $this->Login_model->get_user($username);
    
        if ($user) {
            if (password_verify($password, $user->password)) {
                $session_data = array(
                    'username' => $user->username,
                    'id'       => $user->id_pengguna,
                    'role'     => 'Petugas',
                    'logged_in'=> TRUE
                );
                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Login berhasil!');
                redirect('Dashboard_admin');
            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('login');
            }
        } else {
            // Kalau tidak ketemu di pengguna, cek mahasiswa
            $mahasiswa = $this->Login_model->get_mahasiswa($username);
            if ($mahasiswa) {
                if (password_verify($password, $mahasiswa->password)) {
                    $session_data = array(
                        'username' => $mahasiswa->nama_mahasiswa,
                        'id'       => $mahasiswa->nis,
                        'role'     => 'Mahasiswa',
                        'logged_in'=> TRUE
                    );
                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('success', 'Login mahasiswa berhasil!');
                    redirect('Dashboard_mahasiswa');
                } else {
                    $this->session->set_flashdata('error', 'Password salah!');
                    redirect('login');
                }
            } else {
                // Kalau tidak ketemu di mahasiswa, cek dosen
                $dosen = $this->Login_model->get_dosen($username);
                if ($dosen) {
                    if (password_verify($password, $dosen->password)) {
                        $session_data = array(
                            'username' => $dosen->nama_dosen,
                            'id'       => $dosen->id_dosen, // misalnya pakai NIDN sebagai id
                            'role'     => 'Dosen',
                            'logged_in'=> TRUE
                        );
                        $this->session->set_userdata($session_data);
                        $this->session->set_flashdata('success', 'Login dosen berhasil!');
                        redirect('Dashboard_dosen');
                    } else {
                        $this->session->set_flashdata('error', 'Password salah!');
                        redirect('login');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Username tidak terdaftar!');
                    redirect('login');
                }
            }
        }
    }
    

    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Anda berhasil logout');
        redirect('login');
    }

}
