<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOut.php");
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
$FullName = "LOCAL - DIMAS FARRABI";
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $Company = htmlspecialchars(trim($_POST['Company']), ENT_QUOTES, "UTF-8");
    $ProsesOUT = htmlspecialchars(trim($_POST['ProsesOUT']), ENT_QUOTES, "UTF-8");
    $ProsesIN = htmlspecialchars(trim($_POST['ProsesIN']), ENT_QUOTES, "UTF-8");
    $arr = htmlspecialchars(trim($_POST['arr']), ENT_QUOTES, "UTF-8");
    // echo "$Company >> $ProsesOUT >> $ProsesIN >> $arr";
    $Main = explode(",",$arr);
    foreach($Main as $ValBC){
        $Update = UPDATE_TO_PROCEED($ValBC,"OUT",$ProsesOUT,$Company,$linkMACHWebTrax);
        $Update2 = UPDATE_TO_PROCEED($ValBC,"IN",$ProsesIN,$Company,$linkMACHWebTrax);
    }
    if($Update == 'TRUE' || $Update2 == 'TRUE')
    {
        echo "TRUE";
    }
    else
    {
        echo "FALSE";
    }
}
?>