<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteSelected = htmlspecialchars(trim($_POST['ValQuoteSelected']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $LinkOpt = $linkMACHWebTrax;
	# data recalculate log 
    $QDataRecalculate = GET_LAST_RECALCULATE_LOG($LinkOpt);
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
<style>
    .Points{font-size:11px;}
    .ColSumaryPoints{font-size:12px;}
    .ColSumaryPoints2{font-size:14px;}
    .ColSumaryPointsTotal{font-size:13px;color:#0008ff;backgtrim-color:#F0F0F0;}
    .ColResult{font-size:14px;backgtrim-color:#F0F0F0;}
    .head{backgtrim-color:#F0F0F0;}
    .RowResult{font-size:15px;}
    .TargetColumn{color:#ff0000;font-size:11px;}
    .ActualColumn{color:#0008ff;font-size:11px;}
    .TargetTotalColumn{color:#ff0000;font-size:14px;}
    .ActualTotalColumn{color:#0008ff;font-size:14px;}
    .InfoRecalculate{font-style:italic;font-weight:bold;color:#ff0000;text-decoration: underline;text-decoration-color:#fff600;}
    .InfoRecalculate2{font-size:15px;font-weight:bold;color:#ff0000;}
    .InfoChart{cursor: pointer;color: #337AB7;}
    /* .card {padding: 10px; box-shadow: 0px 1px 3px #888888;backgtrim:#FFFFFF;width: 80%;} */
    .sticky {position: sticky; top: 0; width: 80%;z-index:100;}
    .header {padding: 5px 10px;backgtrim:#FFFFFF;color: #555;box-shadow: 0px 3px 5px #888888;}


</style>
<script src="project/costtracking/lib/liblistotspart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="col-md-10 card ColSumaryPoints2" id="myHeader">Closed Time : <strong><?php echo $ValClosedTime; ?></strong>. Quote Category : <strong><?php echo $ValQuoteCategory; ?></strong>. Quote : <strong><?php echo $ValQuoteSelected; ?></strong>
<br>Last Quote Recalculate : <strong><?php echo $DateRecalculateLog; ?></strong></div>
<div class="col-md-2 text-right InfoChart">[ Season Chart ]</div>
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>
<?php
if($ValQuoteCategory=='Quote'){
?>
<!-- <div class="col-md-12"><i>*) Cost per unit</i></div> -->
<div id="TableSummary" style="margin-top: 90px;">
</div>
<div class="col-md-12">
<div><h5><strong>Achievement</strong></h5></div>
    <div class="table_summary">
        <table id="vertical-2" class="table table-bordered table-hover">
        <tr>
            <th class="ColSumaryPoints" width="180"><strong>Cost Points (%)</strong></th>
            <?php
            $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
            while($RListReport = sqlsrv_fetch_array($QListReport))
            {
            $ValTotalActualCostAndActualQty = $RListReport['TotalTotalActualCostAndActualQty'];
            $ValTotalTargetCostAndActualQty = $RListReport['TotalTotalTargetCostAndActualQty'];
            $ValTotalCostInPercent = @(($ValTotalTargetCostAndActualQty+($ValTotalTargetCostAndActualQty*0.1)-$ValTotalActualCostAndActualQty)/$ValTotalTargetCostAndActualQty*10)*100;
            $ValTotalCostInPercent = number_format((float)$ValTotalCostInPercent,2,'.',',');
            ?>
            <td class="ColSumaryPoints"><strong><?php echo $ValTotalCostInPercent; ?></strong></td>
            <?php
            }
            ?>
        </tr>
        <tr>
        <th class="ColSumaryPoints"><strong>Quantity Points (%)</strong></th>  
        <?php
        $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
        $ValTotalQtyTarget = $RListReport['TotalQtyTarget'];
        $TotalQtyActual = $RListReport['TotalQtyActual'];
        $ValDOT = @($TotalQtyActual/$ValTotalQtyTarget)*100;
        if($ValDOT > 100){ $ValDOT = 100;}
        $ValDOT = number_format((float)$ValDOT,2,'.',',');
        ?>
        <td class="ColSumaryPoints"><strong><?php echo $ValDOT; ?></strong></td>
        <?php
        }
        ?>    
        </tr>  
        <tr>
        <?php
        $ValQualityPoints = 0;
        $QListReport = GET_AVG_QUALITY_POINTS($ValClosedTime,$ValQuoteSelected,$LinkOpt);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
        $ValQualityPoints = $RListReport['AvgQuality'];
        $ValQualityPoints = number_format((float)$ValQualityPoints,2,'.',',');
        if(trim($ValQualityPoints) == ""){$ValQualityPoints = "0.00";};
        ?>
        <th class="ColSumaryPoints"><strong>Quality Points (%)</strong></th>  
        <td class="ColSumaryPoints"><strong><?php echo $ValQualityPoints; ?></strong></td> 
        <?php
        }
        ?> 
        </tr>
        <tr>
            <?php
            $ValTotalBonus= ($ValTotalCostInPercent*$ValDOT*$ValQualityPoints)/10000;
            if ($ValTotalBonus > 100){$ValTotalBonus = 100;};
            $ValTotalBonus = number_format((float)$ValTotalBonus,2,'.',',');

            ?>
        <th class="ColSumaryPointsTotal"><strong>Total (%)</strong></th>  
        <td class="ColSumaryPointsTotal"><strong><?php echo $ValTotalBonus; ?></strong></td>  
        </tr>
        </table>
    </div>
</div>
<?php
}
else{
?>
<div id="TableSummary" style="margin-top: 90px;">
</div>
<div class="col-md-12"></div>

<?php
}
?>
<div class="col-md-12">
<div><h5><strong>Product Cost Without OTS Cost</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="vertical-2">
            <thead>
                <tr>
                    <th class="text-center theadCustom" width="220">COST ALLOCATION</th>      
                    <?php                                  // COST ALLOCATION
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValExpense = $RListReport['ExpenseAllocation'];
                    ?>
                    <th width="70" class="text-center theadCustom"><strong><?php echo $ValExpense; ?></strong></th>
                    <?php
                    }
                    ?>
                    <th width="70" class="text-center theadCustom"><strong>TOTAL</strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                ?>
            </tbody>
        </table>        
        <span><i>Auto Recalculate at : Everyday 07:00 AM, 11:59 AM, 4:30 PM</i></span>
    </div>
</div>

<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><h5><strong>Product OTS Cost Per Division</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                <th class="text-center trowCustom" width="30">No</th>
                <th class="text-center trowCustom" width="130">Part No.</th>
                <th class="text-center trowCustom" >Part Description</th>
                <th class="text-center trowCustom" width="130">Qty Usage</th>
                <th class="text-center trowCustom" width="180">Cost ($)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Number=1;
            $QListOTS = GET_TOP10_OVERALL_OTS_COST_PER_PRODUCT($ValQuoteCategory,$ValQuoteSelected,$ValClosedTime,$LinkOpt);
            while($RListOTS = sqlsrv_fetch_array($QListOTS))
            {
                $ValPartNo = trim($RListOTS['PartNo']);
                $ValPartDes = trim($RListOTS['PartDescription']);
                $ValQtyUsage = trim($RListOTS['QtyUsage']);
                $ValOTSCost = trim($RListOTS['TotalCost']);
                $ValOTSCost = number_format((float)$ValOTSCost, 2, '.', ',');

            ?>
                <tr>
                <td class="text-center"><?php echo $Number; ?></td>
                <td class="text-center"><?php echo $ValPartNo; ?></td>
                <td class="text-left"><?php echo $ValPartDes; ?></td>
                <td class="text-center"><?php echo $ValQtyUsage; ?></td>
                <td class="text-right"><?php echo $ValOTSCost; ?></td>
                </tr>
            <?php
            $Number++;
            }
            ?>
            </tbody>
        </table>
    </div>
    
</div>
</div>
</div>


<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><h5><strong>Product OTS Cost Per Division</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableTotalOTSCostClosed">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30">No</td>
                    <td class="text-center trowCustom">Cost Allocation</td>
                    <td class="text-center trowCustom" width="80">Qty (Built)</td>
                    <td class="text-center trowCustom" width="110"><strong>OTS Cost Per Unit<br>($)</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php 
            $QListOTSCost = GET_REPORT_RUNNING_COST_OTS_CLOSED($ValQuoteCategory,$ValClosedTime,$ValType,$ValQuoteSelected,$LinkOpt);
            $No2 = 1;      
            $ValTotalOTSCost = 0;     
            while($RListOTSCost = sqlsrv_fetch_array($QListOTSCost))
            {
                $ValExpenseAllocation = $RListOTSCost['ExpenseAllocation'];
                $ValOTSCost = $RListOTSCost['OTSCost'];
                $ValQtyQuoteOTS = $RListOTSCost['QtyQuote'];
                $ValQtyQuoteOTS = number_format((float)$ValQtyQuoteOTS, 0, '.', ',');
                $ValOTSCostRow = number_format((float)$ValOTSCost, 2, '.', ',');
                $ValTotalOTSCost = $ValTotalOTSCost + $ValOTSCost; 
                ?>
                <tr class="RowCostAllocation">
                    <td class="text-center"><?php echo $No2; ?></td>
                    <td class="text-left"><strong><?php echo $ValExpenseAllocation; ?></strong></td>
                    <td class="text-right"><?php echo $ValQtyQuoteOTS; ?></td>
                    <td class="text-right"><strong><?php echo $ValOTSCostRow; ?></strong></td>
                </tr>
                <?php
                $No2++;
            }     
                $ValTotalOTSCost2 = number_format((float)$ValTotalOTSCost, 2, '.', ',');       
                ?>
                <tr>
                    <td colspan="3" class="text-center"><strong>Total OTS Cost per Unit</strong></td>
                    <td class="text-right"><strong><?php echo $ValTotalOTSCost2; ?></strong></td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

<?php
            
}
else
{
    echo '';    
}
?>

