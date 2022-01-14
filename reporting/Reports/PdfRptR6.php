<?php


require_once(dirname(__FILE__).'/../PdfReport.php');
require_once(dirname(__FILE__).'/../Traits/R6DataTrait.php');


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
        $acur_total = array();
        $aprv_total = array();
        $aycur_total = array();
        $ayprv_total = array();
        $adiff = array();
    
        $cty = $data['dropoffs'][0]['county'];
        $p_idx = 0;
        while ($p_idx < count($data['dropoffs'])) {
            $left = $this->pdf->GetPageHeight() - $this->pdf->GetY();
            if ($left < 25) {
                $this->newPage();
            }


//            if ($data['dropoffs'][$p_idx]['county'] != $cty) {
//                $this->rowTotal();
//
//                arsort($adiff);
//                $keys = array_keys($adiff);
//                ksort($keys,SORT_STRING);
//                foreach ($keys as $key) {
//                    $a = substr($key,0,3);
//                    $d = $acur_total[$key] - $aprv_total[$key];
//                    $p = $aprv_total[$key] == 0 ? null : ($d / $aprv_total[$key]) * 100.0;
//                    $yd = $aycur_total[$key] - $ayprv_total[$key];
//                    $yp = $ayprv_total[$key] == 0 ? null : ($yd / $ayprv_total[$key]) * 100.0;
//                    $this->rowData(
//                        $key.' Area Agencies',
//                        $a,
//                        $acur_total[$key],
//                        $aprv_total[$key],
//                        $d,
//                        $p,
//                        $aycur_total[$key],
//                        $ayprv_total[$key],
//                        $yd,
//                        $yp);
//                }
//
//                $d = $ccur_total - $cprv_total;
//                $p = $cprv_total == 0 ? null : ($d / $cprv_total) * 100.0;
//                $yd = $cycur_total - $cyprv_total;
//                $yp = $cyprv_total == 0 ? null : ($yd / $cyprv_total) * 100.0;
//                $this->rowTotal(
//                    'Total '.$cty.' Co. Agencies',
//                    '',
//                    $ccur_total,
//                    $cprv_total,
//                    $d,
//                    $p,
//                    $cycur_total,
//                    $cyprv_total,
//                    $yd,
//                    $yp);
//                $this->rowData();
//                $ccur_total = 0.0;
//                $cprv_total = 0.0;
//                $cycur_total = 0.0;
//                $cyprv_total = 0.0;
//                $cty = $data['dropoffs'][$p_idx]['county'];
//
//                $acur_total = array();
//                $aprv_total = array();
//                $aycur_total = array();
//                $ayprv_total = array();
//                $adiff = array();
//            }
        
            $area = substr($data['dropoffs'][$p_idx]['area'], 0, 3);
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
                $area,
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
        
            $area = $data['dropoffs'][$p_idx]['area'];
            if (!array_key_exists($area, $adiff)) {
                $adiff[$area] = 0;
                $acur_total[$area] = 0;
                $aprv_total[$area] = 0;
                $aycur_total[$area] = 0;
                $ayprv_total[$area] = 0;
            }
            $acur_total[$area] += $cw;
            $aprv_total[$area] += $pw;
            $aycur_total[$area] += $ycw;
            $ayprv_total[$area] += $ypw;
            $adiff[$area] += $cw - $pw;
        
            $p_idx++;
        }
    
        $this->rowTotal();
    
    
        arsort($adiff);
        $keys = array_keys($adiff);
        ksort($keys, SORT_STRING);
        error_log(print_r($keys, true));
    
        $valid = array(
            'Beaufort' => array('Bea', 'Blu', 'Hil'),
            'Jasper' => array('Har', 'Rid'),
            'Hampton' => array('Ham'),
        );
        foreach ($valid as $cty => $list) {
            $ccur_total = 0;
            $cprv_total = 0;
            $cycur_total = 0;
            $cyprv_total = 0;
    
            foreach ($keys as $key) {
                $a = substr($key, 0, 3);
                if (!in_array($a, $list)) {
                    continue;
                }
        
                $d = $acur_total[$key] - $aprv_total[$key];
                $p = $aprv_total[$key] == 0 ? null : ($d / $aprv_total[$key]) * 100.0;
                $yd = $aycur_total[$key] - $ayprv_total[$key];
                $yp = $ayprv_total[$key] == 0 ? null : ($yd / $ayprv_total[$key]) * 100.0;
                $this->rowData(
                    $key . ' Area Agencies',
                    $a,
                    $acur_total[$key],
                    $aprv_total[$key],
                    $d,
                    $p,
                    $aycur_total[$key],
                    $ayprv_total[$key],
                    $yd,
                    $yp);
    
                $ccur_total += $acur_total[$key];
                $cprv_total += $aprv_total[$key];
                $cycur_total += $aycur_total[$key];
                $cyprv_total += $ayprv_total[$key];
            }
    
            $d = $ccur_total - $cprv_total;
            $p = $cprv_total == 0 ? null : ($d / $cprv_total) * 100.0;
            $yd = $cycur_total - $cyprv_total;
            $yp = $cyprv_total == 0 ? null : ($yd / $cyprv_total) * 100.0;
            $this->rowTotal(
                'Total ' . $cty . ' Co. Agencies',
                '',
                $ccur_total,
                $cprv_total,
                $d,
                $p,
                $cycur_total,
                $cyprv_total,
                $yd,
                $yp);
            $this->rowData();
        }
        
        $d = $cur_total - $prv_total;
        $p = $prv_total == 0 ? null : ($d / $prv_total) * 100.0;
        $d1 = $ycur_total - $yprv_total;
        $p1 = $yprv_total == 0 ? null : ($d1 / $yprv_total) * 100.0;
        $this->rowTotal(
            'Total Second Helpings Food',
            '',
            $cur_total,
            $prv_total,
            $d,
            $p,
            $ycur_total,
            $yprv_total,
            $d1,
            $p1,
            'B'
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
        $this->pdf->Cell(30.5,4,"Change",1, 0,'C');
        $this->pdf->Cell(0.5,4,"",0, 0,'C');
        $this->pdf->Cell(17.5,4,$this->reportDateLabel,1, 0, 'C');
        $this->pdf->Cell(17.5,4,$this->prevYearLabel,1, 0,'C');
        $this->pdf->Cell(30.5,4,"Change",1, 0,'C');
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
    
    public function rowData($name='',$area='',$cw=null,$pw=null,$d1=null,$p1=null,$ycw=null,$ypw=null,$yd1=null,$yp1=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,$name,'L', 0,'L');
        $this->pdf->Cell(10,4,$area,'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$cw != null ? number_format($cw) : '',0, 0,'R');
        $this->pdf->Cell(17.5,4,$pw != null ? number_format($pw) : '',0, 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$d1 != null ? number_format($d1) : '','L', 0,'R');
        $this->pdf->Cell(13,4,$p1 != null ? number_format($p1)."%" : '',0, 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Cell(0.5,4,"",'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$ycw != null ? number_format($ycw) : '',0, 0,'R');
        $this->pdf->Cell(17.5,4,$ypw != null ? number_format($ypw) : '',0, 0,'R');
        if ($yd1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$yd1 != null ? number_format($yd1) : '','L', 0,'R');
        $this->pdf->Cell(13,4,$yp1 != null ? number_format($yp1)."%" : '','R', 0,'R');
        $this->pdf->Ln();
    }
    
    public function rowTotal($name='',$area='',$cw=null,$pw=null,$d1=null,$p1=null,$ycw=null,$ypw=null,$yd1=null,$yp1=null,$b='T') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(52,4,$name,$b.'L', 0,'L');
        $this->pdf->Cell(10,4,$area,$b.'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$cw != null ? number_format($cw) : '',$b, 0,'R');
        $this->pdf->Cell(17.5,4,$pw != null ? number_format($pw) : '',$b, 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$d1 != null ? number_format($d1) : '',$b.'L', 0,'R');
        $this->pdf->Cell(13,4,$p1 != null ? number_format($p1)."%" : '',$b, 0,'R');
        if ($d1 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Cell(0.5,4,"",$b.'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$ycw != null ? number_format($ycw) : '',$b, 0,'R');
        $this->pdf->Cell(17.5,4,$ypw != null ? number_format($ypw) : '',$b, 0,'R');
        if ($yd1 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(17.5,4,$yd1 != null ? number_format($yd1) : '',$b.'L', 0,'R');
        $this->pdf->Cell(13,4,$yp1 != null ? number_format($yp1)."%" : '',$b.'R', 0,'R');
        $this->pdf->Ln();
    }
    
}