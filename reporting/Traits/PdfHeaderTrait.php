<?php

trait PdfHeaderTrait {
    public $header = array(
        'company' => 'SECOND HELPINGS',
        'reportName' => 'R8',
        'reportDate' => 'Jan-21',
        'confidential' => 'CONFIDENTIAL',
    );
    
    public function setPdfHeaderReportDate($label) {
        $this->header['reportDate'] = $label;
    }
    
    public function writePdfHeader($pdf) {
        $pdf->SetFont('Helvetica','',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(15, 9);
        $pdf->MultiCell(180,5.5,
            $this->header['company'] . "\n" . $this->header['reportName'] . "\n". $this->header['reportDate'],
            0, 'C');
        
        $pdf->SetTextColor(255,0, 0);
        $pdf->SetX(15);
        $pdf->Cell(180,6,$this->header['confidential'],0, 0,'C');
        $pdf->Ln();
    }
}