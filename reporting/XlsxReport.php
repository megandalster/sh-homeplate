<?php
require dirname(__FILE__).'/../vendor/autoload.php';
require_once(dirname(__FILE__).'/Report.php');
require_once(dirname(__FILE__).'/Traits/XlsxHeaderTrait.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//use PhpOffice\PhpSpreadsheet\IOFactory;

class XlsxReport extends Report
{
    public $outputFile = null;
    public $spreadsheet;
    public $header_width = 7;
    
    use XlsxHeaderTrait;

    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        
        setlocale(LC_ALL, 'en_US');
//        ob_start();

        $this->spreadsheet = new Spreadsheet();
        $this->setXlsxHeaderReportDate($this->reportDateLabel);
    
    }
    
    function SetOutputFile($filename) {
        $this->outputFile = $filename;
    }
    
    function cellName($c,$r) {
        return substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ',$c-1,1) . $r;
    }
    
    function run() {
        if ($this->outputFile != null) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$this->outputFile.'.xlsx"');
            header('Cache-Control: max-age=0');
            header('Content-Transfer-Encoding: binary');
            header('Pragma: public');
        }
    }
    
    function output() {
    
        $writer = new Xlsx($this->spreadsheet);
//        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');

    
//        header('Content-Type: application/vnd.ms-excel');
////        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="'.$this->outputFile.'.xlsx"');
//        header('Cache-Control: max-age=0');
//        header('Content-Transfer-Encoding: binary');
//        header('Pragma: public');
    
        ob_end_clean();
        $writer->save('php://output');
        die();
    }
}

//require(dirname(__FILE__).'/xlsxwriter/xlsxwriter.class.php');
//
//class XlsxReport extends Report
//{
//    public $writer = null;
//    public $sheetName = 'Sheet1';
//    public $row = 0;
//    public $num_cols = 7;
//
//
//    use XlsxHeaderTrait;
//
//    function __construct($reportDate=null) {
//        parent::__construct($reportDate);
//
//        $filename = "report.xlsx";
//        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
//        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
//        header('Content-Transfer-Encoding: binary');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//
//        $this->writer = new XLSXWriter();
//
//        $this->setXlsxHeaderReportDate($this->reportDateLabel);
//    }
//
//    function run() {
//
//    }
//
//    function output() {
//        ob_clean();
//
//        $this->writer->writeToStdOut();
//    }
//
//
//}