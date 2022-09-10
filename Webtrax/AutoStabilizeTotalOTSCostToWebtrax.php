<?php
date_default_timezone_set("Asia/Jakarta");
$time_start = microtime(true);
// function connecttodb($servername,$dbname,$dbuser,$dbpassword)
// {
//     $link=sqlsrv_connect($servername,$dbuser,$dbpassword,TRUE) or die ("SQL ERROR :".sqlsrv_get_last_message());
//     if(!$link){die("Could not connect to sqlsrv");}
// 	else
// 	{
// 		sqlsrv_select_db($dbname,$link) or die ("SQL ERROR :".sqlsrv_get_last_message());
// 		return $link;
// 	}
// }


// $linkPSL  = connecttodb("10.250.0.229\SQLEXPRESS","[FMLX-MACH]","client","forindo13");
// $linkTarget  = connecttodb("10.250.0.229\SQLEXPRESS","[FMLX-WEBTRAX]","client","forindo13");


$uid = "client";
$pwd = "forindo13";

$serverName = "10.250.0.229\SQLEXPRESS";

$connectionInfo = array(
"UID"=>$uid,
"PWD"=>$pwd,
"Database"=>"FMLX-MACH");

$connectionInfoWT = array(
"UID"=>$uid,
"PWD"=>$pwd,
"Database"=>"FMLX-WEBTRAX");

// $connectionInfoHris = array( "UID"=>$uid,
// "PWD"=>$pwd,
// "Database"=>"FMLX-HRIS");

/* Connect using SQL Server Authentication. */
$linkPSL = sqlsrv_connect( $serverName, $connectionInfo);
$linkTarget = sqlsrv_connect( $serverName, $connectionInfoWT);
// $linkHRISWebTrax = sqlsrv_connect( $serverName, $connectionInfoHris);

