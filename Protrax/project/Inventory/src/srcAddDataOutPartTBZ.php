<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOutPartTBZ.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
$FullName = "local-DIMAS FARRABI";
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
    $ProcessType = htmlspecialchars(trim($_POST['ProcessType']), ENT_QUOTES, "UTF-8");
    if($ProcessType == "Get Transact Date From TBZ")
    {
        $InputSequence = htmlspecialchars(trim($_POST['InputSequence']), ENT_QUOTES, "UTF-8");
        $QData = GET_DATE_TRANSACTION_TBZ($InputSequence,$LinkPSL);
        if(sqlsrv_num_rows($QData) > 0)
        {
            $RData = sqlsrv_fetch_array($QData);
            echo "TRUE >> ".trim($RData['TransactDate']);
        }
        else
        {
            echo "FALSE >> ".date("m/d/Y");
        }
    }
    if($ProcessType == "Get UOM Conv")
    {
        $InputPartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $QData = GET_UOM_BY_PN($InputPartNo,$LinkPSL);
        if(sqlsrv_num_rows($QData) > 0)
        {
            $RData = sqlsrv_fetch_array($QData);
            echo "TRUE >> ".trim($RData['UOMTransaction']);
        }
        else
        {
            echo "FALSE >> 0";
        }
    }
    if($ProcessType == "CHECK EXISTING TLI")
    {
        $SequenceID = htmlspecialchars(trim($_POST['SequenceID']), ENT_QUOTES, "UTF-8");
        $Total = CHECK_EXISTING_TLI_ON_TRANSACTION($SequenceID, "IN", $LinkPSL);
        echo $Total;
    }
    if($ProcessType == "CHECK EXISTING TLI TANPA STOCK")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $PartDesc = htmlspecialchars(trim($_POST['PartDesc']), ENT_QUOTES, "UTF-8");
        $TransactionDate = htmlspecialchars(trim($_POST['TransactionDate']), ENT_QUOTES, "UTF-8");
        $QtyInput = htmlspecialchars(trim($_POST['QtyInput']), ENT_QUOTES, "UTF-8");
        $UOMConv = htmlspecialchars(trim($_POST['UOMConv']), ENT_QUOTES, "UTF-8");
        $UnitCost = htmlspecialchars(trim($_POST['UnitCost']), ENT_QUOTES, "UTF-8");
        $EmployeeName = htmlspecialchars(trim($_POST['EmployeeName']), ENT_QUOTES, "UTF-8");
        $CategoryMaterial = htmlspecialchars(trim($_POST['CategoryMaterial']), ENT_QUOTES, "UTF-8");
        $MaterialStockOwner = htmlspecialchars(trim($_POST['MaterialStockOwner']), ENT_QUOTES, "UTF-8");
        $TotalCost = htmlspecialchars(trim($_POST['TotalCost']), ENT_QUOTES, "UTF-8");
        $TransactID = htmlspecialchars(trim($_POST['TransactID']), ENT_QUOTES, "UTF-8");
        $WOChildExpense = htmlspecialchars(trim($_POST['WOChildExpense']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $LabelIDIssuedOutTBZ = htmlspecialchars(trim($_POST['LabelIDIssuedOutTBZ']), ENT_QUOTES, "UTF-8");
        $SequenceID = htmlspecialchars(trim($_POST['SequenceID']), ENT_QUOTES, "UTF-8");
        $WOMappingID = htmlspecialchars(trim($_POST['WOMappingID']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");
        $InputStockType = htmlspecialchars(trim($_POST['InputStockType']), ENT_QUOTES, "UTF-8");

        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $Res = INPUT_POSITIVE_ADJUSTMENT($PartNo,$PartDesc,"NEW",$TransactionDate,$QtyInput,$UOMConv,$UnitCost,$EmployeeName,$CategoryMaterial,$Time,$MaterialStockOwner,"IN",$UnitCost,$TotalCost,$TransactID,$WOChildExpense,$StockDivision,$LabelIDIssuedOutTBZ,$SequenceID,$NewCompanyWH,$WOMappingID,$InputStockType,$LinkPSL);
        echo $Res;
    }
    if($ProcessType == "CHECK EXISTING TLI TANPA STOCK OUT")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $PartDesc = htmlspecialchars(trim($_POST['PartDesc']), ENT_QUOTES, "UTF-8");
        $TransactionDate = htmlspecialchars(trim($_POST['TransactionDate']), ENT_QUOTES, "UTF-8");
        $QtyInput = htmlspecialchars(trim($_POST['QtyInput']), ENT_QUOTES, "UTF-8");
        $UOMConv = htmlspecialchars(trim($_POST['UOMConv']), ENT_QUOTES, "UTF-8");
        $UnitCost = htmlspecialchars(trim($_POST['UnitCost']), ENT_QUOTES, "UTF-8");
        $EmployeeName = htmlspecialchars(trim($_POST['EmployeeName']), ENT_QUOTES, "UTF-8");
        $EmployeeNIK = htmlspecialchars(trim($_POST['EmployeeNIK']), ENT_QUOTES, "UTF-8");
        $CategoryMaterial = htmlspecialchars(trim($_POST['CategoryMaterial']), ENT_QUOTES, "UTF-8");
        $MaterialStockOwner = htmlspecialchars(trim($_POST['MaterialStockOwner']), ENT_QUOTES, "UTF-8");
        $TotalCost = htmlspecialchars(trim($_POST['TotalCost']), ENT_QUOTES, "UTF-8");
        $TransactID = htmlspecialchars(trim($_POST['TransactID']), ENT_QUOTES, "UTF-8");
        $WOChildExpense = htmlspecialchars(trim($_POST['WOChildExpense']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $LabelIDIssuedOutTBZ = htmlspecialchars(trim($_POST['LabelIDIssuedOutTBZ']), ENT_QUOTES, "UTF-8");
        $SequenceID = htmlspecialchars(trim($_POST['SequenceID']), ENT_QUOTES, "UTF-8");
        $WOMappingID = htmlspecialchars(trim($_POST['WOMappingID']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");
        $JobID = htmlspecialchars(trim($_POST['InputJobID']), ENT_QUOTES, "UTF-8");
        $Product = htmlspecialchars(trim($_POST['Product']), ENT_QUOTES, "UTF-8");
        $InputStockType = htmlspecialchars(trim($_POST['InputStockType']), ENT_QUOTES, "UTF-8");
		
        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $Res = SAVE_MATERIAL_USAGE_FOR_ASSEMBLY($TransactionDate,$EmployeeNIK,$EmployeeName,"",$JobID,$Product,$WOChildExpense,$PartNo,$PartDesc,$UnitCost,$UOMConv,$UnitCost,$TotalCost,$QtyInput,$CategoryMaterial,$Time,$MaterialStockOwner,"",$TransactID,"OUT",$StockDivision,$LabelIDIssuedOutTBZ,$SequenceID,$NewCompanyWH,$WOMappingID,$InputStockType,$LinkPSL);
        echo $Res;
    }
    if($ProcessType == "CHECK STOCK LIST PN")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $DivisionWH = htmlspecialchars(trim($_POST['DivisionWH']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['Company']), ENT_QUOTES, "UTF-8");
        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $Count = CHECK_PART_ON_STOCK_LIST($PartNo,$DivisionWH,$NewCompanyWH,$LinkPSL);
        echo $Count;
    }
    if($ProcessType == "NEW STOCK")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $PartDesc = htmlspecialchars(trim($_POST['PartDesc']), ENT_QUOTES, "UTF-8");
        $TransactionDate = htmlspecialchars(trim($_POST['TransactionDate']), ENT_QUOTES, "UTF-8");
        $QtyInput = htmlspecialchars(trim($_POST['QtyInput']), ENT_QUOTES, "UTF-8");
        $UOMConv = htmlspecialchars(trim($_POST['UOMConv']), ENT_QUOTES, "UTF-8");
        $UnitCost = htmlspecialchars(trim($_POST['UnitCost']), ENT_QUOTES, "UTF-8");
        $EmployeeName = htmlspecialchars(trim($_POST['EmployeeName']), ENT_QUOTES, "UTF-8");
        $CategoryMaterial = htmlspecialchars(trim($_POST['CategoryMaterial']), ENT_QUOTES, "UTF-8");
        $MaterialStockOwner = htmlspecialchars(trim($_POST['MaterialStockOwner']), ENT_QUOTES, "UTF-8");
        $TotalCost = htmlspecialchars(trim($_POST['TotalCost']), ENT_QUOTES, "UTF-8");
        $TransactID = htmlspecialchars(trim($_POST['TransactID']), ENT_QUOTES, "UTF-8");
        $WOChildExpense = htmlspecialchars(trim($_POST['WOChildExpense']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $LabelIDIssuedOutTBZ = htmlspecialchars(trim($_POST['LabelIDIssuedOutTBZ']), ENT_QUOTES, "UTF-8");
        $SequenceID = htmlspecialchars(trim($_POST['SequenceID']), ENT_QUOTES, "UTF-8");
        $WOMappingID = htmlspecialchars(trim($_POST['WOMappingID']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");

        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $Add = INSERT_STOCK_MATERIAL_PER_DIVISION($PartNo,$PartDesc,"0","NEW",$Time,"0",$UOMConv,$UnitCost,$EmployeeName,$CategoryMaterial,$StockDivision,$NewCompanyWH,$LinkPSL);
        echo $Add;
    }
    if($ProcessType == "CHECK STOCK QTY")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");

        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $CountQty = GET_QTY_PN_DIV($PartNo,$StockDivision,$NewCompanyWH,$LinkPSL);
        echo $CountQty;
    }
    if($ProcessType == "EDIT STOCK QTY")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $QtyInput = htmlspecialchars(trim($_POST['QtyInput']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");

        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $OldQty = htmlspecialchars(trim($_POST['OldQty']), ENT_QUOTES, "UTF-8");
        $Res = UPDATE_QTY_PN_DIV($PartNo,$QtyInput,$StockDivision,$NewCompanyWH,$OldQty,$LinkPSL);
        echo $Res;
    }
    if($ProcessType == "TRANSACTION IN")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $PartDesc = htmlspecialchars(trim($_POST['PartDesc']), ENT_QUOTES, "UTF-8");
        $TransactionDate = htmlspecialchars(trim($_POST['TransactionDate']), ENT_QUOTES, "UTF-8");
        $QtyInput = htmlspecialchars(trim($_POST['QtyInput']), ENT_QUOTES, "UTF-8");
        $UOMConv = htmlspecialchars(trim($_POST['UOMConv']), ENT_QUOTES, "UTF-8");
        $UnitCost = htmlspecialchars(trim($_POST['UnitCost']), ENT_QUOTES, "UTF-8");
        $EmployeeName = htmlspecialchars(trim($_POST['EmployeeName']), ENT_QUOTES, "UTF-8");
        $CategoryMaterial = htmlspecialchars(trim($_POST['CategoryMaterial']), ENT_QUOTES, "UTF-8");
        $MaterialStockOwner = htmlspecialchars(trim($_POST['MaterialStockOwner']), ENT_QUOTES, "UTF-8");
        $TotalCost = htmlspecialchars(trim($_POST['TotalCost']), ENT_QUOTES, "UTF-8");
        $TransactID = htmlspecialchars(trim($_POST['TransactID']), ENT_QUOTES, "UTF-8");
        $WOChildExpense = htmlspecialchars(trim($_POST['WOChildExpense']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $LabelIDIssuedOutTBZ = htmlspecialchars(trim($_POST['LabelIDIssuedOutTBZ']), ENT_QUOTES, "UTF-8");
        $SequenceID = htmlspecialchars(trim($_POST['SequenceID']), ENT_QUOTES, "UTF-8");
        $WOMappingID = htmlspecialchars(trim($_POST['WOMappingID']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");
        $InputStockType = htmlspecialchars(trim($_POST['InputStockType']), ENT_QUOTES, "UTF-8");

        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $ResAdd = INPUT_POSITIVE_ADJUSTMENT($PartNo,$PartDesc,"NEW",$Time,$QtyInput,$UOMConv,$UnitCost,$EmployeeName,$CategoryMaterial,$Time,$MaterialStockOwner,"IN",$UnitCost,$TotalCost,$TransactID,$WOChildExpense,$StockDivision,$LabelIDIssuedOutTBZ,$SequenceID,$NewCompanyWH,$WOMappingID,$InputStockType,$LinkPSL);
        echo $ResAdd;
    }
    if($ProcessType == "ADD HISTORY UPDATE STOCK")
    {
        $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
        $QtyInput = htmlspecialchars(trim($_POST['QtyInput']), ENT_QUOTES, "UTF-8");
        $StockDivision = htmlspecialchars(trim($_POST['StockDivision']), ENT_QUOTES, "UTF-8");
        $TransactID = htmlspecialchars(trim($_POST['TransactID']), ENT_QUOTES, "UTF-8");
        $CompanyWH = htmlspecialchars(trim($_POST['CompanyWH']), ENT_QUOTES, "UTF-8");

        $NewCompanyWH = GET_LOCATION_COMPANY_BY_CODE($CompanyWH,$LinkPSL);
        $Res = SAVE_HISTORY_PERUBAHAN_STOCK($PartNo,$QtyInput,'IN',$StockDivision,$TransactID,$Time,$NewCompanyWH,$LinkPSL);
        echo $Res;
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