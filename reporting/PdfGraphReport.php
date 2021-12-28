<?php
require(dirname(__FILE__).'/PdfReport.php');
require_once (dirname(__FILE__).'/jpgraph/jpgraph.php');
require_once (dirname(__FILE__).'/jpgraph/jpgraph_line.php');
require_once (dirname(__FILE__).'/jpgraph/jpgraph_bar.php');
require_once (dirname(__FILE__).'/jpgraph/jpgraph_utils.inc.php');

class PdfGraphReport extends PdfReport
{
    function __construct($reportDate=null) {
        parent::__construct($reportDate);
    }
    
    
    
}

function valueFormat($aLabel,$dec=0) {
    return number_format($aLabel,$dec);
}

// R9
function percentFormat($aLabel,$dec=1) {
    if ($aLabel > 25.0)
        return "\n\n                         ".number_format($aLabel,$dec).'%';
    if ($aLabel > 10.0)
        return "\n                         ".number_format($aLabel,$dec).'%';
    return "                           ".number_format($aLabel,$dec).'%';
}
