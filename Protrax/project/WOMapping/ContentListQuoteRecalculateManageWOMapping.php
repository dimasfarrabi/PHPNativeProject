<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    # set data variable
    $ArrListAllData1 = array();
    $ArrListAllData2 = array();
    # get data psm closed
    $TempPSM = array();
    $QDataPSM = LIST_TOTAL_QTY_PARENT_QUOTE_CLOSED_PSM();
    while($RDataPSM = mssql_fetch_assoc($QDataPSM))
    {
        $TempPSM = array(
            "Quote" => trim($RDataPSM['Quote']),
            "ClosedTime" => trim($RDataPSM['ClosedTime']),
            "ExpenseAllocation" => trim($RDataPSM['ExpenseAllocation']),
            "TotalQtyParent" => trim($RDataPSM['TotalQtyParent']),
            "Location" => "PSM"
        );
        array_push($ArrListAllData1,$TempPSM);
    }
    $TempPSM = array();
    # get data psm open
    $QDataPSM2 = LIST_TOTAL_QTY_PARENT_QUOTE_OPEN_PSM();
    while($RDataPSM2 = mssql_fetch_assoc($QDataPSM2))
    {
        $TempPSM = array(
            "Quote" => trim($RDataPSM2['Quote']),
            "ClosedTime" => trim($RDataPSM2['ClosedTime']),
            "ExpenseAllocation" => trim($RDataPSM2['ExpenseAllocation']),
            "TotalQtyParent" => trim($RDataPSM2['TotalQtyParent']),
            "Location" => "PSM"
        );
        array_push($ArrListAllData1,$TempPSM);
    }
    $TempPSM = array();
    # get data psl closed
    $TempPSL = array();
    $QDataPSL = LIST_TOTAL_QTY_PARENT_QUOTE_CLOSED_PSL($linkMACHWebTrax);
    while($RDataPSL = mssql_fetch_assoc($QDataPSL))
    {
        $TempPSL = array(
            "Quote" => trim($RDataPSL['Quote']),
            "ClosedTime" => trim($RDataPSL['ClosedTime']),
            "ExpenseAllocation" => trim($RDataPSL['ExpenseAllocation']),
            "TotalQtyParent" => trim($RDataPSL['TotalQtyParent']),
            "Location" => "PSL"
        );
        array_push($ArrListAllData1,$TempPSL);
    }
    $TempPSL = array();
    # get data psl open
    $QDataPSL2 = LIST_TOTAL_QTY_PARENT_QUOTE_OPEN_PSL($linkMACHWebTrax);
    while($RDataPSL2 = mssql_fetch_assoc($QDataPSL2))
    {
        $TempPSL = array(
            "Quote" => trim($RDataPSL2['Quote']),
            "ClosedTime" => trim($RDataPSL2['ClosedTime']),
            "ExpenseAllocation" => trim($RDataPSL2['ExpenseAllocation']),
            "TotalQtyParent" => trim($RDataPSL2['TotalQtyParent']),
            "Location" => "PSL"
        );
        array_push($ArrListAllData1,$TempPSL);
    }
    $TempPSL = array();
    $Loop = 1;
    foreach($ArrListAllData1 as $ListData)
    {
        $TempArray = array(
            "No" => $Loop,
            "Quote" => trim($ListData['Quote']),
            "ClosedTime" => trim($ListData['ClosedTime']),
            "ExpenseAllocation" => trim($ListData['ExpenseAllocation']),
            "TotalQty" => trim($ListData['TotalQtyParent']),
            "Location" => trim($ListData['Location']),
            "Percentage" => number_format((float)($Loop/count($ArrListAllData1))*100, 2, '.', ','),
            "Percentage2" => number_format((float)($Loop/count($ArrListAllData1))*100, 0, '.', ','),
        );
        array_push($ArrListAllData2,$TempArray);
        $Loop++;
    }
    echo json_encode($ArrListAllData2);  
}
else
{
    echo "";    
}
?>