<?php
session_start();
require_once("../../../ConfigDB2.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOutPartTBZ.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 /*
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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValInput = htmlspecialchars(trim($_POST['ValPN']), ENT_QUOTES, "UTF-8");
    if($ValLocation == "PSL")
    {
        $DataItem = GET_DATA_ITEM_MASTER_BY_PARTNO_BY_ID($ValInput,$linkMACHWebTrax);
        if(trim($DataItem['PartNo']) == "")
        {
            echo "0";
        }
        else
        {
            echo "1#".trim($DataItem['PartNo'])."#".utf8_encode(trim($DataItem['PartDescription']));
        }
    }
    if($ValLocation == "PSM")
    {
        $DataItem = GET_DATA_ITEM_MASTER_BY_PARTNO_BY_ID_PSM($ValInput);
        if(trim($DataItem['PartNo']) == "")
        {
            echo "0";
        }
        else
        {
            echo "1#".trim($DataItem['PartNo'])."#".utf8_encode(trim($DataItem['PartDescription']));
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