function GET_LAST_CLOSED_TIME($link)
{
    $sql = "SELECT DISTINCT TOP 1 ClosedTime FROM [FMLX-MACH].dbo.T_WO_MAPPING_WITH_EXPENSE WHERE ClosedTime != 'OPEN' GROUP BY ClosedTime ORDER BY ClosedTime DESC";
	$query = sqlsrv_query($link, $sql);
	return ($query);
}
function GET_LIST_EXPENSE_ALLOCATION($link)
{
    $sql = "SELECT ExpenseOption FROM [FMLX-MACH].dbo.T_EXPENSE_ALLOCATION WHERE Is_Delete = '0' 
	ORDER BY CAST(SortNumber AS INT) ASC, ExpenseOption ASC";
	$query = sqlsrv_query($link, $sql);
	return ($query);
}
function LIST_QUOTE($Half,$link)
{
	$sql = "
	SELECT 
		B.Quote,B.QuoteCategory
	FROM 
		[FMLX-MACH].dbo.T_PROJECT AS A
		INNER JOIN [FMLX-WEBTRAX].dbo.T_WO_MAPPING_WITH_EXPENSE AS B ON A.ProjectName = B.Quote
	WHERE
		B.ClosedTime = '$Half'
	GROUP BY
		B.Quote,B.QuoteCategory
	ORDER BY
		B.QuoteCategory ASC,B.Quote ASC";
		$query = sqlsrv_query($link, $sql);
		return ($query);
}
function GET_RESULT($Category,$Half,$Quote,$Expense,$link)
{
	$sql = "
	SELECT 
		C.PartNo,
		C.PartDescription, 
		AVG(C.UnitCost) AS [UnitCost],
		SUM(C.QtyUsage) AS [QtyUsage],
		SUM(C.TotalCost) AS [TotalCost]
	FROM (
		SELECT
			*
		FROM (
			SELECT
			t0.PartNo,
			t1.PartDescription,
			t0.AlmytaCost as [UnitCost],
			t0.QtyUsage,
			t0.TotalCost
			FROM [FMLX-MACH].dbo.T_MATERIAL_TRACKING as t0
			LEFT OUTER JOIN [FMLX-MACH].dbo.T_ITEM_MASTER as t1 On t1.PartNo = t0.PartNo
			INNER JOIN [FMLX-MACH].dbo.T_ITEM_MASTER_CATEGORY_MAPPING AS t2 ON t2.PartNo = t0.PartNo
			WHERE t0.WOMapping_ID IN (
				SELECT  
					A.Idx
				FROM (
					SELECT
						distinct(t1.ExpenseAllocation), 
						CAST(t2.SortNumber AS INT) AS 'SortNumber',
						t1.TargetHalfClosed, 
						t1.QuoteCategory,
						t1.Quote,
						t1.QtyQuote,
						t1.PSL_Idx as [Idx],
						SUM((t1.RunningCostOTS)) as 'OTSCost'
					FROM
						[FMLX-WEBTRAX].dbo.T_WO_MAPPING_WITH_EXPENSE as t1 
						INNER JOIN [FMLX-MACH].dbo.T_EXPENSE_ALLOCATION AS t2 ON t1.ExpenseAllocation = t2.ExpenseOption
					WHERE 
						t1.QuoteCategory = '$Category' 
						AND t1.ClosedTime = '$Half' 
						AND t1.Quote = '$Quote'
						AND t1.ExpenseAllocation = '$Expense'
					GROUP BY t1.ExpenseAllocation,CAST(t2.SortNumber AS INT),t1.TargetHalfClosed, t1.QuoteCategory,t1.Quote,t1.QtyQuote,(t1.RunningCostOTS),t1.PSL_Idx
				) AS A
				GROUP BY A.ExpenseAllocation,A.SortNumber,A.TargetHalfClosed,A.QuoteCategory,A.Quote,A.QtyQuote,A.Idx
			) AND t0.AdjustmentStatus != 'IN'
			AND t2.Category IN ('ASSETS','OTS','R&D','Customer Kit')
		) AS B
	) AS C
	GROUP BY C.PartNo,C.PartDescription
	ORDER BY SUM(C.TotalCost) DESC";
	$query = sqlsrv_query($link, $sql);
	return ($query);
}
function CHECK_OTS($Quote,$Half,$Expense,$PartNo,$link)
{
	$sql = "SELECT TOP 1 * FROM [FMLX-WEBTRAX].dbo.T_DETAIL_OTS_COST WHERE Quote = '$Quote' AND ClosedTime = '$Half' AND ExpenseAllocation = '$Expense' AND PartNo = '$PartNo' ORDER BY Idx DESC";
	$query = sqlsrv_query($link, $sql);
	return ($query);
}
function INSERT_DATA($Quote,$Category,$Half,$Expense,$PartNo,$PartDesc,$UnitCost,$QtyUsage,$TotalCost,$link)
{
	$sql = "INSERT INTO [FMLX-WEBTRAX].dbo.T_DETAIL_OTS_COST(Quote,QuoteCategory,ClosedTime,ExpenseAllocation,PartNo,PartDescription,UnitCost,QtyUsage,TotalCost) VALUES('$Quote','$Category','$Half','$Expense','$PartNo','$PartDesc',$UnitCost,$QtyUsage,$TotalCost)";
	$query = sqlsrv_query($link, $sql);
	//return ($query);
	if($query)
	{
		return "INPUT OTS COST $PartNo [$Quote :: $Half :: $Expense] Berhasil!";	
	}
	else
	{
		return "INPUT OTS COST $PartNo [$Quote :: $Half :: $Expense] Gagal!";	
	}
}
function UPDATE_DATA($Idx,$Quote,$Category,$Half,$Expense,$PartNo,$PartDesc,$UnitCost,$QtyUsage,$TotalCost,$link)
{
	$sql = "UPDATE [FMLX-WEBTRAX].dbo.T_DETAIL_OTS_COST SET 
	Quote = '$Quote',QuoteCategory = '$Category',ClosedTime = '$Half',ExpenseAllocation = '$Expense',PartNo = '$PartNo',PartDescription = '$PartDesc',UnitCost = '$UnitCost',QtyUsage = '$QtyUsage',TotalCost = '$TotalCost' WHERE Idx = '$Idx'";
		
		$query = sqlsrv_query($link, $sql);
		//return ($query);
	if($query)
	{
		return "EDIT OTS COST $PartNo [$Quote :: $Half :: $Expense] Berhasil!";	
	}
	else
	{
		return "EDIT OTS COST [$Quote :: $Half :: $Expense :: $PartNo] Gagal!";	
	}
}




# Closed Time 
$ArrListClosedTime = array();
$ValClosedTime = "";
$QClosedTime = GET_LAST_CLOSED_TIME($linkPSL);
$ValClosedTime = '2021-H1';
while($RClosedTime = sqlsrv_fetch_array($QClosedTime))
{
    $ArrTemp = array(
        // "ClosedTime" => trim($RClosedTime['ClosedTime'])
        "ClosedTime" => trim($ValClosedTime)
    );
    array_push($ArrListClosedTime,$ArrTemp);
}
// array_push($ArrListClosedTime,array("ClosedTime"=>"OPEN"));
// print_r($ArrListClosedTime);
# List Expense
$ArrListExpense = array();
$QListExpense = GET_LIST_EXPENSE_ALLOCATION($linkPSL);
while($RListExpense = sqlsrv_fetch_array($QListExpense))
{
	array_push($ArrListExpense,array("ExpenseOption"=>trim($RListExpense['ExpenseOption'])));
}

