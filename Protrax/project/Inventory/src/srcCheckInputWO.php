<?php
session_start();
require_once("../../../ConfigDB2.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOutPartTBZ.php");

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

# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValWOID = htmlspecialchars(trim($_POST['ValWO']), ENT_QUOTES, "UTF-8");
    if($ValLocation == "PSL")
    {
        $QDataWO = GET_CHECK_WO((int)$ValWOID,$LinkPSL);
        if(mssql_num_rows($QDataWO) != "0")
        {
            $RDataWO = mssql_fetch_assoc($QDataWO);
            echo "1#".trim($RDataWO['WOChild'])."#".trim($RDataWO['Product'])."#".trim($RDataWO['ExpenseAllocation']);
        }
        else
        {
            echo "0#-";
        }
    }
    if($ValLocation == "PSM")
    {
        $QDataWO = GET_CHECK_WO((int)$ValWOID,$LinkPSM);
        if(mssql_num_rows($QDataWO) != "0")
        {
            $RDataWO = mssql_fetch_assoc($QDataWO);
            echo "1#".trim($RDataWO['WOChild'])."#".trim($RDataWO['Product'])."#".trim($RDataWO['ExpenseAllocation']);
        }
        else
        {
            echo "0#-";
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