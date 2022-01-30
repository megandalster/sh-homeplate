<?php


require_once(dirname(__FILE__).'/../PdfReport.php');
require_once(dirname(__FILE__).'/../Traits/R12DataTrait.php');
require_once(dirname(__FILE__).'/../Traits/R13DataTrait.php');

class PdfRptR13 extends PdfReport
{
    use R12DataTrait,R13DataTrait {
        R13DataTrait::data insteadof R12DataTrait;
        R12DataTrait::data as r12data;
    }
    
    private $year_start = null;
    private $month_start = null;
    private $end_date = null;
    private $weeks_label = '@Wks/Mo.';
    
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        
        // need this month start and end date
        $this->month_start = $this->reportDate->format('y-m').'-01';
        $this->end_date = (new DateTime($this->month_start))->modify('+1 month')->format('y-m-d');
        $this->year_start = $this->reportDate->format('y').'-01-01';

        $this->header['reportName'] = 'R13 - Agency Food Distribution Variance to Target Report';
        $this->filename = 'R13-DIST-TARGETVAR-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    
    }
    
    function run()
    {
        parent::run();
        $data = $this->r12data($this->month_start,$this->end_date);
        $month_pppspw = (array_pop($data['rows']))[9];
    
        $data = $this->r12data($this->year_start,$this->end_date);
        $ytd_pppspw = (array_pop($data['rows']))[9];
    
        $data = $this->data($this->month_start,$this->end_date,$this->year_start,$month_pppspw,$ytd_pppspw);
    
        $this->newPage();
    
        $need_line = true;
        $idx = 0;
        while ($idx < count($data['rows'])) {
            $row = $data['rows'][$idx];
            if ($need_line) {
                $row[] = 'T';
                $need_line = false;
            }

//            error_log(print_r($row,true));
            $type = array_shift($row);
            if ($type == 0) {
                $this->rowData(...$row);
            } else if ($type == 1) {
                $row[] = 'TB';
                $this->rowTotal(...$row);
                $this->rowBlank('TB');
            } else if ($type == 2) {
                $row[] = 'TB';
                $this->rowTotal(...$row);
                if (strpos($row[0], 'Beaufort Co.') === 0) {
                    $this->newPage();
                } else
                    $this->rowBlank('TB');
                $need_line = true;
            } else {
                $row[] = 'TB';
                $this->rowTotal(...$row);
                $this->rowTop();  // have no idea why i need this, but getting tired of debugging it
            }
            
            $idx++;
        }
        
        $this->pdf->Ln();
        $this->pdf->SetFontSize(8);
        $this->pdf->SetX( 8);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(75,4,'(a)  Persons Served Per Week (PSPW), 2021 Agency Data',0, 0,'L');
        $this->pdf->Ln();
        $this->pdf->SetX( 8);
        $this->pdf->Cell(75,4,'(b)  Target PPPSPW is derived from R- 12 Report Mo. and YTD',0, 0,'L');
        $this->pdf->Ln();
        $this->pdf->SetX( 8);
        $this->pdf->Cell(5,4,'(c) ');
        $this->pdf->SetFont('','B');
        $this->pdf->Cell(17.5,4,'Target Need');
        $this->pdf->SetFont('','');
        $this->pdf->Cell(85,4,'is Pounds Per Persons Served Per Week (PPPSPW) that results in');
        $this->pdf->SetFont('','B');
        $this->pdf->Cell(9,4,'Equal');
        $this->pdf->SetFont('','');
        $this->pdf->Cell(35,4,'food distribution across Second Helpings Agencies.');

        $this->output();
    }
    function newPage() {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->Ln();
        
        $y1 = $this->pdf->GetY();
        $y2 = $y1+4;
        $y3 = $y2+4;
        $y4 = $y3+4;
        $y5 = $y4+4;

        $this->pdf->SetFontSize(8);
        $x = 8 + 54 + 13;
        $this->setFill('blue');
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(16*4,4,"Month",1, 'C',1);
        $x = 8 + 54 + 13.5 + (4*16);
        $this->setFill('green');
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(16*4,4,"YTD",1, 'C',1);
        
        $x = 8 + 54;
        $this->pdf->SetXY($x,$y2);
        $this->setFill('yellow');
        $this->pdf->SetFont('','B');
        $this->pdf->MultiCell(13,4,"\n\n    2021",1, 'C',1);
        $this->pdf->SetFont('','');
    
        $this->pdf->SetXY($x,$y4-1);
        $this->pdf->SetFontSize(6);
        $this->pdf->Cell(13,4," (a)",0, 'L');
        $this->pdf->SetFontSize(9);
        $x += 13;
        $this->pdf->SetXY($x,$y2);
        $this->pdf->MultiCell(16,4,"\n\n  Target",1, 'C');
        $this->pdf->SetXY($x,$y4-1);
        $this->pdf->SetFontSize(6);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(13,4," (b)",0, 'L');
        $this->pdf->SetFontSize(9);
        
        
        $x += 16;
        $this->pdf->SetXY($x,$y2);
        $this->pdf->MultiCell(16,4,"\n  Target\nNeed",1, 'C');
        $this->pdf->SetXY($x,$y3-1);
        $this->pdf->SetFontSize(6);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(13,4," (c)",0, 'L');
        $this->pdf->SetFontSize(9);
    
        $x += 16;
        $this->pdf->SetXY($x,$y2);
        $this->setFill('blue');
        $this->pdf->MultiCell(16,4,"Actual\nAdjusted\nDelivery",1, 'C',1);
        $x += 16;
        $this->pdf->SetXY($x,$y2);
        $this->pdf->MultiCell(16,4,"\nVariance\nTo Target",1, 'C',);
        $x += 16.5;
        $this->pdf->SetXY($x,$y2);
        $this->pdf->MultiCell(16,4,"\n\n  Target",1, 'C');
        $this->pdf->SetXY($x,$y4-1);
        $this->pdf->SetFontSize(6);
        $this->pdf->Cell(13,4," (b)",0, 'L');
        $this->pdf->SetFontSize(9);

        $x += 16;
        $this->pdf->SetXY($x,$y2);
        $this->pdf->SetFontSize(9);
        $this->pdf->MultiCell(16,4,"\n  Target\nNeed",1, 'C');
        $this->pdf->SetXY($x,$y3-1);
        $this->pdf->SetFontSize(6);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(13,4," (c)",0, 'L');
        $this->pdf->SetFontSize(9);
    
        $x += 16;
        $this->pdf->SetXY($x,$y2);
        $this->setFill('green');
        $this->pdf->MultiCell(16,4,"Actual\nAdjusted\nDelivery",1, 'C',1);
        $x += 16;
        $this->pdf->SetXY($x,$y2);
        $this->pdf->MultiCell(16,4,"\nVariance\nTo Target",1, 'C',);
        
        
        $this->pdf->SetFont('','');
    
    
        $this->pdf->SetX( 8+54);
        $this->pdf->SetFont('','B');
        $this->setFill('yellow');
        $this->pdf->Cell(13,4,"PSPW",1, 0,'C',1);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(16,4,"PPPSPW",1, 0,'C');
        $this->pdf->Cell(16,4,"Lbs./Wk",1, 0,'C');
        $this->setFill('blue');
        $this->pdf->Cell(16,4,"Lbs./Wk",1, 0,'C',1);
        $this->pdf->Cell(16,4,"Lbs./Wk",1, 0,'C',);
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(16,4,"PPPSPW",1, 0,'C');
        $this->pdf->Cell(16,4,"Lbs./Wk",1, 0,'C');
        $this->setFill('green');
        $this->pdf->Cell(16,4,"Lbs./Wk",1, 0,'C',1);
        $this->pdf->Cell(16,4,"Lbs./Wk",1, 0,'C',);
    
        $this->pdf->Ln();
        
    
    }
    
    public function setFill($color) {
        switch ($color) {
            case 'gray':
                $this->pdf->SetFillColor(242,242,242);
                break;
            case 'blue':
                $this->pdf->SetFillColor( 0xDE,0xEB,0xF7);
                break;
            case 'green':
                $this->pdf->SetFillColor( 0xE1,0xEF, 0xDA);
                break;
            case 'orange':
                $this->pdf->SetFillColor( 0xFE,0xE6,0x99);
                break;
            case 'yellow':
                $this->pdf->SetFillColor(0xFF, 0xF1, 0xCC );
                break;
            case 'red':
                $this->pdf->SetFillColor( 0xFC,0xE4,0xD6);
                break;
            default:
                $this->pdf->SetFillColor(0,0,0);
                break;
        }
    }
    public function rowData($name='',$d1=null,$d2=null,$d3=null,$d4=null,$d5=null,$d6=null,$d7=null,$d8=null,$d9=null,$b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,$name,$b.'LR', 0,'L');
        $this->setFill('yellow');
        $this->pdf->Cell(13,4,$d1 != null ? number_format($d1) : '',$b.'LR', 0,'C',1);
        $this->pdf->Cell(16,4,$d2 != null ? number_format($d2,1) : '',$b, 0,'C');
        $this->pdf->Cell(16,4,$d3 != null ? number_format($d3) : '',$b, 0,'R');
        $this->setFill('blue');
        $this->pdf->Cell(16,4,$d4 != null ? number_format($d4) : '',$b.'LR', 0,'R',1);
        if ($d5 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(16,4,$d5 != null ? number_format($d5) : '',$b.'R', 0,'R');
        if ($d5 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Cell(0.5,4,'',0, 0,'R');
        $this->pdf->Cell(16,4,$d6 != null ? number_format($d6,1) : '',$b.'L', 0,'C');
        $this->pdf->Cell(16,4,$d7 != null ? number_format($d7) : '',$b, 0,'R');
        $this->setFill('green');
        $this->pdf->Cell(16,4,$d8 != null ? number_format($d8) : '',$b.'LR', 0,'R',1);
        if ($d9 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(16,4,$d9 != null ? number_format($d9) : '',$b.'R', 0,'R');
        if ($d9 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Ln();
    }
    
    public function rowTotal($name='',$d1=null,$d2=null,$d3=null,$d4=null,$d5=null,$d6=null,$d7=null,$d8=null,$d9=null,$b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,$name,$b.'LR', 0,'L');
        $this->setFill('yellow');
        $this->pdf->Cell(13,4,$d1 != null ? number_format($d1) : '',$b.'LR', 0,'C',1);
        $this->pdf->Cell(16,4,$d2 != null ? number_format($d2,1) : '',$b, 0,'C');
        $this->pdf->Cell(16,4,$d3 != null ? number_format($d3) : '', $b, 0,'R');
        $this->setFill('blue');
        $this->pdf->Cell(16,4,$d4 != null ? number_format($d4) : '',$b.'LR', 0,'R',1);
        if ($d5 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(16,4,$d5 != null ? number_format($d5) : '',$b.'R', 0,'R');
        if ($d5 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Cell(0.5,4,'',0, 0,'R');
        $this->pdf->Cell(16,4,$d6 != null ? number_format($d6,1) : '',$b.'L', 0,'C');
        $this->pdf->Cell(16,4,$d7 != null ? number_format($d7) : '',$b, 0,'R');
        $this->setFill('green');
        $this->pdf->Cell(16,4,$d8 != null ? number_format($d8) : '',$b.'LR', 0,'R',1);
        if ($d9 < 0) $this->pdf->SetTextColor(255,0, 0);
        $this->pdf->Cell(16,4,$d9 != null ? number_format($d9) : '',$b.'R', 0,'R');
        if ($d9 < 0) $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->Ln();
    }
    
    public function rowBlank($b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,'',$b.'L', 0,'L');
        $this->setFill('yellow');
        $this->pdf->Cell(13,4,'',$b, 0,'C');
        $this->pdf->Cell(16,4,'',$b, 0,'C');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->setFill('blue');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(0.5,4,'',0, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'C');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->setFill('green');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(16,4,'',$b.'R', 0,'R');
        $this->pdf->Ln();
    }
    
    public function rowTop() {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        $b = 'T';
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,'',$b, 0,'L');
        $this->pdf->Cell(13,4,'',$b, 0,'C');
        $this->pdf->Cell(16,4,'',$b, 0,'C');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(0.5,4,'',0, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'C');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Cell(16,4,'',$b, 0,'R');
        $this->pdf->Ln();
    }
}