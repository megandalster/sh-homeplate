<?php


require(dirname(__FILE__).'/../PdfGraphReport.php');
require(dirname(__FILE__).'/../Traits/R10DataTrait.php');

class PdfRptR10 extends PdfGraphReport
{
    use R10DataTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R10 - FOOD TYPE TREND REPORT';
        $this->filename = 'R10-FTYPE-TREND-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    
    }
    
    function run() {
        parent::run();
        
        $data = $this->data($this->reportDate);
        
        $this->newPage();
        

        foreach ($data['food'] as $food_type => $values) {
            if ($food_type != 'Rescued Food') continue;
    
            $this->tableHeader($food_type,1);
            foreach ($values as $month => $pounds) {
                if ($month == 'YTD') continue;
                $this->rowData(
                    $this->months[$month],
                    $pounds[0],
                    $pounds[1],
                    $pounds[2],
                    $pounds[3],
                    $pounds[4],
                    $pounds[5],
                    $pounds[6],
                    $month == 12 ? 'B' : ''
                );
            }
            $this->pdf->Ln();
            $this->tableHeader($food_type,2);
            foreach ($values as $month => $pounds) {
                if ($month == 'YTD') continue;
                $this->rowData2(
                    $this->months[$month],
                    $pounds[0],
                    $pounds[1],
                    $pounds[2],
                    $pounds[3],
                    $pounds[4],
                    $pounds[5],
                    $pounds[6],
                    $month == 12 ? 'B' : ''
                );
            }
            
            $this->chart($food_type,$data['last_month'],$values);
            
//            if ($food_type != 'Food Drive Food') $this->newPage();
        }
        $this->output();
    }


   function chart($food_type,$last_month,$values)
   {
       //Jan
       //$values[1];
       $colors = Array(
           "#4572C4",
           "#ED7D31",
           "#A5A5A5",
           "#FFC000",
           "#5C9BD5",
           "#70AC47",
           "#264478",
           "#9E480E",
           "#636363",
           "#997400",
           "#255E91",
           "#43682B"
       );
    
       // Create the graph. These two calls are always required
       $graph = new Graph(700, 425, 'auto');
       $graph->SetScale("textlin");
    
//       $theme_class = new UniversalTheme;
//       $graph->SetTheme($theme_class);
        $graph->graph_theme = null;

//        $graph->SetBox(true,'gray');
       $graph->ygrid->SetFill(false);
       $graph->yaxis->HideLine(false);
       $graph->yaxis->HideTicks(false, false);
       $graph->yaxis->SetLabelFormatCallback('valueFormat');
    
       // Create the bar plots
       $bplots = array();
       for ($x=1; $x<13; $x++) {
           array_pop($values[$x]);
           $bplot = new BarPlot($values[$x]);
           $bplot->SetLegend($this->months[$x]);
           $bplots[] = $bplot;
       }
    
       $gbplot = new GroupBarPlot($bplots);
       $graph->Add($gbplot);
    
       $bplots[$last_month-1]->value->SetColor('#333');
       $bplots[$last_month-1]->value->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 8);
       $bplots[$last_month-1]->value->SetFormatCallback([$this, 'lineval']);
       $bplots[$last_month-1]->value->SetAlign('right','left');
//       $bplots[$last_month-1]->value->SetMargin(20);
       $bplots[$last_month-1]->value->SetAngle(90);
       $bplots[$last_month-1]->value->Show();
       $bplots[$last_month-1]->value->halign = 'left';
       $bplots[$last_month-1]->value->valign = 'bottom';
//       $bplots[$last_month-1]->valuepos = 'top';
    
       for ($x=0; $x<12; $x++) {
           $bplots[$x]->SetColor($colors[$x]);
           $bplots[$x]->SetFillColor($colors[$x]);
       }
       

       $graph->title->SetFont(FF_DV_SANSSERIF, FS_NORMAL, 12);
       $graph->title->SetColor('#333');
       $graph->title->Set("Second Helpings\n".$food_type." Type Trend\n ");
       $graph->setMargin(80, 1, 0, 70);
       
//       $graph->xscale->SetAutoMax(5);
//       $graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
       $graph->xgrid->Show();
