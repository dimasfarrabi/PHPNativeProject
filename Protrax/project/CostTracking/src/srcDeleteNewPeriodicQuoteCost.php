<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModulePeriodicQuoteCost.php");

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
    $DataID = base64_decode(base64_decode($DataIDEnc));
    $ArrDataID = explode("#",$DataID);
    $ValHalf = trim($ArrDataID [0]);
    $ValCategory = trim($ArrDataID [1]);
    $ValQuote = trim($ArrDataID [2]);
    $ValIdx = trim($ArrDataID [3]);
    # delete data
    $ResDelete = DELETE_PERIODIC_QUOTE_COST($ValIdx,$linkMACHWebTrax);
    echo $ResDelete;
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
