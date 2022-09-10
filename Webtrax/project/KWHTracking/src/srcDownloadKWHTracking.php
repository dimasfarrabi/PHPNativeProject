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
    $Date1 = $ArrValString2['3'];
    $Date2 = $ArrValString2['5'];
    $DateSelected = $Date1."#".$Date2;
    $filename = "DataKWHTracking_".$DateSelected.".csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('Sr.No','Slave ID','DateTime','Kwh'));
    $QListDataKWHTracking = GET_DATA_KWH_TRACKING_BY_DATE($Date1,$Date2,$linkHRISWebTrax);
    $No = 1;
    while($RListDataKWHTracking = mssql_fetch_assoc($QListDataKWHTracking))
    {
        $NoSlave = $RListDataKWHTracking['Slave'];
        $DateSlave = date('m/d/Y H:i:s',strtotime($RListDataKWHTracking['Log']));
        $KWH = $RListDataKWHTracking['KWH'];

        $ArrayTemp = array($No,$NoSlave,$DateSlave,$KWH);
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