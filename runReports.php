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

$isxlsx = array_key_exists('XLSX', $_POST );
$ispdf = array_key_exists('PDF', $_POST );

if ($isxlsx) {
    ob_start();
}

global $fn;
include_once(dirname(__FILE__).'/Utils.php');

$message = null;
$rpt_date = array_key_exists('range_Month_Picker',$_POST) ? $_POST['range_Month_Picker'] : (new DateTime())->format('m/Y');
$parts = explode('/',$rpt_date);
$rpt_date = new DateTime($parts[1].'-'.$parts[0].'-01T00:00:00');

if ($ispdf || $isxlsx) {
    
    $rpt = null;
    switch ($_POST['report_name']) {
        case 'R2' :
        case 'R2ytd' :
            if ($ispdf) {
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR2.php');
                $rpt = new PdfRptR2($rpt_date, $_POST['report_name'] == 'R2ytd');
            } else {
//                require(dirname(__FILE__) . '/reporting/Reports/XlsxRptR2.php');
//                $rpt = new XlsxRptR2($rpt_date, $_POST['report_name'] == 'R2ytd');
            }
            break;
        case 'R3':
            if ($ispdf) {
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR3.php');
                $rpt = new PdfRptR3($rpt_date);
            } else {
//                require(dirname(__FILE__) . '/reporting/Reports/XlsxRptR2.php');
//                $rpt = new XlsxRptR2($rpt_date, $_POST['report_name'] == 'R2ytd');
            }
            break;
        case 'R4':
            if ($ispdf) {
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR4.php');
                $rpt = new PdfRptR4($rpt_date);
            } else {
//                require(dirname(__FILE__) . '/reporting/Reports/XlsxRptR2.php');
//                $rpt = new XlsxRptR2($rpt_date, $_POST['report_name'] == 'R2ytd');
            }
            break;
        case 'R5':
            if ($ispdf) {
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR5.php');
                $rpt = new PdfRptR5($rpt_date);
            } else {
//                require(dirname(__FILE__) . '/reporting/Reports/XlsxRptR2.php');
//                $rpt = new XlsxRptR2($rpt_date, $_POST['report_name'] == 'R2ytd');
            }
            break;
        case 'R6':
            if ($ispdf) {
                require(dirname(__FILE__) . '/reporting/Reports/PdfRptR6.php');
                $rpt = new PdfRptR6($rpt_date);
            } else {
//                require(dirname(__FILE__) . '/reporting/Reports/XlsxRptR2.php');
//                $rpt = new XlsxRptR2($rpt_date, $_POST['report_name'] == 'R2ytd');
            }
            break;
    }
    if ($rpt != null) {
        $filename = "report.pdf";
        header('Content-disposition: attachment; filename="'.$filename.'"');
        header("Content-Type: application/pdf");
//    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    
        $rpt->run();
        exit();
    } else {
        $message = $_POST['report_name'].'('. ($ispdf ? 'PDF' : 'XLSX') .') has not yet been implemented';
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
            <div style="padding-left:8px;">
            Report: <select name="report_name">
                <option value="R2" {$fn(selected($_POST['report_name'],'R2'))} >R2 – Donor and Recipient Month Rank</option>
                <option value="R2ytd" {$fn(selected($_POST['report_name'],'R2ytd'))} >R2 – Donor and Recipient YTD Rank</option>
                <option value="R3" {$fn(selected($_POST['report_name'],'R3'))} >R3 – Donor Monthly Variance</option>
                <option value="R4" {$fn(selected($_POST['report_name'],'R4'))} >R4 – Recipient Monthly Variance</option>
                <option value="R5" {$fn(selected($_POST['report_name'],'R5'))} >R5 – Donor 3 Mo. & YTD Variance</option>
                <option value="R6" {$fn(selected($_POST['report_name'],'R6'))} >R6 – Recipient 3 Mo. & YTD Variance</option>
                <option value="R7" {$fn(selected($_POST['report_name'],'R7'))} disabled>R7 – Donor 6 Mo. Trend</option>
                <option value="R8" {$fn(selected($_POST['report_name'],'R8'))} disabled>R8 – Donor by Area Trend</option>
                <option value="R9" {$fn(selected($_POST['report_name'],'R9'))} disabled>R9 – Recipient 6 Mo. Trend</option>
                <option value="R10" {$fn(selected($_POST['report_name'],'R10'))} disabled>R10 – Food Type Trend</option>
                <option value="R11" {$fn(selected($_POST['report_name'],'R11'))} disabled>R11 – Snapshot</option>
                <option value="R12" {$fn(selected($_POST['report_name'],'R12'))} disabled>R12 – Food Per Person Served</option>
                <option value="R13" {$fn(selected($_POST['report_name'],'R13'))} disabled>R13 – Agency Distribution</option>
                <option value="R14" {$fn(selected($_POST['report_name'],'R14'))} disabled>R14 – Key Rescued Daily Average</option>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Report Month:
            <input type="text"
                    id="range_Month_Picker"
                    name="range_Month_Picker"
                    value="{$rpt_date}"
                    size="15" />
            </div>
    
    
            <br>
            <br>
            <input type="hidden" name="submitted" value="1">
            <input type="submit" formtarget="_blank" name="PDF" value="Generate PDF" style="margin-left: 175px">
            <input type="submit" formtarget="_blank" name="XLSX"  value="Generate XLSX" style="margin-left: 25px">
            
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
