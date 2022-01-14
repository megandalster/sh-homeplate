<?php


require_once(dirname(__FILE__).'/../PdfGraphReport.php');
require_once(dirname(__FILE__).'/../Traits/R7DataTrait.php');


class PdfRptR7 extends PdfGraphReport
{
    use R7DataTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R7 - DONOR 6 MONTH TREND REPORT';
        $this->filename = 'R7-D-6M-TREND-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    }
    
    function run() {
        parent::run();
        
        $data = $this->data($this->reportDate);
        
        $this->newPage();

        $mtotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        
        $donor_type = $data['pickups'][0]['donor_type'];
        $p_idx = 0;
        while ($p_idx < count($data['pickups'])) {
            $left = $this->pdf->GetPageHeight() - $this->pdf->GetY();
            if ($left < 25) {
                $this->newPage();
            }
            
            if ($data['pickups'][$p_idx]['donor_type'] != $donor_type) {
    
                $p = $data['total'] == 0 ? null : ($mtotals['tot'] / $data['total']) * 100.0;
                $this->rowTotal(
                    $donor_type,
                    $mtotals['m1'],
                    $mtotals['m2'],
                    $mtotals['m3'],
                    $mtotals['m4'],
                    $mtotals['m5'],
                    $mtotals['m6'],
                    $mtotals['tot'],
                    $mtotals['tot'] * 2,
                    $p,
                    $donor_type == 'Rescued Food' ? 'BT' : 'T'
                    );
    
                $mtotals = array(
                    'm1' => 0,
                    'm2' => 0,
                    'm3' => 0,
                    'm4' => 0,
                    'm5' => 0,
                    'm6' => 0,
                    'tot' => 0
                );
                if ($donor_type == 'Rescued Food')
                    $this->newPage();
                $this->rowData();
                $donor_type = $data['pickups'][$p_idx]['donor_type'];
                
            }
    
            $p = $data['total'] == 0 ? null : ($data['pickups'][$p_idx]['tot'] / $data['total']) * 100.0;
            $this->rowData(
                $data['pickups'][$p_idx]['client'],
                $data['pickups'][$p_idx]['m1'],
                $data['pickups'][$p_idx]['m2'],
                $data['pickups'][$p_idx]['m3'],
                $data['pickups'][$p_idx]['m4'],
                $data['pickups'][$p_idx]['m5'],
                $data['pickups'][$p_idx]['m6'],
                $data['pickups'][$p_idx]['tot'],
                $data['pickups'][$p_idx]['tot'] * 2,
                $p
            );
            
            $mtotals['m1'] += $data['pickups'][$p_idx]['m1'];
            $mtotals['m2'] += $data['pickups'][$p_idx]['m2'];
            $mtotals['m3'] += $data['pickups'][$p_idx]['m3'];
            $mtotals['m4'] += $data['pickups'][$p_idx]['m4'];
            $mtotals['m5'] += $data['pickups'][$p_idx]['m5'];
            $mtotals['m6'] += $data['pickups'][$p_idx]['m6'];
            $mtotals['tot'] += $data['pickups'][$p_idx]['tot'];
            $p_idx++;
        }

        $p = $data['total'] == 0 ? null : ($mtotals['tot'] / $data['total']) * 100.0;
        $this->rowTotal(
            $donor_type,
            $mtotals['m1'],
            $mtotals['m2'],
            $mtotals['m3'],
            $mtotals['m4'],
            $mtotals['m5'],
            $mtotals['m6'],
            $mtotals['tot'],
            $mtotals['tot'] * 2,
            $p,
            $donor_type == 'Rescued Food' ? 'BT' : 'T'
        );
        
        $this->rowData();
        $p = $data['total'] == 0 ? null : ($data['nrtotals']['tot'] / $data['total']) * 100.0;
        $this->rowTotal(
            'Non-Rescued Food',
            $data['nrtotals']['m1'],
            $data['nrtotals']['m2'],
            $data['nrtotals']['m3'],
            $data['nrtotals']['m4'],
            $data['nrtotals']['m5'],
            $data['nrtotals']['m6'],
            $data['nrtotals']['tot'],
            $data['nrtotals']['tot'] * 2,
            $p,
            'TB'
        );
    
        $this->rowTotal(
            'Total Food',
            $data['alltotals']['m1'],
            $data['alltotals']['m2'],
            $data['alltotals']['m3'],
            $data['alltotals']['m4'],
            $data['alltotals']['m5'],
            $data['alltotals']['m6'],
            $data['alltotals']['tot'],
            $data['alltotals']['tot'] * 2,
            100.0,
            'TB'
        );
    
    
    
    
    
    
    
    
        $data1y=array(
            $data['rtotals']['m1'],
            $data['rtotals']['m2'],
            $data['rtotals']['m3'],
            $data['rtotals']['m4'],
            $data['rtotals']['m5'],
            $data['rtotals']['m6']
        );
        $data2y=array(
            $data['nrtotals']['m1'],
            $data['nrtotals']['m2'],
            $data['nrtotals']['m3'],
            $data['nrtotals']['m4'],
            $data['nrtotals']['m5'],
            $data['nrtotals']['m6']
        );
    
    
        // Create the graph. These two calls are always required
        $graph = new Graph(700,500,'auto');
        $graph->SetScale("textlin");
    
        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);
    
