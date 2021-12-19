<?php


require(dirname(__FILE__).'/../PdfReport.php');
require(dirname(__FILE__).'/../Traits/R4DataTrait.php');


class PdfRptR4 extends PdfReport
{
    use R4DataTrait;
    
    private $prevYearLabel = null;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R4 - RECIPIENT MONTHLY VARIANCE REPORT';
        $this->filename = 'R4-R-MVAR-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    
        $prv_date = new DateTime($this->reportDate->format('Y-m-d'));
        $prv_date->modify('-1 year');
        $this->prevYearLabel = $prv_date->format('M-y');
    
    }
    
    function run() {
        parent::run();
        
        $this->newPage();
        $this->pdf->Ln();
        
        $data = $this->data($this->reportDate);
        $cur_total = 0.0;
        $prv_total = 0.0;
    
        $p_idx = 0;
        while ($p_idx < count($data['dropoffs'])) {
            $cw = $data['dropoffs'][$p_idx]['cur_weight'];
            $pw = $data['dropoffs'][$p_idx]['prv_weight'];
            $d = $cw - $pw;
            $p = $pw == 0 ? null : ($d / $pw) * 100.0;
            $this->rowData(
                $data['dropoffs'][$p_idx]['client'],
                $data['dropoffs'][$p_idx]['cur_weight'],
                $data['dropoffs'][$p_idx]['prv_weight'],
                $d,
                $p);
            $cur_total += $data['dropoffs'][$p_idx]['cur_weight'];
            $prv_total += $data['dropoffs'][$p_idx]['prv_weight'];
            $p_idx++;
            $this->pdf->Ln();
        }
    
        $d = $cur_total - $prv_total;
        $p = $prv_total == 0 ? null : ($d / $prv_total) * 100.0;
        $this->rowTotal(
            'Total Food',
            $cur_total,
            $prv_total,
            $d,
            $p
        );
    
        $this->output();
    }
    
    function newPage() {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetX( 20);
        $this->pdf->Cell(60,4,"",0, 0,'L');
        $this->pdf->Cell(25, 4, $this->reportDateLabel, 1, 0, 'C');
        $this->pdf->Cell(25, 4, $this->prevYearLabel, 1, 0, 'C');
        $this->pdf->Cell(10,4,"",0, 0,'C');
        $this->pdf->Cell(45, 4, "Change YoY", 1, 0, 'C');
        $this->pdf->Ln();

        $this->pdf->SetX( 20);
        $this->pdf->Cell(60,4,"Donor",1, 0,'L');
        $this->pdf->Cell(25,4,"Weight - Lbs",1, 0,'C');
        $this->pdf->Cell(25,4,"Weight - Lbs",1, 0,'C');
        $this->pdf->Cell(10,4,"",0, 0,'C');
        $this->pdf->Cell(25,4,"Weight - Lbs",1, 0,'C');
        $this->pdf->Cell(20,4,"%",1, 0,'C');
    }
    
    public function rowData($name='',$cur_weight=null,$prv_weight=null,$diff=null,$percent=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 20);
        $this->pdf->Cell(60,4,$name,0, 0,'L');
        $this->pdf->Cell(25,4,$cur_weight != null ? number_format($cur_weight) : '',0, 0,'R');
        $this->pdf->Cell(25,4,$prv_weight != null ? number_format($prv_weight) : '',0, 0,'R');
        $this->pdf->Cell(10,4,"",0, 0,'C');
        
        if ($diff < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(25,4,$diff != null ? number_format($diff) : '',0, 0,'R');
        $this->pdf->Cell(20,4,$percent != null ? number_format($percent)."%" : '',0, 0,'R');
    }
    
    public function rowTotal($name='',$cur_weight=null,$prv_weight=null,$diff=null,$percent=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
        
        $this->pdf->SetX( 20);
        $this->pdf->Cell(60,4,$name,'T', 0,'L');
        $this->pdf->Cell(25,4,$cur_weight != null ? number_format($cur_weight) : '','T', 0,'R');
        $this->pdf->Cell(25,4,$prv_weight != null ? number_format($prv_weight) : '','T', 0,'R');
        $this->pdf->Cell(10,4,"",0, 0,'C');
        if ($diff < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(25,4,$diff != null ? number_format($diff) : '','T', 0,'R');
        $this->pdf->Cell(20,4,$percent != null ? number_format($percent)."%" : '','T', 0,'R');
    }
    
    
}