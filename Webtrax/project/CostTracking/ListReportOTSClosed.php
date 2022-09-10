<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $ValQuoteSelected = htmlspecialchars(trim($_POST['ValQuoteSelected']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValCostAllocation = htmlspecialchars(trim($_POST['ValCostAllocation']), ENT_QUOTES, "UTF-8");
    $LinkOpt = $linkMACHWebTrax;
    ?>
<div class="col-md-12"><h5><strong>Top 10 OTS Total Cost (<?php echo $ValCostAllocation; ?>)</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableTOP10OTS">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30">No</td>
                    <td class="text-center trowCustom" width="80">PartNo</td>
                    <td class="text-center trowCustom">Part Description</td>
                    <td class="text-center trowCustom" width="80">Qty Usage</td>
                    <td class="text-center trowCustom" width="110">Total Cost<br>($)</td>
                </tr>
            </thead>
            <tbody>
    <?php
    $QListTop10 = TOP_10_OTS_COST_CLOSED($ValType,$ValQuoteSelected,$ValClosedTime,$ValQuoteCategory,$ValCostAllocation,$LinkOpt);
    $No = 1;
    while($RListTop10 = sqlsrv_fetch_array($QListTop10))
    {
        $ValPartDesc = $RListTop10['PartDescription'];
        $ValQty = $RListTop10['QtyUsage'];
        $ValUnitCost = $RListTop10['UnitCost'];
        $ValTotalCost = $RListTop10['TotalCost'];
        $ValPartNo = $RListTop10['PartNo'];
        
        $ValQty = number_format((float)$ValQty, 2, '.', ',');
        $ValUnitCost = number_format((float)$ValUnitCost, 2, '.', ',');
        $ValTotalCost = number_format((float)$ValTotalCost, 2, '.', ',');

        ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-left"><?php echo $ValPartDesc; ?></td>
                    <td class="text-center"><?php echo $ValQty; ?></td>
                    <td class="text-right"><?php echo $ValTotalCost; ?></td>
                </tr>
        <?php
        $No++;
    }

    ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
else
{
    echo "";    
}
?>