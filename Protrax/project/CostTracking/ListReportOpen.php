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
    //     $ValQuoteCategory = $_SESSION['QuoteCategoryPSL'];
    // }
    // if($ValType == "PSM")
    // {
    //     $ValQuoteCategory = $_SESSION['QuoteCategoryPSM'];
    // }
    $LinkOpt = $linkMACHWebTrax;
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
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
    .TargetColumn{color:#ff0000;}
    .ActualColumn{color:#0008ff;}
    .InfoRecalculate{font-style:italic;font-weight:bold;color:#ff0000;text-decoration: underline;text-decoration-color:#fff600;}
    .InfoRecalculate2{font-size:15px;font-weight:bold;color:#ff0000;}
    .RowResult{font-size:14px;}
    .InfoChart{cursor: pointer;color: #337AB7;}
</style>
<script src="project/costtracking/lib/liblistotspart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="col-md-12"><h5><strong>Running Work Order</strong></h5></div>
<div class="col-md-10">Quote Category : <strong><?php echo $ValQuoteCategory; ?></strong>. Quote : <strong><?php echo $ValQuoteSelected; ?></strong></div>
<div class="col-md-2 text-right InfoChart">[ Season Chart ]</div>
<div class="col-md-12">Last Quote Recalculate : <strong><?php echo $DateRecalculateLog; ?></strong></div>
<div class="col-md-12"><i>*) Open Work Order with total labour cost, machine cost and material cost</i></div>
<div class="col-md-12"><i>*) Standard cost machine $3.76 per hour; Standard cost labor $3 per hour</i></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableListReport">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30">No</td>
                    <td class="text-center trowCustom">Cost Allocation</td>
                    <td class="text-center TargetColumn trowCustom" width="80">Target Labor Cost<br>($)</td>
                    <td class="text-center ActualColumn trowCustom" width="80">Actual Labor Cost<br>($)</td>
                    <td class="text-center TargetColumn trowCustom" width="80">Target Machine Cost<br>($)</td>
                    <td class="text-center ActualColumn trowCustom" width="80">Actual Machine Cost<br>($)</td>
                    <td class="text-center TargetColumn trowCustom" width="80">Target Material Cost<br>($)</td>
                    <td class="text-center ActualColumn trowCustom" width="80">Actual Material Cost<br>($)</td>
                    <td class="text-center TargetColumn trowCustom" width="100"><strong>Total Target Cost<br>($)</strong></td>
                    <td class="text-center ActualColumn trowCustom" width="100"><strong>Total Actual Cost<br>($)</strong></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center TargetColumn trowCustom">(a)</td>
                    <td class="text-center ActualColumn trowCustom">(b)</td>
                    <td class="text-center TargetColumn trowCustom">(c)</td>
                    <td class="text-center ActualColumn trowCustom">(d)</td>
                    <td class="text-center TargetColumn trowCustom">(e)</td>
                    <td class="text-center ActualColumn trowCustom">(f)</td>
                    <td class="text-center TargetColumn trowCustom">(a + c + e)</td>
                    <td class="text-center ActualColumn trowCustom">(b + d + f)</td>
                </tr>
            </thead>
            <tbody><?php 
            $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_OPEN($ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkOpt);
            $No = 1; 
            $BolRecalculate = TRUE;
            $BolRecalculate2 = TRUE;
            $ValTempAllocation = "";
            $ValTotalTargetPeopleHour = 0;
            $ValTotalPeopleeHour = 0;
            $ValTotalTargetMachineeHour = 0;
            $ValTotalMachineeHour = 0;
            $ValTotalTargetMaterialCost = 0;
            $ValTotalMaterialCost = 0;
            $ValTotalTargetCost = 0;
            $ValTotalRealCost = 0;
            while($RListReport = mssql_fetch_assoc($QListReport))
            {
                $ValExpense = $RListReport['ExpenseAllocation'];
                $ValTargetPeopleCost = $RListReport['TargetPeopleHour'];
                $ValPeopleCost = $RListReport['PeopleHour'];
                $ValTargetMachineCost = $RListReport['TargetMachineHour'];
                $ValMachineCost = $RListReport['MachineHour'];
                $ValTargetMaterialCost = $RListReport['TargetMaterialHour'];
                $ValMaterialCost = $RListReport['MaterialHour'];
                $ValQtyQuote = $RListReport['QtyQuoteOpen'];
                if(trim($ValTargetPeopleCost) == ""){$ValTargetPeopleCost = "0.00";};
                if(trim($ValPeopleCost) == ""){$ValPeopleCost = "0.00";};
                if(trim($ValTargetMachineCost) == ""){$ValTargetMachineCost = "0.00";};
                if(trim($ValMachineCost) == ""){$ValMachineCost = "0.00";};
                if(trim($ValTargetMaterialCost) == ""){$ValTargetMaterialCost = "0.00";};
                if(trim($ValMaterialCost) == ""){$ValMaterialCost = "0.00";};

                $ValTotalTargetPeopleHour =  $ValTotalTargetPeopleHour + $ValTargetPeopleCost;
                $ValTotalPeopleeHour = $ValTotalPeopleeHour + $ValPeopleCost;
                $ValTotalTargetMachineeHour = $ValTotalTargetMachineeHour + $ValTargetMachineCost;
                $ValTotalMachineeHour = $ValTotalMachineeHour + $ValMachineCost;
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
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-left"><?php echo $ValExpense; ?></td>
                    <!-- <td class="text-right"><?php echo $ValQtyQuote; ?></td> -->
                    <td class="text-right TargetColumn"><?php echo $ValTargetPeopleCost; ?></td>
                    <td class="text-right ActualColumn"><?php echo $ValPeopleCost; ?></td>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMachineCost; ?></td>
                    <td class="text-right ActualColumn"><?php echo $ValMachineCost; ?></td>
                    <td class="text-right TargetColumn"><?php echo $ValTargetMaterialCost; ?></td>
                    <td class="text-right ActualColumn"><?php echo $ValMaterialCost; ?></td>
                    <td class="text-right RowResult TargetColumn"><strong><?php echo $ValTotalRowTargetCost; ?></strong></td>
                    <td class="text-right RowResult ActualColumn"><strong><?php echo $ValTotalRowRealCost; ?></strong></td>
                </tr>
                <?php
                $BolRecalculate2 = TRUE;
                $No++; 
            }
            $ValTotalTargetPeopleHour = number_format((float)$ValTotalTargetPeopleHour, 2, '.', ',');
            $ValTotalPeopleeHour = number_format((float)$ValTotalPeopleeHour, 2, '.', ',');
            $ValTotalTargetMachineeHour = number_format((float)$ValTotalTargetMachineeHour, 2, '.', ',');
            $ValTotalMachineeHour = number_format((float)$ValTotalMachineeHour, 2, '.', ',');
            $ValTotalTargetMaterialCost = number_format((float)$ValTotalTargetMaterialCost, 2, '.', ',');
            $ValTotalMaterialCost = number_format((float)$ValTotalMaterialCost, 2, '.', ',');
            $ValTotalTargetCost = number_format((float)$ValTotalTargetCost, 2, '.', ',');
            $ValTotalRealCost = number_format((float)$ValTotalRealCost, 2, '.', ',');

            
            ?>
               <tr class="ColResult">
                    <?php //<td colspan="3" class="text-center"><strong>Total</strong></td> ?>                    
                    <td colspan="2" class="text-center"><strong>Total</strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetPeopleHour; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalPeopleeHour; ?></strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetMachineeHour; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalMachineeHour; ?></strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetMaterialCost; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalMaterialCost; ?></strong></td>
                    <td class="text-right TargetColumn"><strong><?php echo $ValTotalTargetCost; ?></strong></td>
                    <td class="text-right ActualColumn"><strong><?php echo $ValTotalRealCost; ?></strong></td>
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
        <table class="table table-bordered table-hover" id="TableTotalOTSCostOpen">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30">No</td>
                    <td class="text-center trowCustom">Cost Allocation</td>
                    <td class="text-center trowCustom" width="110"><strong>OTS Cost<br>($)</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php 
            $QListOTSCost = GET_REPORT_RUNNING_COST_OTS_OPEN($ValQuoteCategory,"OPEN",$ValType,$ValQuoteSelected,$LinkOpt);
            $No2 = 1;      
            $ValTotalOTSCost = 0;     
            while($RListOTSCost = mssql_fetch_assoc($QListOTSCost))
            {
                $ValExpenseAllocation = $RListOTSCost['ExpenseAllocation'];
                $ValOTSCost = $RListOTSCost['OTSCost'];
                $ValOTSCostRow = number_format((float)$ValOTSCost, 2, '.', ',');
                $ValTotalOTSCost = $ValTotalOTSCost + $ValOTSCost; 
                ?>
                <tr class="RowCostAllocationOpen">
                    <td class="text-center"><?php echo $No2; ?></td>
                    <td class="text-left"><?php echo $ValExpenseAllocation; ?></td>
                    <td class="text-right"><strong><?php echo $ValOTSCostRow; ?></strong></td>
                </tr>
                <?php
                $No2++;
            }     
                $ValTotalOTSCost = number_format((float)$ValTotalOTSCost, 2, '.', ',');       
                ?>
                <tr>
                    <td colspan="2" class="text-center"><strong>Total OTS Cost</strong></td>
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