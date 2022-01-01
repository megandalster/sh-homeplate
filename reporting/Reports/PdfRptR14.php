<?php


require(dirname(__FILE__).'/../PdfReport.php');
require(dirname(__FILE__).'/../Traits/R14DataTrait.php');


class PdfRptR14 extends PdfReport
{
    use R14DataTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R14 - KEY* RESCUED FOOD DAILY AVERAGE';
        $this->filename = 'R14-RESCUEAVE-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    }
    
    function run() {
        parent::run();
    
        $data = $this->data($this->reportDate);
        
        $this->newPage();
        
        foreach ($data['pickups'] as $row) {
            $this->rowData(
                $row['client'],
                $row['mon'],
                $row['tue'],
                $row['wed'],
                $row['thu'],
                $row['fri'],
                $row['sat'],
                $row['tot'],
                $row['istotal']
            );
        }
    
        $this->pdf->Ln();
        $this->pdf->SetFontSize(7);
        $this->pdf->SetX(25);
        $this->pdf->Cell(81,4,'* Chain Stores',0, 0,'L');
    
    
        $this->output();
    }

    function newPage() {
        $this->header['reportDate'] = '6 Month Period:  '.$this->date_label;
        
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->Ln();
        $x=20+58;
        $y1 = $this->pdf->GetY();
        $this->pdf->SetX($x);
        $this->pdf->Cell(6*16,4,"Day of Week Average - Lbs",1, 0,'C');
        $x = $this->pdf->GetX();

        $this->pdf->Ln();
        $y2 = $this->pdf->GetY();
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(16,4,"6 Day\nTotal",1, 'C');
    
        $this->pdf->SetXY( 20,$y2);
        $this->pdf->Cell(58,4,"",'B', 0,'C');
        $this->pdf->Cell(16,4,"Mon",1, 0,'C');
        $this->pdf->Cell(16,4,"Tue",1, 0,'C');
        $this->pdf->Cell(16,4,"Wed",1, 0,'C');
        $this->pdf->Cell(16,4,"Thu",1, 0,'C');
        $this->pdf->Cell(16,4,"Fri",1, 0,'C');
        $this->pdf->Cell(16,4,"Sat",1, 0,'C');
    
        $this->pdf->SetFont('','');
        $this->pdf->Ln();
    }
    
    public function rowData($name='',$mon=null,$tue=null,$wed=null,$thu=null,$fri=null,$sat=null,$tot=null,$istot=false,$p='') {
        if ($istot) {
            if (strpos( $name , 'Total' ) !== 0) {
                $this->rowData('',null,null,null,null,null,null,null,false,'T');
            } else {
                $p = 'B';
            }
            $this->pdf->SetFont('','B');
        }
        
        $this->pdf->SetX(20);
        $this->pdf->Cell(58,4,$name,$p.'L', 0,'L');
        $this->pdf->Cell(16,4,$mon != null ? number_format($mon) : '',$p, 0,'R');
        $this->pdf->Cell(16,4,$tue != null ? number_format($tue) : '',$p, 0,'R');
        $this->pdf->Cell(16,4,$wed != null ? number_format($wed) : '',$p, 0,'R');
        $this->pdf->Cell(16,4,$thu != null ? number_format($thu) : '',$p, 0,'R');
        $this->pdf->Cell(16,4,$fri != null ? number_format($fri) : '',$p, 0,'R');
        $this->pdf->Cell(16,4,$sat != null ? number_format($sat) : '',$p, 0,'R');
        $this->pdf->Cell(16,4,$tot != null ? number_format($tot) : '',$p.'LR', 0,'R');
        $this->pdf->Ln();

        if ($istot) {
            $this->pdf->SetFont('','');
            if (strpos( $name , 'Total' ) !== 0) {
                $this->rowData('',null,null,null,null,null,null,null,false,$p);
            }
        }
    }
    
    
}