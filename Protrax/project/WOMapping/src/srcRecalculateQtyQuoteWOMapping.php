<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");

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

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValQuote = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValExpenseAllocation = htmlspecialchars(trim($_POST['ValExpenseAllocation']), ENT_QUOTES, "UTF-8");
    $ValTotalQty = htmlspecialchars(trim($_POST['ValTotalQty']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValNo = htmlspecialchars(trim($_POST['ValNo']), ENT_QUOTES, "UTF-8");
    $ValTotalRow = htmlspecialchars(trim($_POST['ValTotalRow']), ENT_QUOTES, "UTF-8");
    
    if($ValNo == $ValTotalRow)
    {
        $BolError = FALSE;
        if($ValLocation == "PSM")
        {
            $ResUpdate = UPDATE_QTY_QUOTE_BY_MULTI_PARAM_PSM($ValQuote,$ValExpenseAllocation,$ValClosedTime,(int)$ValTotalQty);
            if($ResUpdate == "FALSE")
            {
                $BolError = TRUE;
            }
            # set log 
            INSERT_NEW_RECALCULATE_LOG_PSM();
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }
        if($ValLocation == "PSL")
        {
            $ResUpdate = UPDATE_QTY_QUOTE_BY_MULTI_PARAM($ValQuote,$ValExpenseAllocation,$ValClosedTime,(int)$ValTotalQty,$linkMACHWebTrax);
            if($ResUpdate == "FALSE")
            {
                $BolError = TRUE;
            }
            # set log
            INSERT_NEW_RECALCULATE_LOG($linkMACHWebTrax);
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }        
    }
    else
    {
        $BolError = FALSE;
        if($ValLocation == "PSM")
        {
            $ResUpdate = UPDATE_QTY_QUOTE_BY_MULTI_PARAM_PSM($ValQuote,$ValExpenseAllocation,$ValClosedTime,(int)$ValTotalQty);
            if($ResUpdate == "FALSE")
            {
                $BolError = TRUE;
            }
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }
        if($ValLocation == "PSL")
        {
            $ResUpdate = UPDATE_QTY_QUOTE_BY_MULTI_PARAM($ValQuote,$ValExpenseAllocation,$ValClosedTime,(int)$ValTotalQty,$linkMACHWebTrax);
            if($ResUpdate == "FALSE")
            {
                $BolError = TRUE;
            }
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }
    }
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