<?php
require(dirname(__FILE__).'/Report.php');
require(dirname(__FILE__).'/pdf_mem_image.php');
require(dirname(__FILE__) . '/Traits/PdfHeaderTrait.php');

class PdfReport extends Report
{
    public $pdf = null;

    use PdfHeaderTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);

        $this->setPdfHeaderReportDate($this->reportDateLabel);
        
        $this->pdf = new PDF_MemImage();
        $this->pdf->SetAutoPageBreak(true, 10);
    }

    function run() {
        header('Content-disposition: attachment; filename="'.$this->filename.'"');
        header("Content-Type: application/pdf");
//    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    
    }
    
    function output() {
        $this->pdf->Output('I',$this->filename.'.pdf');
    }
    
    
}