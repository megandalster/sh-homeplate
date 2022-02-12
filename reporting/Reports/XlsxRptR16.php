<?php


require(dirname(__FILE__).'/../XlsxReport.php');
require(dirname(__FILE__).'/../Traits/R16DataTrait.php');

use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;

class XlsxRptR16 extends XlsxReport
{
    use R16DataTrait;
    
    public $report_id = 'R16';
    public $area = 'Beaufort';
    
    public $borders = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        ],
        'font' => [
            'bold' => true
        ]
    ];
    public $overline = [
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ],
        'font' => [
            'bold' => true
        ]
    ];
    public $underline = [
        'borders' => [
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ]
    ];
    
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R2 - DONOR & RECIPIENT RANK REPORT';
        $this->outputFile = $this->report_id.'-RESCUEDDIST-'.strtoupper($this->area).'-'.$this->reportDateLabel;
        $this->header_width = 13;
    }
    
    function run() {
        parent::run();
    
        $data = $this->data($this->reportDate,$this->area);
    
        $this->spreadsheet->removeSheetByIndex(0);
        for ($x=0; $x<7; $x++) {
            $week = $this->WEEKDAYS[$x];
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadsheet, $week);
            $this->spreadsheet->addSheet($myWorkSheet, $x);
            $this->spreadsheet->setActiveSheetIndex($x);
            $this->spreadsheet->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(32.5);
    
            $this->header['reportName'] = $this->report_id." - RESCUED FOOD DISTRIBUTION - ".strtoupper($this->area)." - $week ROUTES";
            $this->writeXlsxHeader($this);
    
            $r = 6;
            // labels
            $this->spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$r,'Donor');
            $this->spreadsheet->getActiveSheet()->getStyleByColumnAndRow(1,$r,1,$r)->applyFromArray($this->borders);
            for ($y=0; $y<12; $y++) {
                $label = new DateTime($data['dropoffs'][$week]['dates'][$y]);
                $this->spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($y+2,$r,
                    $label->format('d-M'));
                $this->spreadsheet->getActiveSheet()->getStyleByColumnAndRow($y+2,$r,$y+2,$r)->applyFromArray($this->borders);
            }
            $r++;
            
            $r = $this->writeTable($data['dropoffs'][$week]['rows'],$this->spreadsheet->getActiveSheet(),$r);
            
            $r += 2;
    
            // labels
            $this->spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$r,'Recipient');
            $this->spreadsheet->getActiveSheet()->getStyleByColumnAndRow(1,$r,1,$r)->applyFromArray($this->borders);
            for ($y=0; $y<12; $y++) {
                $this->spreadsheet->getActiveSheet()->getStyleByColumnAndRow($y+2,$r,$y+2,$r)->applyFromArray($this->underline);
            }
            $r++;
            $r = $this->writeTable($data['pickups'][$week]['rows'],$this->spreadsheet->getActiveSheet(),$r);
    
        }
    
        $this->spreadsheet->setActiveSheetIndex(0);
        $this->output();
    }
    
    public function writeTable($rows,$sheet,$r) {
        $tr = $r;
        $i = 0;
        while ($i < count($rows)) {
            $row = $rows[$i];
        
            $sheet->setCellValueByColumnAndRow(1,$r,$row['client']);
            for ($y=0; $y<12; $y++) {
                if ($row['data'][$y] != 0)
                    $sheet->setCellValueByColumnAndRow($y+2,$r,
                        $row['data'][$y]);
            }
            $r++;
            $i++;
        }
    
        $sheet->setCellValueByColumnAndRow(1,$r,'Total');
        $sheet->getStyleByColumnAndRow(1,$r,1,$r)->applyFromArray($this->overline);
        for ($y=0; $y<12; $y++) {
            $c1 = $this->cellName($y+2,$tr);
            $c2 = $this->cellName($y+2,$r-1);
            $sheet->setCellValueByColumnAndRow($y+2,$r,
                $tr == $r ? 0 : "=SUM($c1:$c2)");
            $sheet->getStyleByColumnAndRow($y+2,$r,$y+2,$r)->applyFromArray($this->overline);
        }
        $r++;
        return $r;
    }
    
}