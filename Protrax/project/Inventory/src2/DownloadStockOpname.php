<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleStockOpname.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Loc = base64_decode(htmlspecialchars(trim($_GET['Loc']), ENT_QUOTES, "UTF-8"));
    $Batch = base64_decode(htmlspecialchars(trim($_GET['Batch']), ENT_QUOTES, "UTF-8"));
    date_default_timezone_set("Asia/Jakarta");
        $TimeNow = date('Y_m_d_H_i_s');
        $filename = "StockOpname[".$Batch."-".$Loc."].csv";
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputcsv($file, array(
            'DateStockOpname',
            'PersonInCharge',
            'StockType',
            'PartNo',
            'QtyStock',
            'PreviousQty',
            'Company',
            'Division',
            'Status'));
        switch ($Loc) {
            case 'PSL':
                $NewCompany = "PT Promanufacture Indonesia - Salatiga";
                break;
            case 'PSM':
                $NewCompany = "PT Promanufacture Indonesia - Semarang";
                break;
            case 'FOR':
                $NewCompany = "PT Formulatrix Indonesia";
                break;
            default:
                $NewCompany = "";
                break;
        }
        $QData = GET_DATA_STOCK_OPNAME($Batch,$NewCompany,$linkMACHWebTrax);
        while($RData = sqlsrv_fetch_array($QData))
        {
            $ArrayTemp = array(trim($RData['Dates']),
            trim($RData['StockOpnameBy']),
            trim($RData['Type']),
            trim($RData['PartNo']),
            trim($RData['QtyStock']),
            trim($RData['PreviousQty']),
            trim($RData['Company']),
            trim($RData['Division']),
            trim($RData['Status']));
            
            fputcsv($file,$ArrayTemp);
        }
        fclose($file);
        exit();
}
?>