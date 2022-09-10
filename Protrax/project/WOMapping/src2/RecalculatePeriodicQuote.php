<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
/*
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
function LIST_ID_DIVISION($link)
{
    $sql = "SELECT Division_ID,DivisionName FROM [FMLX-MACH].dbo.T_DIVISION ORDER BY CAST(Division_ID AS INT)";
    $query = sqlsrv_query($link, $sql);
    return ($query);
}
function GET_LIST_QUOTE_CATEGORY($Cat,$link)
{
    $sql = "SELECT QuoteCategory 
    FROM [FMLX-MACH].dbo.T_WO_MAPPING_WITH_EXPENSE 
    WHERE QuoteCategory IS NOT NULL AND QuoteCategory = '$Cat'
    GROUP BY QuoteCategory 
    ORDER BY QuoteCategory ASC";
    $query = sqlsrv_query($link, $sql);
    return ($query);
}
function GET_DATA_RECALCULATE($QuoteCategory,$ClosedTime,$link)
{
    $sql = "SELECT 
        TB.Division AS [TemplateDivision],
		TB.SortOrder AS [TemplateSortOrder],
        *,
        (COALESCE(tx.TargetPeopleCost,0)+COALESCE(tx.TargetMachineCost,0)+COALESCE(tx.TargetMaterialCost,0)) as[TotalTargetCost],
        (COALESCE(tx.PeopleCost,0)+COALESCE(tx.MachineCost,0)+COALESCE(tx.MaterialCost,0)) as [TotalActualCost],
        ((COALESCE(tx.TargetPeopleCost,0)+COALESCE(tx.TargetMachineCost,0)+COALESCE(tx.TargetMaterialCost,0))*COALESCE(tx.QtyTarget,0)) as [TotalTargetCostTargetQty],
        ((COALESCE(tx.TargetPeopleCost,0)+COALESCE(tx.TargetMachineCost,0)+COALESCE(tx.TargetMaterialCost,0))*COALESCE(tx.QtyQuote,0)) as [TotalTargetCostActualQty],
        ((COALESCE(tx.PeopleCost,0)+COALESCE(tx.MachineCost,0)+COALESCE(tx.MaterialCost,0))*COALESCE(tx.QtyQuote,0)) as [TotalActualCostActualQty]
		,tx.PM,tx.DM
        FROM (
        SELECT
                distinct (t1.ExpenseAllocation), 
                CAST(t2.SortNumber AS INT) AS 'SortNumber',
                t1.ClosedTime, 
                t1.QuoteCategory,
                t1.Quote,
                t1.QtyQuote,
                CASE WHEN (SELECT TOP 1 t10.QtyTarget FROM T_PROJECT_TARGET_QTY AS t10 WHERE t10.Quote = t1.Quote AND t10.ExpenseAllocation = t1.ExpenseAllocation AND t10.Half = t1.ClosedTime) is null or (SELECT TOP 1 t10.QtyTarget FROM T_PROJECT_TARGET_QTY AS t10 WHERE t10.Quote = t1.Quote AND t10.ExpenseAllocation = t1.ExpenseAllocation AND t10.Half = t1.ClosedTime) < t1.QtyQuote
                THEN t1.QtyQuote ELSE (SELECT TOP 1 t10.QtyTarget FROM T_PROJECT_TARGET_QTY AS t10 WHERE t10.Quote = t1.Quote AND t10.ExpenseAllocation = t1.ExpenseAllocation AND t10.Half = t1.ClosedTime) END as [QtyTarget],
                (SELECT TOP 1 t2.TargetCost FROM T_PROJECT_TARGET_COST as t2 WHERE t2.Quote = t1.Quote AND t2.ExpenseAllocation = t1.ExpenseAllocation AND t2.Half = t1.ClosedTime AND t2.CostType = 'PEOPLE' ORDER BY Idx DESC) as [TargetPeopleCost], 
                ((SELECT SUM(t3.ManHourCost) FROM T_WO_MAPPING_WITH_EXPENSE as t3 WHERE t3.Quote = t1.Quote AND t3.ExpenseAllocation = t1.ExpenseAllocation AND t3.ClosedTime = t1.ClosedTime /*AND t3.LocationCode = t1.LocationCode*/)/NULLIF(t1.QtyQuote,0)) as [PeopleCost], 
                (SELECT TOP 1 t4.TargetCost FROM T_PROJECT_TARGET_COST as t4 WHERE t4.Quote = t1.Quote AND t4.ExpenseAllocation = t1.ExpenseAllocation AND t4.Half = t1.ClosedTime AND t4.CostType = 'MACHINE' ORDER BY Idx DESC) as [TargetMachineCost], 
                ((SELECT SUM(t5.MachineHourCost) FROM T_WO_MAPPING_WITH_EXPENSE as t5 WHERE t5.Quote = t1.Quote AND t5.ExpenseAllocation = t1.ExpenseAllocation AND t5.ClosedTime = t1.ClosedTime /*AND t5.LocationCode = t1.LocationCode*/)/NULLIF(t1.QtyQuote,0)) as [MachineCost], 
                (SELECT TOP 1 t6.TargetCost FROM T_PROJECT_TARGET_COST as t6 WHERE t6.Quote = t1.Quote AND t6.ExpenseAllocation = t1.ExpenseAllocation AND t6.Half = t1.ClosedTime AND t6.CostType = 'MATERIAL' ORDER BY Idx DESC) as [TargetMaterialCost],
                ((SELECT SUM(t7.MaterialCost) FROM T_WO_MAPPING_WITH_EXPENSE as t7 WHERE t7.Quote = t1.Quote AND t7.ExpenseAllocation = t1.ExpenseAllocation AND t7.ClosedTime = t1.ClosedTime /*AND t7.LocationCode = t1.LocationCode*/)/NULLIF(t1.QtyQuote,0)) as [MaterialCost], 
                ((SELECT SUM(t8.QtyQCIn) FROM T_WO_MAPPING_WITH_EXPENSE as t8 WHERE t8.Quote = t1.Quote AND t8.ExpenseAllocation = t1.ExpenseAllocation AND t8.ClosedTime = t1.ClosedTime /*AND t8.LocationCode = t1.LocationCode*/)) as QtyQCIn, 
                ((SELECT SUM(t9.QtyQCIn) FROM T_WO_MAPPING_WITH_EXPENSE as t9 WHERE t9.Quote = t1.Quote AND t9.ExpenseAllocation = t1.ExpenseAllocation AND t9.ClosedTime = t1.ClosedTime /*AND t9.LocationCode = t1.LocationCode*/)) as QtyQCOut,  
                (SELECT Z.Division_ID FROM T_DIVISION AS Z WHERE Z.DivisionName = t1.ExpenseAllocation) AS [DivID],
                (SELECT Z2.Idx FROM T_PROJECT AS Z2 WHERE Z2.ProjectName = t1.Quote) AS [ProjectID]
				,t1.PM,t1.DM,t1.CO_PM
            FROM 
                T_WO_MAPPING_WITH_EXPENSE as t1 
                INNER JOIN T_EXPENSE_ALLOCATION AS t2 ON t1.ExpenseAllocation = t2.ExpenseOption		
            WHERE 
            t1.QuoteCategory = '$QuoteCategory' 
            AND t1.ClosedTime = '$ClosedTime'
        ) as tx
		RIGHT OUTER JOIN  [FMLX-MACH].dbo.T_QUALITY_POINT_DIVISION AS TB ON tx.ExpenseAllocation = TB.Division
    ORDER BY tx.Quote,CAST(tx.SortNumber AS INT)";
    
    $query = sqlsrv_query($link, $sql);
    return ($query);
}
function CHECK_DATA_PERIODIC($Expense,$TargetClosed,$Category,$Quote,$link)
{
    $sql = "SELECT TOP 1 * 
    FROM [FMLX-WEBTRAX].dbo.T_PERIODIC_CLOSED_DETAIL_QUOTE_COST 
    WHERE ExpenseAllocation = '$Expense' 
    AND HalfClosed = '$TargetClosed' 
    AND QuoteCategory = '$Category' 
    AND Quote = '$Quote' 
    ORDER BY Idx DESC";
    $query = sqlsrv_query($link, $sql, array(), array( "Scrollable" => 'static' ));
    return ($query);
}
function INSERT_NEW_DATA_PERIODIC_QUOTE($Expense,$SortNumber,$HalfClosed,$QuoteCategory,$Quote,$QtyQuote,$QtyTarget,$TargetPeopleCost,$PeopleCost,$TargetMachineCost,$MachineCost,$TargetMaterialCost,$MaterialCost,$QtyQCIn,$QtyQCOut,$DivID,$ProjectID,$TotalTargetCost,$TotalActualCost,$TotalTargetCostAndTargetQty,$TotalTargetCostAndActualQty,$TotalActualCostAndActualQty,$IsManualInput,$PM,$DM,$COPM,$link)
{
    if(trim($Expense) == "NULL"){$ValExpense = 'NULL';}else{$ValExpense = "'".trim($Expense)."'";}
    if(trim($SortNumber) == "NULL"){$ValSortNumber = 'NULL';}else{$ValSortNumber = "'".trim($SortNumber)."'";}
    if(trim($HalfClosed) == "NULL"){$ValHalfClosed = 'NULL';}else{$ValHalfClosed = "'".trim($HalfClosed)."'";}
    if(trim($QuoteCategory) == "NULL"){$ValQuoteCategory = 'NULL';}else{$ValQuoteCategory = "'".trim($QuoteCategory)."'";}
    if(trim($Quote) == "NULL"){$ValQuote = 'NULL';}else{$ValQuote = "'".trim($Quote)."'";}
    if(trim($QtyQuote) == "NULL"){$ValQtyQuote = 'NULL';}else{$ValQtyQuote = "'".trim($QtyQuote)."'";}
    if(trim($QtyTarget) == "NULL"){$ValQtyTarget = 'NULL';}else{$ValQtyTarget = "'".trim($QtyTarget)."'";}
    if(trim($TargetPeopleCost) == "NULL"){$ValTargetPeopleCost = 'NULL';}else{$ValTargetPeopleCost = "'".trim($TargetPeopleCost)."'";}
    if(trim($PeopleCost) == "NULL"){$ValPeopleCost = 'NULL';}else{$ValPeopleCost = "'".trim($PeopleCost)."'";}
    if(trim($TargetMachineCost) == "NULL"){$ValTargetMachineCost = 'NULL';}else{$ValTargetMachineCost = "'".trim($TargetMachineCost)."'";}
    if(trim($MachineCost) == "NULL"){$ValMachineCost = 'NULL';}else{$ValMachineCost = "'".trim($MachineCost)."'";}
    if(trim($TargetMaterialCost) == "NULL"){$ValTargetMaterialCost = 'NULL';}else{$ValTargetMaterialCost = "'".trim($TargetMaterialCost)."'";}
    if(trim($MaterialCost) == "NULL"){$ValMaterialCost = 'NULL';}else{$ValMaterialCost = "'".trim($MaterialCost)."'";}
    if(trim($QtyQCIn) == "NULL"){$ValQtyQCIn = 'NULL';}else{$ValQtyQCIn = "'".trim($QtyQCIn)."'";}
    if(trim($QtyQCOut) == "NULL"){$ValQtyQCOut = 'NULL';}else{$ValQtyQCOut = "'".trim($QtyQCOut)."'";}
    if(trim($DivID) == "NULL"){$ValDivID = 'NULL';}else{$ValDivID = "'".trim($DivID)."'";}
    if(trim($ProjectID) == "NULL"){$ValProjectID = 'NULL';}else{$ValProjectID = "'".trim($ProjectID)."'";}
    if(trim($TotalTargetCost) == "NULL"){$ValTotalTargetCost = 'NULL';}else{$ValTotalTargetCost = "'".trim($TotalTargetCost)."'";}
    if(trim($TotalActualCost) == "NULL"){$ValTotalActualCost = 'NULL';}else{$ValTotalActualCost = "'".trim($TotalActualCost)."'";}
    if(trim($TotalTargetCostAndTargetQty) == "NULL"){$ValTotalTargetCostAndTargetQty = 'NULL';}else{$ValTotalTargetCostAndTargetQty = "'".trim($TotalTargetCostAndTargetQty)."'";}
    if(trim($TotalTargetCostAndActualQty) == "NULL"){$ValTotalTargetCostAndActualQty = 'NULL';}else{$ValTotalTargetCostAndActualQty = "'".trim($TotalTargetCostAndActualQty)."'";}
    if(trim($TotalActualCostAndActualQty) == "NULL"){$ValTotalActualCostAndActualQty = 'NULL';}else{$ValTotalActualCostAndActualQty = "'".trim($TotalActualCostAndActualQty)."'";}
    if(trim($IsManualInput) == "NULL"){$ValIsManualInput = 'NULL';}else{$ValIsManualInput = "'".trim($IsManualInput)."'";}
    if(trim($PM) == "NULL"){$ValPM = 'NULL';}else{$ValPM = "'".trim($PM)."'";}
    if(trim($DM) == "NULL"){$ValDM = 'NULL';}else{$ValDM = "'".trim($DM)."'";}
	if(trim($COPM) == "NULL"){$ValCOPM = 'NULL';}else{$ValCOPM = "'".trim($COPM)."'";}


    $sql = "INSERT INTO [FMLX-WEBTRAX].dbo.T_PERIODIC_CLOSED_DETAIL_QUOTE_COST(ExpenseAllocation,SortNumber,HalfClosed,QuoteCategory,Quote,QtyQuote,QtyTarget,TargetPeopleCost,PeopleCost,TargetMachineCost,MachineCost,TargetMaterialCost,MaterialCost,QtyQCIn,QtyQCOut,DivID,ProjectID,TotalTargetCost,TotalActualCost,TotalTargetCostAndTargetQty,TotalTargetCostAndActualQty,TotalActualCostAndActualQty,Is_ManualInput,PM,DM,CO_PM) VALUES ($ValExpense,$ValSortNumber,$ValHalfClosed,$ValQuoteCategory,$ValQuote,$ValQtyQuote,$ValQtyTarget,$ValTargetPeopleCost,$ValPeopleCost,$ValTargetMachineCost,$ValMachineCost,$ValTargetMaterialCost,$ValMaterialCost,$ValQtyQCIn,$ValQtyQCOut,$ValDivID,$ValProjectID,$ValTotalTargetCost,$ValTotalActualCost,$ValTotalTargetCostAndTargetQty,$ValTotalTargetCostAndActualQty,$ValTotalActualCostAndActualQty,$ValIsManualInput,$ValPM,$ValDM,$ValCOPM)";
    // echo $sql;
    $query = sqlsrv_query($link, $sql);
    if($query)
    {
        return "TRUE";
    }
    else
    {
        return "FALSE";    
    }
}
function UPDATE_DATA_PERIODIC_QUOTE($Idx,$Expense,$SortNumber,$HalfClosed,$QuoteCategory,$Quote,$QtyQuote,$QtyTarget,$TargetPeopleCost,$PeopleCost,$TargetMachineCost,$MachineCost,$TargetMaterialCost,$MaterialCost,$QtyQCIn,$QtyQCOut,$DivID,$ProjectID,$TotalTargetCost,$TotalActualCost,$TotalTargetCostAndTargetQty,$TotalTargetCostAndActualQty,$TotalActualCostAndActualQty,$IsManualInput,$PM,$DM,$COPM,$link)
{
    if(trim($Expense) == "NULL"){$ValExpense = 'NULL';}else{$ValExpense = "'".trim($Expense)."'";}
    if(trim($SortNumber) == "NULL"){$ValSortNumber = 'NULL';}else{$ValSortNumber = "'".trim($SortNumber)."'";}
    if(trim($HalfClosed) == "NULL"){$ValHalfClosed = 'NULL';}else{$ValHalfClosed = "'".trim($HalfClosed)."'";}
    if(trim($QuoteCategory) == "NULL"){$ValQuoteCategory = 'NULL';}else{$ValQuoteCategory = "'".trim($QuoteCategory)."'";}
    if(trim($Quote) == "NULL"){$ValQuote = 'NULL';}else{$ValQuote = "'".trim($Quote)."'";}
    if(trim($QtyQuote) == "NULL"){$ValQtyQuote = 'NULL';}else{$ValQtyQuote = "'".trim($QtyQuote)."'";}
    if(trim($QtyTarget) == "NULL"){$ValQtyTarget = 'NULL';}else{$ValQtyTarget = "'".trim($QtyTarget)."'";}
    if(trim($TargetPeopleCost) == "NULL"){$ValTargetPeopleCost = 'NULL';}else{$ValTargetPeopleCost = "'".trim($TargetPeopleCost)."'";}
    if(trim($PeopleCost) == "NULL"){$ValPeopleCost = 'NULL';}else{$ValPeopleCost = "'".trim($PeopleCost)."'";}
    if(trim($TargetMachineCost) == "NULL"){$ValTargetMachineCost = 'NULL';}else{$ValTargetMachineCost = "'".trim($TargetMachineCost)."'";}
    if(trim($MachineCost) == "NULL"){$ValMachineCost = 'NULL';}else{$ValMachineCost = "'".trim($MachineCost)."'";}
    if(trim($TargetMaterialCost) == "NULL"){$ValTargetMaterialCost = 'NULL';}else{$ValTargetMaterialCost = "'".trim($TargetMaterialCost)."'";}
    if(trim($MaterialCost) == "NULL"){$ValMaterialCost = 'NULL';}else{$ValMaterialCost = "'".trim($MaterialCost)."'";}
    if(trim($QtyQCIn) == "NULL"){$ValQtyQCIn = 'NULL';}else{$ValQtyQCIn = "'".trim($QtyQCIn)."'";}
    if(trim($QtyQCOut) == "NULL"){$ValQtyQCOut = 'NULL';}else{$ValQtyQCOut = "'".trim($QtyQCOut)."'";}
    if(trim($DivID) == "NULL"){$ValDivID = 'NULL';}else{$ValDivID = "'".trim($DivID)."'";}
    if(trim($ProjectID) == "NULL"){$ValProjectID = 'NULL';}else{$ValProjectID = "'".trim($ProjectID)."'";}
    if(trim($TotalTargetCost) == "NULL"){$ValTotalTargetCost = 'NULL';}else{$ValTotalTargetCost = "'".trim($TotalTargetCost)."'";}
    if(trim($TotalActualCost) == "NULL"){$ValTotalActualCost = 'NULL';}else{$ValTotalActualCost = "'".trim($TotalActualCost)."'";}
    if(trim($TotalTargetCostAndTargetQty) == "NULL"){$ValTotalTargetCostAndTargetQty = 'NULL';}else{$ValTotalTargetCostAndTargetQty = "'".trim($TotalTargetCostAndTargetQty)."'";}
    if(trim($TotalTargetCostAndActualQty) == "NULL"){$ValTotalTargetCostAndActualQty = 'NULL';}else{$ValTotalTargetCostAndActualQty = "'".trim($TotalTargetCostAndActualQty)."'";}
    if(trim($TotalActualCostAndActualQty) == "NULL"){$ValTotalActualCostAndActualQty = 'NULL';}else{$ValTotalActualCostAndActualQty = "'".trim($TotalActualCostAndActualQty)."'";}
    if(trim($IsManualInput) == "NULL"){$ValIsManualInput = 'NULL';}else{$ValIsManualInput = "'".trim($IsManualInput)."'";}
    if(trim($PM) == "NULL"){$ValPM = 'NULL';}else{$ValPM = "'".trim($PM)."'";}
    if(trim($DM) == "NULL"){$ValDM = 'NULL';}else{$ValDM = "'".trim($DM)."'";}
	if(trim($COPM) == "NULL"){$ValCOPM = 'NULL';}else{$ValCOPM = "'".trim($COPM)."'";}


    $sql = "UPDATE [FMLX-WEBTRAX].dbo.T_PERIODIC_CLOSED_DETAIL_QUOTE_COST 
    SET ExpenseAllocation = $ValExpense, SortNumber = $ValSortNumber, HalfClosed = $ValHalfClosed, QuoteCategory = $ValQuoteCategory, Quote = $ValQuote, QtyQuote = $ValQtyQuote, QtyTarget = $ValQtyTarget, TargetPeopleCost = $ValTargetPeopleCost, PeopleCost = $ValPeopleCost, TargetMachineCost = $ValTargetMachineCost, MachineCost = $ValMachineCost, TargetMaterialCost = $ValTargetMaterialCost, MaterialCost = $ValMaterialCost, QtyQCIn = $ValQtyQCIn, QtyQCOut = $ValQtyQCOut, DivID = $ValDivID, ProjectID = $ValProjectID, TotalTargetCost = $ValTotalTargetCost, TotalActualCost = $ValTotalActualCost, TotalTargetCostAndTargetQty = $ValTotalTargetCostAndTargetQty, TotalTargetCostAndActualQty = $ValTotalTargetCostAndActualQty, TotalActualCostAndActualQty = $ValTotalActualCostAndActualQty, Is_ManualInput = $ValIsManualInput, PM = $ValPM, DM = $ValDM, CO_PM = $ValCOPM    
    WHERE Idx = '$Idx'";
    // echo $sql;
    $query = sqlsrv_query($link, $sql);
    if($query)
    {
        return "TRUE";
    }
    else
    {
        return "FALSE";    
    }
}
function LIST_DIVISION($link)
{
    $sql = "SELECT A.SortOrder,A.Division,B.SortNumber 
    FROM [FMLX-MACH].dbo.T_QUALITY_POINT_DIVISION AS A
    LEFT JOIN [FMLX-MACH].dbo.T_EXPENSE_ALLOCATION AS B ON A.Division = B.ExpenseOption
    WHERE A.Is_Active = '1' ORDER BY CAST(A.SortOrder AS INT)";
    $query = sqlsrv_query($link, $sql);
    return ($query);
}
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValHalf = htmlspecialchars(trim($_POST['ValHalf']), ENT_QUOTES, "UTF-8");
    $ValCat = htmlspecialchars(trim($_POST['ValCat']), ENT_QUOTES, "UTF-8");
    $ValLastClosedTime = $ValHalf;
    $linkMach = $linkMACHWebTrax;

    $ArrListDiv = array();
    $QListDiv = LIST_DIVISION($linkMach);
    while($RListDiv = sqlsrv_fetch_array($QListDiv))
    {
        $TempArray = array(
            "SortOrder" => trim($RListDiv['SortOrder']),
            "DivisionName" => trim($RListDiv['Division']),
            "SortNumber" => trim($RListDiv['SortNumber'])
        );
        array_push($ArrListDiv,$TempArray);
    }

    $ArrListDivisionID2 = array();
    $QListIDDivision = LIST_ID_DIVISION($linkMach);
    while($RListIDDivision = sqlsrv_fetch_array($QListIDDivision))
    {
        $TempArray = array(
            "DivisionID" => trim($RListIDDivision['Division_ID']),
            "DivisionName" => trim($RListIDDivision['DivisionName'])
        );
        array_push($ArrListDivisionID2,$TempArray);
    }
    $ArrRes1 = array();
    $ArrRes2 = array();
    $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY($ValCat,$linkMach);
    while ($RListQuoteCategory = sqlsrv_fetch_array($QListQuoteCategory))
    {
        $ArrRes2 = array();
        $ArrListQuote = array();
        $DtQuoteCategory = trim($RListQuoteCategory['QuoteCategory']);
        $TempListDivision = array();
        $QDataRecalculate = GET_DATA_RECALCULATE($DtQuoteCategory,$ValLastClosedTime,$linkMach);
        while ($RDataRecalculate = sqlsrv_fetch_array($QDataRecalculate))
        {
            $ValExpense = trim($RDataRecalculate['ExpenseAllocation']);
            $ValSortNumber = trim($RDataRecalculate['SortNumber']);
            $ValTargetHalfClosed = trim($RDataRecalculate['ClosedTime']);
            $ValQuoteCategory = trim($RDataRecalculate['QuoteCategory']);
            $ValQuote = trim($RDataRecalculate['Quote']);
            $ValQtyQuote = trim($RDataRecalculate['QtyQuote']);
            $ValQtyQuote = number_format((float)$ValQtyQuote, 0, '.', '');
            $ValQtyTarget = trim($RDataRecalculate['QtyTarget']);
            $ValQtyTarget = number_format((float)$ValQtyTarget, 0, '.', '');
            $ValTargetPeopleCost = trim($RDataRecalculate['TargetPeopleCost']);
            $ValTargetPeopleCost = number_format((float)$ValTargetPeopleCost, 2, '.', '');
            $ValPeopleCost = trim($RDataRecalculate['PeopleCost']);
            $ValPeopleCost = number_format((float)$ValPeopleCost, 2, '.', '');
            $ValTargetMachineCost = trim($RDataRecalculate['TargetMachineCost']);
            $ValTargetMachineCost = number_format((float)$ValTargetMachineCost, 2, '.', '');
            $ValMachineCost = trim($RDataRecalculate['MachineCost']);
            $ValMachineCost = number_format((float)$ValMachineCost, 2, '.', '');
            $ValTargetMaterialCost = trim($RDataRecalculate['TargetMaterialCost']);
            $ValTargetMaterialCost = number_format((float)$ValTargetMaterialCost, 2, '.', '');
            $ValMaterialCost = trim($RDataRecalculate['MaterialCost']);
            $ValMaterialCost = number_format((float)$ValMaterialCost, 2, '.', '');
            $ValQtyQCIn = trim($RDataRecalculate['QtyQCIn']);
            $ValQtyQCIn = number_format((float)$ValQtyQCIn, 2, '.', '');
            $ValQtyQCOut = trim($RDataRecalculate['QtyQCOut']);
            $ValQtyQCOut = number_format((float)$ValQtyQCOut, 2, '.', '');
            $ValDivID = trim($RDataRecalculate['DivID']);
            $ValProjectID = trim($RDataRecalculate['ProjectID']);
            $ValTotalTargetCost = trim($RDataRecalculate['TotalTargetCost']);
            $ValTotalTargetCost = number_format((float)$ValTotalTargetCost, 2, '.', '');
            $ValTotalActualCost = trim($RDataRecalculate['TotalActualCost']);
            $ValTotalActualCost = number_format((float)$ValTotalActualCost, 2, '.', '');
            $ValTotalTargetCostAndTargetQty = trim($RDataRecalculate['TotalTargetCostTargetQty']);
            $ValTotalTargetCostAndTargetQty = number_format((float)$ValTotalTargetCostAndTargetQty, 2, '.', '');
            $ValTotalTargetCostAndActualQty = trim($RDataRecalculate['TotalTargetCostActualQty']);
            $ValTotalTargetCostAndActualQty = number_format((float)$ValTotalTargetCostAndActualQty, 2, '.', '');
            $ValTotalActualCostAndActualQty = trim($RDataRecalculate['TotalActualCostActualQty']);
            $ValTotalActualCostAndActualQty = number_format((float)$ValTotalActualCostAndActualQty, 2, '.', '');
            $ValTempDivision = trim($RDataRecalculate['TemplateDivision']);
            $ValTempSortOrder = trim($RDataRecalculate['TemplateSortOrder']);
            $ValTempPM = trim($RDataRecalculate['PM']);
            $ValTempDM = trim($RDataRecalculate['DM']);
            $ValTempCOPM = trim($RDataRecalculate['CO_PM']);

                $ArrTempRes1 = array(
                    "Expense" => $ValExpense,
                    "SortNumber" => $ValSortNumber,
                    "TargetHalfClosed" => $ValTargetHalfClosed,
                    "QuoteCategory" => $ValQuoteCategory,
                    "Quote" => $ValQuote,
                    "QtyQuote" => $ValQtyQuote,
                    "QtyTarget" => $ValQtyTarget,
                    "TargetPeopleCost" => $ValTargetPeopleCost,
                    "PeopleCost" => $ValPeopleCost,
                    "TargetMachineCost" => $ValTargetMachineCost,
                    "MachineCost" => $ValMachineCost,
                    "TargetMaterialCost" => $ValTargetMaterialCost,
                    "MaterialCost" => $ValMaterialCost,
                    "QtyQCIn" => $ValQtyQCIn,
                    "QtyQCOut" => $ValQtyQCOut,
                    "DivID" => $ValDivID,
                    "ProjectID" => $ValProjectID,
                    "TotalTargetCost" => $ValTotalTargetCost,
                    "TotalActualCost" => $ValTotalActualCost,
                    "TotalTargetCostAndTargetQty" => $ValTotalTargetCostAndTargetQty,
                    "TotalTargetCostAndActualQty" => $ValTotalTargetCostAndActualQty,
                    "TotalActualCostAndActualQty" => $ValTotalActualCostAndActualQty,
                    "TempDivision" => $ValTempDivision,
                    "TempSortOrder" => $ValTempSortOrder,
                    "TempPM" => $ValTempPM,
                    "TempDM" => $ValTempDM,
                    "TempCOPM" => $ValTempCOPM
                );  
                array_push($ArrRes1,$ArrTempRes1);  

            $BolCheckQuote = FALSE;
            foreach($ArrListQuote as $ListQuote)
            {
                if($ListQuote['Quote'] == $ValQuote)
                {
                    $BolCheckQuote = TRUE;
                }
            }
            if($BolCheckQuote == FALSE)
            {
                array_push($ArrListQuote,array("Quote" => $ValQuote, "QuoteCategory" => $ValQuoteCategory, "TargetHalfClosed" => $ValTargetHalfClosed));
            }
        }

        # check empty expense
        foreach ($ArrListQuote as $ListQuote) # looping list quote
        {
            $ValLoopQuote = trim($ListQuote['Quote']);
            $ValLoopQuoteCategory = trim($ListQuote['QuoteCategory']);
            $ValLoopTargetHalfClosed = trim($ListQuote['TargetHalfClosed']);
            $TempValProjectIDRes1 = "";
            foreach ($ArrListDiv as $ListDiv)   # looping list expense 
            {
                $ValLoopExpense = trim($ListDiv['DivisionName']);
                $ValLoopSortNumber = trim($ListDiv['SortNumber']);
                $ValLoopSortOrder = trim($ListDiv['SortOrder']);
                $ValBolRes1 = FALSE;
                $TempValPM = "";
                $TempValDM = "";
                $TempValCOPM = "";
                foreach ($ArrRes1 as $Res1) # looping list data untuk pengecekan quote
                {
                    if(trim($Res1['Quote']) == $ValLoopQuote && trim($Res1['Expense']) == $ValLoopExpense)
                    {
                        $ArrTempRes2 = array(
                            "Expense" => trim($Res1['Expense']),
                            "SortNumber" => trim($Res1['SortNumber']),
                            "TargetHalfClosed" => trim($Res1['TargetHalfClosed']),
                            "QuoteCategory" => trim($Res1['QuoteCategory']),
                            "Quote" => trim($Res1['Quote']),
                            "QtyQuote" => trim($Res1['QtyQuote']),
                            "QtyTarget" => trim($Res1['QtyTarget']),
                            "TargetPeopleCost" => trim($Res1['TargetPeopleCost']),
                            "PeopleCost" => trim($Res1['PeopleCost']),
                            "TargetMachineCost" => trim($Res1['TargetMachineCost']),
                            "MachineCost" => trim($Res1['MachineCost']),
                            "TargetMaterialCost" => trim($Res1['TargetMaterialCost']),
                            "MaterialCost" => trim($Res1['MaterialCost']),
                            "QtyQCIn" => trim($Res1['QtyQCIn']),
                            "QtyQCOut" => trim($Res1['QtyQCOut']),
                            "DivID" => trim($Res1['DivID']),
                            "ProjectID" => trim($Res1['ProjectID']),
                            "TotalTargetCost" => trim($Res1['TotalTargetCost']),
                            "TotalActualCost" => trim($Res1['TotalActualCost']),
                            "TotalTargetCostAndTargetQty" => trim($Res1['TotalTargetCostAndTargetQty']),
                            "TotalTargetCostAndActualQty" => trim($Res1['TotalTargetCostAndActualQty']),
                            "TotalActualCostAndActualQty" => trim($Res1['TotalActualCostAndActualQty']),
                            "TempDivision" => trim($Res1['TempDivision']),
                            "TempSortOrder" => trim($Res1['TempSortOrder']),
                            "TempPM" => trim($Res1['TempPM']),
                            "TempDM" => trim($Res1['TempDM']),
                            "TempCOPM" => trim($Res1['TempCOPM'])
                        );  
                        array_push($ArrRes2,$ArrTempRes2); 
                        $ValBolRes1 = TRUE;
                        $TempValDM = trim($Res1['TempDM']);
                    } 
                    if(trim($Res1['Quote']) == $ValLoopQuote)
                    {
                        $TempValProjectIDRes1 = trim($Res1['ProjectID']);
                        $TempValPM = trim($Res1['TempPM']);
                    }
                }
                if($ValBolRes1 == FALSE)
                {
                    $ValDivIDRes1 = "";
                    foreach ($ArrListDivisionID2 as $ListDivisionID2) {
                        if($ValLoopExpense == trim($ListDivisionID2['DivisionName']))
                        {
                            $ValDivIDRes1 = trim($ListDivisionID2['DivisionID']);
                        }
                    }
                    $ArrTempRes2 = array(
                            "Expense" => $ValLoopExpense,
                            "SortNumber" => $ValLoopSortNumber,
                            "TargetHalfClosed" => $ValLoopTargetHalfClosed,
                            "QuoteCategory" => $ValLoopQuoteCategory,
                            "Quote" => $ValLoopQuote,
                            "QtyQuote" => number_format("0", 0, '.', ''),
                            "QtyTarget" => "0",
                            "TargetPeopleCost" => number_format("0", 2, '.', ''),   
                            "PeopleCost" => number_format("0", 2, '.', ''),
                            "TargetMachineCost" => number_format("0", 2, '.', ''),
                            "MachineCost" => number_format("0", 2, '.', ''),
                            "TargetMaterialCost" => number_format("0", 2, '.', ''),
                            "MaterialCost" => number_format("0", 2, '.', ''),
                            "QtyQCIn" => number_format("0", 2, '.', ''),
                            "QtyQCOut" => number_format("0", 2, '.', ''),
                            "DivID" => $ValDivIDRes1,
                            "ProjectID" => $TempValProjectIDRes1,        
                            "TotalTargetCost" => number_format("0", 2, '.', ''),
                            "TotalActualCost" => number_format("0", 2, '.', ''),
                            "TotalTargetCostAndTargetQty" => number_format("0", 2, '.', ''),
                            "TotalTargetCostAndActualQty" => number_format("0", 2, '.', ''),
                            "TotalActualCostAndActualQty" => number_format("0", 2, '.', ''),
                            "TempDivision" => $ValLoopExpense,
                            "TempSortOrder" => $ValLoopSortOrder,
                            "TempPM" => $TempValPM,
                            "TempDM" => $TempValDM,
                            "TempCOPM" => $TempValCOPM
                        );  
                        array_push($ArrRes2,$ArrTempRes2); 
                        $ValBolRes1 = TRUE;
                }
            }
        }

        # result 2
        $No = 1;
        foreach($ArrRes2 as $Res2)
        {

            $ValExpense2 = trim($Res2['Expense']);
            $ValSortNumber2 = trim($Res2['SortNumber']);
            $ValTargetHalfClosed2 = trim($Res2['TargetHalfClosed']);
            $ValQuoteCategory2 = trim($Res2['QuoteCategory']);
            $ValQuote2 = trim($Res2['Quote']);
            $ValQtyQuote2 = trim($Res2['QtyQuote']);
            $ValQtyTarget2 = trim($Res2['QtyTarget']);
            $ValTargetPeopleCost2 = trim($Res2['TargetPeopleCost']);
            $ValTargetPeopleCost2 = number_format((float)$ValTargetPeopleCost2, 2, '.', '');
            $ValPeopleCost2 = trim($Res2['PeopleCost']);
            $ValPeopleCost2 = number_format((float)$ValPeopleCost2, 2, '.', '');
            $ValTargetMachineCost2 = trim($Res2['TargetMachineCost']);
            $ValTargetMachineCost2 = number_format((float)$ValTargetMachineCost2, 2, '.', '');
            $ValMachineCost2 = trim($Res2['MachineCost']);
            $ValMachineCost2 = number_format((float)$ValMachineCost2, 2, '.', '');
            $ValTargetMaterialCost2 = trim($Res2['TargetMaterialCost']);
            $ValTargetMaterialCost2 = number_format((float)$ValTargetMaterialCost2, 2, '.', '');
            $ValMaterialCost2 = trim($Res2['MaterialCost']);
            $ValMaterialCost2 = number_format((float)$ValMaterialCost2, 2, '.', '');
            $ValQtyQCIn2 = trim($Res2['QtyQCIn']);
            $ValQtyQCOut2 = trim($Res2['QtyQCOut']);
            $ValDivID2 = trim($Res2['DivID']);
            $ValProjectID2 = trim($Res2['ProjectID']);
            $ValTotalTargetCost2 = trim($Res2['TotalTargetCost']);
            $ValTotalTargetCost2 = number_format((float)$ValTotalTargetCost2, 2, '.', '');
            $ValTotalActualCost2 = trim($Res2['TotalActualCost']);
            $ValTotalActualCost2 = number_format((float)$ValTotalActualCost2, 2, '.', '');
            $ValTotalTargetCostAndTargetQty2 = trim($Res2['TotalTargetCostAndTargetQty']);
            $ValTotalTargetCostAndTargetQty2 = number_format((float)$ValTotalTargetCostAndTargetQty2, 2, '.', '');
            $ValTotalTargetCostAndActualQty2 = trim($Res2['TotalTargetCostAndActualQty']);
            $ValTotalTargetCostAndActualQty2 = number_format((float)$ValTotalTargetCostAndActualQty2, 2, '.', '');
            $ValTotalActualCostAndActualQty2 = trim($Res2['TotalActualCostAndActualQty']);
            $ValTotalActualCostAndActualQty2 = number_format((float)$ValTotalActualCostAndActualQty2, 2, '.', '');
            $ValTempDivision2 = trim($Res2['TempDivision']);
            $ValTempSortOrder2 = trim($Res2['TempSortOrder']);
            $ValTempPM2 = trim($Res2['TempPM']);
            $ValTempDM2 = trim($Res2['TempDM']);
            $ValTempCOPM2 = trim($Res2['TempCOPM']);

            # check data
            $QCheckData = CHECK_DATA_PERIODIC($ValExpense2,$ValTargetHalfClosed2,$ValQuoteCategory2,$ValQuote2,$linkMach);
            if(sqlsrv_num_rows($QCheckData) != 0)
            {
                $RCheckData = sqlsrv_fetch_array($QCheckData);
                if(trim($RCheckData['Is_ManualInput']) == 0)  # kondisi tidak diupdate manual
                {
                    $ValIdx = trim($RCheckData['Idx']);
                    $ValStatus = trim($RCheckData['Is_ManualInput']);
                    if($ValStatus == "0")
                    {
                        if(trim($ValQuote2) != "")
                        {
                            # update data
                            $ResUpdate = UPDATE_DATA_PERIODIC_QUOTE($ValIdx,$ValExpense2,$ValSortNumber2,$ValTargetHalfClosed2,$ValQuoteCategory2,$ValQuote2,$ValQtyQuote2,$ValQtyTarget2,$ValTargetPeopleCost2,$ValPeopleCost2,$ValTargetMachineCost2,$ValMachineCost2,$ValTargetMaterialCost2,$ValMaterialCost2,$ValQtyQCIn2,$ValQtyQCOut2,$ValDivID2,$ValProjectID2,$ValTotalTargetCost2,$ValTotalActualCost2,$ValTotalTargetCostAndTargetQty2,$ValTotalTargetCostAndActualQty2,$ValTotalActualCostAndActualQty2,"0",$ValTempPM2,$ValTempDM2,$ValTempCOPM2,$linkMach);
                            if($ResUpdate == "TRUE")
                            {
                                echo "*Quote [ ".$ValQuote2." - $ValTargetHalfClosed2 ] : Berhasil diupdate!";
                            }
                            else
                            {
                                echo "*Quote [ ".$ValQuote2." - $ValTargetHalfClosed2 ] : Gagal diupdate!";
                                exit();
                            }
                        }
                    }
                }
            }
            else
            {
                if(trim($ValQuote2) != "")
                {
                    # insert data
                    $ResInsert = INSERT_NEW_DATA_PERIODIC_QUOTE($ValExpense2,$ValSortNumber2,$ValTargetHalfClosed2,$ValQuoteCategory2,$ValQuote2,$ValQtyQuote2,$ValQtyTarget2,$ValTargetPeopleCost2,$ValPeopleCost2,$ValTargetMachineCost2,$ValMachineCost2,$ValTargetMaterialCost2,$ValMaterialCost2,$ValQtyQCIn2,$ValQtyQCOut2,$ValDivID2,$ValProjectID2,$ValTotalTargetCost2,$ValTotalActualCost2,$ValTotalTargetCostAndTargetQty2,$ValTotalTargetCostAndActualQty2,$ValTotalActualCostAndActualQty2,"0",$ValTempPM2,$ValTempDM2,$ValTempCOPM2,$linkMach);
                    if($ResInsert == "TRUE")
                    {
                        echo "*Quote [ ".$ValQuote2." - $ValTargetHalfClosed2 ] : Berhasil ditambahkan!";
                    }
                    else
                    {
                        echo "*Quote [ ".$ValQuote2." - $ValTargetHalfClosed2 ] : Gagal ditambahkan!";
                        exit();
                    }
                }
            }   
        }
    }
}