<?php
require dirname(__FILE__).'/../vendor/autoload.php';
require_once(dirname(__FILE__).'/Report.php');
require_once(dirname(__FILE__).'/Traits/XlsxHeaderTrait.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        ob_end_clean();
        $writer->save('php://output');
        die();
    }
}

