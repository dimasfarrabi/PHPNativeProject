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
<br>

<div><h5><strong>Achievement</strong></h5></div>
<div class="table_summary">
<table id="vertical-2" class="table table-bordered table-hover">
    <tr>
        <th class="ColSumaryPoints" width="180"><strong>Cost Points (%)</strong></th>
        <?php
        $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
        $ValTotalTargetCostAndActualQty = $ValTotalActualCostAndActualQty = 0;
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
        $TargetCostActualQty = trim($RListReport['TotalTargetCostAndActualQty']);
        $ActualCostActualQty = trim($RListReport['TotalActualCostAndActualQty']);
        $ValTotalTargetCostAndActualQty = $ValTotalTargetCostAndActualQty + $TargetCostActualQty;
        $ValTotalActualCostAndActualQty = $ValTotalActualCostAndActualQty + $ActualCostActualQty;
        }
        $ValTotalCostInPercent = trim(@(($ValTotalTargetCostAndActualQty+($ValTotalTargetCostAndActualQty*0.1)-$ValTotalActualCostAndActualQty)/$ValTotalTargetCostAndActualQty*10))*100;
        // if($ValTotalCostInPercent < 0){$ValTotalCostInPercent = 0;}
        $ValTotalCostInPercent = number_format((float)$ValTotalCostInPercent,2,'.',',');
        ?>
        <td class="ColSumaryPoints"><strong><?php echo $ValTotalCostInPercent; ?></strong></td>
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
    // // $ValCostQty = @($ValTotalCostInPercent*$ValTotalDot)/100;
    // if ($ValTotalCostInPercent > 100){$ValTotalCostInPercent = 100;};
    // // $ValTotalBonus= ($ValCostQty*$ValQualityPoints)/100;
    // if ($ValDOT > 100){$ValDOT = 100;};
    // $ValTotalBonus= ($ValTotalCostInPercent*$ValDOT*100)/10000;
    // if ($ValTotalBonus > 100){$ValTotalBonus = 100;};
    // $ValTotalBonus = number_format((float)$ValTotalBonus,2,'.',',');
    $ValTotalBonus= ($ValTotalCostInPercent*$ValDOT*$ValQualityPoints)/10000;
    if ($ValTotalBonus > 100){$ValTotalBonus = 100;};
    $ValTotalBonus = number_format((float)$ValTotalBonus,2,'.',',');

    ?>
  <th class="ColSumaryPointsTotal"><strong>Total (%)</strong></th>  
  <td class="ColSumaryPointsTotal"><strong><?php echo $ValTotalBonus; ?></strong></td>  
  </tr>
