<?php


require_once(dirname(__FILE__).'/../PdfReport.php');
require_once(dirname(__FILE__).'/../Traits/R3DataTrait.php');


class PdfRptR3 extends PdfReport
{
    use R3DataTrait;
    
    private $prevYearLabel = null;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R3 - DONOR MONTHLY VARIANCE REPORT';
        $this->filename = 'R3-D-MVAR-'.$this->reportDateLabel;
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
        $donor_type = count($data['pickups']) > 0 ? $data['pickups'][0]['donor_type'] : '';
        $cur_total = 0.0;
        $prv_total = 0.0;
        $d_cur_total = 0.0;
        $d_prv_total = 0.0;
    
        $p_idx = 0;
        while ($p_idx < count($data['pickups'])) {
            if ($p_idx < count($data['pickups']) && $data['pickups'][$p_idx]['donor_type'] != $donor_type) {
                // change of type
                $d = $d_cur_total - $d_prv_total;
                $p = $d_prv_total == 0 ? null : ($d / $d_prv_total) * 100.0;
                $this->rowTotal(
                    $donor_type,
                    $d_cur_total,
                    $d_prv_total,
                    $d,
                    $p
                );
                $donor_type = $data['pickups'][$p_idx]['donor_type'];
                $d_cur_total = 0.0;
                $d_prv_total = 0.0;
                $this->pdf->Ln();
            } else if ($p_idx < count($data['pickups'])) {
                $cw = $data['pickups'][$p_idx]['cur_weight'];
                $pw = $data['pickups'][$p_idx]['prv_weight'];
                $d = $cw - $pw;
                $p = $pw == 0 ? null : ($d / $pw) * 100.0;
                $this->rowData(
                    $data['pickups'][$p_idx]['client'],
                    $data['pickups'][$p_idx]['cur_weight'],
                    $data['pickups'][$p_idx]['prv_weight'],
                    $d,
                    $p);
                $cur_total += $data['pickups'][$p_idx]['cur_weight'];
                $prv_total += $data['pickups'][$p_idx]['prv_weight'];
                $d_cur_total += $data['pickups'][$p_idx]['cur_weight'];
                $d_prv_total += $data['pickups'][$p_idx]['prv_weight'];
                $p_idx++;
            }
            $this->pdf->Ln();
        }
        if ($donor_type != '') {
            $d = $d_cur_total - $d_prv_total;
            $p =$d_prv_total == 0 ? null : ($d / $d_prv_total) * 100;
            $this->rowTotal(
                $donor_type,
                $d_cur_total,
                $d_prv_total,
                $d,
                $p
            );
            $donor_type = '';
            $this->pdf->Ln();
        }
    
        $this->pdf->Ln();
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