<?php


require_once(dirname(__FILE__).'/../PdfReport.php');
require_once(dirname(__FILE__).'/../Traits/R12DataTrait.php');

class PdfRptR12 extends PdfReport
{
    use R12DataTrait;
    
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

        $this->header['reportName'] = 'R12 - FOOD PER PERSON SERVED REPORT';
        $this->filename = 'R12-PSERVED-'.$this->reportDateLabel;
        $this->pdf->setTitle($this->filename);
    
    }
    
    function run()
    {
        parent::run();
        $this->_run($this->month_start,$this->end_date,);
    
        $this->weeks_label = '@Wks/YTD';
        $this->header['reportDate'] = $this->header['reportDate'] . ' YTD';
        $this->_run($this->year_start,$this->end_date);
        $this->output();
    }
    function _run($start_date,$end_date) {
        $data = $this->data($start_date,$end_date);
    
        $this->newPage($data['weeks_in_report']);
    
        $idx = 0;
        while ($idx < count($data['rows'])) {
            $row = $data['rows'][$idx];
            if ($idx == 0) $row[] = 'T';

//            error_log(print_r($row,true));
            $type = array_shift($row);
            if ($type == 0) {
                $this->rowData(...$row);
            } else if ($type == 1) {
                $this->rowData();
                $this->rowTotal(...$row);
                $this->rowData();
            } else if ($type == 2) {
                $this->rowData();
                $this->rowTotal(...$row);
                $this->rowData();
            } else {
                $row[] = 'TBLR';
                $row[] = 3;
                $this->rowTotal(...$row);
            }
            
            $idx++;
        }

        
//        $this->rowData('Canaan Missionary Baptist Church',
//            500000,
//            500000,
//            500000,
//            500000,
//            500000,
//            9999,
//            500000,
//            999.9);
//        $this->rowData(null,null,null,null,null,null,null,null,null,null);
//        $this->rowTotal('Hampton Co.',
//            2852863,
//            235687,
//            92152,
//            12991,
//            6046,
//            24,
//            8137,
//            6.5,
//            'B');
//        $this->pdf->Ln();
//        $this->rowTotal('Total of Above',
//            2852863,
//            235687,
//            92152,
//            0,
//            12991,
//            314,
//            12312,
//            6.5,
//            'TBLR');
//
//        $this->pdf->Ln();
    
    
        $this->pdf->Ln();
        $this->pdf->SetX( 8);
        $this->pdf->SetFont('','');
        $this->pdf->Cell(75,4,'*** Table excludes Recipients without Persons Served Per Week Data',0, 0,'L');

    }
    function newPage($weeks_in_report) {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
    
        $x = 8+54;
        $y1 = $this->pdf->GetY();
        $y2 = $y1+4;
        $y3 = $y2+4;
        $y4 = $y3+4;
        $y5 = $y4+4;
        $y6 = $y5+4;
//        $this->pdf->SetX( $x);
//        $this->pdf->Cell((3*16)+16,4,"Month - Lbs",1, 0,'C');
//        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
//        $this->pdf->Cell((3*16)+16,4,"Year-to-Date - Lbs",1, 0,'C');
//        $this->pdf->Ln();
    
        $this->pdf->SetFontSize(8);
        $this->pdf->SetTextColor(255,0,0);
        $x = 8 + 54 + (5*18) + 15;
        $this->pdf->SetXY($x,$y5);
        $this->pdf->MultiCell(18,4,$this->weeks_label,1, 'C');
        $x = 8 + 54 + (6*18) + 15;
        $this->setFill('green');
        $this->pdf->SetXY($x,$y5);
        $this->pdf->MultiCell(17,4,$this->weeks_label,1, 'C',1);
    
        $this->pdf->SetFontSize(9);
        $x = 8 + 54 + (5*18) + 15;
        $this->pdf->SetXY($x,$y6);
        $this->pdf->MultiCell(18,4,$weeks_in_report,1, 'C');
    
        $x = 8 + 54 + (6*18) + 15;
        $this->pdf->SetXY($x,$y6);
        $this->pdf->MultiCell(17,4,$weeks_in_report,1, 'C',1);
    
        $this->pdf->SetTextColor(0,0,0);
    
        $x = 8 + 54;
        $this->pdf->SetXY($x,$y1);
        $this->setFill('gray');
        $this->pdf->MultiCell(18,4,"\n\n\n\nDelivered\nFood",1, 'C',1);
        $x += 18;
        $this->pdf->SetXY($x,$y1);
        $this->pdf->SetFontSize(8);
        $this->pdf->MultiCell(18,4,"\n\n\n\nTransported\nFood",1, 'C');
        $this->pdf->SetFontSize(9);
        $x += 18;
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(18,4,"\n\n\n\nPurchased\nFood",1, 'C');
        $x += 18;
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(18,4,"\n\n\n\nFood Drive Food",1, 'C');
        $x += 18;
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(18,4,"\n\n\nAdjusted\nDelivered\nFood",1, 'C',1);
        $x += 18.5;
        $this->pdf->SetXY($x,$y1);
        $this->setFill('blue');
        $this->pdf->MultiCell(14,4,"Persons\nServed\nPer\nWeek (2021 data)",1, 'C',1);
        $x += 14.5;
        $this->pdf->SetXY($x,$y1);
        $this->pdf->MultiCell(18,4,"\nAdjusted\nDelivered\nFood",1, 'C');
        $x += 18;
        $this->pdf->SetXY($x,$y1);
        $this->setFill('green');
        $this->pdf->MultiCell(17,4,"Adjusted\nDelivered\nFood        \n ",1, 'C',1);
        $this->pdf->SetXY($x,$y1);
        $this->pdf->SetFont('','B');
        $this->pdf->MultiCell(17,4,"\n\n         Per\nPerson",1, 'C',);
        $this->pdf->SetFont('','');
    
        $this->pdf->Ln();
        $this->pdf->Ln();
    
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,"Recipient ***",1, 0,'L');
        $this->setFill('gray');
        $this->pdf->Cell(18,4,"Lbs.",1, 0,'C',1);
        $this->pdf->Cell(18,4,"Lbs.",1, 0,'C');
        $this->pdf->Cell(18,4,"Lbs.",1, 0,'C');
        $this->pdf->Cell(18,4,"Lbs.",1, 0,'C');
        $this->pdf->Cell(18,4,"Lbs.",1, 0,'C',1);
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->setFill('blue');
        $this->pdf->Cell(14,4,"No.",1, 0,'C',1);
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(18,4,"Lbs./Wk",1, 0,'C');
        $this->setFill('green');
        $this->pdf->Cell(17,4,"Lbs./Wk",1, 0,'C',1);
    
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
                $this->pdf->SetFillColor(0xFE,0xFF,0x00 );
                break;
            case 'red':
                $this->pdf->SetFillColor( 0xFC,0xE4,0xD6);
                break;
            default:
                $this->pdf->SetFillColor(0,0,0);
                break;
        }
    }
    public function rowData($name='',$dw=null,$tw=null,$pw=null,$fd=null,$adf=null,$pspw=null,$adfpw=null,$adfpp=null,$b='') {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,$name,$b.'LR', 0,'L');
        $this->setFill('gray');
        $this->pdf->Cell(18,4,$dw != null ? number_format($dw) : '',$b.'LR', 0,'R',1);
        $this->pdf->Cell(18,4,$tw != null ? number_format($tw) : '',$b, 0,'R');
        $this->pdf->Cell(18,4,$pw != null ? number_format($pw) : '',$b, 0,'R');
        $this->pdf->Cell(18,4,$fd != null ? number_format($fd) : '',$b, 0,'R');
        $this->pdf->Cell(18,4,$adf != null ? number_format($adf) : '',$b.'LR', 0,'R',1);
        $this->pdf->Cell(0.5,4,'',0, 0,'R');
        $this->setFill('blue');
        $this->pdf->Cell(14,4,$pspw != null ? number_format($pspw) : '',$b.'LR', 0,'R',1);
        $this->pdf->Cell(0.5,4,'',0, 0,'R');
        $this->pdf->Cell(18,4,$adfpw != null ? number_format($adfpw) : '',$b.'LR', 0,'R');
        $this->setFill('green');
        $this->pdf->Cell(17,4,$adfpp  != null ? number_format($adfpp,1) : '',$b.'LR', 0,'C',1);
        $this->pdf->Ln();
    }
    
    public function rowTotal($name='',$dw=null,$tw=null,$pw=null,$fd=null,$adf=null,$pspw=null,$adfpw=null,$adfpp=null,$b='',$dec=1) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetX( 8);
        $this->pdf->Cell(54,4,$name,$b.'LR', 0,'L');
        $this->setFill('gray');
        $this->pdf->Cell(18,4,$dw != null ? number_format($dw) : '-   ',$b.'LR', 0,'R',1);
        $this->pdf->Cell(18,4,$tw != null ? number_format($tw) : '-   ',$b, 0,'R');
        $this->pdf->Cell(18,4,$pw != null ? number_format($pw) : '-   ',$b, 0,'R');
        $this->pdf->Cell(18,4,$fd != null ? number_format($fd) : '-   ',$b, 0,'R');
        $this->pdf->Cell(18,4,$adf != null ? number_format($adf) : '-   ',$b.'LR', 0,'R',1);
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->setFill('blue');
        $this->pdf->Cell(14,4,$pspw != null ? number_format($pspw) : '-   ',$b.'LR', 0,'R',1);
        $this->pdf->Cell(0.5,4,'','LR', 0,'R');
        $this->pdf->Cell(18,4,$adfpw != null ? number_format($adfpw) : '-   ',$b.'L', 0,'R');
        $this->setFill('green');
        $this->pdf->Cell(17,4,$adfpp  != null ? number_format($adfpp,$dec) : '-',$b.'LR', 0,'C',1);
        $this->pdf->Ln();
    }
    
    
}