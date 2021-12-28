<?php


require(dirname(__FILE__).'/../PdfGraphReport.php');
require(dirname(__FILE__).'/../Traits/R9DataTrait.php');

class PdfRptR9 extends PdfGraphReport
{
    use R9DataTrait;
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R9 - RECIPIENT BY AREA 6 MONTH TREND REPORT';
        $this->filename = 'R9-RAREA-6M-TREND-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
        $this->data1y = [];
        $this->data2y = [];
        $this->data3y = [];
        $this->data4y = [];
        $this->data5y = [];
        $this->m6data = [];
    }
    
    function bar1PctVal($aLabel) {
        return $this->barPctVal($aLabel,1);
    }
    function bar2PctVal($aLabel) {
        return $this->barPctVal($aLabel,2);
    }
    function bar3PctVal($aLabel) {
        return $this->barPctVal($aLabel,3);
    }
    function bar4PctVal($aLabel) {
        return $this->barPctVal($aLabel,4);
    }
    function bar5PctVal($aLabel) {
        return $this->barPctVal($aLabel,5);
    }
    function barPctVal($aLabel,$dset) {
        if ($aLabel > 20.0)
            return "\n\n                         ".number_format($aLabel,1).'%';
        if ($aLabel > 10.0)
            return "\n                         ".number_format($aLabel,1).'%';
        if ($dset == 4)
            return "\n                           ".number_format($aLabel,1).'%';
        return "                           ".number_format($aLabel,1).'%';
    }
    
    function line1Val($aLabel) {
        return $this->lineVal($aLabel,$this->data1y);
    }
    function line2Val($aLabel) {
        return $this->lineVal($aLabel,$this->data2y);
    }
    function line3Val($aLabel) {
        return $this->lineVal($aLabel,$this->data3y);
    }
    function line4Val($aLabel) {
        return $this->lineVal($aLabel,$this->data4y);
    }
    function line5Val($aLabel) {
        return $this->lineVal($aLabel,$this->data5y);
    }
    function lineVal($aLabel,$data) {
        if ($aLabel != $data[5])
            return "";
        
//        $pos = array_search($aLabel, $this->m6data);
//        if ($pos > 0 && $pos < count($this->m6data) - 1) {
//            $pct = $this->m6data[$pos] / $this->m6data[$pos+1];
//            if ($pct > 0.95) {
//                return '';
//            }
//        }
        return " ".number_format($aLabel,0);
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
        
        $area = $data['dropoffs'][0]['area'];
        $p_idx = 0;
        while ($p_idx < count($data['dropoffs'])) {
            $left = $this->pdf->GetPageHeight() - $this->pdf->GetY();
            if ($left < 25) {
                $this->newPage();
            }
            if ($data['dropoffs'][$p_idx]['area'] != $area) {
                $tots = [];
                $ctots = null;
                switch ($area) {
                    case 'Hilton Head Area':
                        $tots = $data['hhiTotals'];
                        break;
                    case 'Bluffton Area':
                        $tots = $data['bluTotals'];
                        break;
                    case 'Beaufort Area':
                        $tots = $data['beaTotals'];
                        $ctots = $data['bcTotals'];
                        break;
                    case 'Jasper County':
                        $tots = $data['jcTotals'];
                        break;
                    case 'Hampton County':
                        $tots = $data['hcTotals'];
                        break;
                }
                $p = $data['total'] == 0 ? null : ($tots['tot'] / $data['total']) * 100.0;
                $this->rowTotal(
                    $area,
                    $tots['m1'],
                    $tots['m2'],
                    $tots['m3'],
                    $tots['m4'],
                    $tots['m5'],
                    $tots['m6'],
                    $tots['tot'],
                    $tots['tot'] * 2,
                    $p,
                    'T'
                );
                $this->rowPercent(
                    '% Total Food',
                    $data['alltotals']['m1'] == 0 ? null : ($tots['m1'] / $data['alltotals']['m1']) * 100.0,
                    $data['alltotals']['m2'] == 0 ? null : ($tots['m2'] / $data['alltotals']['m2']) * 100.0,
                    $data['alltotals']['m3'] == 0 ? null : ($tots['m3'] / $data['alltotals']['m3']) * 100.0,
                    $data['alltotals']['m4'] == 0 ? null : ($tots['m4'] / $data['alltotals']['m4']) * 100.0,
                    $data['alltotals']['m5'] == 0 ? null : ($tots['m5'] / $data['alltotals']['m5']) * 100.0,
                    $data['alltotals']['m6'] == 0 ? null : ($tots['m6'] / $data['alltotals']['m6']) * 100.0
                );

                $this->rowData();
                $this->rowData();
                
                if ($ctots != null) {
    
                    $p = $data['total'] == 0 ? null : ($ctots['tot'] / $data['total']) * 100.0;
                    $this->rowTotal(
                        'Beaufort County Total',
                        $ctots['m1'],
                        $ctots['m2'],
                        $ctots['m3'],
                        $ctots['m4'],
                        $ctots['m5'],
                        $ctots['m6'],
                        $ctots['tot'],
                        $ctots['tot'] * 2,
                        $p,
                        'T'
                    );
                    $this->rowPercent(
                        '% Total Food',
                        $data['alltotals']['m1'] == 0 ? null : ($ctots['m1'] / $data['alltotals']['m1']) * 100.0,
                        $data['alltotals']['m2'] == 0 ? null : ($ctots['m2'] / $data['alltotals']['m2']) * 100.0,
                        $data['alltotals']['m3'] == 0 ? null : ($ctots['m3'] / $data['alltotals']['m3']) * 100.0,
                        $data['alltotals']['m4'] == 0 ? null : ($ctots['m4'] / $data['alltotals']['m4']) * 100.0,
                        $data['alltotals']['m5'] == 0 ? null : ($ctots['m5'] / $data['alltotals']['m5']) * 100.0,
                        $data['alltotals']['m6'] == 0 ? null : ($ctots['m6'] / $data['alltotals']['m6']) * 100.0,
                        'B'
                    );
    
                    $this->newPage();
                    $this->rowData();
    
                }
                $area = $data['dropoffs'][$p_idx]['area'];
            }
    
            $p = $data['total'] == 0 ? null : ($data['dropoffs'][$p_idx]['tot'] / $data['total']) * 100.0;
            $this->rowData(
                $data['dropoffs'][$p_idx]['client'],
                $data['dropoffs'][$p_idx]['m1'],
                $data['dropoffs'][$p_idx]['m2'],
                $data['dropoffs'][$p_idx]['m3'],
                $data['dropoffs'][$p_idx]['m4'],
                $data['dropoffs'][$p_idx]['m5'],
                $data['dropoffs'][$p_idx]['m6'],
                $data['dropoffs'][$p_idx]['tot'],
                $data['dropoffs'][$p_idx]['tot'] * 2,
                $p
            );
            
            $mtotals['m1'] += $data['dropoffs'][$p_idx]['m1'];
            $mtotals['m2'] += $data['dropoffs'][$p_idx]['m2'];
            $mtotals['m3'] += $data['dropoffs'][$p_idx]['m3'];
            $mtotals['m4'] += $data['dropoffs'][$p_idx]['m4'];
            $mtotals['m5'] += $data['dropoffs'][$p_idx]['m5'];
            $mtotals['m6'] += $data['dropoffs'][$p_idx]['m6'];
            $mtotals['tot'] += $data['dropoffs'][$p_idx]['tot'];
            $p_idx++;
        }
    
        $tots = $data['hcTotals'];
        $p = $data['total'] == 0 ? null : ($tots['tot'] / $data['total']) * 100.0;
        $this->rowTotal(
            'Hamilton County',
            $tots['m1'],
            $tots['m2'],
            $tots['m3'],
            $tots['m4'],
            $tots['m5'],
            $tots['m6'],
            $tots['tot'],
            $tots['tot'] * 2,
            $p,
            'T'
        );
        $this->rowPercent(
            '% Total Food',
            $data['alltotals']['m1'] == 0 ? null : ($tots['m1'] / $data['alltotals']['m1']) * 100.0,
            $data['alltotals']['m2'] == 0 ? null : ($tots['m2'] / $data['alltotals']['m2']) * 100.0,
            $data['alltotals']['m3'] == 0 ? null : ($tots['m3'] / $data['alltotals']['m3']) * 100.0,
            $data['alltotals']['m4'] == 0 ? null : ($tots['m4'] / $data['alltotals']['m4']) * 100.0,
            $data['alltotals']['m5'] == 0 ? null : ($tots['m5'] / $data['alltotals']['m5']) * 100.0,
            $data['alltotals']['m6'] == 0 ? null : ($tots['m6'] / $data['alltotals']['m6']) * 100.0
        );
    
    
        $this->rowData();
        $this->rowData();
    
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
    
    
    
    
    
        $this->data1y=array(
            $data['jcTotals']['m1'],
            $data['jcTotals']['m2'],
            $data['jcTotals']['m3'],
            $data['jcTotals']['m4'],
            $data['jcTotals']['m5'],
            $data['jcTotals']['m6']
        );
        $this->data2y=array(
            $data['bluTotals']['m1'],
            $data['bluTotals']['m2'],
            $data['bluTotals']['m3'],
            $data['bluTotals']['m4'],
            $data['bluTotals']['m5'],
            $data['bluTotals']['m6']
        );
        $this->data3y=array(
            $data['beaTotals']['m1'],
            $data['beaTotals']['m2'],
            $data['beaTotals']['m3'],
            $data['beaTotals']['m4'],
            $data['beaTotals']['m5'],
            $data['beaTotals']['m6']
        );
        $this->data4y=array(
            $data['hhiTotals']['m1'],
            $data['hhiTotals']['m2'],
            $data['hhiTotals']['m3'],
            $data['hhiTotals']['m4'],
            $data['hhiTotals']['m5'],
            $data['hhiTotals']['m6']
        );
        $this->data5y=array(
            $data['hcTotals']['m1'],
            $data['hcTotals']['m2'],
            $data['hcTotals']['m3'],
            $data['hcTotals']['m4'],
            $data['hcTotals']['m5'],
            $data['hcTotals']['m6']
        );
        $this->m6data = array(
            $data['jcTotals']['m6'],
            $data['hhiTotals']['m6'],
            $data['bluTotals']['m6'],
            $data['beaTotals']['m6'],
            $data['hcTotals']['m6']
        );
        sort($this->m6data);
        
    
        // Create the graph. These two calls are always required
        $graph = new Graph(700,250,'auto');
        $graph->SetScale("textlin");
    
        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);
