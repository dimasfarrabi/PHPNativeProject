<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
// date_default_timezone_set("Asia/Jakarta");


if($_SERVER['REQUEST_METHOD'] == "GET")
{   
    $ValClosedTime = htmlspecialchars(trim($_GET['ClosedTime']), ENT_QUOTES, "UTF-8");
    // $ValTemplateName = base64_decode($ValTemplateName);
    // $ValTemplateName = str_replace("TN","",$ValTemplateName);
    # download data
    date_default_timezone_set("Asia/Jakarta");
    $filename = "ListEmployeeSMG.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $ValArrayPrint = array();
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No','Name','Position','Location','TimeSpent','Points'));
    $QData = GET_LIST_EMPLOYEE_PRODUCTION_PSM();
    $No = 1;
    // $ValClosedTime = "2021-h2";
    while($RData = sqlsrv_fetch_array($QData))
    {
        $ValEmployee = trim($RData['FullName']);
        $ValDetailPosition = trim($RData['DetailPosition']);
        $Location = "SEMARANG";
        // $ValEmployee = trim($RData['FullName']);
        // $ValDetailPosition = trim($RData['DetailPosition']);
        $Total = GET_TOTAL_STABILIZE($ValClosedTime,$ValEmployee,$Location,$linkMACHWebTrax);
            while($RTotal = sqlsrv_fetch_array($Total))
            {
                $TotalTime = trim($RTotal['TOTAL']);
                
            }
        $TotalHour = $TotalTime;  
        $ValProjectPoints=$ValTotalIndv=$ValTotalHour=$ValPercentTime=$ValPoin=$ValTotalPercentTime=0;
        $QListReport = CHECK_POINTS($ValClosedTime,$ValEmployee,$Location,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
                
                $ValStabilize = trim($RListReport['Stabilize']);
                $ValQCategory = trim($RListReport['QuoteCategory']);
                $Valtcaq = trim($RListReport['tcaq']);
                $Valacaq = trim($RListReport['acaq']);
                $ValDoT = trim($RListReport['DoT']);
                $ValQP = trim($RListReport['QP']);
                $ValTargetHour = trim($RListReport['TargetCost']);
                $ValActualHour = trim($RListReport['RunningTime']);

                $ValUnquoteProjectPoint = @($ValActualHour/$ValTargetHour)*100;
                if($ValUnquoteProjectPoint > 110){$ValUnquoteProjectPoint = 0;}
                // $ValIndvUnquotePoint = ($ValUnquoteProjectPoint*$ValResSumStabilize)/100;
                elseif($ValQCategory == 'Quote'){$ValUnquoteProjectPoint=0;}
                else{$ValUnquoteProjectPoint = 10*(110-@($ValActualHour/$ValTargetHour*100)); if ($ValUnquoteProjectPoint>100){$ValUnquoteProjectPoint=100;}}
                
                $ValCost = @(($Valtcaq+($Valtcaq*0.1)-$Valacaq)/$Valtcaq*10)*100;
                if($ValCost>100){$ValCost=100;}
                elseif($ValQCategory == 'Unquote'){$ValCost=0;}
                elseif($ValCost<0){$ValCost=0;}
                $ValProjectPoints = @($ValCost*$ValDoT*$ValQP)/10000;
                

                $ValAllProjectPoint = @($ValUnquoteProjectPoint+$ValProjectPoints);
                $ValIndv = @($ValAllProjectPoint*$ValStabilize)/100;
                $ValTotalIndv = $ValTotalIndv+$ValIndv;
                $ValTotalPercentTime = $ValTotalPercentTime+$ValPercentTime;
                $ValPoin = @($ValTotalIndv/$TotalHour)*100;
                $ValPoin = number_format((float)$ValPoin, 2, '.', ',');       
        }
        
        $TotalHour = number_format((float)$TotalHour, 2, '.', ',');

        $ArrayTemp = array($No,$ValEmployee,$ValDetailPosition,$Location,$TotalHour,$ValPoin);
        fputcsv($file,$ArrayTemp);
        
        $No++; 
    }
    
    fclose($file);
    exit();


}
else
{
    echo "";    
}
?>