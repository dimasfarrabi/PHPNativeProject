<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleStockOpname.php");
// $FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
// $UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
$FullName = "DIMAS RIZKY FARRABI";
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $PartNo = (htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8"));
    $Qty = (htmlspecialchars(trim($_POST['Qty']), ENT_QUOTES, "UTF-8"));
    $StockType = (htmlspecialchars(trim($_POST['StockType']), ENT_QUOTES, "UTF-8"));
    $Gudang = (htmlspecialchars(trim($_POST['Gudang']), ENT_QUOTES, "UTF-8"));
    $Lokasi = base64_decode(base64_decode(htmlspecialchars(trim($_POST['Lokasi']), ENT_QUOTES, "UTF-8")));
    $arr = explode(":",$Lokasi);
    switch ($arr[1]) {
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
    $cekData = CEK_TEMPORARY($PartNo,$NewCompany,$StockType,$Gudang,$linkMACHWebTrax);
    if(sqlsrv_num_rows($cekData) > 0){
        echo "FALSE:2";
    }
    else{
        $insertTemporary = INSERT_TEMPORARY($PartNo,$FullName,$NewCompany,$StockType,$Gudang,$Qty,$linkMACHWebTrax);
        echo $insertTemporary.":1";
    }
}
else { }
?>