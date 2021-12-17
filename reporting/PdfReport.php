<?php
require(dirname(__FILE__).'/Report.php');
require(dirname(__FILE__).'/pdf_mem_image.php');
require(dirname(__FILE__).'/PdfHeaderTrait.php');

class PdfReport extends Report
{
    public $pdf = null;

    use PdfHeaderTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);

        $this->setPdfHeaderReportDate($this->reportDateLabel);
        
        $this->pdf = new PDF_MemImage();
    }

    function run() {
    
    }
    
    function output() {
        $this->pdf->Output();
    }
    
    
}