        //$graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
        $graph->SetBox(true,'gray');
    
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($this->headerLabels);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->SetLabelFormatCallback('valueFormat');

        // Create the linear regression
        $lr = new LinearRegression(array(0,1,2,3,4,5), $data1y);
        list( $stderr, $corr ) = $lr->GetStat();
        list( $xd, $yd ) = $lr->GetY(0,5);
        $lplot = new LinePlot($yd);
    
        
        
        
        // Create the bar plots
        $b1plot = new BarPlot($data1y);
        $b2plot = new BarPlot($data2y);
    
        // Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot,$b2plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);
        $graph->Add($lplot);
    
    
        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#9DC3E6");
        $b1plot->SetLegend('Rescued Food');
        $b1plot->value->show = true;
        $b1plot->value->SetFormatCallback('valueFormat');
        $b1plot->SetWidth(25);
    
        $b2plot->SetColor("white");
        $b2plot->SetFillColor("#ED7D31");
        $b2plot->SetLegend('Non-Rescued Food');
        $b2plot->value->show = true;
        $b2plot->value->SetFormatCallback('valueFormat');
        $b2plot->SetLineWeight(0);
        $b2plot->SetWidth(25);
    
        $graph->title->SetFont(FF_DEFAULT,FS_NORMAL,14);
        $graph->title->SetColor('#333');
        $graph->title->Set("Second Helpings\nDonor 6 Month Food Trend\n ");
        $graph->legend->Pos(0.5,0.95, 'center', 'bottom');
        $graph->legend->SetMarkAbsHSize(18);
        $graph->legend->SetMarkAbsHSize(18);
//        $graph->legend->SetLineWeight(0);
        $graph->legend->SetFont(FF_DEFAULT,FS_NORMAL,10);
        $graph->legend->SetColor('#333');
        
        $graph->setMargin(80,1,0,80);
        $graph->yaxis->title->Set('Pounds');
        $graph->yaxis->title->setMargin(40);
        $graph->yaxis->title->SetFont(FF_DEFAULT,FS_NORMAL,10);
        
        $lplot->SetBarCenter();
//        $lplot->SetStyle('dashed');
        $lplot->SetLegend('Linear (Rescued Food)');
        $lplot->SetLineWeight(5);
        $lplot->SetWeight(1);
        $lplot->SetColor('red');
//        $lplot->mark->SetType(MARK_X,'',1.0);
//        $lplot->mark->SetWeight(2);
//        $lplot->mark->SetWidth(8);
//        $lplot->mark->setColor("red");
//        $lplot->mark->setFillColor("red");
    
        $im=$graph->Stroke(_IMG_HANDLER);
    