//       $graph->xaxis->SetLabelPos( SIDE_UP );
//       $graph->xaxis->SetTickSide( SIDE_DOWN );
//       $graph->xaxis->SetLabelAlign( 'left' );
//       $graph->xaxis->SetTextTickInterval(1,0.5);
       
       $graph->xaxis->SetTickLabels(array('Meat','Deli','Bakery','Grocery','Dairy','Produce'));
       $graph->yaxis->title->Set('Weight - Pounds');
       $graph->yaxis->title->setMargin(40);
       $graph->yaxis->title->SetFont(FF_DEFAULT, FS_NORMAL, 10);
    
       $graph->legend->Pos(0.5,0.89, 'center', 'top');
       $graph->legend->SetLayout(LEGEND_HOR);
       $graph->legend->SetMarkAbsHSize(8);
//       $graph->legend->SetMarkAbsHSize(8);
       $graph->legend->SetColumns(6);
//       $graph->legend->SetLineSpacing(15);
    
       $im = $graph->Stroke(_IMG_HANDLER);
    
       ob_start();
       imagepng($im);
       $img_data = ob_get_contents();
       ob_end_clean();
       $this->pdf->MemImage($img_data, 12, 160);
   }
    
    
    function lineval($aLabel) {
        $nf = number_format($aLabel,0);
        return $nf == '0' ? '' : $nf;
    }
    
    
    function newPage()
    {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
        $this->pdf->Ln();
    }
    
    function tableHeader($food_type = '',$type=1) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
    
        $subtype = ' - Pounds';
        if ($type == 2) {
            $subtype = '- Fraction of Month - %';
        }
        
        $x=61;
        $this->pdf->SetX(35+12);
        $this->pdf->Cell((7*17.5),4,$food_type.$subtype,1, 0,'C');
        $this->pdf->Ln();
    
        $this->pdf->SetX( 35);
        $this->pdf->Cell(12,4,"Month",1, 0,'L');
        $this->pdf->Cell(17.5,4,"Meat",1, 0,'C');
        $this->pdf->Cell(17.5,4,"Deli",1, 0,'C');
        $this->pdf->Cell(17.5,4,"Bakery",1, 0,'C');
        $this->pdf->Cell(17.5,4,"Grocery",1, 0,'C');
        $this->pdf->Cell(17.5,4,"Dairy",1, 0,'C');
        $this->pdf->Cell(17.5,4,"Produce",1, 0,'C');
        $this->pdf->Cell(17.5,4,'Total',1, 0,'C');
    
        $this->pdf->Ln();
    }
    
    public function rowData($month='',$m1=null,$m2=null,$m3=null,$m4=null,$m5=null,$m6=null,$tot=null,$b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 35);
        $this->pdf->Cell(12,4,$month, $b.'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$m1  ? number_format($m1) : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$m2  ? number_format($m2) : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$m3  ? number_format($m3) : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$m4  ? number_format($m4) : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$m5  ? number_format($m5) : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$m6  ? number_format($m6) : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$tot  ? number_format($tot) : '', $b.'LR', 0,'R');
        $this->pdf->Ln();
    }
    
    public function rowData2($month='',$m1=null,$m2=null,$m3=null,$m4=null,$m5=null,$m6=null,$tot=null,$b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $p1 = $tot == 0 ? null : ($m1 / $tot) * 100.0;
        $p2 = $tot == 0 ? null : ($m2 / $tot) * 100.0;
        $p3 = $tot == 0 ? null : ($m3 / $tot) * 100.0;
        $p4 = $tot == 0 ? null : ($m4 / $tot) * 100.0;
        $p5 = $tot == 0 ? null : ($m5 / $tot) * 100.0;
        $p6 = $tot == 0 ? null : ($m6 / $tot) * 100.0;
        
        $this->pdf->SetX( 35);
        $this->pdf->Cell(12,4,$month, $b.'LR', 0,'C');
        $this->pdf->Cell(17.5,4,$p1  ? number_format($p1,1)."%"  : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$p2  ? number_format($p2,1)."%"  : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$p3  ? number_format($p3,1)."%"  : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$p4  ? number_format($p4,1)."%"  : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$p5  ? number_format($p5,1)."%"  : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,$p6  ? number_format($p6,1)."%"  : '', $b, 0,'R');
        $this->pdf->Cell(17.5,4,'100.0%', $b.'LR', 0,'R');
        $this->pdf->Ln();
    }
    
    
}