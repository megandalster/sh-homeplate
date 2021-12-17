<?php

class Report
{
    public $reportDate;
    public $reportDateLabel;
    
    function __construct($reportDate=null) {
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