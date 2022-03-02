<?php


require_once(dirname(__FILE__).'/../PdfGraphReport.php');
require_once(dirname(__FILE__).'/../Traits/R11DataTrait.php');


class PdfRptR11 extends PdfGraphReport
{
    use R11DataTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R11 - SNAPSHOT REPORT';
        $this->filename = 'R11-SNAPSHOT-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    }
    
    function run() {
        parent::run();
    
        $data = $this->data($this->reportDate);
        
        $this->newPage($data['cur_yr'],$data['yb_yr']);
        
        $rescued_weight = 0;
        $yb_rescued_weight = 0;
        $this->subHeader("A. Received Food by Source");
        foreach ($data['A'] as $row) {
            if ($row['type'] == 'Rescued') {
                $rescued_weight = $row['cur_weight'];
                $yb_rescued_weight = $row['yb_weight'];
            }
            $this->rowData(
                $row['type'] == 'Rescued' ? 'Rescued *' : $row['type'],
                $row['cur_weight'],
                $row['yb_weight'],
                $row['pcytd'],
                $row['yoy_diff'],
                $row['yoy_pct'],
                $row['type'] == 'Total Food Received' ? 'T' : ""
            );
        }

        $this->subHeader("B. Rescued Food by Type");
        $ytd = $data['B']['Total Rescued Food'][0];
        foreach ($data['B'] as $type => $row) {
            $this->rowData(
                $type,
                $row[0],
                $row[1],
                $type == 'Total Rescued Food' ? 100.0 : ($ytd == 0 ? null : ($row[0] / $ytd) * 100.0),
                $row[0]-$row[1],
                ($row[1] == 0 ? null : (($row[0] - $row[1]) / $row[1]) * 100.0),
                $type == 'Total Rescued Food' ? 'T' : ""
            );
        }
        
        $this->subHeader("C. Rescued Food by Donor Area");
        foreach ($data['C'] as $type => $row) {
            $this->rowData(
                $type,
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $type == 'Total Rescued Food' ? 'T' : ""
            );
        }

        $this->subHeader("D. Rescued Food by Base Operations");
        foreach ($data['D'] as $type => $row) {
            $this->rowData(
                $type,
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $type == 'Total Rescued Food' ? 'T' : ""
            );
        }
    
        $distributed_weight = 0;
        $yb_distributed_weight = 0;
        $this->subHeader("E. Distributed Food by County");
        foreach ($data['E'] as $type => $row) {
            if ($type == 'Total Distributed Food') {
                $distributed_weight = $row[0];
                $yb_distributed_weight = $row[1];
            }
            $this->rowData(
                $type,
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $type == 'Total Distributed Food' ? 'T' : ""
            );
        }
    
        $this->subHeader("F. Key Rescued Food Locations");
        foreach ($data['F'] as $type => $row) {
            $this->rowData(
                $type,
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4]
            );
        }
    
        
        $this->subHeader("G. Top 3 Agencies w/ Largest + & - Weight Change");
        $c = 0;
        foreach ($data['G'] as $type => $row) {
            $this->rowData(
                $type,
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $c++ == 2 ? 'X' : ""
            );
        }
    
        $perPound = 1.67;
        $perPound = $data['CONSTANTS']['valuePerPound'];
        $m1 = $rescued_weight * $perPound;
        $m2 = $yb_rescued_weight * $perPound;
        $m4 = $m1 - $m2;
        $m5 = ($m2 == 0 ? null : (($m1 - $m2) / $m2) * 100.0);
        $this->subHeader2("H. Rescued Food Value @ $".$perPound."/lb",$m1,$m2,$m4,$m5,'T');
    
