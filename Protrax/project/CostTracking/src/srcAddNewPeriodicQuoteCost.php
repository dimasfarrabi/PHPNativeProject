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
    $DtPM = htmlspecialchars(trim($_POST['PM']), ENT_QUOTES, "UTF-8");
    $DtDM = htmlspecialchars(trim($_POST['DM']), ENT_QUOTES, "UTF-8");
    $DtQuote = htmlspecialchars(trim($_POST['Quote']), ENT_QUOTES, "UTF-8");
    $DtCategory = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $DtHalf = htmlspecialchars(trim($_POST['Half']), ENT_QUOTES, "UTF-8");
    $DtSortNumber = htmlspecialchars(trim($_POST['SortNumber']), ENT_QUOTES, "UTF-8");
    $DtExpense = htmlspecialchars(trim($_POST['Expense']), ENT_QUOTES, "UTF-8");    
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
    $DtTotalTargetCostNTargetQty = htmlspecialchars(trim($_POST['TotalTargetCostAndTargetQty']), ENT_QUOTES, "UTF-8");
    $DtTotalTargetCostNActualQty = htmlspecialchars(trim($_POST['TotalTargetCostAndActualQty']), ENT_QUOTES, "UTF-8");
    $DtTotalActualCostNActualQty = htmlspecialchars(trim($_POST['TotalActualCostAndActualQty']), ENT_QUOTES, "UTF-8");
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

    # check data sebelumnya
    $DataRow = CHECK_PERIODIC_DATA($DtQuote,$DtCategory,$DtHalf,$DtExpense,$linkMACHWebTrax);
    if($DataRow == "0")
    {
        # get divisi id
        $ValDivID = GET_DIVISION_ID_MACH($DtExpense,$linkMACHWebTrax);
        # get project id
        $ValProjectID = GET_PROJECT_ID($DtQuote,$linkMACHWebTrax);
        # input
        $ResAdd = ADD_NEW_PERIODIC_QUOTE_COST($DtExpense,$DtSortNumber,$DtHalf,$DtCategory,$DtQuote,$DtPM,$DtDM,$ResQtyQuote,$ResQtyTarget,$ResTargetPeopleCost,$ResPeopleCost,$ResTargetMachineCost,$ResMachineCost,$ResTargetMaterialCost,$ResMaterialCost,$ResQtyQCIn,$ResQtyQCOut,$ValDivID,$ValProjectID,$ResTotalTargetCost,$ResTotalActualCost,$ResTotalTargetCostNTargetQty,$ResTotalTargetCostNActualQty,$ResTotalActualCostNActualQty,$linkMACHWebTrax);
        if($ResAdd == "TRUE")
        {        
            ?>
            <script>
                $(document).ready(function () {
                    var dt = $("#TableViewData").DataTable();
                    var newrow = $('<tr><td class="text-center">-</td><td class="text-center">-</td><td class="text-start"><?php echo $DtExpense; ?></td><td class="text-center"><?php echo $DtHalf; ?></td><td class="text-center"><?php echo $DtCategory; ?></td><td class="text-start"><?php echo $DtQuote; ?></td><td class="text-start"><?php echo $DtPM; ?></td><td class="text-start"><?php echo $DtDM; ?></td><td class="text-center"><?php echo $ResQtyQuote; ?></td><td class="text-center"><?php echo $ResQtyTarget; ?></td><td class="text-end"><?php echo $ResTargetPeopleCost; ?></td><td class="text-end"><?php echo $ResPeopleCost; ?></td><td class="text-end"><?php echo $ResTargetMachineCost; ?></td><td class="text-end"><?php echo $ResMachineCost; ?></td><td class="text-end"><?php echo $ResTargetMaterialCost; ?></td><td class="text-end"><?php echo $ResMaterialCost; ?></td><td class="text-center"><?php echo $ResQtyQCIn; ?></td><td class="text-center"><?php echo $ResQtyQCOut; ?></td><td class="text-end"><?php echo $ResTotalTargetCost; ?></td><td class="text-end"><?php echo $ResTotalActualCost; ?></td><td class="text-end"><?php echo $ResTotalTargetCostNTargetQty; ?></td><td class="text-end"><?php echo $ResTotalTargetCostNActualQty; ?></td><td class="text-end"><?php echo $ResTotalActualCostNActualQty; ?></td></tr>');
                    dt.row.add(newrow).draw(false);                
                    $('#ModalNewPeriodicQuoteCost').modal("hide");
                });
            </script>
            <?php 
        }
        else
        {
            ?>
            <div class="alert alert-dark fw-bold" role="alert">
                Data gagal disimpan, mohon diulangi lagi nanti!
            </div>
            <?php
        }        
    }
    else
    {
        ?>
        <div class="alert alert-dark fw-bold" role="alert">
            Data untuk expense terpilih sudah disimpan sebelumnya!
        </div>
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
