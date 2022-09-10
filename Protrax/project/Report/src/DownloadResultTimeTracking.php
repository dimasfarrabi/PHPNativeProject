<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleReport.php");

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
}*/
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $ValDateStart = htmlspecialchars(trim($_GET['ds']), ENT_QUOTES, "UTF-8");
    $ValDateEnd = htmlspecialchars(trim($_GET['de']), ENT_QUOTES, "UTF-8");
    $ValSeason = htmlspecialchars(trim($_GET['sea']), ENT_QUOTES, "UTF-8");
    $ValCategory = htmlspecialchars(trim($_GET['cat']), ENT_QUOTES, "UTF-8");
    $ValKeywordsEnc = htmlspecialchars(trim($_GET['key']), ENT_QUOTES, "UTF-8");
    $ValKeywords = base64_decode($ValKeywordsEnc); 
    # data
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "DataTimeTrack[".$ValDateStart."-".$ValDateEnd."]_$TimeNow.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No','Date','Employee','WOMapping','WOChild','Quote','ClosedTime','QtyQuote','Division','ExpenseAllocation','RealTime','EstimateTime','PM','Activity','ShiftCode','DateSC+Name','WOParent','EstCostHour','EstFinishDate','Idx','QuoteCategory','Product','Location'));
    
    $NoLoop = 1;
    $QData = GET_DATA_TIMETRACK_CUSTOM($ValDateStart,$ValDateEnd,$ValSeason,$ValCategory,$ValKeywords,$linkMACHWebTrax);
    while($RData = sqlsrv_fetch_array($QData)){
        $ArrayTemp = array($NoLoop,trim($RData['Date']),trim($RData['EmployeeFullName']),trim($RData['WOMapping_ID']),trim($RData['WOChild']),trim($RData['Quote']),trim($RData['ClosedTime']),trim($RData['QtyQuote']),trim($RData['DivisionName']),trim($RData['ExpenseAllocation']),trim($RData['RealTime']),trim($RData['EstimateTime']),trim($RData['PM']),trim($RData['Activity']),trim($RData['ShiftCode']),trim($RData['DateSC+Name']),trim($RData['WOParent']),trim($RData['EstCostHour']),trim($RData['EstFinishDate']),trim($RData['Idx']),trim($RData['QuoteCategory']),trim($RData['Product']),trim($RData['Location']));
        fputcsv($file,$ArrayTemp);
        $NoLoop++;
    }
    
    fclose($file);
    exit();
}
else
{
    ?>
    <!-- <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script> -->
    <?php
    exit();  
}
?>
