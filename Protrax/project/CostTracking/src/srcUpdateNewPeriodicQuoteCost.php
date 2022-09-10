<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModulePeriodicQuoteCost.php");

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
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataID = base64_decode(base64_decode($DataIDEnc));
    $ArrDataID = explode("#",$DataID);
    $ValHalf = trim($ArrDataID [0]);
    $ValCategory = trim($ArrDataID [1]);
    $ValQuote = trim($ArrDataID [2]);
    $ValIdx = trim($ArrDataID [3]);
    $DtPM = htmlspecialchars(trim($_POST['PM']), ENT_QUOTES, "UTF-8");
    $DtDM = htmlspecialchars(trim($_POST['DM']), ENT_QUOTES, "UTF-8");
    $DtQtyQuote = htmlspecialchars(trim($_POST['QtyQuote']), ENT_QUOTES, "UTF-8");
    $DtQtyTarget = htmlspecialchars(trim($_POST['QtyTarget']), ENT_QUOTES, "UTF-8");
    $DtTargetPeopleCost = htmlspecialchars(trim($_POST['TargetPeopleCost']), ENT_QUOTES, "UTF-8");
    $DtPeopleCost = htmlspecialchars(trim($_POST['PeopleCost']), ENT_QUOTES, "UTF-8");
    $DtTargetMachineCost = htmlspecialchars(trim($_POST['TargetMachineCost']), ENT_QUOTES, "UTF-8");
    $DtMachineCost = htmlspecialchars(trim($_POST['MachineCost']), ENT_QUOTES, "UTF-8");
    $DtTargetMaterialCost = htmlspecialchars(trim($_POST['TargetMaterialCost']), ENT_QUOTES, "UTF-8");
    $DtMaterialCost = htmlspecialchars(trim($_POST['MaterialCost']), ENT_QUOTES, "UTF-8");
    $DtQtyQCIn = htmlspecialchars(trim($_POST['QtyQCIn']), ENT_QUOTES, "UTF-8");
    $DtQtyQCOut = htmlspecialchars(trim($_POST['QtyQCOut']), ENT_QUOTES, "UTF-8");
    $DtTotalTargetCost = htmlspecialchars(trim($_POST['TotalTargetCost']), ENT_QUOTES, "UTF-8");
    $DtTotalActualCost = htmlspecialchars(trim($_POST['TotalActualCost']), ENT_QUOTES, "UTF-8");
    $DtTotalTargetCostNTargetQty = htmlspecialchars(trim($_POST['TotalTargetCostNTargetQty']), ENT_QUOTES, "UTF-8");
    $DtTotalTargetCostNActualQty = htmlspecialchars(trim($_POST['TotalTargetCostNActualQty']), ENT_QUOTES, "UTF-8");
    $DtTotalActualCostNActualQty = htmlspecialchars(trim($_POST['TotalActualCostNActualQty']), ENT_QUOTES, "UTF-8");
    if($DtQtyQuote == ""){$DtQtyQuote = "0";}
    if($DtQtyTarget == ""){$DtQtyTarget = "0";}
    if($DtTargetPeopleCost == ""){$DtTargetPeopleCost = "0";}
    if($DtPeopleCost == ""){$DtPeopleCost = "0";}
    if($DtTargetMachineCost == ""){$DtTargetMachineCost = "0";}
    if($DtMachineCost == ""){$DtMachineCost = "0";}
    if($DtTargetMaterialCost == ""){$DtTargetMaterialCost = "0";}
    if($DtMaterialCost == ""){$DtMaterialCost = "0";}
    if($DtQtyQCIn == ""){$DtQtyQCIn = "0";}
    if($DtQtyQCOut == ""){$DtQtyQCOut = "0";}
    if($DtTotalTargetCost == ""){$DtTotalTargetCost = "0";}
    if($DtTotalActualCost == ""){$DtTotalActualCost = "0";}
    if($DtTotalTargetCostNTargetQty == ""){$DtTotalTargetCostNTargetQty = "0";}
    if($DtTotalTargetCostNActualQty == ""){$DtTotalTargetCostNActualQty = "0";}
    if($DtTotalActualCostNActualQty == ""){$DtTotalActualCostNActualQty = "0";}
    $ResQtyQuote = sprintf('%.0f',floatval($DtQtyQuote));
    $ResQtyTarget = sprintf('%.0f',floatval($DtQtyTarget));
    $ResTargetPeopleCost = sprintf('%.2f',floatval($DtTargetPeopleCost));
    $ResPeopleCost = sprintf('%.2f',floatval($DtPeopleCost));
    $ResTargetMachineCost = sprintf('%.2f',floatval($DtTargetMachineCost));
    $ResMachineCost = sprintf('%.2f',floatval($DtMachineCost));
    $ResTargetMaterialCost = sprintf('%.2f',floatval($DtTargetMaterialCost));
    $ResMaterialCost = sprintf('%.2f',floatval($DtMaterialCost));
    $ResQtyQCIn = sprintf('%.0f',floatval($DtQtyQCIn));
    $ResQtyQCOut = sprintf('%.0f',floatval($DtQtyQCOut));
    $ResTotalTargetCost = sprintf('%.2f',floatval($DtTotalTargetCost));
    $ResTotalActualCost = sprintf('%.2f',floatval($DtTotalActualCost));
    $ResTotalTargetCostNTargetQty = sprintf('%.2f',floatval($DtTotalTargetCostNTargetQty));
    $ResTotalTargetCostNActualQty = sprintf('%.2f',floatval($DtTotalTargetCostNActualQty));
    $ResTotalActualCostNActualQty = sprintf('%.2f',floatval($DtTotalActualCostNActualQty));    
    # update data
    $ResUpdate = UPFATE_DATA_PERIODIC_BY_ID($ValIdx,$DtQtyQuote,$DtQtyTarget,$DtTargetPeopleCost,$DtPeopleCost,$DtTargetMachineCost,$DtMachineCost,$DtTargetMaterialCost,$DtMaterialCost,$DtQtyQCIn,$DtQtyQCOut,$DtTotalTargetCost,$DtTotalActualCost,$DtTotalTargetCostNTargetQty,$DtTotalTargetCostNActualQty,$DtTotalActualCostNActualQty,$DtPM,$DtDM,$linkMACHWebTrax);
    if($ResUpdate == "TRUE")
    {
        ?>
        <script>
            $(document).ready(function () {
                var $row = $("#TableViewData tr [data-datatoken='<?php echo trim($DataIDEnc); ?>']").closest('tr');
                $row.find("td:eq(6)").html('<?php echo $DtPM; ?>');   
                $row.find("td:eq(7)").html('<?php echo $DtDM; ?>');   
                $row.find("td:eq(8)").html('<?php echo $ResQtyQuote; ?>');   
                $row.find("td:eq(9)").html('<?php echo $ResQtyTarget; ?>');
                $row.find("td:eq(10)").html('<?php echo $ResTargetPeopleCost; ?>');
                $row.find("td:eq(11)").html('<?php echo $ResPeopleCost; ?>');
                $row.find("td:eq(12)").html('<?php echo $ResTargetMachineCost; ?>');
                $row.find("td:eq(13)").html('<?php echo $ResMachineCost; ?>');
                $row.find("td:eq(14)").html('<?php echo $ResTargetMaterialCost; ?>');
                $row.find("td:eq(15)").html('<?php echo $ResMaterialCost; ?>');
                $row.find("td:eq(16)").html('<?php echo $ResQtyQCIn; ?>');
                $row.find("td:eq(17)").html('<?php echo $ResQtyQCOut; ?>');
                $row.find("td:eq(18)").html('<?php echo $ResTotalTargetCost; ?>');
                $row.find("td:eq(19)").html('<?php echo $ResTotalActualCost; ?>');
                $row.find("td:eq(20)").html('<?php echo $ResTotalTargetCostNTargetQty; ?>');
                $row.find("td:eq(21)").html('<?php echo $ResTotalTargetCostNActualQty; ?>');
                $row.find("td:eq(22)").html('<?php echo $ResTotalActualCostNActualQty; ?>');
                $("#ModalUpdatePeriodicQuoteCost").modal("hide");             
                $('#ModalUpdatePeriodicQuoteCost').on('hide.bs.modal', function () {
                    $("#ModalContentInfo").html("");
                    $("#TemporarySpace").html("");
                });
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
