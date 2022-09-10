<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOutPartTBZ.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
/*
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
*/
$LinkPSL = $linkMACHWebTrax;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValID = htmlspecialchars(trim($_POST['ValID']), ENT_QUOTES, "UTF-8");
    $ValCompanyID = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $Location = GET_LOCATION_COMPANY_BY_CODE($ValCompanyID,$LinkPSL);
    # check label issued sudah tersimpan sebelumnya di material tracking
    $TotalLabelIssued = CHECK_TOTAL_LABEL_ISSUED($ValID,$Location,$LinkPSL);
    if($TotalLabelIssued == 0)
    {
        # data ID
        $QDataID = GET_DETAIL_DATA_INVENTORY_ISSUED_OUT_LABEL($ValID,$LinkPSL);
        if(sqlsrv_num_rows($QDataID) > 0)
        {
            $RDataID = sqlsrv_fetch_array($QDataID);
            $DataPN = trim($RDataID['PartNo']);
            // $DataQty = number_format((float)trim($RDataID['Qty']), 2, '.', ',');
            $DataQty = number_format((float)trim($RDataID['Qty']), 2, '.', '');
            $DataUOM = trim($RDataID['UOM']);
            $DataWOTBZ = trim($RDataID['WorkOrderID']);
            $DataJobID = trim($RDataID['JobID']);
            $DataTBZLineItem = trim($RDataID['SequenceID']);
            # get stock type
            $StockType = GET_STOCK_TYPE($DataPN,$LinkPSL);
            # get category pn
            $CategoryPN = GET_CATEGORY_PN($DataPN,$LinkPSL);
            # get partno desc
            $QDataPN = GET_PN_DESCRIPTION($DataPN,$LinkPSL);
            $RDataPN = sqlsrv_fetch_array($QDataPN);
            $PNDesc = utf8_decode(trim($RDataPN['PartDescription']));
            // $UnitCost = number_format((float)utf8_encode(trim(trim($RDataPN['UnitCost']))), 4, '.', ',');
            $UnitCost = number_format((float)utf8_encode(trim(trim($RDataPN['UnitCost']))), 4, '.', '');
            # get data wo mapping
            // $QDataWOMapping = GET_DETAIL_WO_MAPPING($DataJobID,$ValExpenseWH,$LinkPSL);
            // $RDataWOMapping = sqlsrv_fetch_array($QDataWOMapping);
            // $WOID = trim($RDataWOMapping['Idx']);
            // $WOChild = trim($RDataWOMapping['WOChild']);
            // $Product = trim($RDataWOMapping['Product']);
            // $Expense = trim($RDataWOMapping['ExpenseAllocation']);
            // $ClosedTime = trim($RDataWOMapping['ClosedTime']);
            # get data jenis stok
            $JenisStock = GET_TYPE_STOCK_PN($DataPN,$LinkPSL);

            $DataResult = array(
                "Idx" => $ValID,
                // "ExpenseWH" => $ValExpenseWH,
                "ExpenseWH" => "",
                "CompanyID" => $ValCompanyID,
                "PartNo" => $DataPN,
                "Qty" => $DataQty,
                "UOM" => $DataUOM,
                "WOID" => $DataWOTBZ,
                // "WOID" => "",
                "JobID" => $DataJobID,
                "SequenceID" => $DataTBZLineItem,
                "StockType" => $StockType,
                "CategoryPN" => $CategoryPN,
                "PNDesc" => $PNDesc,
                "UnitCost" => $UnitCost,
                // "WOChild" => $WOChild,
                "WOChild" => "",
                // "Product" => $Product,
                "Product" => "",
                // "Expense" => $Expense,
                "Expense" => "",
                // "ClosedTime" => $ClosedTime,
                "ClosedTime" => "",
                "JenisStock" => $JenisStock,
                // "WOIdx" => $WOID
                "WOIdx" => ""
            );
            echo "TRUE >> ".json_encode($DataResult);
        }
        else
        {
            echo "FALSE >> 0";
        }
    }
    else
    {
        echo "FALSE >> 1";
    }
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>