        $perMeal = 1.2;
        $perMeal = $data['CONSTANTS']['poundsPerMeal'];
        $m1 = $distributed_weight / $perMeal;
        $m2 = $yb_distributed_weight / $perMeal;
        $m4 = $m1 - $m2;
        $m5 = ($m2 == 0 ? null : (($m1 - $m2) / $m2) * 100.0);
        $this->subHeader2("I. Distributed Food Equivalent Meals @ ".$perMeal." lbs/meal",$m1,$m2,$m4,$m5,'B');
    
        $this->pdf->SetFontSize(7);
        $this->pdf->SetX(25);
        $this->pdf->Cell(81,4,'* May not match because of discrepancies in Homeplate database',0, 0,'L');
    
        





        // Create the graph. These two calls are always required
        $graph = new Graph(640,250,'auto');
        $graph->SetScale("textlin");

        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);

        //$graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
        $graph->SetBox(true,'gray');

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($data['CHART']['xticks']);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->SetLabelFormatCallback('valueFormat');

        // Create the bar plots
        $b1plot = new BarPlot($data['CHART']['data1']);
        $b2plot = new BarPlot($data['CHART']['data2']);

        // Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot,$b2plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#9DC3E6");
        $b1plot->SetLegend($data['yb_yr']);
        $b1plot->value->show = true;
        $b1plot->value->SetColor('#333');
        $b1plot->value->SetAngle(90);
        $b1plot->value->SetFormatCallback([$this,'barFormat']);
//        $b1plot->SetWidth(25);

        $b2plot->SetColor("white");
        $b2plot->SetFillColor("#ED7D31");
        $b2plot->SetLegend($data['cur_yr']);
        $b2plot->value->show = true;
        $b2plot->value->SetColor('#333');
        $b2plot->value->SetAngle(90);
        $b2plot->value->SetFormatCallback([$this,'barFormat']);
        $b2plot->SetLineWeight(0);
//        $b2plot->SetWidth(25);

        $graph->yscale->SetGrace(10);
        $graph->title->SetFont(FF_DEFAULT,FS_NORMAL,10);
        $graph->title->SetColor('#333');
        $graph->title->Set("Rescued Food");
        $graph->legend->Pos(0.5,0.95, 'center', 'bottom');
        $graph->legend->SetMarkAbsHSize(12);
        $graph->legend->SetMarkAbsHSize(12);
