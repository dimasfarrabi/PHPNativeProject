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
$FullName = "local-Dimas Farrabi";
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $WOPexist = $QtyExist = $Product = "";
    $valWOP = htmlspecialchars(trim($_POST['val']), ENT_QUOTES, "UTF-8");
    $ValQuoteX = base64_decode(htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8"));
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $arr = explode("*",$ValQuoteX);
    $ValQuote = $arr[0];
    $cekData = CHECK_NEW_WOP($ValQuote,$Category,$valWOP,$linkMACHWebTrax);
    if(sqlsrv_num_rows($cekData) > 0)
    {
        while($res=sqlsrv_fetch_array($cekData))
        {
            $WOPexist = trim($res['WOParent']);
            $QtyExist = trim($res['Qty']);
            $Product = trim($res['Product']);
        }
        echo "$WOPexist:$QtyExist:$Product:TRUE";
    }
    else
    {
        echo "$WOPexist:$QtyExist:$Product:FALSE";
    }
}
?>