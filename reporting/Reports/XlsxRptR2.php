<?php


require(dirname(__FILE__).'/../XlsxReport.php');
require(dirname(__FILE__).'/../Traits/R2DataTrait.php');


class XlsxRptR2 extends XlsxReport
{
    use R2DataTrait;
    
    private $ytd = false;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R2 - DONOR & RECIPIENT RANK REPORT';
    }
    
    function run() {
    
        $this->writer->writeSheetHeader(
            $this->sheetName,
            [
                'c1' => 'string',
                'c2' => '#,##0',
                'c3' => '0.0%',
                'c4' => 'string',
                'c5' => 'string',
                'c6' => '#,##0',
                'c7' => '0.0%'
            ],
//            [
//            'c1' => 'string',
//                'c2' => 'string',
//                'c3' => 'string',
//                'c4' => 'string',
//                'c5' => 'string',
//                'c6' => 'string',
//                'c7' => 'string'
//            ],
            [
                'widths'=>[28,10,9,1,28,10,9],
                'suppress_row'=>true ,
            ]
        );
    
        $this->writeXlsxHeader($this);
    
        $this->writer->writeSheetRow(
            $this->sheetName,
            [
                'Donor', 'Weight - Lbs', '% Total', '', 'Recipient', 'Weight - Lbs', '% Total'
            ],
            [
                [
                    'halign' => 'left',
                    'font-size'=>10,
                    'font-style' => 'bold',
                    'border' => 'left,right,top,bottom',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'center',
                    'font-size'=>10,
                    'font-style' => 'bold',
                    'border' => 'left,right,top,bottom',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'center',
                    'font-size'=>10,
                    'font-style' => 'bold',
                    'border' => 'left,right,top,bottom',
                    'border-color' => '#000',
                ],
                [],
                [
                    'halign' => 'left',
                    'font-size'=>10,
                    'font-style' => 'bold',
                    'border' => 'left,right,top,bottom',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'center',
                    'font-size'=>10,
                    'font-style' => 'bold',
                    'border' => 'left,right,top,bottom',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'center',
                    'font-size'=>10,
                    'font-style' => 'bold',
                    'border' => 'left,right,top,bottom',
                    'border-color' => '#F00',
                ],
            ]
        );
        $this->row++;
    
    
        $this->writer->writeSheetHeader(
            $this->sheetName,
            [
                'c1' => 'string',
                'c2' => '#,##0',
                'c3' => '0.0%',
                'c4' => 'string',
                'c5' => 'string',
                'c6' => '#,##0',
                'c7' => '0.0%'
            ],
            [
                'widths'=>[28,10,9,1,28,10,9],
                'suppress_row'=>true ,
            ]
        );
        
        
        $this->row++;
    
        $data = $this->data($this->reportDate, $this->ytd);
        $donor_type = count($data['pickups']) > 0 ? $data['pickups'][0]['donor_type'] : '';
    
        $p_idx = 0;
        $p_skip = 0;
        $p_subtot = 0;

        $d_idx = 0;
        while ($p_idx < count($data['pickups']) || $d_idx < count($data['dropoffs'])) {
            $rowdata = ['',null,null,null,'',null,null];
            $rowtype = [0,0];
            if ($p_skip) {
                $p_skip--;
            } else if ($p_idx < count($data['pickups']) && $data['pickups'][$p_idx]['donor_type'] != $donor_type) {
                // change of type
                $p = ($p_subtot / $data['tw_pickups']);
                $rowdata[0] = $donor_type;
                $rowdata[1] = $p_subtot; //number_format($p_subtot);
                $rowdata[2] = $p/100.0; //number_format($p,1).'%';
                $rowtype[0] = 1;
                $donor_type = $data['pickups'][$p_idx]['donor_type'];
                $p_subtot = 0;
                $p_skip = 2;
            } else if ($p_idx < count($data['pickups'])) {
                $p = ($data['pickups'][$p_idx]['weight'] / $data['tw_pickups']);
                $rowdata[0] = $data['pickups'][$p_idx]['client'];
                $rowdata[1] = $data['pickups'][$p_idx]['weight'];
                $rowdata[2] = $p / 100.0;
//                $rowdata[1] = number_format($data['pickups'][$p_idx]['weight']);
//                $rowdata[2] = number_format($p,1).'%';
                $p_subtot += $data['pickups'][$p_idx]['weight'];
                $p_idx++;
            } else {
                if ($donor_type != '') {
                    $p = ($p_subtot / $data['tw_pickups']);
                    $rowdata[0] = $donor_type;
                    $rowdata[1] = $p_subtot;
                    $rowdata[2] = $p / 100.0;
//                    $rowdata[1] = number_format($p_subtot);
//                    $rowdata[2] = number_format($p,1).'%';
                    $rowtype[0] = 1;
                    $donor_type = '';
                }
            }
            
            if ($d_idx < count($data['dropoffs'])) {
                $p = ($data['dropoffs'][$d_idx]['weight'] / $data['tw_dropoffs']) ;
                $rowdata[4] = $data['dropoffs'][$d_idx]['client'];
                $rowdata[5] = $data['dropoffs'][$d_idx]['weight'];
                $rowdata[6] = $p / 100.0;
//                $rowdata[5] = number_format($data['dropoffs'][$d_idx]['weight']);
//                $rowdata[6] = number_format($p,1).'%';
                $d_idx++;
            }
            $this->writeRow($rowdata,$rowtype);
        }
    
        $this->writeRow(
            ['Total Food',
                $data['tw_pickups'],
                100.0,
                null,
                'Total Food',
                $data['tw_dropoffs'],
                100.0
            ],
            [1,1]
        );

        $this->output();
    }
    
    public function writeRow($rowdata,$rowtype) {
        $this->writer->writeSheetRow(
            $this->sheetName,
            $rowdata,
            [
                [
                    'halign' => 'left',
                    'font-size'=>10,
                    'font-style' => $rowtype[0] ? 'bold' : '',
                    'border' => $rowtype[0] ? 'top' : '',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'right',
                    'font-size'=>10,
                    'font-style' => $rowtype[0] ? 'bold' : '',
                    'border' => $rowtype[0] ? 'top' : '',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'right',
                    'font-size'=>10,
                    'font-style' => $rowtype[0] ? 'bold' : '',
                    'border' => $rowtype[0] ? 'top' : '',
                    'border-color' => '#000',
                ],
                [],
                [
                    'halign' => 'left',
                    'font-size'=>10,
                    'font-style' => $rowtype[1] ? 'bold' : '',
                    'border' => $rowtype[1] ? 'top' : '',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'right',
                    'font-size'=>10,
                    'font-style' => $rowtype[1] ? 'bold' : '',
                    'border' => $rowtype[1] ? 'top' : '',
                    'border-color' => '#000',
                ],
                [
                    'halign' => 'right',
                    'font-size'=>10,
                    'font-style' => $rowtype[1] ? 'bold' : '',
                    'border' => $rowtype[1] ? 'top' : '',
                    'border-color' => '#000',
                ],
            ]
        );
        $this->row++;
    }
    
}