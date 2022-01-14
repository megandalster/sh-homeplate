<?php
require_once(dirname(__FILE__).'/Report.php');
require_once(dirname(__FILE__).'/pdf_mem_image.php');
require_once(dirname(__FILE__) . '/Traits/PdfHeaderTrait.php');

class PdfReport extends Report
{
    public $pdf = null;
    public $outputFile = null;

    use PdfHeaderTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);

        $this->setPdfHeaderReportDate($this->reportDateLabel);
        
        $this->pdf = new PDF_MemImage();
        $this->pdf->SetAutoPageBreak(true, 10);
    }
    
    function SetOutputFile($filename) {
        $this->outputFile = $filename;
    }

    function run() {
        if ($this->outputFile == null) {
            header('Content-disposition: attachment; filename="'.$this->filename.'"');
            header("Content-Type: application/pdf");
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        }
    }
    
    function output() {
        if ($this->outputFile == null)
            $this->pdf->Output('I',$this->filename.'.pdf');
        else
            $this->pdf->Output('F',$this->outputFile);
    }
    
    
}