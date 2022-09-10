<?php 
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");
date_default_timezone_set("Asia/Jakarta");
 
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $ValString = htmlspecialchars(trim($_GET['str']), ENT_QUOTES, "UTF-8");
    $ArrValString2 = explode(" ",$ValString);
    $Date1 = $ArrValString2['2'];
    $Date2 = $ArrValString2['4'];
    $DateSelected = $Date1."#".$Date2;
    $ValDate1 = str_replace("(","",$Date1);
    $ValDate2 = str_replace(")","",$Date2);
    $filename = "DataKWHTrackingPSM_".$DateSelected.".csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No','DateTime','KWH'));
    $QListDataKWHTracking = GET_DATA_USAGE_BY_DATE($ValDate1,$ValDate2,"PSM",$linkHRISWebTrax);
    $No = 1;
    while($RListDataKWHTracking = mssql_fetch_assoc($QListDataKWHTracking))
    {
        $DateSlave = date('m/d/Y',strtotime($RListDataKWHTracking['Log']));
        $KWH = $RListDataKWHTracking['KWH'];
        $ArrayTemp = array($No,$DateSlave,$KWH);
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