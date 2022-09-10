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
    $ValInputQuoteID = htmlspecialchars(trim($_POST['InputQuoteID']), ENT_QUOTES, "UTF-8");
    $ValInputHalf = htmlspecialchars(trim($_POST['InputHalf']), ENT_QUOTES, "UTF-8");
    $ValInputTotalTargetCost = htmlspecialchars(trim($_POST['InputTotalTargetCost']), ENT_QUOTES, "UTF-8");
    $ValInputTotalActualCost = htmlspecialchars(trim($_POST['InputTotalActualCost']), ENT_QUOTES, "UTF-8");
    $ValInputTotalQtyBuilt = htmlspecialchars(trim($_POST['InputTotalQtyBuilt']), ENT_QUOTES, "UTF-8");
    $ValInputTotalQtyTarget = htmlspecialchars(trim($_POST['InputTotalQtyTarget']), ENT_QUOTES, "UTF-8");
    $ValInputTotalOTS = htmlspecialchars(trim($_POST['InputTotalOTS']), ENT_QUOTES, "UTF-8");
    $ValInputQuote = htmlspecialchars(trim($_POST['InputQuote']), ENT_QUOTES, "UTF-8");
    $ArrInputQuoteID = explode(":",$ValInputQuoteID);
    $ValQuoteID =  trim($ArrInputQuoteID[1]);    
    # check data
    $Check = CHECK_DATA_WO_CLOSED_CHART_SELECTED($ValQuoteID,$ValInputQuote,$ValInputHalf,$linkMACHWebTrax);
    if(mssql_num_rows($Check) == 0)
    {
        $ValTotalCalculate = $ValInputTotalOTS + ($ValInputTotalQtyBuilt * $ValInputTotalActualCost);        
        # insert data
        $Result = INSERT_NEW_DATA_WO_CLOSED_CHART($ValQuoteID,$ValInputQuote,$ValInputHalf,$ValInputTotalTargetCost,$ValInputTotalActualCost,$ValInputTotalQtyBuilt,$ValInputTotalQtyTarget,$ValInputTotalOTS,$ValTotalCalculate,$linkMACHWebTrax);
        if($Result == "TRUE")
        {
            $ValInputTotalTargetCost2 = sprintf('%.2f',floatval(trim($ValInputTotalTargetCost)));
            $ValInputTotalActualCost2 = sprintf('%.2f',floatval(trim($ValInputTotalActualCost)));
            $ValInputTotalQtyBuilt2 = sprintf('%.2f',floatval(trim($ValInputTotalQtyBuilt)));
            $ValInputTotalOTS2 = sprintf('%.2f',floatval(trim($ValInputTotalOTS)));
            $ValInputTotalQtyTarget2 = sprintf('%.2f',floatval(trim($ValInputTotalQtyTarget)));
            ?>
            <script>
                $(document).ready(function () {
                var newrow = '<tr><td class="text-center">-</td><td class="text-center"><?php echo $ValInputHalf; ?></td><td class="text-start"><?php echo $ValInputQuote; ?></td><td class="text-end"><?php echo $ValInputTotalTargetCost2; ?></td><td class="text-end"><?php echo $ValInputTotalActualCost2; ?></td><td class="text-end"><?php echo $ValInputTotalQtyBuilt2; ?></td><td class="text-end"><?php echo $ValInputTotalQtyTarget2; ?></td><td class="text-end"><?php echo $ValInputTotalOTS2; ?></td><td class="text-center">-</td></tr>';
                $("#TableViewData tbody").append(newrow);
                $("#InputTargetCost").val("");
                $("#InputActualCost").val("");
                $("#InputQtyBuilt").val("");
                $("#InputQtyTarget").val("");
                $("#InputOTS").val("");
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
        # info data sdh ada seblmnya
        ?>
            <script>
                $(document).ready(function () {
                    alert("Data saved before!");
                    $("#InputTargetCost").val("");
                    $("#InputActualCost").val("");
                    $("#InputQtyBuilt").val("");
                    $("#InputQtyTarget").val("");
                    $("#InputOTS").val("");
                    $("#InputHalf").focus();
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
