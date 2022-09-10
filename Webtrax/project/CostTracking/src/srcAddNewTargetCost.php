<?php
require_once("../../../src/Modules/ModuleLogin.php");
require("../../../../src/srcProcessFunction.php");
require_once("../Modules/ModuleTarget.php");
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValSeason = htmlspecialchars(trim($_POST['ValSeason']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValQuote = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $ValExpense = htmlspecialchars(trim($_POST['ValExpense']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $ValTargetCost = htmlspecialchars(trim($_POST['ValTargetCost']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValTargetCost = sprintf('%.2f',floatval($ValTargetCost));
    $ValCode = $ValSeason."#".$ValType."#".$ValQuote."#".$ValExpense;
    # check data
    $QCheckData = CHECK_TARGET_COST_BEFORE($ValQuote,$ValSeason,$ValExpense,$ValType,$ValLocation,$linkMACHWebTrax);
    $Row = mssql_num_rows($QCheckData);
    if($Row == 0)
    {
        INSERT_NEW_TARGET_COST($ValQuote,$ValSeason,$ValExpense,$ValType,$ValTargetCost,$ValCode,$ValLocation,$linkMACHWebTrax);
        echo "Target cost saved!";
    }
    else
    {
        echo "Target cost has been saved before!";
    }
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
