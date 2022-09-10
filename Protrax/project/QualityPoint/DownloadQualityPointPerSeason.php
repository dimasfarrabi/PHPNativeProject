<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../project/QualityPoint/Modules/ModuleQualityPoint.php"); 
require_once("../../project/CostTracking/Modules/ModuleCostTracking.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Valtime = htmlspecialchars(trim($_GET['time']), ENT_QUOTES, "UTF-8");
	# list quote
    $ArrQuote = array();
	$QListProject = LIST_QUOTE_QUALITY_POINT($Valtime,$linkMACHWebTrax);
	while($RListProject = mssql_fetch_assoc($QListProject))
	{
		$ValName = trim($RListProject['Quote']);
		$ValIdx = trim($RListProject['Idx']);
		
		$TempArray = array(
			"Quote" => $ValName,
			"Idx" => $ValIdx
		);
		array_push($ArrQuote,$TempArray);
	}
    # list division
    $ArrListDivision = array();
    $QListDivisionPSL = GET_LIST_DIVISION($linkMACHWebTrax);
    while($RListDivisionPSL = mssql_fetch_assoc($QListDivisionPSL))
    {
        $TempArray = array(
            "DivisionID" => trim($RListDivisionPSL['Division_ID']),
            "DivisionName" => trim($RListDivisionPSL['DivisionName'])
        );
        array_push($ArrListDivision,$TempArray);    
    }  
    $ArrHeader = array();
    $ArrContent = array();
    $ArrResult = array();
    array_push($ArrHeader,array("Header"=>"KPI per Division by QA (max 100)"));
	    foreach($ArrQuote as $ListQuote)
    {
        $ValQuote = trim($ListQuote['Quote']);
        $ValProjectID = trim($ListQuote['Idx']);
        array_push($ArrHeader,array("Header"=>trim($ValQuote)));
    }
    foreach ($ArrListDivision as $ListDivision)
    {
        $ValDivisionID = trim($ListDivision['DivisionID']);
        $ValDivision = trim($ListDivision['DivisionName']);
        $TempContentArray = array();
        array_push($TempContentArray,trim($ValDivision));
        foreach($ArrQuote as $ListQuote)
        {
            $ValQuote = trim($ListQuote['Quote']);
            $ValProjectID = trim($ListQuote['Idx']);            
            if($ValDivision == "QUALITY ASSURANCE")
            {
                $QDataPercentage = DOWNLOAD_ALL_QUALITY_POINT_PER_HALF($ValProjectID,$ValDivisionID,$Valtime,$linkMACHWebTrax);
                if(mssql_num_rows($QDataPercentage) != 0)
                {
                    $RDataPercentage = mssql_fetch_assoc($QDataPercentage);
                    $ValPercentage = trim($RDataPercentage['GoalAchievement']);
                    array_push($TempContentArray,trim($ValPercentage));
                }
                else
                {
                    $QValGoal = GET_TOTAL_COUNT_AVG_QA($Valtime,$ValProjectID,"PSL",$linkMACHWebTrax);
                    if(mssql_num_rows($QValGoal) != 0)
                    {   
                        $RValGoal = mssql_fetch_assoc($QValGoal);
                        $ValGoal = number_format((float)trim($RValGoal['Result2']), 2, '.', ',');
                        array_push($TempContentArray,trim($ValGoal));
                    }
                    else
                    {
                        array_push($TempContentArray,"");
                    }
                }
            }
            else
            {
                $QDataPercentage = DOWNLOAD_ALL_QUALITY_POINT_PER_HALF($ValProjectID,$ValDivisionID,$Valtime,$linkMACHWebTrax);
                if(mssql_num_rows($QDataPercentage) != 0)
                {
                    $RDataPercentage = mssql_fetch_assoc($QDataPercentage);
                    $ValPercentage = trim($RDataPercentage['GoalAchievement']);
                    array_push($TempContentArray,trim($ValPercentage));
                }
                else
                {
                    array_push($TempContentArray,"");
                }
            }
        }
        array_push($ArrContent,$TempContentArray);
    }
    $CountColHeader = 0;
    $TempArrayHeader  = array();
    foreach($ArrHeader as $Header)
    {
        array_push($TempArrayHeader,trim($Header['Header']));
        $CountColHeader++;
    }
    array_push($ArrResult,$TempArrayHeader);
    foreach($ArrContent as $Header)
    {
        $TempArray  = array();
        for($i=0;$i<$CountColHeader;$i++)
        {
            array_push($TempArray,trim($Header[$i]));
        }
        array_push($ArrResult,$TempArray);
    }
    # data
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('YmdHis');
    $filename = "DataQualityPoint[".$Valtime."].csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');    
    $NoLoop = 1;    
    foreach($ArrResult as $DtResult)
    {
        $ResArr = array();
        for($i=0;$i<$CountColHeader;$i++)
        {
            array_push($ResArr,trim($DtResult[$i]));
        }
        fputcsv($file,$ResArr);
    }
    fclose($file);
    exit();
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