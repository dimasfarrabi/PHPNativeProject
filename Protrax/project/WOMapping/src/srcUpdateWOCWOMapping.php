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
    $ValNewWOC = htmlspecialchars(trim($_POST['ValNewWOC']), ENT_QUOTES, "UTF-8");
    $ValNewWOC = strtoupper(trim($ValNewWOC));
    $ValIDRow = htmlspecialchars(trim($_POST['ValTemporaryBtn']), ENT_QUOTES, "UTF-8");

    if($ValLocation == "PSM")
    {        
        $BolError = FALSE;
        $ResUpdateWOCWOMapping = UPDATE_MACH_WOC_WOMAPPING_WITH_EXPENSE_BY_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCWOMapping == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCTT = UPDATE_MACH_WOC_TT_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCTT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCMACHTRAX = UPDATE_MACH_WOC_MACHTRAX_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCMACHTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCMATTRAX = UPDATE_MACH_WOC_MATTRAX_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCMATTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCMAIN = UPDATE_MACH_WOC_MAIN_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCMAIN == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCRAWMAT = UPDATE_MACH_WOC_RAWMAT_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCRAWMAT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCToolsUsage = UPDATE_MACH_WOC_TOOLS_USAGE_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewWOC);
        if($ResUpdateWOCToolsUsage == "FALSE")
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
        $BolError = FALSE;
        $ResUpdateWOCWOMapping = UPDATE_MACH_WOC_WOMAPPING_WITH_EXPENSE_BY_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCWOMapping == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCTT = UPDATE_MACH_WOC_TT_BY_WOMAPPING_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCTT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCMACHTRAX = UPDATE_MACH_WOC_MACHTRAX_BY_WOMAPPING_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCMACHTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCMATTRAX = UPDATE_MACH_WOC_MATTRAX_BY_WOMAPPING_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCMATTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCMAIN = UPDATE_MACH_WOC_MAIN_BY_WOMAPPING_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCMAIN == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCRAWMAT = UPDATE_MACH_WOC_RAWMAT_BY_WOMAPPING_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCRAWMAT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateWOCToolsUsage = UPDATE_MACH_WOC_TOOLS_USAGE_BY_WOMAPPING_ID($ValWOMappingID,$ValNewWOC,$linkMACHWebTrax);
        if($ResUpdateWOCToolsUsage == "FALSE")
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