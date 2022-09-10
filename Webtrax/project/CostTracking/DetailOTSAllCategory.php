<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValClosedTime = htmlspecialchars(trim($_POST['ValSeason']), ENT_QUOTES, "UTF-8");
    $ValPartNo = htmlspecialchars(trim($_POST['ValPartNo']), ENT_QUOTES, "UTF-8");
    $QuoteCategory = htmlspecialchars(trim($_POST['QuoteCategory']), ENT_QUOTES, "UTF-8");
    $Tipe = htmlspecialchars(trim($_POST['Tipe']), ENT_QUOTES, "UTF-8");
    // echo "$ValClosedTime >> $Tipe >> $ValPartNo";
    if( $Tipe == 'OTS')
    {
?>
    <div><h5><strong>Detail OTS Cost (<?php echo $ValPartNo; ?>)</strong></h5></div>
    <?php
    }
    else
    {
    ?>
    <div><h5><strong>Detail Material Cost (<?php echo $ValPartNo; ?>)</strong></h5></div>
    <?php
    }
    ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="">
                <thead class="theadCustom">
                     <tr>
                    <th class="text-center trowCustom" width="60">No</th>
                    <th class="text-center TargetColumn trowCustom">Quote</th>
                    <th class="text-center ActualColumn trowCustom" width="150">Qty Usage</th>
                    <th class="text-center ActualColumn trowCustom">UOM</th>
                    <th class="text-center ActualColumn trowCustom" width="150">Cost ($)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $Numb=1;
                $TotalQty = $TotalOTS = 0;
                if( $Tipe == 'OTS')
                {
                    $QListOTSAll = GET_DETAIL_OTS_COST_ALL_CATEGORY($ValClosedTime,$ValPartNo,$QuoteCategory,$linkMACHWebTrax);
                }
                else 
                {
                    $QListOTSAll = GET_DETAIL_MATERIAL_COST_ALL_CATEGORY($ValClosedTime,$ValPartNo,$QuoteCategory,$linkMACHWebTrax);
                }
                while($RListOTSAll = sqlsrv_fetch_array($QListOTSAll))
                {
                    $ValPartNoAll = trim($RListOTSAll['PartNo']);
                    $ValQuote = trim($RListOTSAll['Quote']);
                    $ValQtyAll = trim($RListOTSAll['QtyUsage']);
                    $ValOTSCostAll = trim($RListOTSAll['TotalCost']);
                    $UOM = trim($RListOTSAll['UOM']);
                    $TotalQty = $TotalQty + $ValQtyAll;
                    $TotalOTS = $TotalOTS + $ValOTSCostAll;
                    $ValOTSCostAll = number_format((float)$ValOTSCostAll, 2, '.', ',');
                    $ValQtyAll = number_format((float)$ValQtyAll, 2, '.', ',');

                ?>
                    <tr>
                        <td class="text-center"><?php echo $Numb; ?></td>
                        <td class="text-left"><?php echo $ValQuote; ?></td>
                        <td class="text-right"><?php echo $ValQtyAll; ?></td>
                        <td class="text-center"><?php echo $UOM; ?></td>
                        <td class="text-right"><?php echo $ValOTSCostAll; ?></td>
                    </tr>
                <?php
                $Numb++;
                }
                $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                $TotalOTS = number_format((float)$TotalOTS, 2, '.', ',');
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center"><strong>TOTAL</strong>
                        <td class="text-right"><strong><?php echo $TotalQty; ?></strong>
                        <td class="text-right"><strong></strong>
                        <td class="text-right"><strong><?php echo $TotalOTS; ?></strong>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

<?php
}
else { echo "";}
?>