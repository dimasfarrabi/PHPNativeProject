<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "GET")
{   
    $ValTemplateName = htmlspecialchars(trim($_GET['template']), ENT_QUOTES, "UTF-8");
    # download data
    date_default_timezone_set("Asia/Jakarta");
    $filename = "DataListPartWIP_".$ValTemplateName.".csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No','PartNo','PartDesc','QtyTarget','QtyTotal','QtyInventoryPSL','QtyInTransit','QtyInventoryPSM'));
    $QData = GET_DATA_WIPSIMS_GUDANGKECIL($ValTemplateName,$linkMACHWebTrax);
    $No = 1;
    while($RData = sqlsrv_fetch_array($QData))
    {
        $ValPartNo = trim($RData['PartNo']);
        $ValPartDesc = trim($RData['PartDescription']);
        $QtyStockPSL = trim($RData['QtyStockSLG']);
        $QtyStockPSM = trim($RData['QtyStockSMG']);
        $QtyInTransit = trim($RData['QtyStockTR']);
        $QtyStock = trim($RData['QtyStock']);
        $ValQtyTarget = trim($RData['QtyTarget']);
        if(trim($QtyStockPSL) == ""){$QtyStockPSL = "";} else {$QtyStockPSL = number_format((float)$QtyStockPSL, 2, '.', ',');}   
        if(trim($QtyStockPSM) == ""){$QtyStockPSM = "";} else {$QtyStockPSM = number_format((float)$QtyStockPSM, 2, '.', ',');}   
        if(trim($QtyInTransit) == ""){$QtyInTransit = "";} else {$QtyInTransit = number_format((float)$QtyInTransit, 2, '.', ',');}   
        $ValQtyTarget = number_format((float)$ValQtyTarget, 2, '.', ',');
        $QtyStock = number_format((float)$QtyStock, 2, '.', ',');

        $ArrayTemp = array($No,$ValPartNo,$ValPartDesc,$ValQtyTarget,$QtyStock,$QtyStockPSL,$QtyInTransit,$QtyStockPSM);
        fputcsv($file,$ArrayTemp);
        
        $No++; 
    }

    fclose($file);
    exit();


}
else
{
    echo "";    
}
?>