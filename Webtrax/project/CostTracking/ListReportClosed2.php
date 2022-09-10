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
    $RowRecalculateLog = mssql_num_rows($QDataRecalculate);
    if($RowRecalculateLog != "0")
    {
        while($RDataRecalculate = mssql_fetch_assoc($QDataRecalculate))
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
    .ColSumaryPoints{font-size:12px;}
    .ColSumaryPoints2{font-size:14px;}
    .ColSumaryPointsTotal{font-size:13px;color:#0008ff;background-color:#F0F0F0;}
    .ColResult{font-size:14px;background-color:#F0F0F0;}
    .head{background-color:#F0F0F0;}
    .RowResult{font-size:15px;}
    .TargetColumn{color:#ff0000;}
    .ActualColumn{color:#0008ff;}
    .InfoRecalculate{font-style:italic;font-weight:bold;color:#ff0000;text-decoration: underline;text-decoration-color:#fff600;}
    .InfoRecalculate2{font-size:15px;font-weight:bold;color:#ff0000;}
    .InfoChart{cursor: pointer;color: #337AB7;}
    .card {padding: 10px; box-shadow: 0px 1px 3px #888888;background:#FFFFFF;width: 80%;}
    .sticky {position: sticky; top: 0; width: 80%;z-index:100;}
    .header {padding: 5px 10px;background:#FFFFFF;color: #555;box-shadow: 0px 3px 5px #888888;}
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
<div class="col-md-12">
<br></br>
<div><h5><strong>Achievement</strong></h5></div>
<div class="table_summary">
<table id="vertical-2" class="table table-bordered table-hover">
<tr>
    <th class="ColSumaryPoints" width="180"><strong>Cost Points (%)</strong></th>
    <?php
    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
    while($RListReport = mssql_fetch_assoc($QListReport))
    {
    $ValTotalActualCostAndActualQty = $RListReport['TotalActualCostAndActualQty'];
    $ValTotalTargetCostAndActualQty = $RListReport['TotalTargetCostAndActualQty'];
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
while($RListReport = mssql_fetch_assoc($QListReport))
{
$ValTotalDot = $RListReport['TotalDoT'];
$ValTotalDot = number_format((float)$ValTotalDot,2,'.',',');
?>
<td class="ColSumaryPoints"><strong><?php echo $ValTotalDot; ?></strong></td>
<?php
}
?>    
  </tr>  
  <tr>
<?php
$ValQualityPoints = 0;
$QListReport = GET_AVG_QUALITY_POINTS($ValClosedTime,$ValQuoteSelected,$LinkOpt);
while($RListReport = mssql_fetch_assoc($QListReport))
{
$ValQualityPoints = $RListReport['AvgQuality'];
if(trim($ValQualityPoints) == ""){$ValQualityPoints = "0.00";};
$ValQualityPoints = number_format((float)$ValQualityPoints,2,'.',',');
?>
  <th class="ColSumaryPoints"><strong>Quality Points (%)</strong></th>  
  <td class="ColSumaryPoints"><strong><?php echo $ValQualityPoints; ?></strong></td> 
<?php
}
?> 
<?php
	$ValTotalBonus= @($ValTotalCostInPercent*$ValTotalDot*$ValQualityPoints);
	$ValTotalBonus = number_format((float)$ValTotalBonus,2,'.',',');
?>
  </tr>
  <tr>
  <th class="ColSumaryPointsTotal"><strong>Total</strong></th>  
  <td class="ColSumaryPointsTotal"><strong><?php echo $ValTotalBonus; ?></strong></td>  
  </tr>
</table>
</div>

<br></br>
<div><h5><strong>Product Cost Without OTS Cost</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="vertical-2">
                <tr>
                    <th class="head trowCustom">COST ALLOCATION</th>      
                    <?php                                  // COST ALLOCATION
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValExpense = $RListReport['ExpenseAllocation'];
                    ?>
                    <td width="100" class="text-center head trowCustom"><strong><?php echo $ValExpense; ?></strong></td>
                    <?php
                    }
                    ?>
                    <td width="100" class="text-center head trowCustom"><strong>TOTAL</strong></td>
                </tr>
                <tr>   
                    <td>Target Labor Cost ($)</td>
                    <?php                                //TARGET LABOR COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetPeopleCost = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTargetPeopleCost = $RListReport['TargetPeopleCost'];
                    if(trim($ValTargetPeopleCost) == ""){$ValTargetPeopleCost = "0.00";};
                    $ValTotalTargetPeopleCost =  @($ValTotalTargetPeopleCost + $ValTargetPeopleCost);
                    $ValTargetPeopleCost = number_format((float)$ValTargetPeopleCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetPeopleCost; ?></td>
                    <?php
                    }
                    $ValTotalTargetPeopleCost = number_format((float)$ValTotalTargetPeopleCost, 2, '.', ',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetPeopleCost; ?></td>
                </tr>
                <tr>
                    <td>Actual Labor Cost ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalPeopleCost = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValPeopleCost = $RListReport['PeopleCost'];
                    if(trim($ValPeopleCost) == ""){$ValPeopleCost = "0.00";};
                    $ValTotalPeopleCost = @($ValTotalPeopleCost + $ValPeopleCost);
                    $ValPeopleCost = number_format((float)$ValPeopleCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValPeopleCost; ?></td>
                    <?php
                    }
                    $ValTotalPeopleCost = number_format((float)$ValTotalPeopleCost, 2, '.', ',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalPeopleCost; ?></td>
                </tr>
                <tr>
                    <td>Target Machine Cost ($)</td>
                    <?php                                 //TARGET MACHINE COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetMachineCost = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTargetMachineCost = $RListReport['TargetMachineCost'];
                    if(trim($ValTargetMachineCost) == ""){$ValTargetMachineCost = "0.00";};
                    $ValTotalTargetMachineCost = $ValTotalTargetMachineCost + $ValTargetMachineCost;
                    $ValTargetMachineCost = number_format((float)$ValTargetMachineCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMachineCost; ?></td>
                    <?php
                    }
                    $ValTotalTargetMachineCost = number_format((float)$ValTotalTargetMachineCost, 2, '.', ',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetMachineCost; ?></td>
                </tr>
                <tr>
                    <td>Actual Machine Cost ($)</td>
                    <?php                                 //ACTUAL MACHINE COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalMachineCost = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValMachineCost = $RListReport['MachineCost'];
                    if(trim($ValMachineCost) == ""){$ValMachineCost = "0.00";};
                    $ValTotalMachineCost = $ValTotalMachineCost + $ValMachineCost;
                    $ValMachineCost = number_format((float)$ValMachineCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValMachineCost; ?></td>
                    <?php
                    }
                    $ValTotalMachineCost = number_format((float)$ValTotalMachineCost, 2, '.', ',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalMachineCost; ?></td>
                </tr>
                <tr>
                    <td>Target Material Cost ($)</td>
                    <?php                               //TARGET MATERIAL COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetMaterialCost = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTargetMaterialCost = $RListReport['TargetMaterialCost'];
                    if(trim($ValTargetMaterialCost) == ""){$ValTargetMaterialCost = "0.00";};
                    $ValTotalTargetMaterialCost = @($ValTotalTargetMaterialCost + $ValTargetMaterialCost);
                    $ValTargetMaterialCost = number_format((float)$ValTargetMaterialCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMaterialCost; ?></td>
                    <?php
                    }
                    $ValTotalTargetMaterialCost = number_format((float)$ValTotalTargetMaterialCost, 2, '.', ',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetMaterialCost; ?></td>
                </tr>
                <tr>                                
                    <td>Actual Material Cost ($)</td>
                    <?php                              //ACTUAL MATERIAL COST
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalMaterialCost = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValMaterialCost = $RListReport['MaterialCost'];
                    if(trim($ValMaterialCost) == ""){$ValMaterialCost = "0.00";};
                    $ValTotalMaterialCost = @($ValTotalMaterialCost + $ValMaterialCost);
                    $ValMaterialCost = number_format((float)$ValMaterialCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValMaterialCost; ?></td>
                    <?php
                    }
                    $ValTotalMaterialCost = number_format((float)$ValTotalMaterialCost, 2, '.', ',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalMaterialCost; ?></td>
                </tr>
                <tr>
                    <th class="RowResult">Target Cost Per Division ($)</th>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalTargetCost = 0;
                    $ValTotalTargetCostf = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalRowTargetCost = $RListReport['TotalTargetCost'];
                    $ValTargetPeopleCost = $RListReport['TargetPeopleCost'];
                    $ValTargetMachineCost = $RListReport['TargetMachineCost'];
                    $ValTargetMaterialCost = $RListReport['TargetMaterialCost'];
                    $ValTotalTargetCost = $ValTotalTargetCost + ($ValTargetPeopleCost + $ValTargetMachineCost + $ValTargetMaterialCost);
                    $ValTotalRowTargetCost = number_format((float)$ValTotalRowTargetCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right RowResult TargetColumn"><strong><?php echo $ValTotalRowTargetCost; ?></strong></td>
                    <?php
                    }
                    $ValTotalTargetCost = number_format((float)$ValTotalTargetCost, 2, '.', ',');
                    ?>
                    <td class="text-right RowResult TargetColumn"><strong><?php echo $ValTotalTargetCost; ?></strong></td>

                </tr>
                <tr>
                    <th class="RowResult">Actual Cost Per Division ($)</th>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    $ValTotalRealCost = 0;
                    $ValTotalRealCostf = 0;
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalRowRealCost = $RListReport['TotalActualCost'];
                    $ValPeopleCost = $RListReport['PeopleCost'];
                    $ValMachineCost = $RListReport['MachineCost'];
                    $ValMaterialCost = $RListReport['MaterialCost'];
                    $ValTotalRealCost = $ValTotalRealCost + ($ValPeopleCost + $ValMachineCost + $ValMaterialCost);
                    $ValTotalRowRealCost = number_format((float)$ValTotalRowRealCost, 2, '.', ',');
                    
                    ?>
                    <td class="text-right RowResult ActualColumn"><strong><?php echo $ValTotalRowRealCost; ?></strong></td>
                    <?php
                    }
                    $ValTotalRealCost = number_format((float)$ValTotalRealCost, 2, '.', ',');
                    ?>
                    <td class="text-right RowResult ActualColumn"><strong><?php echo $ValTotalRealCost; ?></strong></td>
                </tr>
                <tr>
                    <td>Target Cost * Target Qty ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTargetCostAndTargetQty = $RListReport['TotalTargetCostAndTargetQty'];
                    $ValTargetCostAndTargetQty = number_format((float)$ValTargetCostAndTargetQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTargetCostAndTargetQty; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalTargetCostAndTargetQty = $RListReport['TotalTargetCostAndTargetQty'];
                    $ValTotalTargetCostAndTargetQty = number_format((float)$ValTotalTargetCostAndTargetQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalTargetCostAndTargetQty; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Total Target Cost * Actual Qty ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTargetCostAndRealQty = $RListReport['TotalTargetCostAndActualQty'];
                    $ValTargetCostAndRealQty = number_format((float)$ValTargetCostAndRealQty,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTargetCostAndRealQty; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalTargetCostAndActualQty = $RListReport['TotalTargetCostAndActualQty'];
                    $ValTotalTargetCostAndActualQty = number_format((float)$ValTotalTargetCostAndActualQty,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalTargetCostAndActualQty; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Total Actual Cost * Actual Qty ($)</td>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValRealCostAndRealQty = $RListReport['TotalActualCostAndActualQty'];
                    // $ValRealCostAndRealQty = number_format((float)$ValRealCostAndRealQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValRealCostAndRealQty; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalActualCostAndActualQty = $RListReport['TotalActualCostAndActualQty'];
                    $ValTotalActualCostAndActualQty = number_format((float)$ValTotalActualCostAndActualQty,2,'.',',');
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalActualCostAndActualQty; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <th class="RowResult">Cost (%)</th>
                    <?php
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTargetCostAndRealQty = $RListReport['TotalTargetCostAndActualQty'];
                    $ValRealCostAndRealQty = $RListReport['TotalActualCostAndActualQty'];  
                    $ValCostInPercent = @(($ValTargetCostAndRealQty+($ValTargetCostAndRealQty*0.1)-$ValRealCostAndRealQty)/$ValTargetCostAndRealQty*10)*100;
                    
                    $ValCostInPercent = number_format((float)$ValCostInPercent,2,'.',',');
                    ?>
                    <td class="text-right RowResult ActualColumn"><strong><?php if ($ValCostInPercent == "nan" | $ValCostInPercent == "inf"){$ValCostInPercent = "-";}; echo $ValCostInPercent; ?></strong></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalActualCostAndActualQty = $RListReport['TotalActualCostAndActualQty'];
                    $ValTotalTargetCostAndActualQty = $RListReport['TotalTargetCostAndActualQty'];
                    $ValTotalCostInPercent = @(($ValTotalTargetCostAndActualQty+($ValTotalTargetCostAndActualQty*0.1)-$ValTotalActualCostAndActualQty)/$ValTotalTargetCostAndActualQty*10)*100;
                    $ValTotalCostInPercent = number_format((float)$ValTotalCostInPercent,2,'.',',');
                    ?>
                    <td class="text-right RowResult ActualColumn"><strong><?php echo $ValTotalCostInPercent; ?></strong></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Weight Per Product (Wpp) (%)</td>
                    <?php
                    $Divisi = 'MACHINING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    <td>Done On Time (DoT) (%)</td>
                    <?php
                    $Divisi = 'MACHINING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'FABRICATION';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'INJECTION';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'ELECTRONICS';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'ASSEMBLY';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'QUALITY ASSURANCE';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $Divisi = 'SHIPPING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValDot = $RListReport['DoT'];
                    $ValDot = number_format((float)$ValDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValDot; ?></td>
                    <?php
                    }
                    ?>
                    <?php
                    $QListReport = GET_TOTAL_COLUMN($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalDot = $RListReport['TotalDoT'];
                    $ValTotalDot = number_format((float)$ValTotalDot,2,'.',',');
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalDot; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Wpp * DoT (%)</td>
                    <?php
                    $Divisi = 'MACHINING';
                    $QListReport = GET_WPP_AND_DOT($ValClosedTime,$ValQuoteCategory,$Divisi,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                <tr>
                    <td>Quantity Target</td>
                    <?php                               
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalQtyTarget = $RListReport['TotalQtyTarget'];
                    ?>
                    <td class="text-right ActualColumn"><?php echo $ValTotalQtyTarget; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td>Quantity Built</td>
                    <?php                               
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
                    while($RListReport = mssql_fetch_assoc($QListReport))
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
                    while($RListReport = mssql_fetch_assoc($QListReport))
                    {
                    $ValTotalQtyBuilt = $RListReport['TotalQtyActual'];
                    ?>
                    <td class="text-right TargetColumn"><?php echo $ValTotalQtyBuilt; ?></td>
                    <?php
                    }
                    ?>
                </tr>
            
        </table>
    </div>
</div>



<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><h5><strong>Product OTS Cost </strong></h5></div>
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
            while($RListOTSCost = mssql_fetch_assoc($QListOTSCost))
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
                $ValTotalOTSCost = number_format((float)$ValTotalOTSCost, 2, '.', ',');       
                ?>
                <tr>
                    <td colspan="3" class="text-center"><strong>Total OTS Cost per Unit</strong></td>
                    <td class="text-right"><strong><?php echo $ValTotalOTSCost; ?></strong></td>
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

