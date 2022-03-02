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
        $report->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $report->spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        
        $report->spreadsheet->getActiveSheet()
            ->setCellValue('A1', $this->header['company'])
            ->setCellValue('A2', $this->header['reportName'])
            ->setCellValue('A3', $this->header['reportDate'])
            ->setCellValue('A4', $this->header['confidential']);
    
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
    
        $report->spreadsheet->getActiveSheet()->getStyle('A1:A4')->applyFromArray($styleArray);
        $report->spreadsheet->getActiveSheet()->getStyle('A4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED);

        $report->spreadsheet->getActiveSheet()
            ->mergeCells('A1:'.$report->cellName($report->header_width,1))
            ->mergeCells('A2:'.$report->cellName($report->header_width,2))
            ->mergeCells('A3:'.$report->cellName($report->header_width,3))
            ->mergeCells('A4:'.$report->cellName($report->header_width,4));
        
    }
}