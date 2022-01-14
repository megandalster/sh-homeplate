<?php


require_once(dirname(__FILE__).'/../PdfReport.php');
require_once(dirname(__FILE__).'/../Traits/R15DataTrait.php');


class PdfRptR15 extends PdfReport
{
    use R15DataTrait;
    
    private $prevYearLabel = null;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R15 - RECIPIENT NON RESCUED FOOD REPORT';
        $this->filename = 'R15-R-NONRESCUED-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    
        $prv_date = new DateTime($this->reportDate->format('Y-m-d'));
        $prv_date->modify('-1 year');
        $this->prevYearLabel = $prv_date->format('M-y');
    }
    
    function run() {
        parent::run();
        
        $data = $this->data($this->reportDate);
    
        $this->newPage();
    
//        $this->rowData('Long Test Client Name',
//            500000,
//            500000,
//            500000,
//            500000,
//            500000,
//            500000,
//            500000,
//            500000,
//            99.9);
    
        $ytd_total = $data['ytd_total']['ytd_total_weight'];
        $p_idx = 0;
        while ($p_idx < count($data['dropoffs'])) {
            $left = $this->pdf->GetPageHeight() - $this->pdf->GetY();
            if ($left < 25) {
                $this->newPage();
            }

            $ytw = $data['dropoffs'][$p_idx]['ytd_total_weight'];
            $yp = $ytd_total == 0 ? null : ($ytw / $ytd_total) * 100.0;
            $this->rowData(
                $data['dropoffs'][$p_idx]['client'],
                $data['dropoffs'][$p_idx]['transported_weight'],
                $data['dropoffs'][$p_idx]['purchased_weight'],
                $data['dropoffs'][$p_idx]['food_drive_weight'],
                $data['dropoffs'][$p_idx]['total_weight'],
                $data['dropoffs'][$p_idx]['ytd_transported_weight'],
                $data['dropoffs'][$p_idx]['ytd_purchased_weight'],
                $data['dropoffs'][$p_idx]['ytd_food_drive_weight'],
                $data['dropoffs'][$p_idx]['ytd_total_weight'],
                $yp);
        
            $p_idx++;
        }
        $this->rowTotal(
            $data['ytd_total']['client'],
            $data['ytd_total']['transported_weight'],
            $data['ytd_total']['purchased_weight'],
            $data['ytd_total']['food_drive_weight'],
            $data['ytd_total']['total_weight'],
            $data['ytd_total']['ytd_transported_weight'],
            $data['ytd_total']['ytd_purchased_weight'],
            $data['ytd_total']['ytd_food_drive_weight'],
            $data['ytd_total']['ytd_total_weight'],
            100.0
        );
    
    
        $this->output();
    }
    function newPage() {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $x = 8+52;
        $y = $this->pdf->GetY();
        $this->pdf->SetX( $x);
        $this->pdf->Cell((3*16)+16,4,"Month - Lbs",1, 0,'C');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell((3*16)+16,4,"Year-to-Date - Lbs",1, 0,'C');
        $this->pdf->Ln();
    
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,"Recipient",1, 0,'L');
        $this->pdf->SetFontSize(8);
        $this->pdf->Cell(16,4,"Transport'd",1, 0,'C');
        $this->pdf->Cell(16,4,"Purchased",1, 0,'C');
        $this->pdf->Cell(16,4,"Food Drive",1, 0,'C');
        $this->pdf->SetFontSize(9);
        $this->pdf->Cell(16,4,"Total",1, 0,'C');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->SetFontSize(8);
        $this->pdf->Cell(16,4,"Transport'd",1, 0,'C');
        $this->pdf->Cell(16,4,"Purchased",1, 0,'C');
        $this->pdf->Cell(16,4,"Food Drv",1, 0,'C');
        $this->pdf->SetFontSize(9);
        $this->pdf->Cell(16,4,"Total",1, 0,'C');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
    
        $x = $this->pdf->GetX();
        $this->pdf->SetXY($x,$y);
        $this->pdf->MultiCell(13.5,4,"% YTD\nTotal",1, 'C');
    
    
    }
    
    public function rowData($name='',$w1=null,$w2=null,$w3=null,$tw=null,$yw1=null,$yw2=null,$yw3=null,$ytw=null,$yp=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,$name,'LR', 0,'L');
        $this->pdf->Cell(16,4,$w1 != null ? number_format($w1) : '',0, 0,'R');
        $this->pdf->Cell(16,4,$w2 != null ? number_format($w2) : '',0, 0,'R');
        $this->pdf->Cell(16,4,$w3 != null ? number_format($w3) : '',0, 0,'R');
        $this->pdf->Cell(16,4,$tw != null ? number_format($tw) : '','L', 0,'R');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(16,4,$yw1 != null ? number_format($yw1) : '',0, 0,'R');
        $this->pdf->Cell(16,4,$yw2 != null ? number_format($yw2) : '',0, 0,'R');
        $this->pdf->Cell(16,4,$yw3 != null ? number_format($yw3) : '',0, 0,'R');
        $this->pdf->Cell(16,4,$ytw != null ? number_format($ytw) : '','L', 0,'R');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(13.5,4,$yp != null ? number_format($yp,1).'%' : '','LR', 0,'R');
        $this->pdf->Ln();
    }
    
    public function rowTotal($name='',$w1=null,$w2=null,$w3=null,$tw=null,$yw1=null,$yw2=null,$yw3=null,$ytw=null,$yp=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,$name,1, 0,'L');
        $this->pdf->Cell(16,4,$w1 != null ? number_format($w1) : '','TB', 0,'R');
        $this->pdf->Cell(16,4,$w2 != null ? number_format($w2) : '','TB', 0,'R');
        $this->pdf->Cell(16,4,$w3 != null ? number_format($w3) : '','TB', 0,'R');
        $this->pdf->Cell(16,4,$tw != null ? number_format($tw) : '','TBL', 0,'R');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(16,4,$yw1 != null ? number_format($yw1) : '','TB', 0,'R');
        $this->pdf->Cell(16,4,$yw2 != null ? number_format($yw2) : '','TB', 0,'R');
        $this->pdf->Cell(16,4,$yw3 != null ? number_format($yw3) : '','TB', 0,'R');
        $this->pdf->Cell(16,4,$ytw != null ? number_format($ytw) : '','TBL', 0,'R');
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(13.5,4,$yp != null ? number_format($yp,1).'%' : '',1, 0,'R');
        $this->pdf->Ln();
    }
    
}