<?php


require(dirname(__FILE__).'/../PdfReport.php');
require(dirname(__FILE__).'/../Traits/R6DataTrait.php');


class PdfRptR6 extends PdfReport
{
    use R6DataTrait;
    
    private $prevYearLabel = null;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R6 - RECIPIENT 3 MO. & YTD VARIANCE RANK REPORT';
        $this->filename = 'R6-R-3M&YTD-VAR-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    
        $prv_date = new DateTime($this->reportDate->format('Y-m-d'));
        $prv_date->modify('-1 year');
        $this->prevYearLabel = $prv_date->format('M-y');
    
    }
    
    function run() {
        parent::run();
        $data = $this->data($this->reportDate);
        
        $this->newPage();
        
        $cur_total = 0.0;
        $prv_total = 0.0;
        $ycur_total = 0.0;
        $yprv_total = 0.0;
        $ccur_total = 0.0;
        $cprv_total = 0.0;
        $cycur_total = 0.0;
        $cyprv_total = 0.0;
    
        $cty = $data['dropoffs'][0]['county'];
        $p_idx = 0;
        while ($p_idx < count($data['dropoffs'])) {
            $left = $this->pdf->GetPageHeight() - $this->pdf->GetY();
            if ($left < 25) {
                $this->newPage();
            }
            
            
            if ($data['dropoffs'][$p_idx]['county'] != $cty) {
                $d = $ccur_total - $cprv_total;
                $p = $cprv_total == 0 ? null : ($d / $cprv_total) * 100.0;
                $yd = $cycur_total - $cyprv_total;
                $yp = $cyprv_total == 0 ? null : ($yd / $cyprv_total) * 100.0;
                $this->rowTotal(
                    'Total '.$cty.' Co. Agencies',
                    $ccur_total,
                    $cprv_total,
                    $d,
                    $p,
                    $cycur_total,
                    $cyprv_total,
                    $yd,
                    $yp);
                $this->pdf->Ln();
                $this->blankRow();
                $this->pdf->Ln();
                $ccur_total = 0.0;
                $cprv_total = 0.0;
                $cycur_total = 0.0;
                $cyprv_total = 0.0;
                $cty = $data['dropoffs'][$p_idx]['county'];
            }
        
            $cw = $data['dropoffs'][$p_idx]['cur_weight'];
            $pw = $data['dropoffs'][$p_idx]['prv_weight'];
            $d = $cw - $pw;
            $p = $pw == 0 ? null : ($d / $pw) * 100.0;
            $ycw = $data['dropoffs'][$p_idx]['ycur_weight'];
            $ypw = $data['dropoffs'][$p_idx]['yprv_weight'];
            $yd = $ycw - $ypw;
            $yp = $ypw == 0 ? null : ($yd / $ypw) * 100.0;
            $this->rowData(
                $data['dropoffs'][$p_idx]['client'],
                $data['dropoffs'][$p_idx]['area'],
                $cw,
                $pw,
                $d,
                $p,
                $ycw,
                $ypw,
                $yd,
                $yp);
            $cur_total += $cw;
            $prv_total += $pw;
            $ycur_total += $ycw;
            $yprv_total += $ypw;
            $ccur_total += $cw;
            $cprv_total += $pw;
            $cycur_total += $ycw;
            $cyprv_total += $ypw;
    
            $p_idx++;
            $this->pdf->Ln();
        }
    
        $d = $ccur_total - $cprv_total;
        $p = $cprv_total == 0 ? null : ($d / $cprv_total) * 100.0;
        $yd = $cycur_total - $cyprv_total;
        $yp = $cyprv_total == 0 ? null : ($yd / $cyprv_total) * 100.0;
        $this->rowTotal(
            'Total '.$cty.' Co. Agencies',
            $ccur_total,
            $cprv_total,
            $d,
            $p,
            $cycur_total,
            $cyprv_total,
            $yd,
            $yp);
        $this->pdf->Ln();
        $this->blankRow();
        $this->pdf->Ln();
    
        $d = $cur_total - $prv_total;
        $p = $prv_total == 0 ? null : ($d / $prv_total) * 100.0;
        $d1 = $ycur_total - $yprv_total;
        $p1 = $yprv_total == 0 ? null : ($d1 / $yprv_total) * 100.0;
        $this->rowTotal(
            'Total Second Helpings Food',
            $cur_total,
            $prv_total,
            $d,
            $p,
            $ycur_total,
            $yprv_total,
            $d1,
            $p1
        );
    
        $this->output();
    }
    
    function newPage() {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
    
        $x = 70;
        $this->pdf->SetX( $x);
        $this->pdf->Cell((3*17.5)+13,4,"Rolling 3 Months",1, 0,'C');
        $this->pdf->Cell(0.5,4,"",0, 0,'C');
        $this->pdf->Cell((3*17.5)+13,4,"YTD",1, 0,'C');
        $this->pdf->Ln();
    
        $x=70;
        $this->pdf->SetX($x);
        $lab = substr($this->startDateLabel,0,4).$this->reportDateLabel;
        $this->pdf->Cell(17.5,4,$lab,1, 0, 'C');
        $lab = substr($this->startDateLabel,0,4).$this->prevYearLabel;
        $this->pdf->Cell(17.5,4,$lab,1, 0,'C');
        $this->pdf->Cell(17.5,4,"Change",1, 0,'C');
        $this->pdf->Cell(13,4,"",1, 0,'C');
        $this->pdf->Cell(0.5,4,"",0, 0,'C');
        $this->pdf->Cell(17.5,4,$this->reportDateLabel,1, 0, 'C');
        $this->pdf->Cell(17.5,4,$this->prevYearLabel,1, 0,'C');
        $this->pdf->Cell(17.5,4,"Change",1, 0,'C');
        $this->pdf->Cell(13,4,"",1, 0,'C');
        $this->pdf->Ln();
    
        $y = $this->pdf->GetY();
        $this->pdf->Ln();
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,"Recipient",1, 0,'L');
        $this->pdf->Cell(10,4,"Area",1, 0,'L');
    
        $x=70;
        $this->pdf->SetXY($x,$y);
        $this->pdf->MultiCell(17.5,4,"Weight\nLbs",1, 'C');
        $this->pdf->SetXY($x += 17.5,$y);
        $this->pdf->MultiCell(17.5,4,"Weight\nLbs",1, 'C');
        $this->pdf->SetXY($x += 17.5,$y);
        $this->pdf->MultiCell(17.5,4,"Weight\nLbs",1, 'C');
        $this->pdf->SetXY($x += 17.5,$y);
        $this->pdf->MultiCell(13,4,"\n%",1, 'C');
    
        $this->pdf->SetXY($x += 13.5,$y);
        $this->pdf->MultiCell(17.5,4,"Weight\nLbs",1, 'C');
        $this->pdf->SetXY($x += 17.5,$y);
        $this->pdf->MultiCell(17.5,4,"Weight\nLbs",1, 'C');
        $this->pdf->SetXY($x += 17.5,$y);
        $this->pdf->MultiCell(17.5,4,"Weight\nLbs",1, 'C');
        $this->pdf->SetXY($x += 17.5,$y);
        $this->pdf->MultiCell(13,4,"\n%",1, 'C');
    
    
    }
    
    public function blankRow() {
        $this->pdf->SetX( 8+52+10+17.5+17.5+17.5+13);
        $this->pdf->Cell(0.5,4,"",'LR', 0,'C');
    }
        
        
        public function rowData($name='',$area='',$cw=null,$pw=null,$d1=null,$p1=null,$ycw=null,$ypw=null,$yd1=null,$yp1=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,$name,0, 0,'L');
        $this->pdf->Cell(10,4,$area,0, 0,'L');
        $this->pdf->Cell(17.5,4,$cw != null ? number_format($cw) : '',0, 0,'R');
        $this->pdf->Cell(17.5,4,$pw != null ? number_format($pw) : '',0, 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$d1 != null ? number_format($d1) : '',0, 0,'R');
        $this->pdf->Cell(13,4,$p1 != null ? number_format($p1)."%" : '',0, 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Cell(0.5,4,"",'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$ycw != null ? number_format($ycw) : '',0, 0,'R');
        $this->pdf->Cell(17.5,4,$ypw != null ? number_format($ypw) : '',0, 0,'R');
        if ($yd1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$yd1 != null ? number_format($yd1) : '',0, 0,'R');
        $this->pdf->Cell(13,4,$yp1 != null ? number_format($yp1)."%" : '',0, 0,'R');
    }
    
    public function rowTotal($name='',$cw=null,$pw=null,$d1=null,$p1=null,$ycw=null,$ypw=null,$yd1=null,$yp1=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetX( 8);
        $this->pdf->Cell(62,4,$name,'T', 0,'L');
        $this->pdf->Cell(17.5,4,$cw != null ? number_format($cw) : '','T', 0,'R');
        $this->pdf->Cell(17.5,4,$pw != null ? number_format($pw) : '','T', 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$d1 != null ? number_format($d1) : '','T', 0,'R');
        $this->pdf->Cell(13,4,$p1 != null ? number_format($p1)."%" : '','T', 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Cell(0.5,4,"",'LRT', 0,'C');
        $this->pdf->Cell(17.5,4,$ycw != null ? number_format($ycw) : '','T', 0,'R');
        $this->pdf->Cell(17.5,4,$ypw != null ? number_format($ypw) : '','T', 0,'R');
        if ($yd1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$yd1 != null ? number_format($yd1) : '','T', 0,'R');
        $this->pdf->Cell(13,4,$yp1 != null ? number_format($yp1)."%" : '','T', 0,'R');
    }
    
    
}