//        $graph->legend->SetLineWeight(0);
        $graph->legend->SetFont(FF_DEFAULT,FS_NORMAL,10);
        $graph->legend->SetColor('#333');

        $graph->setMargin(60,1,0,60);
        $graph->yaxis->title->Set('Lbs. - Thousands');
        $graph->yaxis->title->setMargin(20);
        $graph->yaxis->title->SetFont(FF_DEFAULT,FS_NORMAL,10);


        $im=$graph->Stroke(_IMG_HANDLER);

        ob_start();
        imagepng($im);
        $img_data = ob_get_contents();
        ob_end_clean();
        $this->pdf->MemImage($img_data, 20, 220);

        $this->output();
    }

    function barFormat($aLabel) {
        if ($aLabel == 0) return '';
        return number_format($aLabel);
    }
    function newPage($cur_yr,$yb_yr) {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $x=101;
        $y1 = $this->pdf->GetY();
        $this->pdf->SetX($x);
        $this->pdf->Cell(18,4,$cur_yr." YTD",1, 0,'C');
        $this->pdf->Cell(18,4,$yb_yr." YTD",1, 0,'C');
        $x = $this->pdf->GetX();
        $this->pdf->Cell(18,4,"",0, 0,'C');
        $this->pdf->Cell(34,4,"YoY Change",1, 0,'C');

        $this->pdf->Ln();
        $y2 = $this->pdf->GetY();
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(18,4,"% Current\nYTD Total",1, 'C');
    
        $this->pdf->SetXY( 101,$y2);
        $this->pdf->Cell(36,4,"Weight - Lbs.",1, 0,'C');
        $this->pdf->Cell(18,4,"",0, 0,'C');
        $this->pdf->Cell(18,4,"Lbs.",1, 0,'C');
        $this->pdf->Cell(16,4,"%",1, 0,'C');
    
        $this->pdf->Ln();
    }
    public function subHeader($title) {
        $this->pdf->SetFont('','B');
        $this->pdf->SetX(20);
        #DCEBF7
        $this->pdf->setFillColor(220,235, 247);
        $this->pdf->Cell(81,4,$title,1, 0,'L', true);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(18,4,"",'L', 0,'L', false);
        $this->pdf->Cell(18,4,"",'L', 0,'L', false);
        $this->pdf->Cell(18,4,"",'L', 0,'L', false);
        $this->pdf->Cell(18,4,"",'L', 0,'L', false);
        $this->pdf->Cell(16,4,"",'LR', 0,'L', false);
        $this->pdf->SetFont('','');
        $this->pdf->Ln();
    }
    public function subHeader2($title,$m1=null,$m2=null,$m3=null,$m4=null,$p=null) {
        $dlr = strpos( $title , 'H.' ) === 0 ? '$ ' : '';
        $this->pdf->SetFont('','B');
        $this->pdf->SetX(20);
        #E1EFDA
        $this->pdf->setFillColor(225,239, 218);
        $this->pdf->Cell(81,4,$title,1, 0,'L', true);
        $this->pdf->Cell(18,4,$m1 != null ? $dlr.number_format($m1) : '',1, 0,'R',true);
        $this->pdf->Cell(18,4,$m2 != null ? $dlr.number_format($m2) : '',1, 0,'R',true);
        $this->pdf->Cell(18,4, '',$p.'LR', 0,'L',true);
        if ($m3 < 0) $this->pdf->SetTextColor(175,57, 55);
        $this->pdf->Cell(18,4,$m3 != null ? $dlr.number_format($m3) : '',1, 0,'R',true);
        $this->pdf->Cell(16,4,$m4 != null ? number_format($m4,1).'%' : '',1, 0,'R',true);
        if ($m3 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFont('','');
        $this->pdf->Ln();
    }
    
    public function rowData($name='',$m1=null,$m2=null,$m3=null,$m4=null,$m5=null,$p=null) {
        if ($p == 'X') {
            $this->pdf->SetDrawColor(200);
            $this->pdf->SetX(20);
            $this->pdf->Cell(81,4,'','B', 0,'L');
            $this->pdf->Cell(18,4,'','B', 0,'R');
            $this->pdf->Cell(18,4,'','B', 0,'R');
            $this->pdf->Cell(18,4,'','B', 0,'R');
            $this->pdf->Cell(18,4,'','B', 0,'R');
            $this->pdf->Cell(16,4,'','B', 0,'R');
            $this->pdf->SetDrawColor(0,0,0);
            $p = '';
        }
        $this->pdf->SetX(20);
        $this->pdf->Cell(81,4,'   '.$name.($p ? ' *' : ''),$p.'LR', 0,'L');
        if ($name == 'Rescued *') {
            $this->pdf->SetFont('','B');
        }
        $this->pdf->Cell(18,4,$m1 != null ? number_format($m1) : '',$p.'LR', 0,'R');
        $this->pdf->Cell(18,4,$m2 != null ? number_format($m2) : '',$p.'LR', 0,'R');
        if ($name == 'Rescued *') {
            $this->pdf->SetFont('','');
        }
        $this->pdf->Cell(18,4,$m3 != null ? number_format($m3,1).'%  ' : '',$p.'LR', 0,'R');
        #AF3937
        if ($m4 < 0) $this->pdf->SetTextColor(175,57, 55);
    
        $this->pdf->Cell(18,4,$m4 != null ? number_format($m4) : '',$p.'LR', 0,'R');
        $this->pdf->Cell(16,4,$m5 != null ? number_format($m5).'%  ' : '',$p.'LR', 0,'R');
        if ($m4 < 0) $this->pdf->SetTextColor(0,0, 0);
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