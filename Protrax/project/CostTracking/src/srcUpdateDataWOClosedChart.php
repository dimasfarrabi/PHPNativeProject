<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleCostTrackingChart.php");
require_once("../Modules/ModuleTarget.php");

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
    $ValInputQuoteID = htmlspecialchars(trim($_POST['ValDataToken']), ENT_QUOTES, "UTF-8");
    $ValInputQuoteID = base64_decode(base64_decode($ValInputQuoteID));
    $ArrInputQuoteID = explode("#",$ValInputQuoteID);
    $ValIdx =  trim($ArrInputQuoteID[0]);
    $ValQuoteID =  trim($ArrInputQuoteID[1]);
    $ValDataTotalTargetCost = htmlspecialchars(trim($_POST['ValDataTotalTargetCost']), ENT_QUOTES, "UTF-8");
    $ValDataTotalActualCost = htmlspecialchars(trim($_POST['ValDataTotalActualCost']), ENT_QUOTES, "UTF-8");
    $ValDataTotalQtyBuilt = htmlspecialchars(trim($_POST['ValDataTotalQtyBuilt']), ENT_QUOTES, "UTF-8");
    $ValDataTotalQtyTarget = htmlspecialchars(trim($_POST['ValDataTotalQtyTarget']), ENT_QUOTES, "UTF-8");
    $ValDataTotalOTS = htmlspecialchars(trim($_POST['ValDataTotalOTS']), ENT_QUOTES, "UTF-8");
    $ValTotalCalculate = $ValDataTotalOTS + ($ValDataTotalQtyBuilt * $ValDataTotalActualCost);  

    # get data
    $ValRow = base64_encode(base64_encode(trim($ValIdx)."#".trim($ValQuoteID)));
    $Result = UPDATE_DATA_WO_CLOSED_CHART_BY_ID($ValIdx,$ValDataTotalTargetCost,$ValDataTotalActualCost,$ValDataTotalQtyBuilt,$ValDataTotalQtyTarget,$ValDataTotalOTS,$ValTotalCalculate,$linkMACHWebTrax);
        
    if($Result == "TRUE")
    {
        $ValDataTotalTargetCost2 = sprintf('%.2f',floatval(trim($ValDataTotalTargetCost)));
        $ValDataTotalActualCost2 = sprintf('%.2f',floatval(trim($ValDataTotalActualCost)));
        $ValDataTotalQtyBuilt2 = sprintf('%.2f',floatval(trim($ValDataTotalQtyBuilt)));
        $ValDataTotalQtyTarget2 = sprintf('%.2f',floatval(trim($ValDataTotalQtyTarget)));
        $ValDataTotalOTS2 = sprintf('%.2f',floatval(trim($ValDataTotalOTS)));
            
        ?>
        <script>
            $(document).ready(function () {
                $('#TableViewData tbody tr[data-cookies="<?php echo $ValRow; ?>"]').find("td:eq(3)").html("<?php echo $ValDataTotalTargetCost2;?>");
                $('#TableViewData tbody tr[data-cookies="<?php echo $ValRow; ?>"]').find("td:eq(4)").html("<?php echo $ValDataTotalActualCost2;?>");
                $('#TableViewData tbody tr[data-cookies="<?php echo $ValRow; ?>"]').find("td:eq(5)").html("<?php echo $ValDataTotalQtyBuilt2;?>");
                $('#TableViewData tbody tr[data-cookies="<?php echo $ValRow; ?>"]').find("td:eq(6)").html("<?php echo $ValDataTotalQtyTarget2;?>");
                $('#TableViewData tbody tr[data-cookies="<?php echo $ValRow; ?>"]').find("td:eq(7)").html("<?php echo $ValDataTotalOTS2;?>");  
                $("#ModalUpdateDataChart").modal("hide");      
            });
        </script>
        <?php 
    }
    else
    {
        ?>
        <script>
            $(document).ready(function () {
                alert("Error! Please try again later!");
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
