<?php


require(dirname(__FILE__).'/XlsxRptR16.php');


class XlsxRptR18 extends XlsxRptR16
{
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->report_id = 'R18';
        $this->area = 'Hilton Head';
        $this->outputFile = 'R18-RESCUEDDIST-'.strtoupper($this->area).'-'.$this->reportDateLabel;
    }
}