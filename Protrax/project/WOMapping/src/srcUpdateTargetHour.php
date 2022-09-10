<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");
/*
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValWOMapping_ID = htmlspecialchars(trim($_POST['ValWOMapping_ID']), ENT_QUOTES, "UTF-8");
    $NewTargetHour = htmlspecialchars(trim($_POST['NewTargetHour']), ENT_QUOTES, "UTF-8");
    $Update = UPDATE_WO_TARGET_HOUR($NewTargetHour,$ValWOMapping_ID,$linkMACHWebTrax);
    echo $Update;
}
?>