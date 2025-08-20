<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

class Testpdf extends CI_Controller {
    public function index() {
        require_once APPPATH . '../vendor/autoload.php'; // Panggil Composer autoload

        $dompdf = new Dompdf();
        $dompdf->loadHtml('<h1>Hello PDF</h1>');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('contoh.pdf', ['Attachment' => false]);
    }
}