        ob_start();
        imagepng($im);
        $img_data = ob_get_contents();
        ob_end_clean();
        $this->pdf->MemImage($img_data, 12, 150);
    
    
    
    
    
    
    
    
    
    
    
    
        $this->output();
    }
    
    function newPage() {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
    
        $x=61;
        $y1 = $this->pdf->GetY();
        $this->pdf->SetX($x);
        $this->pdf->Cell((6*15)+0.5+17.5+17.5,4,"Weight - Lbs.",1, 0,'C');
        $x = $this->pdf->GetX();
        $this->pdf->Ln();
        $y2 = $this->pdf->GetY();
        $this->pdf->SetXY($x,$y1);
        $this->pdf->SetFont('','B');
        $this->pdf->MultiCell(14,4,"% Total\nFood",1, 'C');
    
        $this->pdf->SetXY( 9,$y2);
        $this->pdf->Cell(52,4,"Donor",1, 0,'L');
        $this->pdf->Cell(15,4,$this->headerLabels[0],1, 0,'C');
        $this->pdf->Cell(15,4,$this->headerLabels[1],1, 0,'C');
        $this->pdf->Cell(15,4,$this->headerLabels[2],1, 0,'C');
        $this->pdf->Cell(15,4,$this->headerLabels[3],1, 0,'C');
        $this->pdf->Cell(15,4,$this->headerLabels[4],1, 0,'C');
        $this->pdf->Cell(15,4,$this->headerLabels[5],1, 0,'C');
        $this->pdf->Cell(0.5,4,"",0, 0,'C');
        $this->pdf->Cell(17.5,4,'6 Mo. Total',1, 0,'C');
        $this->pdf->Cell(17.5,4,'Annualized',1, 0,'C');
    
        $this->pdf->Ln();
    }
    
    public function rowData($name='',$m1=null,$m2=null,$m3=null,$m4=null,$m5=null,$m6=null,$tot=null,$atot=null,$p=null) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 9);
        $this->pdf->Cell(52,4,$name,'LR', 0,'L');
        $this->pdf->Cell(15,4,$m1 != null ? number_format($m1) : '',0, 0,'R');
        $this->pdf->Cell(15,4,$m2 != null ? number_format($m2) : '',0, 0,'R');
        $this->pdf->Cell(15,4,$m3 != null ? number_format($m3) : '',0, 0,'R');
        $this->pdf->Cell(15,4,$m4 != null ? number_format($m4) : '',0, 0,'R');
        $this->pdf->Cell(15,4,$m5 != null ? number_format($m5) : '',0, 0,'R');
        $this->pdf->Cell(15,4,$m6 != null ? number_format($m6) : '',0, 0,'R');

        $this->pdf->Cell(0.5,4,"",'LR', 0,'C');
    
        $this->pdf->Cell(17.5,4,$tot != null ? number_format($tot) : '',0, 0,'R');
        $this->pdf->Cell(17.5,4,$atot != null ? number_format($atot) : '',0, 0,'R');
        $this->pdf->Cell(14,4,$p != null ? number_format($p,1)."%" : '','R', 0,'R');
        $this->pdf->Ln();
    }
    
    public function rowTotal($name='',$m1=null,$m2=null,$m3=null,$m4=null,$m5=null,$m6=null,$tot=null,$atot=null,$p=null,$b='T') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetX( 9);
        $this->pdf->Cell(52,4,$name,$b.'LR', 0,'L');
        $this->pdf->Cell(15,4,$m1 != null ? number_format($m1) : '-    ',$b, 0,'R');
        $this->pdf->Cell(15,4,$m2 != null ? number_format($m2) : '-    ',$b, 0,'R');
        $this->pdf->Cell(15,4,$m3 != null ? number_format($m3) : '-    ',$b, 0,'R');
        $this->pdf->Cell(15,4,$m4 != null ? number_format($m4) : '-    ',$b, 0,'R');
        $this->pdf->Cell(15,4,$m5 != null ? number_format($m5) : '-    ',$b, 0,'R');
        $this->pdf->Cell(15,4,$m6 != null ? number_format($m6) : '-    ',$b, 0,'R');
    
        $this->pdf->Cell(0.5,4,"",$b.'LR', 0,'C');

        $this->pdf->Cell(17.5,4,$tot != null ? number_format($tot) : '',$b, 0,'R');
        $this->pdf->Cell(17.5,4,$atot != null ? number_format($atot) : '',$b, 0,'R');
        $this->pdf->Cell(14,4,$p != null ? number_format($p,1)."%" : '',$b.'R', 0,'R');
        $this->pdf->Ln();
    }
    
    
}