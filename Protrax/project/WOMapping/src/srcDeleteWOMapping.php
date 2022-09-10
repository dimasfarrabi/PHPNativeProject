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
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValWOMappingID = htmlspecialchars(trim($_POST['ValWOMappingID']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValDataRows = htmlspecialchars(trim($_POST['ValDataRows']), ENT_QUOTES, "UTF-8");
    
    if($ValLocation == "PSM")
    {        
        $BolError = FALSE;
        $ResDeleteMach = DELETE_WO_MAPPING_BY_WOMAPPING_ID_PSM($ValWOMappingID);
        if($ResDeleteMach == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResDeleteWebtrax = DELETE_WO_MAPPING_BY_WOMAPPING_ID_WEBTRAX_PSM($ValWOMappingID,$linkMACHWebTrax);
        if($ResDeleteMach == "FALSE")
        {
            $BolError = TRUE;
        }
        # set info error
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
        $BolError = FALSE;
        $ResDeleteMach = DELETE_WO_MAPPING_BY_WOMAPPING_ID($ValWOMappingID,$linkMACHWebTrax);
        if($ResDeleteMach == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResDeleteWebtrax = DELETE_WO_MAPPING_BY_WOMAPPING_ID_WEBTRAX_PSL($ValWOMappingID,$linkMACHWebTrax);
        if($ResDeleteMach == "FALSE")
        {
            $BolError = TRUE;
        }
        # set info error
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
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>