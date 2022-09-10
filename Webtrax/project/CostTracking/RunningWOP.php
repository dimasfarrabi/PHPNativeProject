<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteSelected = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
?>
<style>
    .WOPList{
    cursor: pointer;
    }   
    .tableFixHead {
        overflow-y: auto;
        height: 450px;
    }
    .tableFixHead thead th {
    position: sticky;
    top: 0;
    }
    table {
    border-collapse: collapse;
    width: 100%;
    }
    th,
    td {
    padding: 8px 16px;
    border: 1px solid #ccc;
    }
    th {
    background: #eee;
    }
</style>
<div class="col-md-12"><h5><strong>Table Running Cost Per WO Parent (Without OTS)</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive tableFixHead">
        <table class="table table-bordered table-hover table-fixed" id="TableOpenWOP">
            <thead class="theadCustom">
                <tr>
                    <th>WO Parent</th>
                    <th>Expense Allocation</th>
                    <th>Total Running Cost ($)</th>
                    <th>Total Target Cost ($)</th>
                    <th>Batch Qty</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Data = RUNNING_COST_PER_WOP($ValQuoteSelected,$linkMACHWebTrax);
            while($DataRes = sqlsrv_fetch_array($Data))
            {
                $WOP = trim($DataRes['WOParent']);
                $Expense = trim($DataRes['ExpenseAllocation']);
                $TotalRunCost = trim($DataRes['TotalRunningCost']);
                $TargetCost = trim($DataRes['TotalTargetCost']);
                $Qtys = trim($DataRes['QtyParent']);
                if( $Qtys == 0){ $Qty = 1; } else { $Qty = $Qtys; }
                $TotTargetCost = @($TargetCost*$Qty);
                $TotalRunCost = number_format((float)$TotalRunCost, 2, '.', ',');
                $TotTargetCost = number_format((float)$TotTargetCost, 2, '.', ',');
                $Qtys = number_format((float)$Qtys, 2, '.', ',');
                $EncData = base64_encode(base64_encode($WOP."#".$Expense."#".$ValQuoteSelected));
            ?>
                <tr class="WOPList" data-float="<?php echo $EncData; ?>">
                    <td class="text-left"><?php echo $WOP; ?></td>
                    <td class="text-left"><?php echo $Expense; ?></td>
                    <td class="text-right"><?php echo $TotalRunCost; ?></td>
                    <td class="text-right"><?php echo $TotTargetCost; ?></td>
                    <td class="text-right"><?php echo $Qtys; ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>