</table>
</div>
<?php
}
else{
?>
<div id="TableSummary" style="margin-top: 90px;">
</div>
<div class="col-md-12">

<?php
}
?>
<br></br>
<div><h5><strong>Product Cost Without OTS Cost</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="vertical-2">
                <tr>
                    <th class="head trowCustom" width="220">COST ALLOCATION</th>      
                    <?php 
                    $arrExpense = array();                                 // COST ALLOCATION
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValExpense = $RListReport['ExpenseAllocation'];
                    array_push($arrExpense,$ValExpense);
                    ?>
                    <td width="70" class="text-center head trowCustom"><strong><?php echo $ValExpense; ?></strong></td>
                    <?php
                    }
                    ?>
                    <td width="70" class="text-center head trowCustom"><strong>TOTAL</strong></td>
                </tr>
                <tr>   
                    <td class="Points">Target Labor Cost ($)</td>
                    <?php                                //TARGET LABOR COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetPeopleCost = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetPeopleCost = trim($RListReport['TargetPeopleCost']);
                    if(trim($ValTargetPeopleCost) == ""){$ValTargetPeopleCost = "0.00";};
                    $ValTargetPeopleCost = number_format((float)$ValTargetPeopleCost, 2, '.', ',');
                    $ValTotalTargetPeopleCost =  @($ValTotalTargetPeopleCost + $ValTargetPeopleCost);
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetPeopleCost; ?></td>
                    <?php
                    }
                    $ValTotalTargetPeopleCost = round($ValTotalTargetPeopleCost);
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetPeopleCost; ?></td>
                </tr>
                <tr>
                    <td class="Points">Actual Labor Cost ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalPeopleCost = 0;
                    $a=1;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValPeopleCost = trim($RListReport['PeopleCost']);
                    if(trim($ValPeopleCost) == ""){$ValPeopleCost = "0.00";};
                    $ValTotalPeopleCost = @($ValTotalPeopleCost + $ValPeopleCost);
                    $ValPeopleCost = number_format((float)$ValPeopleCost, 2, '.', ',');
                    $Expense = trim($RListReport['ExpenseAllocation']);
                    ?>
                    <td class="text-right ActualColumn"><span style="cursor:Pointer;" onclick="ShowData('Labor','<?php echo $Expense; ?>')"><?php echo $ValPeopleCost; ?></span></td>
                    <?php
                    
                    }
                    $ValTotalPeopleCost = round($ValTotalPeopleCost);
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalPeopleCost; ?></td>
                </tr>
                <tr>
                    <td class="Points">Target Machine Cost ($)</td>
                    <?php                                 //TARGET MACHINE COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetMachineCost = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetMachineCost = trim($RListReport['TargetMachineCost']);
                    if(trim($ValTargetMachineCost) == ""){$ValTargetMachineCost = "0.00";};
                    $ValTargetMachineCost = number_format((float)$ValTargetMachineCost, 2, '.', ',');
                    $ValTotalTargetMachineCost = $ValTotalTargetMachineCost + $ValTargetMachineCost;
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMachineCost; ?></td>
                    <?php
                    }
                    $ValTotalTargetMachineCost = round($ValTotalTargetMachineCost);
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetMachineCost; ?></td>
                </tr>
                <tr>
                    <td class="Points">Actual Machine Cost ($)</td>
                    <?php                                 //ACTUAL MACHINE COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalMachineCost = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValMachineCost = trim($RListReport['MachineCost']);
                    if(trim($ValMachineCost) == ""){$ValMachineCost = 0;};
                    $ValTotalMachineCost = $ValTotalMachineCost + $ValMachineCost;
                    $ValMachineCost = number_format((float)$ValMachineCost, 2, '.', ',');
                    $Expense = trim($RListReport['ExpenseAllocation']);
                    ?>
                    <td class="text-right ActualColumn"><span style="cursor:Pointer;" onclick="ShowData('Machine','<?php echo $Expense; ?>')"><?php echo $ValMachineCost; ?></span></td>
                    <?php
                    }
                    $ValTotalMachineCost = round($ValTotalMachineCost);
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalMachineCost; ?></td>
                </tr>
                <tr>
                    <td class="Points">Target Material Cost ($)</td>
                    <?php                               //TARGET MATERIAL COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetMaterialCost = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetMaterialCost = trim($RListReport['TargetMaterialCost']);
                    if(trim($ValTargetMaterialCost) == ""){$ValTargetMaterialCost = "0.00";};
                    $ValTargetMaterialCost = number_format((float)$ValTargetMaterialCost, 2, '.', ',');
                    $ValTotalTargetMaterialCost = @($ValTotalTargetMaterialCost + $ValTargetMaterialCost);
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMaterialCost; ?></td>
                    <?php
                    }
                    $ValTotalTargetMaterialCost = round($ValTotalTargetMaterialCost);
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetMaterialCost; ?></td>
                </tr>
                <tr>                                
                    <td class="Points">Actual Material Cost ($)</td>
                    <?php                              //ACTUAL MATERIAL COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalMaterialCost = 0; $a = 1;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValMaterialCost = trim($RListReport['MaterialCost']);
                    if(trim($ValMaterialCost) == ""){$ValMaterialCost = "0.00";};
                    $ValTotalMaterialCost = @($ValTotalMaterialCost + $ValMaterialCost);
                    $ValMaterialCost = number_format((float)$ValMaterialCost, 2, '.', ',');
                    $Expense = trim($RListReport['ExpenseAllocation']);
                    ?>
                    <td class="text-right ActualColumn"><span style="cursor:Pointer;" onclick="ShowData('Material','<?php echo $Expense; ?>')"><?php echo $ValMaterialCost; ?></span></td>
                    <?php
                    $a++;
                    }
                    $ValTotalMaterialCost = round($ValTotalMaterialCost);
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalMaterialCost; ?></td>
                </tr>
                <tr>
                    <th class="RowResult">Target Cost Per Division ($)</th>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetCost = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalRowTargetCost = round(trim($RListReport['TotalTargetCost']));
                    $ValTargetPeopleCost = trim($RListReport['TargetPeopleCost']);
                    $ValTargetMachineCost = trim($RListReport['TargetMachineCost']);
                    $ValTargetMaterialCost = trim($RListReport['TargetMaterialCost']);
                    $ValTotalRowTargetCost = number_format((float)$ValTotalRowTargetCost, 2, '.', ',');
                    $ValTotalTargetCost = @($ValTotalTargetCost + $ValTotalRowTargetCost);
                    
                    ?>
                    <td class="text-right RowResult TargetTotalColumn"><strong><?php echo $ValTotalRowTargetCost; ?></strong></td>
                    <?php
                    }
                    $ValTotalTargetCost = round($ValTotalTargetCost);
                    $ValTotalTargetCost = number_format((float)$ValTotalTargetCost, 2, '.', ',');
                    ?>
                    <td class="text-right RowResult TargetTotalColumn"><strong><?php echo $ValTotalTargetCost; ?></strong></td>

                </tr>
                <tr>
                    <th class="RowResult">Actual Cost Per Division ($)</th>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalRealCost = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalRowRealCost = trim($RListReport['TotalActualCost']);
                    $ValPeopleCost = trim($RListReport['PeopleCost']);
                    $ValMachineCost = trim($RListReport['MachineCost']);
                    $ValMaterialCost = trim($RListReport['MaterialCost']);
                    $ValTotalRowRealCost = number_format((float)$ValTotalRowRealCost, 2, '.', ',');
                    $ValTotalRealCost = @($ValTotalRealCost + $ValTotalRowRealCost);
                    $ValTotalRealCostf = number_format((float)$ValTotalRealCost, 2, '.', ',');
                    ?>
                    <td class="text-right RowResult ActualTotalColumn"><strong><?php echo $ValTotalRowRealCost; ?></strong></td>
                    <?php
                    }
                    ?>
                    <td class="text-right RowResult ActualTotalColumn"><strong><?php echo $ValTotalRealCostf; ?></strong></td>
                </tr>
                <?php
                if($ValQuoteCategory == 'Quote'){
                ?>
                <tr>
                    <td class="Points">Quantity Target</td>
                    <?php                               
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetQty = $RListReport['QtyTarget'];
                    if(trim($ValTargetQty) == ""){$ValTargetQty = "0";};
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTargetQty; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalQtyTarget = $RListReport['TotalQtyTarget'];
                    ?>
                    <!-- <td class="text-right ActualColumn"><?php/* echo $ValTotalQtyTarget; */?></td> -->
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td class="Points">Quantity Built</td>
                    <?php                               
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValActualQty = $RListReport['QtyQuote'];
                    if(trim($ValActualQty) == ""){$ValActualQty = "0";};
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValActualQty; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalQtyBuilt = $RListReport['TotalQtyActual'];
                    ?>
                    <!-- <td class="text-right TargetColumn"><?php/* echo $ValTotalQtyBuilt; */?></td> -->
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td class="Points">Target Cost * Target Qty ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetCostAndTargetQty = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetCostAndTargetQty = trim($RListReport['TotalTargetCostAndTargetQty']);
                    $ValTotalTargetCostAndTargetQty = $ValTotalTargetCostAndTargetQty + $ValTargetCostAndTargetQty;
                    $ValTargetCostAndTargetQty = number_format((float)$ValTargetCostAndTargetQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetCostAndTargetQty; ?></td>
                    <?php
                    }
                    $ValTotalTargetCostAndTargetQtyf = number_format((float)$ValTotalTargetCostAndTargetQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetCostAndTargetQtyf; ?></td>
                </tr>
                <tr>
                    <td class="Points">Total Target Cost * Actual Qty ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetCostAndActualQty = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetCostAndRealQty = trim($RListReport['TotalTargetCostAndActualQty']);
                    $ValTotalTargetCostAndActualQty = $ValTotalTargetCostAndActualQty + $ValTargetCostAndRealQty;
                    $ValTargetCostAndRealQty = number_format((float)$ValTargetCostAndRealQty,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTargetCostAndRealQty; ?></td>
                    <?php
                    }
                    $ValTotalTargetCostAndActualQtyf = number_format((float)$ValTotalTargetCostAndActualQty,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalTargetCostAndActualQtyf; ?></td>
                </tr>
                <tr>
                    <td class="Points">Total Actual Cost * Actual Qty ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalActualCostAndActualQty = 0;
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValRealCostAndRealQty = trim($RListReport['TotalActualCostAndActualQty']);
                    $ValTotalActualCostAndActualQty = $ValTotalActualCostAndActualQty + $ValRealCostAndRealQty;
                    $ValRealCostAndRealQty = number_format((float)$ValRealCostAndRealQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValRealCostAndRealQty; ?></td>
                    <?php
                    }
                    $ValTotalActualCostAndActualQtyf = number_format((float)$ValTotalActualCostAndActualQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalActualCostAndActualQtyf; ?></td>
                </tr>
                <tr>
                    <th class="RowResult">Cost (%)</th>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetCostAndRealQty = trim($RListReport['TotalTargetCostAndActualQty']);
                    $ValRealCostAndRealQty = trim($RListReport['TotalActualCostAndActualQty']);  
                    $ValCostInPercent = @(($ValTargetCostAndRealQty+($ValTargetCostAndRealQty*0.1)-$ValRealCostAndRealQty)/$ValTargetCostAndRealQty*10)*100;
                    if($ValCostInPercent < 0){$ValCostInPercent = 0;}
                    $ValCostInPercent = number_format((float)$ValCostInPercent,2,'.',',');
                    ?>
                    <td class="text-right RowResult ActualTotalColumn"><strong><?php if ($ValCostInPercent == "nan" | $ValCostInPercent == "inf"){$ValCostInPercent = "-";}; echo $ValCostInPercent; ?></strong></td>
                    <?php
                    }
                    $ValTotalCostInPercent = trim(@(($ValTotalTargetCostAndActualQty+($ValTotalTargetCostAndActualQty*0.1)-$ValTotalActualCostAndActualQty)/$ValTotalTargetCostAndActualQty*10))*100;
                    // if($ValTotalCostInPercent < 0){$ValTotalCostInPercent = 0;}
                    $ValTotalCostInPercent = number_format((float)$ValTotalCostInPercent,2,'.',',');
                    ?>
                    <td class="text-right RowResult ActualTotalColumn"><strong><?php if ($ValTotalCostInPercent == "nan" | $ValTotalCostInPercent == "inf"){$ValTotalCostInPercent = "-";}; echo $ValTotalCostInPercent; ?></strong></td>
                </tr>
                <tr>
                    <td class="Points">WO Mapping Ratio (Closed WO/Total WO)</td>
                    <?php 
                    $totalClosed = $TotalOpen = 0;
                    foreach($arrExpense as $Exp)
                    {
                        $DataClosed = GET_COUNT_CLOSED_WO("Closed",$Exp,$ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$LinkOpt);
					    $DataOpen = GET_COUNT_CLOSED_WO("Open",$Exp,$ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$LinkOpt);
                        $totalClosed = @($totalClosed + $DataClosed);
                        $TotalOpen = @($TotalOpen + $DataOpen);
                        $Ratio = $DataClosed."/".$DataOpen;
                        ?>
                        <td class="text-right"><?php echo $Ratio; ?></td>
                        <?php
                    }
                    $TotalRatio = $totalClosed."/".$TotalOpen;
                    ?>
                    <td class="text-right"><?php echo $TotalRatio; ?></td>
                </tr>
                <?php
                /*
                <tr>
                    <td class="Points">Weight Per Product (Wpp) (%)</td>
                    <?php
                    $Divisi = 'MACHINING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'FABRICATION';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'INJECTION';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'ELECTRONICS';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'ASSEMBLY';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'QUALITY ASSURANCE';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'SHIPPING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValWpp = $RListReport['Wpp'];
                    $ValWpp = number_format((float)$ValWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalWpp = $RListReport['TotalWpp'];
                    $ValTotalWpp = number_format((float)$ValTotalWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalWpp; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                
                <tr>
                    <td class="Points">Done On Time (DoT) (%)</td>
                    <?php                               
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTargetQty = $RListReport['QtyTarget'];
                    $QtyQuote = $RListReport['QtyQuote'];
                    if(trim($ValTargetQty) == ""){$ValTargetQty = "0";};
                    if(trim($ValTargetQty) == ""){$ValTargetQty = "0";};
                    $ValDOT = @($QtyQuote/$ValTargetQty)*100;
                    if($ValDOT > 100){ $ValDOT = 100;}
                    $ValDOT = number_format((float)$ValDOT,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDOT; ?></td>
                    <?php
                    }
                    ?>
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
                    <!-- <td class="text-right ActualColumn"><?php/* echo $ValDOT; ?></td> -->
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td class="Points">Wpp * DoT (%)</td>
                    <?php
                    $Divisi = 'MACHINING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'ASSEMBLY';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'ELECTRONICS';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'QUALITY ASSURANCE';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'SHIPPING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'INJECTION';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'FABRICATION';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValDotAndWpp = $RListReport['DoTAndWpp'];
                    $ValDotAndWpp = number_format((float)$ValDotAndWpp,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValDotAndWpp; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalDot = $RListReport['TotalDoT'];
                    $ValTotalWpp = $RListReport['TotalWpp'];
                    $ValTotalWppAndDot = @($ValTotalDot*$ValTotalWpp)/100;
                    $ValTotalWppAndDot = number_format((float)$ValTotalWppAndDot,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalWppAndDot; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                */
                ?>
                
                <?php
                }
                else{
                ?>
                <tr>
                    <td class="Points">Quantity Built</td>
                    <?php                               
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValActualQty = $RListReport['QtyQuote'];
                    if(trim($ValActualQty) == ""){$ValActualQty = "0";};
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValActualQty; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValTotalQtyBuilt = $RListReport['TotalQtyActual'];
                    ?>
                    <!-- <td class="text-right TargetColumn"><?php/* echo $ValTotalQtyBuilt; */?></td> -->
                    <?php
                    }
                    ?>
                </tr>
                <?php
                }
                ?>
                
            
        </table>
        <span><i>Auto Recalculate at : Everyday 07:00 AM, 11:59 AM, 4:30 PM</i></span>
    </div>
</div>

<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><h5><strong>Top 10 OTS Cost</strong></h5></div>
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
<div class="col-md-12"><h5><strong>Top 10 Material Cost</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                <th class="text-center trowCustom" width="30">No</th>
                <th class="text-center trowCustom" width="130">Part No.</th>
                <th class="text-center trowCustom">Part Description</th>
                <th class="text-center trowCustom" width="130">Qty Usage</th>
                <th class="text-center trowCustom">UOM</th>
                <th class="text-center trowCustom" width="180">Cost ($)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Number=1;
            $QListOTS = GET_TOP10_OVERALL_MATERIAL_COST($ValQuoteCategory,$ValQuoteSelected,$ValClosedTime,$LinkOpt);
            while($RListOTS = sqlsrv_fetch_array($QListOTS))
            {
                $ValPartNo = trim($RListOTS['PartNo']);
                $ValPartDes = trim($RListOTS['PartDescription']);
                $ValQtyUsage = trim($RListOTS['Qty']);
                $ValOTSCost = trim($RListOTS['TotalCost']);
                $UOM = trim($RListOTS['TransactUOM']);
                $ValOTSCost = number_format((float)$ValOTSCost, 2, '.', ',');

            ?>
                <tr>
                <td class="text-center"><?php echo $Number; ?></td>
                <td class="text-center"><?php echo $ValPartNo; ?></td>
                <td class="text-left"><?php echo $ValPartDes; ?></td>
                <td class="text-center"><?php echo $ValQtyUsage; ?></td>
                <td class="text-center"><?php echo $UOM; ?></td>
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
<script>
    $(document).ready(function () {
        var TotalLabor = '<?php echo $ValTotalPeopleCost ?>';
        var TotalMachine = '<?php echo $ValTotalMachineCost ?>';
        var TotalMaterial = '<?php echo $ValTotalMaterialCost ?>';
        var TotalOTS = '<?php echo $ValTotalOTSCost ?>';
        var Category = '<?php echo $ValQuoteCategory ?>';
        var formdata = new FormData();
        formdata.append('TotalLabor', TotalLabor);
        formdata.append('TotalMachine', TotalMachine);
        formdata.append('TotalMaterial', TotalMaterial);
        formdata.append('TotalOTS', TotalOTS);
        formdata.append('Category', Category);
        $.ajax({
                url: 'project/CostTracking/SummaryChartQuote.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                $('#TableSummary').html("");
                $('#TableSummary').html("");
                $('#TableSummary').html("");
                $("#TableSummary").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#TableSummary").html("");
                $('#TableSummary').html("");
                },
                success: function (xaxa) {
                $('#TableSummary').html("");
                $('#TableSummary').hide();
                $('#TableSummary').html(xaxa);
                $('#TableSummary').fadeIn('fast');
                $("#ContentLoadingTT").remove();
                },
                error: function () {
                alert("Request cannot proceed!");
                $('#TableSummary').html("");
                }
            });
        
    });
    function ShowData(Data1,Data2)
    {
        var Cat = '<?php echo $ValQuoteCategory; ?>';
        var Quote = '<?php echo $ValQuoteSelected; ?>';
        var Half = '<?php echo $ValClosedTime; ?>';
        var Tipe = Data1;
        var Expense =  Data2;
        window.location.href = 'project/CostTracking/DownloadRawData.php?Cat='+Cat+'&&Quote='+Quote+'&&Half='+Half+'&&Tipe='+Tipe+'&&Expense='+Expense;
    }
</script>

