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
    $ValNewExpense = htmlspecialchars(trim($_POST['ValNewExpense']), ENT_QUOTES, "UTF-8");
    $ValIDRow = htmlspecialchars(trim($_POST['ValTemporaryBtn']), ENT_QUOTES, "UTF-8");
    

    if($ValLocation == "PSM")
    {        
        $BolError = FALSE;
        $ResUpdateExpenseWOMapping = UPDATE_MACH_EXPENSE_WOMAPPING_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseWOMapping == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseTT = UPDATE_MACH_EXPENSE_TT_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseTT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseMACHTRAX = UPDATE_MACH_EXPENSE_MACHTRAX_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseMACHTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseMATTRAX = UPDATE_MACH_EXPENSE_MATTRAX_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseMATTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseMAIN = UPDATE_MACH_EXPENSE_MAIN_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseMAIN == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseRAWMAT = UPDATE_MACH_EXPENSE_RAWMAT_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseRAWMAT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseToolsUsage = UPDATE_MACH_EXPENSE_TOOLS_USAGE_BY_WOMAPPING_ID_PSM($ValWOMappingID,$ValNewExpense);
        if($ResUpdateExpenseToolsUsage == "FALSE")
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
        $ResUpdateExpenseWOMapping = UPDATE_MACH_EXPENSE_WOMAPPING_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseWOMapping == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseTT = UPDATE_MACH_EXPENSE_TT_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseTT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseMACHTRAX = UPDATE_MACH_EXPENSE_MACHTRAX_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseMACHTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseMATTRAX = UPDATE_MACH_EXPENSE_MATTRAX_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseMATTRAX == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseMAIN = UPDATE_MACH_EXPENSE_MAIN_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseMAIN == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseRAWMAT = UPDATE_MACH_EXPENSE_RAWMAT_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseRAWMAT == "FALSE")
        {
            $BolError = TRUE;
        }
        $ResUpdateExpenseToolsUsage = UPDATE_MACH_EXPENSE_TOOLS_USAGE_BY_WOMAPPING_ID($ValWOMappingID,$ValNewExpense,$linkMACHWebTrax);
        if($ResUpdateExpenseToolsUsage == "FALSE")
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