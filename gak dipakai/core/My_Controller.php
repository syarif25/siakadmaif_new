<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $auth_required = TRUE;
    protected $allowed_roles = [];
    protected $role_map = [];

    public function __construct()
    {
        parent::__construct();

        /**
         * =============================
         * 🔒 1. HEADER KEAMANAN DASAR
         * =============================
         */
        // Deteksi apakah controller ini perlu izin tambahan untuk CDN (mis. DataTables Buttons, SweetAlert, JSZip, dll)
        $relaxed = in_array(strtolower($this->router->class), [
            'matakuliah',       // butuh CDN jsDelivr, Cloudflare, dan unsafe-eval
            'tahun_akademik', // tambahkan di sini jika pakai DataTables export juga
            'mahasiswa',
        ]);

        // Base policy (ketat)
        $scriptSrc  = " 'self'";
        $scriptElem = " 'self'";
        $styleSrc   = " 'self' 'unsafe-inline'";
        $imgSrc     = " 'self' data: blob:";
        $fontSrc    = " 'self' data:";
        $connectSrc = " 'self'";

        if ($relaxed) {
            // Izinkan CDN umum & eval untuk JSZip/pdfmake/DataTables Buttons
            $cdn = " https://cdn.jsdelivr.net https://cdn.datatables.net https://cdnjs.cloudflare.com";
            $scriptSrc  .= $cdn . " 'unsafe-eval'";
            $scriptElem .= $cdn . " 'unsafe-eval'";
            $styleSrc   .= $cdn;
        }

        $csp = "default-src 'self'; "
             . "script-src{$scriptSrc}; "
             . "script-src-elem{$scriptElem}; "
             . "style-src{$styleSrc}; "
             . "img-src{$imgSrc}; "
             . "font-src{$fontSrc}; "
             . "connect-src{$connectSrc}; "
             . "object-src 'none'; "
             . "frame-ancestors 'none';";

        $this->output
            ->set_header('X-Frame-Options: DENY')
            ->set_header('X-Content-Type-Options: nosniff')
            ->set_header('Referrer-Policy: strict-origin-when-cross-origin')
            ->set_header("Content-Security-Policy: $csp");

        /**
         * =============================
         * 🔐 2. AUTENTIKASI & ROLE AKSES
         * =============================
         */
        if (!$this->auth_required) {
            return; // Controller publik
        }

        // 1) Wajib login
        if (!$this->session->userdata('logged_in')) {
            $this->deny_and_redirect('Silakan login terlebih dahulu.');
            return;
        }

        // 2) Ambil role user
        $user_role = strtolower((string)$this->session->userdata('role'));

        // 3) Cek pembatasan level controller (allowed_roles)
        if (!empty($this->allowed_roles) && !$this->role_in($user_role, $this->allowed_roles)) {
            $this->deny_and_redirect('Akses tidak diizinkan.');
            return;
        }

        // 4) Cek pembatasan level method (role_map)
        $method = $this->router->fetch_method();
        if (!empty($this->role_map) && isset($this->role_map[$method])) {
            $allowed = array_map('strtolower', (array)$this->role_map[$method]);
            if (!$this->role_in($user_role, $allowed)) {
                $this->deny_and_redirect('Akses tidak diizinkan.');
                return;
            }
        }
    }

    /**
     * Cek apakah role user ada dalam daftar yang diizinkan
     */
    protected function role_in($user_role, array $allowed): bool
    {
        return in_array(strtolower($user_role), array_map('strtolower', $allowed), true);
    }

    /**
     * Tampilkan pesan error dan redirect ke login
     */
    protected function deny_and_redirect($message = 'Akses tidak diizinkan.')
    {
        $this->session->set_flashdata('error', $message);
        redirect('login');
        exit;
    }

    /**
     * Validasi akses dinamis di dalam method
     * Contoh: $this->require_roles(['petugas','dosen']);
     */
    protected function require_roles(array $roles)
    {
        $user_role = strtolower((string)$this->session->userdata('role'));
        if (!$this->role_in($user_role, $roles)) {
            $this->deny_and_redirect('Akses tidak diizinkan.');
        }
    }
}

/*
 * ===================================
 * 📘 CONTOH PEMAKAIAN DI CONTROLLER
 * ===================================
 *
 * // Controller dengan multi-role global
 * protected $allowed_roles = ['petugas', 'dosen'];
 *
 * // Controller dengan aturan berbeda tiap method
 * protected $role_map = [
 *   'index'  => ['petugas','dosen','mahasiswa'],
 *   'create' => ['petugas'],
 *   'edit'   => ['petugas','dosen'],
 *   'hapus'  => ['petugas'],
 * ];
 *
 * // Controller publik
 * protected $auth_required = FALSE;
 */
