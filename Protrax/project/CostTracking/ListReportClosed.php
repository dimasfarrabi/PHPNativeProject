<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");

if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteSelected = htmlspecialchars(trim($_POST['ValQuoteSelected']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    // $LinkOpt = "";
    // if($ValType == "PSL"){$LinkOpt = $linkMACHWebTrax;}
    // if($ValType == "PSM"){$LinkOpt = $linkMACHWebTrax;}    
    // $LinkOpt = $linkMACHWebTrax;
    // if($ValType == "PSL")
    // {
    //     $ValClosedTime = $_SESSION['ClosedTimePSL'];
    //     $ValQuoteCategory = $_SESSION['QuoteCategoryPSL'];
    // }
    // if($ValType == "PSM")
    // {
    //     $ValClosedTime = $_SESSION['ClosedTimePSM'];
    //     $ValQuoteCategory = $_SESSION['QuoteCategoryPSM'];
    // }
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
    .ColResult{font-size:14px;background-color:#F0F0F0;}
    .RowResult{font-size:14px;}
    .TargetColumn{color:#ff0000;}
    .ActualColumn{color:#0008ff;}
    .InfoRecalculate{font-style:italic;font-weight:bold;color:#ff0000;text-decoration: underline;text-decoration-color:#fff600;}
    .InfoRecalculate2{font-size:15px;font-weight:bold;color:#ff0000;}
    .InfoChart{cursor: pointer;color: #337AB7;}
</style>
<script src="project/costtracking/lib/liblistotspart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="col-md-10">Closed Time : <strong><?php echo $ValClosedTime; ?></strong>. Quote Category : <strong><?php echo $ValQuoteCategory; ?></strong>. Quote : <strong><?php echo $ValQuoteSelected; ?></strong></div>
<div class="col-md-2 text-right InfoChart">[ Season Chart ]</div>
<div class="col-md-12">Last Quote Recalculate : <strong><?php echo $DateRecalculateLog; ?></strong></div>
<div class="col-md-12"><i>*) Cost per unit</i></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableListReport">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30">No</td>
                    <td class="text-center trowCustom">Cost Allocation</td>
                    <td colspan="2" class="text-center trowCustom">Qty</td>
                    <td class="text-center TargetColumn trowCustom" width="80">Target Labor Cost<br>($)</td>
                    <td class="text-center ActualColumn trowCustom" width="80">Actual Labor Cost<br>($)</td>
                    <td class="text-center TargetColumn trowCustom" width="80">Target Machine Cost<br>($)</td>
                    <td class="text-center ActualColumn trowCustom" width="80">Actual Machine Cost<br>($)</td>
                    <td class="text-center TargetColumn trowCustom" width="80">Target Material Cost<br>($)</td>
                    <td class="text-center ActualColumn trowCustom" width="80">Actual Material Cost<br>($)</td>
                    <td class="text-center TargetColumn trowCustom" width="100"><strong>Total Target Cost<br>($)</strong></td>
                    <td class="text-center ActualColumn trowCustom" width="100"><strong>Total Actual Cost<br>($)</strong></td>
                    <td class="text-center trowCustom" width="40">Quality<br>Point</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center trowCustom" width="40">Target</td>
                    <td class="text-center trowCustom" width="40">Built</td>
                    <td class="text-center TargetColumn trowCustom">(a)</td>
                    <td class="text-center ActualColumn trowCustom">(b)</td>
                    <td class="text-center TargetColumn trowCustom">(c)</td>
                    <td class="text-center ActualColumn trowCustom">(d)</td>
                    <td class="text-center TargetColumn trowCustom">(e)</td>
                    <td class="text-center ActualColumn trowCustom">(f)</td>
                    <td class="text-center TargetColumn trowCustom">(a + c + e)</td>
                    <td class="text-center ActualColumn trowCustom">(b + d + f)</td>
                    <td></td>
                </tr>
            </thead>
            <tbody><?php 
            $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
            $No = 1;
            $BolRecalculate = TRUE;
            $BolRecalculate2 = TRUE;
            $ValTempAllocation = "";
            $ValTotalTargetPeopleCost = 0;
            $ValTotalPeopleCost = 0;
            $ValTotalTargetMachineCost = 0;
            $ValTotalMachineCost = 0;
            $ValTotalTargetMaterialCost = 0;
            $ValTotalMaterialCost = 0;
            $ValTotalTargetCost = 0;
            $ValTotalRealCost = 0;
            while($RListReport = mssql_fetch_assoc($QListReport))
            {
                $ValExpense = $RListReport['ExpenseAllocation'];
                $ValTargetPeopleCost = $RListReport['TargetPeopleCost'];
                $ValPeopleCost = $RListReport['PeopleCost'];
                $ValTargetMachineCost = $RListReport['TargetMachineCost'];
                $ValMachineCost = $RListReport['MachineCost'];
                $ValTargetMaterialCost = $RListReport['TargetMaterialCost'];
                $ValMaterialCost = $RListReport['MaterialCost'];
                $ValQtyQuote = $RListReport['QtyQuote'];
                $ValQtyTarget = $RListReport['QtyTarget'];
                if(trim($ValTargetPeopleCost) == ""){$ValTargetPeopleCost = "0.00";};
                if(trim($ValPeopleCost) == ""){$ValPeopleCost = "0.00";};
                if(trim($ValTargetMachineCost) == ""){$ValTargetMachineCost = "0.00";};
                if(trim($ValMachineCost) == ""){$ValMachineCost = "0.00";};
                if(trim($ValTargetMaterialCost) == ""){$ValTargetMaterialCost = "0.00";};
                if(trim($ValMaterialCost) == ""){$ValMaterialCost = "0.00";};

                $ValTotalTargetPeopleCost =  $ValTotalTargetPeopleCost + $ValTargetPeopleCost;
                $ValTotalPeopleCost = $ValTotalPeopleCost + $ValPeopleCost;
                $ValTotalTargetMachineCost = $ValTotalTargetMachineCost + $ValTargetMachineCost;
                $ValTotalMachineCost = $ValTotalMachineCost + $ValMachineCost;
                $ValTotalTargetMaterialCost = $ValTotalTargetMaterialCost + $ValTargetMaterialCost;
                $ValTotalMaterialCost = $ValTotalMaterialCost + $ValMaterialCost;
                            
                $ValTotalRowTargetCost = 0;
                $ValTotalRowRealCost = 0;
                $ValTotalRowTargetCost = $ValTargetPeopleCost + $ValTargetMachineCost + $ValTargetMaterialCost;
                $ValTotalRowRealCost = $ValPeopleCost + $ValMachineCost + $ValMaterialCost;
                
                $ValTotalTargetCost = $ValTotalTargetCost + ($ValTargetPeopleCost + $ValTargetMachineCost + $ValTargetMaterialCost);
                $ValTotalRealCost = $ValTotalRealCost + ($ValPeopleCost + $ValMachineCost + $ValMaterialCost);

                $ValTotalRowTargetCost = number_format((float)$ValTotalRowTargetCost, 2, '.', ',');
                $ValTotalRowRealCost = number_format((float)$ValTotalRowRealCost, 2, '.', ',');

                $ValTargetPeopleCost = number_format((float)$ValTargetPeopleCost, 2, '.', ',');
                $ValPeopleCost = number_format((float)$ValPeopleCost, 2, '.', ',');
                $ValTargetMachineCost = number_format((float)$ValTargetMachineCost, 2, '.', ',');
                $ValMachineCost = number_format((float)$ValMachineCost, 2, '.', ',');
                $ValTargetMaterialCost = number_format((float)$ValTargetMaterialCost, 2, '.', ',');
                $ValMaterialCost = number_format((float)$ValMaterialCost, 2, '.', ',');
                $ValQtyQuote = number_format((float)$ValQtyQuote, 0, '.', ',');
                $ValQtyTarget = number_format((float)$ValQtyTarget, 0, '.', ',');

                # check cost allocation double
                if($ValTempAllocation != $ValExpense)
                {
                    $ValTempAllocation = $ValExpense;
                }
                else
                {
                    $BolRecalculate = FALSE;
                    $BolRecalculate2 = FALSE;
                }
                if($BolRecalculate == FALSE && $BolRecalculate2 == FALSE)
                {
                    $ValExpense = $ValExpense.' <span class="InfoRecalculate2">*</span>';
                }

                # data quality point 
                $ValDivID = $RListReport['DivID'];
                $ValProjectID = $RListReport['ProjectID'];
                $QResQualityPoint = GET_DATA_QUALITY_POINT($ValProjectID,$ValDivID,$ValClosedTime,$LinkOpt);
                if(mssql_num_rows($QResQualityPoint) > 0)
                {
                    $RResQualityPoint = mssql_fetch_assoc($QResQualityPoint);
                    $ValGoalAchievement = trim($RResQualityPoint['GoalAchievement']);
                }
                else
                {
                    $ValGoalAchievement = 0;
                }
                $ValGoalAchievement = number_format((float)$ValGoalAchievement, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-left"><?php echo $ValExpense; ?></td>
                    <td class="text-right"><?php echo $ValQtyTarget; ?></td>
                    <td class="text-right"><?php echo $ValQtyQuote; ?></td>
                    <td class="text-right TargetColumn"><?php echo $ValTargetPeopleCost; ?></td>
                    <td class="text-right ActualColumn"><?php echo $ValPeopleCost; ?></td>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMachineCost; ?></td>
                    <td class="text-right ActualColumn"><?php echo $ValMachineCost; ?></td>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMaterialCost; ?></td>
                    <td class="text-right ActualColumn"><?php echo $ValMaterialCost; ?></td>
                    <td class="text-right RowResult TargetColumn"><strong><?php echo $ValTotalRowTargetCost; ?></strong></td>
                    <td class="text-right RowResult ActualColumn"><strong><?php echo $ValTotalRowRealCost; ?></strong></td>
                    <td class="text-center"><?php echo $ValGoalAchievement; ?></td>
                </tr>
                <?php
                $BolRecalculate2 = TRUE;
                $No++; 
            }
            
            
            $ValTotalTargetPeopleCost = number_format((float)$ValTotalTargetPeopleCost, 2, '.', ',');
            $ValTotalPeopleCost = number_format((float)$ValTotalPeopleCost, 2, '.', ',');
            $ValTotalTargetMachineCost = number_format((float)$ValTotalTargetMachineCost, 2, '.', ',');
            $ValTotalMachineCost = number_format((float)$ValTotalMachineCost, 2, '.', ',');
            $ValTotalTargetMaterialCost = number_format((float)$ValTotalTargetMaterialCost, 2, '.', ',');
            $ValTotalMaterialCost = number_format((float)$ValTotalMaterialCost, 2, '.', ',');
            $ValTotalTargetCost = number_format((float)$ValTotalTargetCost, 2, '.', ',');
            $ValTotalRealCost = number_format((float)$ValTotalRealCost, 2, '.', ',');

            ?>
                <tr class="ColResult">
                    <td colspan="4" class="text-center"><strong>Total</strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetPeopleCost; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalPeopleCost; ?></strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetMachineCost; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalMachineCost; ?></strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetMaterialCost; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalMaterialCost; ?></strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetCost; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalRealCost; ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div><?php 
if($BolRecalculate == FALSE)
{
    echo '<div class="col-md-12"><div class="InfoRecalculate">*) Problem found in data above, please running quote recalculate in production apps</div></div>';
}
else
{
    echo '';
}
?>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><h5><strong>OTS Cost By Division</strong></h5></div>
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
                    <td class="text-left"><?php echo $ValExpenseAllocation; ?></td>
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
    echo "";    
}
?>