<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleShiftCodeMachine.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
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
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "TemplateShiftcodeMachine.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('Location','Machine','Date','UtilizeHour'));   
    
    # list machine PSM
    $ArrListMachine = array();
    $ArrListMachineHours = array();
    $QListMachinePSM = LOAD_LIST_MACHINE_PSM();
    while($RListMachinePSM = mssql_fetch_assoc($QListMachinePSM))
    {
        $TempArray = array(
            "Machine" => trim($RListMachinePSM['Machine']),
            "Location" => "SEMARANG"
        );
        array_push($ArrListMachine,$TempArray);
    }
    $QListMachinePSL = LOAD_LIST_MACHINE($linkMACHWebTrax);
    while($RListMachinePSL = mssql_fetch_assoc($QListMachinePSL))
    {
        $TempArray = array(
            "Machine" => trim($RListMachinePSL['Machine']),
            "Location" => "SALATIGA"
        );
        array_push($ArrListMachine,$TempArray);
    }
    asort($ArrListMachine);
    foreach($ArrListMachine as $ListMachine)
    {
        $ArrTemp = array(trim($ListMachine['Location']),trim($ListMachine['Machine']),$DateNow,"8");
        fputcsv($file,$ArrTemp);
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
