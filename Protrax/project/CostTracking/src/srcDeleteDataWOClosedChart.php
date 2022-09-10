<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleCostTrackingChart.php");
require_once("../Modules/ModuleTarget.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValInputQuoteID = htmlspecialchars(trim($_POST['ValDataToken']), ENT_QUOTES, "UTF-8");
    $ValInputQuoteID = base64_decode(base64_decode($ValInputQuoteID));
    $ArrInputQuoteID = explode("#",$ValInputQuoteID);
    $ValQuoteID =  trim($ArrInputQuoteID[0]);   
    $Delete = DELETE_DATA_WO_CLOSED_CHART_BY_ID($ValQuoteID,$linkMACHWebTrax);
    echo $Delete;
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
