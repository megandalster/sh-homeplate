<?php


require(dirname(__FILE__).'/XlsxRptR16.php');


class XlsxRptR17 extends XlsxRptR16
{
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->report_id = 'R17';
        $this->area = 'Bluffton';
        $this->outputFile = 'R17-RESCUEDDIST-'.strtoupper($this->area).'-'.$this->reportDateLabel;
    }
    
}