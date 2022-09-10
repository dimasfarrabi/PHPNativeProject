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
    $ValInput = htmlspecialchars(trim($_POST['ValInput']), ENT_QUOTES, "UTF-8");
    if($ValLocation == "PSL")
    {
        $QDataPN = GET_CHECK_PN_TYPE_STOCK($ValInput,$linkMACHWebTrax);
        if(mssql_num_rows($QDataPN) != "0")
        {
            $RDataPN = mssql_fetch_assoc($QDataPN);
            echo "1#".trim($RDataPN['JenisStock']);
        }
        else
        {
            echo "0#-";
        }
    }
    if($ValLocation == "PSM")
    {
        $QDataPN = GET_CHECK_PN_TYPE_STOCK_PSM($ValInput);
        if(mssql_num_rows($QDataPN) != "0")
        {
            $RDataPN = mssql_fetch_assoc($QDataPN);
            echo "1#".trim($RDataPN['JenisStock']);
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