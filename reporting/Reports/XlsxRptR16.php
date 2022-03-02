<?php


require(dirname(__FILE__).'/../XlsxReport.php');
require(dirname(__FILE__).'/../Traits/R16DataTrait.php');

use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Fill;

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
    public $borders_dates = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        ],
        'font' => [
            'size' => 10,
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
    public $center = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        ]
    ];
    public $color = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED,
            ],
        ],
    ];
    
    
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
        $this->header['reportName'] = 'R2 - DONOR & RECIPIENT RANK REPORT';
        $this->outputFile = $this->report_id.'-RESCUEDDIST-'.strtoupper($this->area).'-'.$this->reportDateLabel;
        $this->header_width = 17;
    }
    
    function run() {
        parent::run();
    
        $data = $this->data($this->reportDate,$this->area);
        $this->spreadsheet->removeSheetByIndex(0);
        for ($x=0; $x<7; $x++) {
            $week = $this->WEEKDAYS[$x];
            $newSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadsheet, $week);
            $this->spreadsheet->addSheet($newSheet, $x);
            $this->spreadsheet->setActiveSheetIndex($x);
            $sheet = $this->spreadsheet->getActiveSheet();
    
            $sheet->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setLeft(0.25);
            $sheet->getPageMargins()->setRight(0.25);
            $sheet->getPageSetup()->setFitToWidth(1);
            
    
        
            $this->header['reportName'] = $this->report_id." - RESCUED FOOD DISTRIBUTION - ".strtoupper($this->area)." - $week ROUTES";
            $this->writeXlsxHeader($this);
    
            $r = 6;
            // labels
            $sheet->getRowDimension($r)->setRowHeight(28);
            $sheet->setCellValueByColumnAndRow(1,$r,'Donor');
//            $sheet->getStyle('P9')->applyFromArray($this->color);
//            $sheet->getStyleByColumnAndRow(1,$r)->getFill()
//                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
//                ->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED);
    
            $sheet->setCellValueByColumnAndRow(2,$r,"P/U\nArea");
            $sheet->getStyleByColumnAndRow(2,$r)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(1,$r,1,$r)->applyFromArray($this->borders);
            $sheet->getStyleByColumnAndRow(2,$r,2,$r)->applyFromArray($this->borders_dates);
            for ($y=0; $y<12; $y++) {
                $label = new DateTime($data['pickups'][$week]['dates'][$y]);
                $sheet->setCellValueByColumnAndRow($y+3,$r,
                    $label->format('d-M'));
                $sheet->getStyleByColumnAndRow($y+3,$r,$y+3,$r)->applyFromArray($this->borders_dates);
            }
            $r++;
    
            $r = $this->writeTable($data['pickups'][$week]['rows'],$sheet,$r);
    
            $r ++;
            $sheet->setCellValueByColumnAndRow(15,$r,'Weight - Lbs.');
            $sheet->mergeCells($this->cellName(15,$r).':'.$this->cellName(17,$r));
            $sheet->getStyleByColumnAndRow(15,$r,17,$r)->applyFromArray($this->borders);
            $sheet->getStyleByColumnAndRow(15,$r)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDEEAF6');
            $r ++;
            $sheet->setCellValueByColumnAndRow(15,$r,"Avg.\nDrop Off");
            $sheet->mergeCells($this->cellName(15,$r).':'.$this->cellName(15,$r+1));
            $sheet->getStyleByColumnAndRow(15,$r,15,$r+1)->applyFromArray($this->borders);
            $sheet->getStyleByColumnAndRow(15,$r)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(15,$r)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDEEAF6');
    
            $sheet->setCellValueByColumnAndRow(16,$r,"Target\nDrop Off");
            $sheet->mergeCells($this->cellName(16,$r).':'.$this->cellName(16,$r+1));
            $sheet->getStyleByColumnAndRow(16,$r,16,$r+1)->applyFromArray($this->borders);
            $sheet->getStyleByColumnAndRow(16,$r)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(16,$r)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDEEAF6');
    
            $sheet->setCellValueByColumnAndRow(17,$r,"Avg.\nVariance\nTo Target");
            $sheet->mergeCells($this->cellName(17,$r).':'.$this->cellName(17,$r+1));
            $sheet->getStyleByColumnAndRow(17,$r,17,$r+1)->applyFromArray($this->borders);
            $sheet->getStyleByColumnAndRow(17,$r)->getAlignment()->setWrapText(true);
            $sheet->getStyleByColumnAndRow(17,$r)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDEEAF6');
    
            $r++;
            $sheet->getRowDimension($r)->setRowHeight(28);
            // labels
            $sheet->setCellValueByColumnAndRow(1,$r,'Recipient');
            $sheet->setCellValueByColumnAndRow(2,$r,"D/O\nArea");
            $sheet->getStyleByColumnAndRow(2,$r)->getAlignment()->setWrapText(true);
    
            $sheet->setCellValueByColumnAndRow(3,$r,'DROP OFF');
            $sheet->mergeCells($this->cellName(3,$r).':'.$this->cellName(14,$r));
            $sheet->getStyleByColumnAndRow(1,$r,1,$r)->applyFromArray($this->borders);
            $sheet->getStyleByColumnAndRow(2,$r,2,$r)->applyFromArray($this->borders_dates);
            $sheet->getStyleByColumnAndRow(3,$r,14,$r)->applyFromArray($this->borders);

//            for ($y=0; $y<12; $y++) {
//                $sheet->getStyleByColumnAndRow($y+2,$r,$y+2,$r)->applyFromArray($this->underline);
//            }
            $r++;
            $r = $this->writeTable($data['dropoffs'][$week]['rows'],$sheet,$r,true);
    
    
            foreach ($sheet->getColumnIterator() as $column) {
                $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
//                $sheet->getColumnDimension($column->getColumnIndex())->setWidth(7.62);
            }
    
    
            $sheet->getColumnDimension('B')->setAutoSize(false);
            $sheet->getColumnDimension('B')->setWidth(4.67);
        }
    
        $this->spreadsheet->setActiveSheetIndex(0);
        $this->output();
    }
    
    public function writeTable($rows,$sheet,$r,$table=false) {
        $tr = $r;
        $i = 0;
        while ($i < count($rows)) {
            $row = $rows[$i];
        
            $sheet->setCellValueByColumnAndRow(1,$r,$row['client']);
            $sheet->setCellValueByColumnAndRow(2,$r,$row['area']);
            $sheet->getStyleByColumnAndRow(2,$r,2,$r)->applyFromArray($this->center);
            for ($y=0; $y<12; $y++) {
                if ($row['data'][$y] != 0)
                    $sheet->setCellValueByColumnAndRow($y+3,$r,
                        $row['data'][$y]);
            }
    
            if ($table) {
                $c1 = $this->cellName(3, $r);
                $c2 = $this->cellName(14, $r);
                $sheet->setCellValueByColumnAndRow(15, $r,
                    "=ROUND(AVERAGE($c1:$c2),0)");
                $sheet->getStyleByColumnAndRow(15, $r, 15, $r)->applyFromArray($this->borders);
                $sheet->getStyleByColumnAndRow(15,$r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFDEEAF6');
                $sheet->getStyleByColumnAndRow(15,$r)->getFont()->setBold(false);
    
    
                $sheet->setCellValueByColumnAndRow(16,$r,$row['target_do']);
                $sheet->getStyleByColumnAndRow(16, $r, 16, $r)->applyFromArray($this->borders);
                $sheet->getStyleByColumnAndRow(16,$r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFDEEAF6');
                $sheet->getStyleByColumnAndRow(16,$r)->getFont()->setBold(false);
    
                $c1 = $this->cellName(15, $r);
                $c2 = $this->cellName(16, $r);
                $sheet->setCellValueByColumnAndRow(17, $r,
                    "=$c1-$c2");
                $sheet->getStyleByColumnAndRow(17, $r, 17, $r)->applyFromArray($this->borders);
                $sheet->getStyleByColumnAndRow(17, $r, 17, $r)
                    ->getNumberFormat()->setFormatCode('0;[Red](0)');;
                $sheet->getStyleByColumnAndRow(17,$r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFDEEAF6');
                $sheet->getStyleByColumnAndRow(17,$r)->getFont()->setBold(false);

//                $c1 = $this->cellName(15, $r);
//                $c2 = $this->cellName(17, $r);
//                $sheet->getStyle($c1.':'.$c2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
//                $sheet->getStyle($c1.':'.$c2)->getFill()->getStartColor()->setARGB('FFDEEAF6');
    
    
            }
    
            $r++;
            $i++;
        }
    
        $sheet->setCellValueByColumnAndRow(1,$r,'Total');
        $sheet->getStyleByColumnAndRow(1,$r,2,$r)->applyFromArray($this->overline);
        for ($y=0; $y<12; $y++) {
            $c1 = $this->cellName($y+3,$tr);
            $c2 = $this->cellName($y+3,$r-1);
            $sheet->setCellValueByColumnAndRow($y+3,$r,
                $tr == $r ? 0 : "=SUM($c1:$c2)");
            $sheet->getStyleByColumnAndRow($y+3,$r,$y+3,$r)->applyFromArray($this->overline);
        }
        
        $r++;
        return $r;
    }
    
}