//        $graph->SetBox(true,'gray');
        $graph->legend->Hide(true);
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($this->headerLabels);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->SetLabelFormatCallback('valueFormat');

        // Create the bar plots
        $b1plot = new BarPlot($this->data1y);
        $b2plot = new BarPlot($this->data2y);
        $b3plot = new BarPlot($this->data3y);
        $b4plot = new BarPlot($this->data4y);
        $b5plot = new BarPlot($this->data5y);
    
        $gbplot = new GroupBarPlot(array($b4plot,$b2plot,$b3plot,$b1plot,$b5plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);
    
        // Yellow Jasper County
//        $b1plot->SetFastStroke(false);
        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#FFC003");
//        $b1plot->SetLineWeight(5);
//        $b1plot->SetWeight(1);
        $b1plot->value->show = true;
        $b1plot->value->SetFormatCallback([$this,'line1val']);
        $b1plot->value->SetAlign('center');
        $b1plot->value->SetAngle(90);
//        $b1plot->value->SetMargin(-5);
        $b1plot->value->SetColor('#555');

//        $b1plot->SetLegend('Jasper County Total');
    
        // Orange Bluffton
//        $b2plot->SetFastStroke(false);
//        $b2plot->SetLineWeight(1);
        $b2plot->SetColor("white");
        $b2plot->SetFillColor("#ED7D31");
//        $b2plot->SetWeight(1);
//        $b2plot->SetStyle('solid');
        $b2plot->value->show = true;
        $b2plot->value->SetFormatCallback([$this,'line2val']);
        $b2plot->value->SetAlign('center');
        $b2plot->value->SetAngle(90);
//        $b2plot->value->SetMargin(-5);
        $b2plot->value->SetColor('#555');
    
        // Gray Beaufort
//        $b3plot->SetFastStroke(false);
        $b3plot->SetColor("white");
        $b3plot->SetFillColor("#A5A5A5");
//        $b3plot->SetLineWeight(1);
        $b3plot->value->show = true;
        $b3plot->value->SetFormatCallback([$this,'line3val']);
        $b3plot->value->SetAlign('center');
        $b3plot->value->SetAngle(90);
//        $b3plot->value->SetMargin(-5);
        $b3plot->value->SetColor('#555');
    
        // blue hhi
//        $b4plot->SetFastStroke(false);
        $b4plot->SetColor("white");
        $b4plot->SetFillColor("#4572C4");
//        $b4plot->SetLineWeight(1);
        $b4plot->value->show = true;
        $b4plot->value->SetFormatCallback([$this,'line4val']);
        $b4plot->value->SetAlign('center');
        $b4plot->value->SetAngle(90);
//        $b4plot->value->SetMargin(-5);
        $b4plot->value->SetColor('#555');
    
//        $b5plot->SetFastStroke(false);
        $b5plot->SetColor("white");
        $b5plot->SetFillColor("#00B050");
//        $b5plot->SetLineWeight(1);
        $b5plot->value->show = true;
        $b5plot->value->SetFormatCallback([$this,'line5val']);
        $b5plot->value->SetAlign('center');
        $b5plot->value->SetAngle(90);
//        $b5plot->value->SetMargin(-5);
        $b5plot->value->SetColor('#555');


        $graph->title->SetFont(FF_DV_SANSSERIF,FS_NORMAL,12);
        $graph->title->SetColor('#333');
        $graph->title->Set("Second Helpings\nR9 - Recipient Agencies by Area 6 Month Trend Report\n ");
        $graph->setMargin(80,1,0,20);
    
        $graph->yaxis->title->Set('Pounds');
        $graph->yaxis->title->setMargin(40);
        $graph->yaxis->title->SetFont(FF_DEFAULT,FS_NORMAL,10);
    
//            error_log("Exists: ".function_exists('imageantialias'));
//        $this->pdf->Ln();
//        $this->pdf->Cell(60,4,"Exists: ".function_exists('imageantialias'),'', 0,'L');
    
//        $graph->img->SetAntiAliasing(true);
        
        $im=$graph->Stroke(_IMG_HANDLER);
        
        ob_start();
        imagepng($im);
        $img_data = ob_get_contents();
        ob_end_clean();
        $this->pdf->MemImage($img_data, 12, 150);
    
    
        if ($data['alltotals']['m1'] != 0) {
    
    
            $data1y = array(
                ($data['jcTotals']['m1'] / $data['alltotals']['m1']) * 100.0,
                ($data['jcTotals']['m2'] / $data['alltotals']['m2']) * 100.0,
                ($data['jcTotals']['m3'] / $data['alltotals']['m3']) * 100.0,
                ($data['jcTotals']['m4'] / $data['alltotals']['m4']) * 100.0,
                ($data['jcTotals']['m5'] / $data['alltotals']['m5']) * 100.0,
                ($data['jcTotals']['m6'] / $data['alltotals']['m6']) * 100.0,
            );
            $data2y = array(
                ($data['bluTotals']['m1'] / $data['alltotals']['m1']) * 100.0,
                ($data['bluTotals']['m2'] / $data['alltotals']['m2']) * 100.0,
                ($data['bluTotals']['m3'] / $data['alltotals']['m3']) * 100.0,
                ($data['bluTotals']['m4'] / $data['alltotals']['m4']) * 100.0,
                ($data['bluTotals']['m5'] / $data['alltotals']['m5']) * 100.0,
                ($data['bluTotals']['m6'] / $data['alltotals']['m6']) * 100.0,
            );
            $data3y = array(
                ($data['beaTotals']['m1'] / $data['alltotals']['m1']) * 100.0,
                ($data['beaTotals']['m2'] / $data['alltotals']['m2']) * 100.0,
                ($data['beaTotals']['m3'] / $data['alltotals']['m3']) * 100.0,
                ($data['beaTotals']['m4'] / $data['alltotals']['m4']) * 100.0,
                ($data['beaTotals']['m5'] / $data['alltotals']['m5']) * 100.0,
                ($data['beaTotals']['m6'] / $data['alltotals']['m6']) * 100.0,
            );
            $data4y = array(
                ($data['hhiTotals']['m1'] / $data['alltotals']['m1']) * 100.0,
                ($data['hhiTotals']['m2'] / $data['alltotals']['m2']) * 100.0,
                ($data['hhiTotals']['m3'] / $data['alltotals']['m3']) * 100.0,
                ($data['hhiTotals']['m4'] / $data['alltotals']['m4']) * 100.0,
                ($data['hhiTotals']['m5'] / $data['alltotals']['m5']) * 100.0,
                ($data['hhiTotals']['m6'] / $data['alltotals']['m6']) * 100.0,
            );
            $data5y = array(
                ($data['hcTotals']['m1'] / $data['alltotals']['m1']) * 100.0,
                ($data['hcTotals']['m2'] / $data['alltotals']['m2']) * 100.0,
                ($data['hcTotals']['m3'] / $data['alltotals']['m3']) * 100.0,
                ($data['hcTotals']['m4'] / $data['alltotals']['m4']) * 100.0,
                ($data['hcTotals']['m5'] / $data['alltotals']['m5']) * 100.0,
                ($data['hcTotals']['m6'] / $data['alltotals']['m6']) * 100.0,
            );
    
            // Create the graph. These two calls are always required
            $graph = new Graph(700, 250, 'auto');
            $graph->SetScale("textlin");
    
            $theme_class = new UniversalTheme;
            $graph->SetTheme($theme_class);
//            $graph->SetBox(true, 'gray');
    
            $graph->ygrid->SetFill(false);
            $graph->xaxis->SetTickLabels($this->headerLabels);
            $graph->yaxis->HideLine(false);
            $graph->yaxis->HideTicks(false, false);
            $graph->yscale->setAutoMax(100);
//            $graph->yaxis->SetLabelFormatCallback('valueFormat');
    
            // Create the bar plots
            $b1plot = new BarPlot($data1y);
            $b2plot = new BarPlot($data2y);
            $b3plot = new BarPlot($data3y);
            $b4plot = new BarPlot($data4y);
            $b5plot = new BarPlot($data5y);
    
            $gbplot = new AccBarPlot(array($b1plot,$b2plot,$b3plot,$b4plot,$b5plot));
            // ...and add it to the graPH
            $graph->Add($gbplot);
    
            $b1plot->SetColor("#FFC003");
            $b1plot->SetFillColor("#FFC003");
            $b1plot->SetLegend('Jasper County Total');
            $b1plot->value->SetColor('#555');
            $b1plot->value->SetFont(FF_DV_SANSSERIF,FS_NORMAL,7);
            $b1plot->value->show = true;
//            $b1plot->value->SetMargin(20);
            $b1plot->value->SetFormatCallback([$this,'bar1PctVal']);
            
            $b2plot->SetColor("#ED7D31");
            $b2plot->SetFillColor("#ED7D31");
            $b2plot->SetLegend('Bluffton Area');
            $b2plot->value->show = true;
            $b2plot->value->SetColor('#555');
            $b2plot->value->SetFont(FF_DEFAULT,FS_NORMAL,7);
//            $b2plot->value->SetMargin(35);
            $b2plot->value->SetFormatCallback([$this,'bar2PctVal']);
    
            $b3plot->SetColor("#A5A5A5");
            $b3plot->SetFillColor("#A5A5A5");
            $b3plot->SetLegend('Beaufort Area');
            $b3plot->value->show = true;
            $b3plot->value->SetColor('#555');
            $b3plot->value->SetFont(FF_DEFAULT,FS_NORMAL,7);
//            $b3plot->value->SetMargin(15);
            $b3plot->value->SetFormatCallback([$this,'bar3PctVal']);
    
            $b4plot->SetColor("#4572C4");
            $b4plot->SetFillColor("#4572C4");
            $b4plot->SetLegend('Hilton Head Area');
            $b4plot->value->show = true;
            $b4plot->value->SetColor('#555');
            $b4plot->value->SetFont(FF_DEFAULT,FS_NORMAL,7);
//            $b4plot->value->SetMargin(15);
            $b4plot->value->SetFormatCallback([$this,'bar4PctVal']);
    
            $b5plot->SetColor("#00B050");
            $b5plot->SetFillColor("#00B050");
            $b5plot->SetLegend('Hamilton County Total');
            $b5plot->value->show = true;
            $b5plot->value->SetColor('#555');
            $b5plot->value->SetFont(FF_DEFAULT,FS_NORMAL,7);
//            $b5plot->value->SetMargin(15);
            $b5plot->value->SetFormatCallback([$this,'bar5PctVal']);
    
            $graph->title->Hide(true);
            $graph->legend->Pos(0.5, 0.79, 'center', 'botton');
            $graph->legend->SetMarkAbsHSize(15);
            $graph->legend->SetMarkAbsHSize(15);
            $graph->legend->SetColumns(3);
            $graph->legend->SetLineSpacing(10);
            $graph->legend->SetLineWeight(0);
            $graph->legend->SetFont(FF_DEFAULT, FS_NORMAL, 8);
            $graph->legend->SetColor('#333');
    
            $graph->setMargin(80, 1, 10, 85);
            $graph->yaxis->title->Set('Percent Total Food');
            $graph->yaxis->title->setMargin(40);
            $graph->yaxis->title->SetFont(FF_DEFAULT, FS_NORMAL, 10);
    
            $im = $graph->Stroke(_IMG_HANDLER);
    
            ob_start();
            imagepng($im);
            $img_data = ob_get_contents();
            ob_end_clean();
            $this->pdf->MemImage($img_data, 12, 220);
    
        }
    
    
    
    
    
    
    
    
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
    
    public function rowPercent($name='',$m1=null,$m2=null,$m3=null,$m4=null,$m5=null,$m6=null,$b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 9);
        $this->pdf->Cell(52,4,$name,$b.'LR', 0,'R');
        $this->pdf->Cell(15,4,$m1 != null ? number_format($m1,1)."%" : '0.0%',$b, 0,'R');
        $this->pdf->Cell(15,4,$m2 != null ? number_format($m2,1)."%" : '0.0%',$b, 0,'R');
        $this->pdf->Cell(15,4,$m3 != null ? number_format($m3,1)."%" : '0.0%',$b, 0,'R');
        $this->pdf->Cell(15,4,$m4 != null ? number_format($m4,1)."%" : '0.0%',$b, 0,'R');
        $this->pdf->Cell(15,4,$m5 != null ? number_format($m5,1)."%" : '0.0%',$b, 0,'R');
        $this->pdf->Cell(15,4,$m6 != null ? number_format($m6,1)."%" : '0.0%',$b, 0,'R');
        
        $this->pdf->Cell(0.5,4,"",$b.'LR', 0,'C');
        
        $this->pdf->Cell(17.5,4,'',$b, 0,'R');
        $this->pdf->Cell(17.5,4,'',$b, 0,'R');
        $this->pdf->Cell(14,4,'',$b.'R', 0,'R');
        $this->pdf->Ln();
    }
    
    
}