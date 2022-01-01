<?php

class Report
{
    public $reportDate;
    public $reportDateLabel;
    public $filename;
    
    public $months;
    
    function __construct($reportDate=null) {
        $this->months = array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
    
        switch (gettype($reportDate)) {
            case 'object':
                $this->reportDate = $reportDate;
                break;
            case 'string':
                $this->reportDate = new DateTime($reportDate);
                break;
            default:
                $this->reportDate = new DateTime();
        }
        $this->reportDateLabel = $this->reportDate->format('M-y');
    }
    
    function run() {
    
    }
    
    
}