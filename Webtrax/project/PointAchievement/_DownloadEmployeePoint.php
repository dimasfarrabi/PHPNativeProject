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
    $filename = "ListEmployee".$ValClosedTime.".csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $ValArrayPrint = array();
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No','Name','Position','Location','TimeSpent','Points'));
    $No = 1;
    $QData = GET_LIST_EMPLOYEE_POINTS($ValClosedTime,$linkMACHWebTrax);
    while($RData = sqlsrv_fetch_array($QData))
    {
        $ValEmployee = trim($RData['FullName']);
        $ValDetailPosition = trim($RData['DetailPosition']);
        $Location = trim($RData['Location']);
        $TotalHour = trim($RData['TimeSpent']);
        $ValPoin = trim($RData['Points']);
        
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