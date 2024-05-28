<?php



namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService

{

    private $dompdf ;
    public function __construct() {

        $this->dompdf = new Dompdf();

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont','Garamond');
        $this->dompdf->setOptions($pdfOptions);
        

    }
    public function ShowPdfFille($html){

        {
            $this->dompdf->loadHtml($html);
            $this->dompdf->render();
            $this->dompdf->stream('detailPersonne.pdf', ['Attachment' => false]);
        }
    
    


    }
    public function genereteBinaryPDF($html){

        $this->dompdf->loadHtml( $html );
        $this->dompdf->render();
        $this->dompdf->output();

    }

}