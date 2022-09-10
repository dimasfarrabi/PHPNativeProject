<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
# data session
// $FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
// $UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
$FullName = "local-Dimas Farrabi";
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $All = htmlspecialchars(trim($_POST['DataAtas']), ENT_QUOTES, "UTF-8");
    $exp = htmlspecialchars(trim($_POST['DataBawah']), ENT_QUOTES, "UTF-8");
    $arr1 = explode("*",$All);
    $arrQuote = explode("*",base64_decode($arr1[0]));
    $Quote = $arrQuote[0];
    $NamaPM = $arr1[1];
    $NamaCOPM = $arr1[2];
    $QuoteCategory = $arr1[3];
    $arrWOP = explode(":",$arr1[4]);
    $WOP = $arrWOP[0];
    $QtyParent = $arr1[5];
    $Product = $arr1[6];
    $WOC = $arr1[7];
    $JenisWO = $arr1[8];
    $OrderType = $arr1[9];

    $arr2 = explode("*",$exp);
    $Machining = $arr2[0];
    $Fabrication = $arr2[1];
    $Injection = $arr2[2];
    $Electronics = $arr2[3];
    $Assembly = $arr2[4];
    $Quality = $arr2[5];
    $Shipping = $arr2[6];
    $LaserBending = $arr2[7];
    $InjectionMold = $arr2[8];
    $Maintenance = $arr2[9];
    $MachEngineering = $arr2[10];
    $IT = $arr2[11];
    $Warehouse = $arr2[12];
    $PE = $arr2[13];
    $arrBawah = array($Machining,$Fabrication,$Injection,$Electronics,$Assembly,$Quality,$Shipping,$LaserBending,$InjectionMold,$Maintenance,$MachEngineering,$IT,$Warehouse,$PE);
}
    $arrBawah2 = array();
    foreach($arrBawah as $Main)
    {
        if($Main != '')
        { 
        $arrMain = explode("+",$Main);
        $Expense = $arrMain[0];
        $TargetManHour = $arrMain[1];
        $TargetMachHour = $arrMain[2];
        $TargetMatCost = $arrMain[3];
        $LimMax = $arrMain[4];
        $EstDate = $arrMain[5];
        $EstHalf = $arrMain[6];
        $DM = $arrMain[7];
        $Loc = $arrMain[8];
            $TempArray = array("Quote" => $Quote,
            "NamaPM" => $NamaPM,
            "NamaCOPM" => $NamaCOPM,
            "QuoteCategory" => $QuoteCategory,
            "WOP" => $WOP,
            "QtyParent" => $QtyParent,
            "Product" => $Product,
            "WOC" => $WOC,
            "JenisWO" => $JenisWO,
            "Order" => $OrderType,
            "Expense" => $Expense,
            "TargetManHour" => $TargetManHour,
            "TargetMachHour" => $TargetMachHour,
            "TargetMatCost" => $TargetMatCost,
            "LimMax" => $LimMax,
            "EstDate" => $EstDate,
            "EstHalf" => $EstHalf,
            "NamaDM" => $DM,
            "Lokasi" => $Loc);
            array_push($arrBawah2,$TempArray);
        }
        else {}
    }
    foreach($arrBawah2 as $Main2)
    {
        $ValQuote = trim($Main2['Quote']);
        $ValNamaPM = trim($Main2['NamaPM']);
        $ValNamaCOPM = trim($Main2['NamaCOPM']);
        $ValQuoteCategory = trim($Main2['QuoteCategory']);
        $ValWOP = trim($Main2['WOP']);
        $ValQtyParent = trim($Main2['QtyParent']);
        $ValProduct = trim($Main2['Product']);
        $ValWOC = trim($Main2['WOC']);
        $ValJenisWO = trim($Main2['JenisWO']);
        $ValOrder = trim($Main2['Order']);
        $ValExpense = trim($Main2['Expense']);
        $ValTargetManHour = trim($Main2['TargetManHour']);
        $ValTargetMachHour = trim($Main2['TargetMachHour']);
        $ValTargetMatCost = trim($Main2['TargetMatCost']);
        $ValLimMax = trim($Main2['LimMax']);
        $ValEstDate = trim($Main2['EstDate']);
        $ValEstHalf = trim($Main2['EstHalf']);
        $ValNamaDM = trim($Main2['NamaDM']);
        $ValLoc = trim($Main2['Lokasi']);
        $cek = CEK_WOP($ValQuote,$ValWOC,$ValExpense,$ValEstHalf,$linkMACHWebTrax);
        if(sqlsrv_num_rows($cek) > 0)
        { 
            echo '<div class="alert alert-danger fw-bold" id="FailedBar2" role="alert">Expense '.$ValExpense.': Already Exist</div>';
        }
        else
        {
            $insert = INSERT_WO_MAPPING_BARU($ValLoc,$FullName,$ValQuote,$ValNamaPM,$ValNamaCOPM,$ValQuoteCategory,$ValWOP,$ValQtyParent,$ValProduct,$ValWOC,$ValJenisWO,$ValOrder,$ValExpense,$ValTargetManHour,$ValTargetMachHour,$ValTargetMatCost,$ValLimMax,$ValEstDate,$ValEstHalf,$ValNamaDM,$linkMACHWebTrax);
            if($insert == 'FALSE')
            {
                echo '<div class="alert alert-danger fw-bold" id="FailedBar" role="alert">ERROR INSERTING:'.$ValExpense.'</div>';
            }
            else
            {
                echo '<div class="alert alert-success fw-bold" id="SuccessBar" role="alert">INSERT SUCCESS:'.$ValExpense.'</div>';
            }
            
        }
    }

?>
<script language="javascript">
    setTimeout(myFunction2, 2000);
    function myFunction2()
    {
        $('#ModalProsesCreate').modal('hide');
        $("#FormExpense tr").find("td:eq(1)").text('');
        $("#FormExpense tr").find("td:eq(2)").text('');
        $("#FormExpense tr").find("td:eq(3)").text('');
        $("#FormExpense tr").find("td:eq(4)").text('');
        $("#FormExpense tr").find("td:eq(5)").text('');
        $("#FormExpense tr").find("td:eq(6)").text('');
        $("#FormExpense tr").find("td:eq(7)").text('');
        $("#FormExpense tr").find("td:eq(8)").text('');
        $("#QtyWOP").val('');
        $("#FilProduct").val('');
        $("#FilWOC").val('');
        $("#FILWOP").val('');
    }
</script>