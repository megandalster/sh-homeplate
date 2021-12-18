<?php
require(dirname(__FILE__).'/Report.php');
require(dirname(__FILE__).'/xlsxwriter/xlsxwriter.class.php');
require(dirname(__FILE__).'/Traits/XlsxHeaderTrait.php');

class XlsxReport extends Report
{
    public $writer = null;
    public $sheetName = 'Sheet1';
    public $row = 0;
    public $num_cols = 7;
    

    use XlsxHeaderTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
    
        $filename = "report.xlsx";
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    
        $this->writer = new XLSXWriter();
        
        $this->setXlsxHeaderReportDate($this->reportDateLabel);
    }

    function run() {
    
    }
    
    function output() {
        ob_clean();
    
        $this->writer->writeToStdOut();
    }
    
    
}