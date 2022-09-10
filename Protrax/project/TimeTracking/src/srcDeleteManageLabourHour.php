<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleLabourHour.php");

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
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $ValDataID = $DataIDEnc;
    $DataIDEnc = base64_decode(base64_decode($DataIDEnc));
    $ArrDataID = explode("#",$DataIDEnc);
    $ID = trim($ArrDataID[1]);    
    # delete data
    DELETE_NEW_LABOUR_HOUR_BY_ID($ID,$linkMACHWebTrax);
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
