<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('format_tanggal_indonesia')) {
    function format_tanggal_indonesia($tanggal) {
        if (!$tanggal || $tanggal === '0000-00-00') return '-';

        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $parts = explode('-', $tanggal);
        if (count($parts) !== 3) return $tanggal;

        $tahun = $parts[0];
        $bulan_angka = (int)$parts[1];
        $hari = ltrim($parts[2], '0'); // Hilangkan 0 di depan

        return $hari . ' ' . $bulan[$bulan_angka] . ' ' . $tahun;
    }
}
