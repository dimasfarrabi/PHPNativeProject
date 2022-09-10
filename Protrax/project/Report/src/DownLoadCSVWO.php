<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleReport.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
/* 
if(!session_is_registered("UIDProTrax"))
{
    ?'
    'script language="javascript"'
        window.location.replace("https://protrax.formulatrix.com/");
    '/script'
    '?php
    exit();
}*/
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $ValSeason = htmlspecialchars(trim($_GET['sea']), ENT_QUOTES, "UTF-8");
    $UsedCL = htmlspecialchars(trim($_GET['ucl']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_GET['typ']), ENT_QUOTES, "UTF-8");
    $ValKey = htmlspecialchars(trim($_GET['key']), ENT_QUOTES, "UTF-8");
    $ValOpen = htmlspecialchars(trim($_GET['op']), ENT_QUOTES, "UTF-8");
    // echo $ValSeason."||".$UsedCL."||".$ValType."||".$ValKey."||".$ValOpen;


    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "WOMapping[".$ValSeason."]_$TimeNow.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No',
    'Quote',
    'ExpenseAllocation',
    'WOMapping_ID',
    'WOChild',
    'WOParent',
    'ClosedTime',
    'QtyParent',
    'QtyQuote',
    'Product',
    'OrderType',
    'PM',
    'EstLaborTime (Hour)',
    'LaborTime (Hour)',
    'TargetMachineTime (Hour)',
    'MachineTime (Hour)',
    'TargetMaterialCost($)',
    'MaterialCost($)',
    'EstFinishDate',
    'ClosedDate',
    'QuoteCategory',
    'QtyQCIn',
    'QtyQCOut',
    'MappingCode',
    'Location',
    'WOType',
    'PSM_Idx'));

    $NoLoop = 1;
    $QData = GET_DATA_WO_MAPPING_CUSTOM($ValSeason,$ValType,$ValKey,$UsedCL,$ValOpen,$linkMACHWebTrax);
    while($RData = sqlsrv_fetch_array($QData))
    {
        $QtyParent = trim($RData['QtyParent']);
        $QtyQuote = trim($RData['QtyQuote']);
        $TargetLaborTime = trim($RData['TargetLaborTime']);
        $TargetMachineTime = trim($RData['TargetMachineTime']);
        $TargetMaterialCost = trim($RData['TargetMaterialCost']);
        $LaborTime = trim($RData['LaborTime']);
        $MachineTime = trim($RData['MachineTime']);
        $MaterialCost = trim($RData['MaterialCost']);
        $QtyQCIn = trim($RData['QtyQCIn']);
        $QtyQCOut = trim($RData['QtyQCOut']);
        $EstCostHour = trim($RData['TargetLaborTime']);

        $QtyParent = number_format((float)$QtyParent,2,'.',',');
        $QtyQuote = number_format((float)$QtyQuote,2,'.',',');
        $TargetLaborTime = number_format((float)$TargetLaborTime,2,'.',',');
        $TargetMachineTime = number_format((float)$TargetMachineTime,2,'.',',');
        $TargetMaterialCost = number_format((float)$TargetMaterialCost,2,'.',',');
        $LaborTime = number_format((float)$LaborTime,2,'.',',');
        $MachineTime = number_format((float)$MachineTime,2,'.',',');
        $MaterialCost = number_format((float)$MaterialCost,2,'.',',');
        $QtyQCIn = number_format((float)$QtyQCIn,2,'.',',');
        $QtyQCOut = number_format((float)$QtyQCOut,2,'.',',');
        $EstCostHour = number_format((float)$EstCostHour,2,'.',',');

        $ArrayTemp = array($NoLoop,
        trim($RData['Quote']),
        trim($RData['ExpenseAllocation']),
        trim($RData['WOMapping_ID']),
        trim($RData['WOChild']),
        trim($RData['WOParent']),
        trim($RData['ClosedTime']),
         $QtyParent,
        $QtyQuote,
        trim($RData['Product']),
        trim($RData['OrderType']),
        trim($RData['PM']),
        $EstCostHour,
        $LaborTime,
        $TargetMachineTime,
        $MachineTime,
        $TargetMaterialCost,
        $MaterialCost,
        trim($RData['EstFinishDate']),
        trim($RData['ClosedDate']),
        trim($RData['QuoteCategory']),
        $QtyQCIn,
        $QtyQCOut,
        trim($RData['MappingCode']),
        trim($RData['LocationCode']),
        trim($RData['WOType']),
        trim($RData['PSM_Idx'])
        );
    fputcsv($file,$ArrayTemp);
    $NoLoop++;
    }
    fclose($file);
    exit();
}

?>