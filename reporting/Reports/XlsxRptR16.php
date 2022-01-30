<?php


require(dirname(__FILE__).'/../XlsxReport.php');
require(dirname(__FILE__).'/../Traits/R2DataTrait.php');


class XlsxRptR16 extends XlsxReport
{
    use R2DataTrait;
    
    private $ytd = false;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R2 - DONOR & RECIPIENT RANK REPORT';
        $this->outputFile = 'R16-RESCUEAVE-'.$this->reportDateLabel;
    }
    
    function run() {
        parent::run();
    
        $this->writeXlsxHeader($this);
//        $data = $this->data($this->reportDate, $this->ytd);

        $this->output();
    }
    
    public function writeRow($rowdata,$rowtype) {
        $this->row++;
    }
    
}