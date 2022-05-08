<?php


require(dirname(__FILE__).'/../XlsxReport.php');
require(dirname(__FILE__).'/../Traits/R19DataTrait.php');

use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Fill;

class XlsxRptR19 extends XlsxReport
{
    use R19DataTrait;
    
    public $report_id = 'R19';
    
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
        $this->header['reportName'] = 'R19 - TRUCK VOLUNTEER TRIP REPORT';
        $this->outputFile = $this->report_id.'-TRKVOLTRIP-'.$this->reportDateLabel;
        $this->header_width = 7;
    }
    
    function run() {
        parent::run();
        
        $data = $this->data($this->reportDate);
        $this->spreadsheet->removeSheetByIndex(0);

        $newSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadsheet, 'Trip Summary');
        $this->spreadsheet->addSheet($newSheet, 0);
        $this->spreadsheet->setActiveSheetIndex(0);
        $sheet = $this->spreadsheet->getActiveSheet();
        
        $sheet->getPageMargins()->setLeft(0.30);
        $sheet->getPageMargins()->setRight(0.25);
//        $sheet->getPageSetup()->setFitToWidth(1);
        
        $this->header['reportName'] = $this->report_id." - Truck Volunteer Summary Trip Report";
        $this->writeXlsxHeader($this);
        $r = 6;
        $sheet->getColumnDimension('A')->setWidth(23.00);
        $sheet->getColumnDimension('B')->setWidth(9.00);
        $sheet->getColumnDimension('C')->setWidth(23.00);
        $sheet->getColumnDimension('D')->setWidth(9.83);
        $sheet->getColumnDimension('E')->setWidth(9.83);
        $sheet->getColumnDimension('F')->setWidth(9.83);
        $sheet->getColumnDimension('G')->setWidth(9.83);
    
        $sheet->setCellValueByColumnAndRow(4,$r,'Trips');
        $sheet->mergeCells($this->cellName(4,$r).':'.$this->cellName(7,$r));
        $sheet->getStyleByColumnAndRow(4,$r,7,$r)->applyFromArray($this->borders);
        $r++;
    
        $sheet->getRowDimension($r)->setRowHeight(28);
        $sheet->setCellValueByColumnAndRow(1,$r,'Truck Volunteer');
        $sheet->getStyleByColumnAndRow(1,$r,1,$r)->applyFromArray($this->borders);
        $sheet->setCellValueByColumnAndRow(2,$r,'Base');
        $sheet->getStyleByColumnAndRow(2,$r,2,$r)->applyFromArray($this->borders);
        $sheet->setCellValueByColumnAndRow(3,$r,'Roles');
        $sheet->getStyleByColumnAndRow(3,$r,3,$r)->applyFromArray($this->borders);
        $sheet->setCellValueByColumnAndRow(4,$r,"Current\nYTD");
        $sheet->getStyleByColumnAndRow(4,$r)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(4,$r,4,$r)->applyFromArray($this->borders);
        
        $sheet->setCellValueByColumnAndRow(5,$r,"Prior 3\nMonths");
        $sheet->getStyleByColumnAndRow(5,$r)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(5,$r,5,$r)->applyFromArray($this->borders);
        $sheet->setCellValueByColumnAndRow(6,$r,"Prior\nYear");
        $sheet->getStyleByColumnAndRow(6,$r)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(6,$r,6,$r)->applyFromArray($this->borders);
        $sheet->setCellValueByColumnAndRow(7,$r,"All\nTime");
        $sheet->getStyleByColumnAndRow(7,$r)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow(7,$r,7,$r)->applyFromArray($this->borders);
        $r++;
        
        $i = 0;
        while ($i < count($data['summary'])) {
            $row = $data['summary'][$i];
    
            $sheet->setCellValueByColumnAndRow(1, $r, $row['last_name'].', '.$row['first_name']);
            $sheet->setCellValueByColumnAndRow(2, $r, $row['area']);
            $sheet->getStyleByColumnAndRow(2,$r,2,$r)->applyFromArray($this->center);
    
            $sheet->setCellValueByColumnAndRow(3, $r, $row['roles']);
            $sheet->getStyleByColumnAndRow(3,$r)->getAlignment()->setWrapText(true);
            
            $sheet->setCellValueByColumnAndRow(4, $r, $row['YTD']);
            $sheet->setCellValueByColumnAndRow(5, $r, $row['Prior3']);
            $sheet->setCellValueByColumnAndRow(6, $r, $row['PriorYear']);
            $sheet->setCellValueByColumnAndRow(7, $r, $row['ALL_TIME']);
    
            error_log(print_r($row,true));
            $i++;
            $r++;
        }
        
        
        
            $newSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadsheet, 'Trip Detail');
        $this->spreadsheet->addSheet($newSheet, 1);
        $this->spreadsheet->setActiveSheetIndex(1);
        $sheet = $this->spreadsheet->getActiveSheet();
    
        $sheet->getPageMargins()->setLeft(0.30);
        $sheet->getPageMargins()->setRight(0.25);
    
        $this->header['reportName'] = $this->report_id." - Truck Volunteer Detail Trip Report";
        $this->writeXlsxHeader($this);
    
    
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