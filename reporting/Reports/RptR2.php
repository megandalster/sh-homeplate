<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

require(dirname(__FILE__).'/../PdfReport.php');


class RptR2 extends PdfReport
{
    private $ytd = false;
 
    function __construct($reportDate=null,$ytd=false) {
        parent::__construct($reportDate);
        $this->ytd = $ytd;
        $this->header['reportName'] = 'R2 - DONOR & RECIPIENT RANK REPORT';
    }
    
    function run() {
        $this->newPage();
        $this->pdf->Ln();
        
        $data = $this->data($this->reportDate, $this->ytd);
        $donor_type = count($data['pickups']) > 0 ? $data['pickups'][0]['donor_type'] : '';
    
        $p_idx = 0;
        $p_skip = 0;
        $p_subtot = 0;

        $d_idx = 0;
        while ($p_idx < count($data['pickups']) || $d_idx < count($data['dropoffs'])) {
            if ($p_skip) {
                $p_skip--;
                $this->rowData('', null, null);
            } else if ($p_idx < count($data['pickups']) && $data['pickups'][$p_idx]['donor_type'] != $donor_type) {
                // change of type
                $p = ($p_subtot / $data['tw_pickups']) * 100.0;
                $this->subTotal($donor_type,$p_subtot,$p);
                $donor_type = $data['pickups'][$p_idx]['donor_type'];
                $p_subtot = 0;
                $p_skip = 2;
            } else if ($p_idx < count($data['pickups'])) {
                $p = ($data['pickups'][$p_idx]['weight'] / $data['tw_pickups']) * 100.0;
                $this->rowData($data['pickups'][$p_idx]['client'],$data['pickups'][$p_idx]['weight'],$p);
                $p_subtot += $data['pickups'][$p_idx]['weight'];
                $p_idx++;
            } else {
                if ($donor_type != '') {
                    $p = ($p_subtot / $data['tw_pickups']) * 100.0;
                    $this->subTotal($donor_type,$p_subtot,$p);
                    $donor_type = '';
                } else {
                    $this->rowData('',null,null);
                }
            }
            
            if ($d_idx < count($data['dropoffs'])) {
                $p = ($data['dropoffs'][$d_idx]['weight'] / $data['tw_dropoffs']) * 100.0;
                $this->rowData($data['dropoffs'][$d_idx]['client'],$data['dropoffs'][$d_idx]['weight'],$p,true);
                $d_idx++;
            } else {
                $this->rowData('',null,null,true);
            }
            $this->pdf->Ln();
        }
    
        $this->grandTotal($data['tw_pickups'], $data['tw_dropoffs']);
        $this->output();
    }
    
    function newPage() {
        $this->pdf->AddPage();
        $this->writePdfHeader($this->pdf);
    
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetXY( 95-28, 35);
        if ($this->ytd) {
            $this->pdf->Cell(34, 4, "Year To Date", 1, 0, 'C');
            $this->pdf->SetX(190 - 28);
            $this->pdf->Cell(34, 4, "Year To Date", 1, 0, 'C');
            $this->pdf->Ln();
        }
        $this->pdf->SetX( 15);
        $this->pdf->Cell(52,4,"Donor",1, 0,'L');
        $this->pdf->Cell(20,4,"Weight - Lbs",1, 0,'C');
        $this->pdf->Cell(14,4,"% Total",1, 0,'C');
        $this->pdf->SetX( 110);
        $this->pdf->Cell(52,4,"Donor",1, 0,'L');
        $this->pdf->Cell(20,4,"Weight - Lbs",1, 0,'C');
        $this->pdf->Cell(14,4,"% Total",1, 0,'C');
    
    }
    
    public function rowData($name='',$weight=0,$percent=0,$right_side=false) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','');
        
        $this->pdf->SetX( $right_side ? 110 : 15);
        $this->pdf->Cell(52,4,$name,0, 0,'L');
        $this->pdf->Cell(20,4,$weight != null ? number_format($weight) : '',0, 0,'R');
        $this->pdf->Cell(14,4,$percent != null ? number_format($percent,1)."%" : '',0, 0,'R');
    }
    
    public function grandTotal($donor_total=0,$recipient_total=0) {
        $this->subTotal('Total Food',$donor_total,100);
        $this->subTotal('Total Food',$recipient_total,100, true);
    }
    
    public function subTotal($label='',$total=0,$percent=0,$right_side=false) {
        $this->pdf->SetTextColor(0,0, 0);
        $this->pdf->SetFontSize(9);
        $this->pdf->SetFont('','B');
    
        $this->pdf->SetX( $right_side ? 110 : 15);
        $this->pdf->Cell(52,4,$label,'T', 0,'L');
        $this->pdf->Cell(20,4,number_format($total),'T', 0,'R');
        $this->pdf->Cell(14,4,number_format($percent,1)."%",'T', 0,'R');
    }
    
    public function data($rpt_date=null,$isytd=false) {
        $start_date = $rpt_date->format('y-m-d');
        if ($isytd) {
            $start_date = new DateTime($rpt_date->format('y-m-d'));
            $start_date->setDate( $start_date->format('Y'), 1, 1);
            $start_date = $start_date->format('y-m-d');
        }
        $end_date = new DateTime($rpt_date->format('y-m-d'));
        $end_date->modify('+1 month');
        $end_date = $end_date->format('y-m-d');
        error_log($start_date.' --> '.$end_date);
    
        $pickups = array();
        $dropoffs = array();
    
        // keep track of total weights
        $tw_pickups  = 0;
        $tw_dropoffs = 0;
    
        $con = connect();
        $query = <<<SQL
                    SELECT s.client, s.type, s.weight,
                    case when c.donor_type='Rescued Food' then 'Rescued Food' else 'Other Food' end as donor_type,
                    case when c.donor_type='Rescued Food' THEN 0 else 1 end as orderby
                    from (
                        SELECT client, type, SUM(weight) as weight
                        FROM dbStops
                        WHERE date >= '$start_date' AND date < '$end_date'
                            AND weight > 0
                        GROUP BY client ) s
                    JOIN dbClients c on c.id = s.client
                    order by 5,3 desc
SQL;
        error_log($query);
        $result = mysqli_query ($con,$query);
        $theStops = array();
        while ($result_row = mysqli_fetch_assoc($result)) {
            $clientName = $result_row['client'];
            $type = $result_row['type'];
            $weight = $result_row['weight'];
            $donor_type = $result_row['donor_type'];
            if($type == "pickup"){
                $pickups[] = array(
                    'client' => $clientName,
                    'weight' => $weight,
                    'donor_type' => $donor_type,
                );
                $tw_pickups += $weight;
            } else{
                $dropoffs[] = array(
                    'client' => $clientName,
                    'weight' => $weight,
                    'donor_type' => $donor_type,
                );
                $tw_dropoffs += $weight;
            }
        }
        mysqli_close($con);
        $max = max(count($pickups), count($dropoffs));
        return array(
            'count' => $max,
            'tw_pickups' => $tw_pickups,
            'tw_dropoffs' => $tw_dropoffs,
            'pickups' => $pickups,
            'dropoffs' => $dropoffs,
        );
    }
    
}