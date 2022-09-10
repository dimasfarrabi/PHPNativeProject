<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleNewBCPartJob.php");

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
    $WOMappingID = htmlspecialchars(trim($_POST['WOMappingID']), ENT_QUOTES, "UTF-8");
    $Company = htmlspecialchars(trim($_POST['Company']), ENT_QUOTES, "UTF-8");
    $WOC = htmlspecialchars(trim($_POST['WOC']), ENT_QUOTES, "UTF-8");
    $WOP = htmlspecialchars(trim($_POST['WOP']), ENT_QUOTES, "UTF-8");
    $Quote = htmlspecialchars(trim($_POST['Quote']), ENT_QUOTES, "UTF-8");
    $Product = utf8_decode(trim($_POST['Product']));
    $OrderType = htmlspecialchars(trim($_POST['OrderType']), ENT_QUOTES, "UTF-8");
    $Expense = htmlspecialchars(trim($_POST['Expense']), ENT_QUOTES, "UTF-8");
    $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
    $InputQty = htmlspecialchars(trim($_POST['InputQty']), ENT_QUOTES, "UTF-8");
    $CheckFinishing = htmlspecialchars(trim($_POST['CheckFinishing']), ENT_QUOTES, "UTF-8");
    $PartStatus = htmlspecialchars(trim($_POST['PartStatus']), ENT_QUOTES, "UTF-8");
    $UniqueCode = date("Ymd-His.").substr(round(microtime(true) * 1000),-3);
    # insert data
    $QInsert = INSERT_NEW_BARCODE_MAIN($WOC,$PartNo,$InputQty,$PartStatus,$CheckFinishing,$Product,$OrderType,$Expense,$FullName,$UniqueCode,$WOMappingID,$InputQty,$Company,$linkHRISWebTrax);
    # response
    echo $QInsert;
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
