<?php
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModulePointAchievement.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Half = htmlspecialchars(trim($_GET['CL']), ENT_QUOTES, "UTF-8");
    // echo "$Category >> $Keywords >> $Half";
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "EmployeePoint_$Half.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('Idx','Employee Name',
    'Division',
    'Points',
    'Discretion',
    'Exception',
    'Total Points'));

    $data = GET_TOTAL_EMP_POINT($Half,"LEFT",$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($data))
    {
        $Idx = trim($res['Idx']);
        $ValName = trim($res['FullName']);
        $ValDiv = trim($res['Division']);
        $Points = trim($res['Points']);
        $Discretion = trim($res['Discretion']);
        $Exception = trim($res['Exception']);
        $TotalPoints = trim($res['TotalPoints2']);
        if(trim($Discretion) == ""){$Discretion = "";} else {$Discretion = number_format((float)$Discretion, 2, '.', ',');} 
        if(trim($Exception) == ""){$Exception = "";} else {$Exception = number_format((float)$Exception, 2, '.', ',');} 
        // $TotalPoints = ($Points - (0.2*($Points)));
        $TotalPoints = number_format((float)$TotalPoints,2,'.',',');
        $Points = number_format((float)$Points,2,'.',',');
        $ArrayTemp = array($Idx,$ValName,
        $ValDiv,
        $Points,
        $Discretion,
        $Exception,
        $TotalPoints);
        fputcsv($file,$ArrayTemp);
    }
    fclose($file);
    exit();
}
?>