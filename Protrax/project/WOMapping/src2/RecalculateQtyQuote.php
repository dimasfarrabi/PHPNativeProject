<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
/*
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
    $ValHalf = htmlspecialchars(trim($_POST['ValHalf']), ENT_QUOTES, "UTF-8");
    $UsedOpen = htmlspecialchars(trim($_POST['UsedOpen']), ENT_QUOTES, "UTF-8");

    $data = GET_QUOTE_LIST_BY_HALF($ValHalf,$UsedOpen,$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($data))
    {
        $ValQuote = trim($res['Quote']);
        $ValClosedTime = trim($res['ClosedTime']);
        $ValExpense = trim($res['ExpenseAllocation']);
        $ValQtyParent = trim($res['TotalQtyParent']);
        $ResUpdate = UPDATE_QTY_QUOTE_BY_MULTI_PARAM($ValQuote,$ValExpense,$ValClosedTime,(int)$ValQtyParent,$linkMACHWebTrax);
        if($ResUpdate == "FALSE")
        {
            echo "$ValQuote _ $ValClosedTime _ $ValExpense # N*";
        }
        else
        {
            echo "$ValQuote _ $ValClosedTime _ $ValExpense # Y*";
        }
    }
}
?>