<?php

/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation recepients
 * (see <http://www.gnu.org/licenses/).
*/

session_start();
session_cache_expire(30);

$ispdf = array_key_exists('PDF', $_POST );

global $fn;
include_once(dirname(__FILE__).'/Utils.php');

$message = null;
$rpt_date = (new DateTime())->format('m/Y');
if (array_key_exists('range_Month_Picker',$_POST)) {
    $rpt_date = $_POST['range_Month_Picker'];
} else {
    $rpt_date = new DateTime();
    $rpt_date = new DateTime($rpt_date->format('Y-m').'-01');
    $rpt_date->modify('-1 month');
    $rpt_date = $rpt_date->format('m/Y');
}

//$rpt_date = array_key_exists('range_Month_Picker',$_POST) ? $_POST['range_Month_Picker'] : (new DateTime())->sub({months:1})->format('m/Y');
$parts = explode('/',$rpt_date);
$rpt_date = new DateTime($parts[1].'-'.$parts[0].'-01T00:00:00');

if ($ispdf) {
    error_log('runReports: '.join('.',$_POST['report_name']).' for '.$rpt_date->format('Y-m-d'));
    $rpts = array();
    $rptname = 'Reports-'.join('.',$_POST['report_name']).'-'.$rpt_date->format('M-y').'.pdf';
    foreach ($_POST['report_name'] as $rpt) {
        switch ($rpt) {
            case 'R2' :
            case 'R2ytd' :
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR2.php');
                $rpts[] = new PdfRptR2($rpt_date, $rpt == 'R2ytd');
                break;
            case 'R3':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR3.php');
                $rpts[] = new PdfRptR3($rpt_date);
                break;
            case 'R4':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR4.php');
                $rpts[] = new PdfRptR4($rpt_date);
                break;
            case 'R5':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR5.php');
                $rpts[] = new PdfRptR5($rpt_date);
                break;
            case 'R6':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR6.php');
                $rpts[] = new PdfRptR6($rpt_date);
                break;
            case 'R7':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR7.php');
                $rpts[] = new PdfRptR7($rpt_date);
                break;
            case 'R8':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR8.php');
                $rpts[] = new PdfRptR8($rpt_date);
                break;
            case 'R9':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR9.php');
                $rpts[] = new PdfRptR9($rpt_date);
                break;
            case 'R10':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR10.php');
                $rpts[] = new PdfRptR10($rpt_date);
                break;
            case 'R11':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR11.php');
                $rpts[] = new PdfRptR11($rpt_date);
                break;
            case 'R12':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR12.php');
                $rpts[] = new PdfRptR12($rpt_date);
                break;
            case 'R14':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR14.php');
                $rpts[] = new PdfRptR14($rpt_date);
                break;
            case 'R15':
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR15.php');
                $rpts[] = new PdfRptR15($rpt_date);
                break;
        }
    }

    if (count($rpts) == 1) {
        $filename = $rpts[0]->filename;
        header('Content-disposition: inline; filename="'.$filename.'"');
        header("Content-Type: application/pdf");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    
        $rpts[0]->run();
        exit();
    } else if (count($rpts) > 1) {
        $files = array();
        foreach ($rpts as $rpt) {
            $tmpfname = tempnam(sys_get_temp_dir(), 'PDF');

            $rpt->SetOutputFile($tmpfname);
            $rpt->run(true);
            $files[] = $tmpfname;
        }

        $pdftk = 'reporting/pdftk/pdftk';
        if (PHP_OS == 'Darwin') {
            $pdftk = '/usr/local/bin/pdftk';
        }
        
        $output=null;
        $retval=null;
        error_log($pdftk.' '.join(' ',$files).' cat output -');

//        header('Content-disposition: attachment; filename="'.$rpt->filename.'"');
        header('Content-disposition: inline; filename="'.$rptname.'"');
        header("Content-Type: application/pdf");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        passthru($pdftk.' '.join(' ',$files).' cat output -'); //.$tmpfname2, $output, $retval);
//        error_log('pdftk: '.print_r($output,true));
        

//        $pdf = file_get_contents($tmpfname2);
//        echo $pdf;

//        unlink($tmpfname2);
        foreach($files as $f) unlink($f);
        exit();
    } else {
        $message = print_r($_POST['report_name'],true).' has not yet been implemented';
    }
}

$rpt_date = $rpt_date->format('m/Y');

if (!array_key_exists('report_name',$_POST)) $_POST['report_name'] = '';
?>
<html>
    <head>
        <title>Run Reports</title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        
        <style>
            .message {
                margin-top: 50px;
                margin-bottom: 50px;
                color: red;
                font-weight: bold;
                text-align: center;
                width: 600px;
            }
            .ui-datepicker-calendar {
                display: none;
            }
            select:invalid {
                color: #999999;
            }
        </style>
    </head>
    <body>
        <div id="container"><?php include('header.php');?>
        <div id="content">
<?php
    echo "<h4>Today is ".date('l F j, Y', time())."</h4>";
    echo <<<END
        <form method="post" action="">
            <div style="padding-left:8px; float: left;">
            <!--
            <table>
                <tr>
                    <td><input type="checkbox" name="R2">R2 – Donor and Recipient Month Rank</td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="R2ytd">R2 – Donor and Recipient YTD Rank</td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="R3">R3 – Donor Monthly Variance</td>
                </tr>
            </table>
            -->
            <span style="display: inline-block; vertical-align: top;">Report: &nbsp;</span>
            <select name="report_name[]" multiple size="15">
                <option value="R2" {$fn(selected($_POST['report_name'],'R2'))} >R2 – Donor & Recipient Mo. & YTD Rank</option>
                <!--
                <option value="R2ytd" {$fn(selected($_POST['report_name'],'R2ytd'))} >R2 – Donor and Recipient YTD Rank</option>
                -->
                <option value="R3" {$fn(selected($_POST['report_name'],'R3'))} >R3 – Donor Monthly Variance</option>
                <option value="R4" {$fn(selected($_POST['report_name'],'R4'))} >R4 – Recipient Monthly Variance</option>
                <option value="R5" {$fn(selected($_POST['report_name'],'R5'))} >R5 – Donor 3 Mo. & YTD Variance</option>
                <option value="R6" {$fn(selected($_POST['report_name'],'R6'))} >R6 – Recipient 3 Mo. & YTD Variance</option>
                <option value="R7" {$fn(selected($_POST['report_name'],'R7'))} >R7 – Donor 6 Mo. Trend</option>
                <option value="R8" {$fn(selected($_POST['report_name'],'R8'))} >R8 – Donor by Area Trend</option>
                <option value="R9" {$fn(selected($_POST['report_name'],'R9'))} >R9 – Recipient 6 Mo. Trend</option>
                <option value="R10" {$fn(selected($_POST['report_name'],'R10'))} >R10 – Food Type Trend</option>
                <option value="R11" {$fn(selected($_POST['report_name'],'R11'))} >R11 – Snapshot</option>
                <option value="R12" {$fn(selected($_POST['report_name'],'R12'))} >R12 – Food Per Person Served</option>
                <option value="R13" {$fn(selected($_POST['report_name'],'R13'))} disabled>R13 – Agency Distribution</option>
                <option value="R14" {$fn(selected($_POST['report_name'],'R14'))} >R14 – Key Rescued Daily Average</option>
                <option value="R15" {$fn(selected($_POST['report_name'],'R15'))} >R15 – Recipient Non-Rescued Food</option>
            </select>
            <br/>
            <span style="display: inline-block; text-align: right; font-size: 9px; font-style: italic; width: 100%;">Use CTRL/Command key to select multiple Reports.</span>
            </div>
            <div style="display: inline-block; padding-left:30px;">
            Report Month:
            <input type="text"
                    id="range_Month_Picker"
                    name="range_Month_Picker"
                    value="{$rpt_date}"
                    size="15" />

            <br/>
            <br/>
            <input type="hidden" name="submitted" value="1">
            <input type="submit" formtarget="_blank" name="PDF" value="Generate PDF" style="margin-left: 106px;">

            <input type="submit" formtarget="_blank" name="TEST" value="Test" style="margin-left: 175px; display:none;">
            </div>
    
    
            
        </form>
         <script>
            $(function() {
                let today = new Date()
                let lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1)
                $( "#range_Month_Picker" ).datepicker({
                                minDate: new Date(2020,1,1),
                                maxDate: lastMonth,
                                defaultDate: lastMonth,
                                dateFormat: "mm/yy",
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                onClose: function(dateText, inst) {
                                    function isDonePressed(){
                                        return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                                    }
        
                                    if (isDonePressed()){
                                        let month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                                        let year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                        $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
                                        $('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
                                    }
                                },
                                beforeShow : function(input, inst) {
                                    inst.dpDiv.addClass('month_year_datepicker')
        
                                    if ((datestr = $(this).val()).length > 0) {
                                        year = datestr.substring(datestr.length-4, datestr.length);
                                        month = datestr.substring(0, 2);
                                        $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
                                        $(this).datepicker('setDate', new Date(year, month-1, 1));
                                        $(".ui-datepicker-calendar").hide();
                                    }
                                }
                            })
                            $( "#range_Month_Picker" ).datepicker( "setDate", lastMonth)
        
            });
        </script>

END;

if ($message != null) {
    echo '<div class="message">'.$message.'</div>';
}

?>

        </div>
    </div>
    <?php include('footer.inc');?>
    </body>
</html>