// echo "<table border='1'>";
// echo "<tr>";
// echo "<td>Half</td>";
// echo "<td>Quote</td>";
// echo "<td>QuoteCategory</td>";
// echo "<td>Expense</td>";
// echo "<td>PartNo</td>";
// echo "<td>PartDescription</td>";
// echo "<td>UnitCost</td>";
// echo "<td>QtyUsage</td>";
// echo "<td>TotalCost</td>";
// echo "</tr>";
# list result
foreach ($ArrListClosedTime as $ListClosedTime)
{
	$ValClosedTime = trim($ListClosedTime['ClosedTime']);	
	$QListExpense = GET_LIST_EXPENSE_ALLOCATION($linkPSL);
	foreach($ArrListExpense as $ListExpense)
	{
		$ValExpense = trim($ListExpense['ExpenseOption']);
		$QListQuote = LIST_QUOTE($ValClosedTime,$linkPSL);
		while($RListQuote = sqlsrv_fetch_array($QListQuote))
		{
			$ValQuote = trim($RListQuote['Quote']);
			$ValQuoteCategory = trim($RListQuote['QuoteCategory']);
			$QResult = GET_RESULT($ValQuoteCategory,$ValClosedTime,$ValQuote,$ValExpense,$linkPSL);
			while ($RResult = sqlsrv_fetch_array($QResult))
			{
				// echo "<tr>";
				// echo "<td>$ValClosedTime</td>";
				// echo "<td>$ValQuote</td>";
				// echo "<td>$ValQuoteCategory</td>";
				// echo "<td>$ValExpense</td>";
				// echo "<td>".trim($RResult['PartNo'])."</td>";
				// echo "<td>".trim($RResult['PartDescription'])."</td>";
				// echo "<td>".trim($RResult['UnitCost'])."</td>";
				// echo "<td>".trim($RResult['QtyUsage'])."</td>";
				// echo "<td>".trim($RResult['TotalCost'])."</td>";
				// echo "</tr>";
				
				$ValPartNo = trim($RResult['PartNo']);
				// $ValPartDesc = htmlspecialchars(trim($RResult['PartDescription']), ENT_QUOTES, "UTF-8");
				$ValPartDesc = trim($RResult['PartDescription']);
				$ValUnitCost = sprintf('%0.2f', trim($RResult['UnitCost']));
				$ValQtyUsage = sprintf('%0.2f', trim($RResult['QtyUsage']));
				$ValTotalCost = sprintf('%0.2f', trim($RResult['TotalCost']));

				# check data
				$QCheck = CHECK_OTS($ValQuote,$ValClosedTime,$ValExpense,$ValPartNo,$linkTarget);
				if(sqlsrv_num_rows($QCheck) == 0)
				{
					$ResAdd = INSERT_DATA($ValQuote,$ValQuoteCategory,$ValClosedTime,$ValExpense,$ValPartNo,$ValPartDesc,$ValUnitCost,$ValQtyUsage,$ValTotalCost,$linkTarget);
					// echo "<br> ".$ResAdd;
					echo "\n ".$ResAdd;
				}
				else
				{
					$RCheck = sqlsrv_fetch_array($QCheck);
					$ValIdx = trim($RCheck['Idx']);
					$ResUpdate = UPDATE_DATA($ValIdx,$ValQuote,$ValQuoteCategory,$ValClosedTime,$ValExpense,$ValPartNo,$ValPartDesc,$ValUnitCost,$ValQtyUsage,$ValTotalCost,$linkTarget);
					// echo "<br> ".$ResUpdate;
					echo "\n ".$ResUpdate;
				}
			}
		}
	}
}
// echo "</table>";


$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

// echo "<br><br> Proses stabilize total ots webtrax sudah selesai!! (".number_format((float)$execution_time, 2, '.', '')." seconds)";
// echo "<br> ==============================================";
echo "\n\n Proses stabilize total ots webtrax sudah selesai!! (".number_format((float)$execution_time, 2, '.', '')." seconds)";
echo "\n ==============================================";


?>