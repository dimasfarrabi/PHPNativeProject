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
    $datax = (htmlspecialchars(trim($_POST['DataFloat']), ENT_QUOTES, "UTF-8"));
    // echo $datax;
    $arr = explode("*",$datax);
    $ValQuoteCategory = $arr[2];
    $ValQuoteSelected = $arr[0];
    $ClosedTime = $arr[1];
    $QDataRecalculate = GET_LAST_RECALCULATE_LOG($linkMACHWebTrax);
    $RowRecalculateLog = sqlsrv_num_rows($QDataRecalculate);
    if($RowRecalculateLog != "0")
    {
        while($RDataRecalculate = sqlsrv_fetch_array($QDataRecalculate))
        {
            $DateRecalculateLog = date("m/d/Y H:i A",strtotime($RDataRecalculate['DateCreated']));
        }
    }
    else
    {
        $DateRecalculateLog = "-";   
    }
?>
<div class="col-md-10">Quote Category : <strong><?php echo $ValQuoteCategory; ?></strong>. Quote : <strong><?php echo $ValQuoteSelected; ?></strong></div>
<div class="col-md-2 text-right InfoChart PointerList">[ Season Chart ]</div>
<div class="col-md-12">Last Quote Recalculate : <strong><?php echo $DateRecalculateLog; ?></strong></div>
<div class="col-md-12"><i>*) Open Work Order with total labour cost, machine cost and material cost</i></div>
<div class="col-md-12"><i>*) Standard cost machine $3.76 per hour; Standard cost labor $3 per hour</i></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="QuoteCost">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="30">No</th>
                    <th class="text-center trowCustom" width="450">WO Parent</th>
                    <th class="text-center trowCustom">Labor Cost</th>
                    <th class="text-center trowCustom">Machine Cost</th>
                    <th class="text-center trowCustom">Material Cost</th>
                    <th class="text-center trowCustom">OTS Cost</th>
                    <th class="text-center trowCustom">Total Cost</th>
                    <th class="text-center trowCustom" width="100">Qty WO Created</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalALL = $TotalWO = 0;
            $no = 1;
            $data = GET_DATA_OPEN_PER_WOP($ValQuoteCategory,$ValQuoteSelected,$ClosedTime,$linkMACHWebTrax);
            while($res3=sqlsrv_fetch_array($data))
            {
                $WOParent = trim($res3['WOParent']);
                $LaborCost = trim($res3['RunManCost']);
                $MachCost = trim($res3['RunMachCost']);
                $MatCost = trim($res3['RunMatCost']);
                $OTSCost = trim($res3['RunOTSCost']);
                $TotalActualCost = trim($res3['RunTotalCost']);
                $TotalALL = @($TotalALL + $TotalActualCost);
                $LaborCost = number_format((float)$LaborCost, 2, '.', ',');
                $MachCost = number_format((float)$MachCost, 2, '.', ',');
                $MatCost = number_format((float)$MatCost, 2, '.', ',');
                $OTSCost = number_format((float)$OTSCost, 2, '.', ',');
                $TotalActualCost = number_format((float)$TotalActualCost, 2, '.', ',');
                $CountWO = COUNT_WO_OPEN("ByWOP",$WOParent,$ValQuoteCategory,$ClosedTime,$linkMACHWebTrax);
                $TotalWO = $TotalWO + $CountWO;
                $enc = base64_encode($WOParent."*".$ValQuoteCategory."*".$ClosedTime);
            ?>
                <tr class="DataChild" data-float="<?php echo $enc; ?>">
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="text-left"><?php echo $WOParent; ?></td>
                    <td class="text-right"><?php echo $LaborCost; ?></td>
                    <td class="text-right"><?php echo $MachCost; ?></td>
                    <td class="text-right"><?php echo $MatCost; ?></td>
                    <td class="text-right"><?php echo $OTSCost; ?></td>
                    <td class="text-right"><?php echo $TotalActualCost; ?></td>
                    <td class="text-right"><?php echo $CountWO; ?></td>
                </tr>
            <?php
            $no++;
            }
            $TotalALL = number_format((float)$TotalALL, 2, '.', ',');
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="6"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalALL; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalWO; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-12" id="MaterialDetail">
</div>
<?php
}
?>