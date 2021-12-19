<?php

trait XlsxHeaderTrait {
    public $header = array(
        'company' => 'SECOND HELPINGS',
        'reportName' => 'R8',
        'reportDate' => 'Jan-21',
        'confidential' => 'CONFIDENTIAL',
    );
    
    public function setXlsxHeaderReportDate($label) {
        $this->header['reportDate'] = $label;
    }
    
    public function writeXlsxHeader($report) {
        $report->writer->writeSheetRow(
            $report->sheetName,
            [$this->header['company']],
            [
                'font'=>'Arial',
                'font-size'=>12,
                'font-style'=>'bold',
                'halign'=>'center'
            ]);
        $report->writer->markMergedCell(
            $report->sheetName,
            $start_row=$report->row,
            $start_col=0,
            $end_row=$report->row,
            $end_col=$report->num_cols - 1
        );
        $report->row++;
        $report->writer->writeSheetRow(
            $report->sheetName,
            [$this->header['reportName']],
            [
                'font'=>'Arial',
                'font-size'=>12,
                'font-style'=>'bold',
                'halign'=>'center'
            ]);
        $report->writer->markMergedCell(
            $report->sheetName,
            $start_row=$report->row,
            $start_col=0,
            $end_row=$report->row,
            $end_col=$report->num_cols - 1
        );
        $report->row++;
        $report->writer->writeSheetRow(
            $report->sheetName,
            [$this->header['reportDate']],
            [
                'font'=>'Arial',
                'font-size'=>12,
                'font-style'=>'bold',
                'halign'=>'center'
            ]);
        $report->writer->markMergedCell(
            $report->sheetName,
            $start_row=$report->row,
            $start_col=0,
            $end_row=$report->row,
            $end_col=$report->num_cols - 1
        );
        $report->row++;
    
        if (strlen($this->header['confidential'])) {
            $report->writer->writeSheetRow(
                $report->sheetName,
                [$this->header['confidential']],
                [
                    'font'=>'Arial',
                    'font-size'=>12,
                    'font-style'=>'bold',
                    'halign'=>'center',
                    'color'=>'#f00',
                ]);
            $report->writer->markMergedCell(
                $report->sheetName,
                $start_row=$report->row,
                $start_col=0,
                $end_row=$report->row,
                $end_col=$report->num_cols - 1
            );
            $report->row++;
        }
        
        $report->writer->writeSheetRow($report->sheetName,[]);
    }
}