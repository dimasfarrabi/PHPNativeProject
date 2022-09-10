<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleUser.php");

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
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValName = htmlspecialchars(trim($_POST['ValName']), ENT_QUOTES, "UTF-8");
    $ValUsername = htmlspecialchars(trim($_POST['ValUsername']), ENT_QUOTES, "UTF-8");
    $ValPassword = htmlspecialchars(trim($_POST['ValPassword']), ENT_QUOTES, "UTF-8");
    $ValCompany = htmlspecialchars(trim($_POST['ValCompany']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $ValIsAdmin = htmlspecialchars(trim($_POST['ValIsAdmin']), ENT_QUOTES, "UTF-8");
    $ValMnSecurity = htmlspecialchars(trim($_POST['ValMnSecurity']), ENT_QUOTES, "UTF-8");
    $ValMnCostTracking = htmlspecialchars(trim($_POST['ValMnCostTracking']), ENT_QUOTES, "UTF-8");
    $ValMnProduction = htmlspecialchars(trim($_POST['ValMnProduction']), ENT_QUOTES, "UTF-8");
    $ValMnCCTV = htmlspecialchars(trim($_POST['ValMnCCTV']), ENT_QUOTES, "UTF-8");
    $ValPasswordEnc = md5($ValPassword);    
    $ValMnReport = htmlspecialchars(trim($_POST['ValMnReport']), ENT_QUOTES, "UTF-8");
    $ValMnPPIC = htmlspecialchars(trim($_POST['ValMnPPIC']), ENT_QUOTES, "UTF-8");
    $ValMnOprMachCNC = htmlspecialchars(trim($_POST['ValMnOprMachCNC']), ENT_QUOTES, "UTF-8");
    $ValMnOprMachManual = htmlspecialchars(trim($_POST['ValMnOprMachManual']), ENT_QUOTES, "UTF-8");
    $ValMnOprFabrication = htmlspecialchars(trim($_POST['ValMnOprFabrication']), ENT_QUOTES, "UTF-8");
    $ValMnOprFinishing = htmlspecialchars(trim($_POST['ValMnOprFinishing']), ENT_QUOTES, "UTF-8");
    $ValMnOprQA = htmlspecialchars(trim($_POST['ValMnOprQA']), ENT_QUOTES, "UTF-8");
    $ValMnOprQC = htmlspecialchars(trim($_POST['ValMnOprQC']), ENT_QUOTES, "UTF-8");
    $ValMnOprAssembly = htmlspecialchars(trim($_POST['ValMnOprAssembly']), ENT_QUOTES, "UTF-8");
    $ValMnOprCuttingMaterial = htmlspecialchars(trim($_POST['ValMnOprCuttingMaterial']), ENT_QUOTES, "UTF-8");
    $ValMnOprPacking = htmlspecialchars(trim($_POST['ValMnOprPacking']), ENT_QUOTES, "UTF-8");
    $ValMnOprInjection = htmlspecialchars(trim($_POST['ValMnOprInjection']), ENT_QUOTES, "UTF-8");
    $ValMnWarehouse = htmlspecialchars(trim($_POST['ValMnWarehouse']), ENT_QUOTES, "UTF-8");
    $ValMnExim = htmlspecialchars(trim($_POST['ValMnExim']), ENT_QUOTES, "UTF-8");
    $ValMnKAShift = htmlspecialchars(trim($_POST['ValMnKAShift']), ENT_QUOTES, "UTF-8");
    $ValMnClosedWO = htmlspecialchars(trim($_POST['ValMnClosedWO']), ENT_QUOTES, "UTF-8");
    # check username
    $TotalRow = CHECK_USERNAME($ValUsername,$linkMACHWebTrax);
    if($TotalRow == 0)
    {
        INSERT_NEW_PROTRAX_USER($ValUsername,$ValPasswordEnc,$ValName,$ValCompany,$ValType,$ValMnSecurity,$ValMnCostTracking,$ValMnProduction,$ValIsAdmin,$ValMnCCTV,$ValMnReport,$ValMnPPIC,$ValMnOprMachCNC,$ValMnOprMachManual,$ValMnOprFabrication,$ValMnOprFinishing,$ValMnOprQA,$ValMnOprQC,$ValMnOprAssembly,$ValMnOprCuttingMaterial,$ValMnOprPacking,$ValMnOprInjection,$ValMnWarehouse,$ValMnExim,$ValMnKAShift,$ValMnClosedWO,$linkMACHWebTrax);
        ?>
        <script>
            $(document).ready(function () {
                location.reload();
            });
        </script>
        <?php
    }
    else
    {
        ?>
        <script>
            $(document).ready(function () {
                $("#ResultMsg").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Username already used!</div>');
                $("#InputNewUsername").focus();
                $("#InputNewUsername").val("");
            });
        </script>
        <?php
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
