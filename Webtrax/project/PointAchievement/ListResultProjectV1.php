
<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValPM = htmlspecialchars(trim($_POST['ValPM']), ENT_QUOTES, "UTF-8");
    $ValRolesEncrypt = htmlspecialchars(trim($_POST['ValRoles']), ENT_QUOTES, "UTF-8");
    $ValRoles = base64_decode(base64_decode($ValRolesEncrypt));
    $ValLocation = "SALATIGA";
    $ArrVarRoles = explode("#",$ValRoles);
    $ValRolesPosition = $ArrVarRoles[1];  
    echo $ValClosedTime.">>".$ValPM.">>".$ValLocation.">>".$ValRolesPosition;
    
    $ArrDataResult = array();
    if($ValRolesPosition == "PM")
    {
        $ValPosition = "PRODUCTION MANAGER";
        $QDataPSL = GET_TOTAL_TIMETRACK_LEADER($ValClosedTime,$ValPM,$linkMACHWebTrax);
    }
    elseif($ValRolesPosition == "DM")
    {
        $ValPosition = "DIVISION MANAGER";
        $QDataPSL = GET_TOTAL_TIMETRACK_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);
    }
    else 
    {
        $ValPosition = "DIRECTOR";
		$QDataPSL = GET_TOTAL_TIMETRACK_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);
    }
/*
    while($RDataPSL = sqlsrv_fetch_array($QDataPSL))
        {
            $ValQuotePSL = trim($RDataPSL['Quote']);
            $ValExpenseAllocationPSL = trim($RDataPSL['ExpenseAllocation']);
            $ValStabilizePSL = trim($RDataPSL['Stabilize']);
            $ValTotalStabilizePSL = trim($RDataPSL['TotalStabilize']);
            $TempArray = array(
                "Stabilize" => $ValStabilizePSL,
                "TotalStabilize" => $ValTotalStabilizePSL,
                "Quote" => $ValQuotePSL,
                "ExpenseAllocation" => $ValExpenseAllocationPSL
            );
            array_push($ArrDataResult,$TempArray);
        }
    
$ValTotalPoints = 0;    
?>
<style>
    .card {padding: 10px; box-shadow: 0px 1px 3px #888888;background:#FFFFFF;width: 100%;}
    .sticky {position: sticky; top: 0; width: 100%;z-index:100;}
    .header {padding: 5px 10px;background:#FFFFFF;color: #555;}
    .header2 {padding: 5px 10px;background:#FFFFFF;color: #555;}
</style>



<?php
if($ValClosedTime == "OPEN")
{
    ?>
    <div class="col-md-12 card" id="myHeader"></div>
    <div class="col-md-12">
        <br>
        <div><h5><strong>Time Allocation</strong></h5></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="ListTableProjectPM">
                    <thead class="theadCustom">
                        <tr>
                            <th class="text-center trowCustom" width="20">No</th>
                            <th class="text-center trowCustom">Quote</th>
                            <th class="text-center trowCustom">Cost Allocation</th>
                            <th class="text-center trowCustom" width="100">Time Spent<br>(Hour)</th>
                            <th class="text-center trowCustom" width="100">Time Spent<br>(%)</th>
                        </tr>
                    </thead>
                    <tbody class=" PointerListTT"><?php
                    $No = 1;
                    $TotalStabilize = 0;
                    $TotalStablizePercentage = 0;
                    while($RDataPSL = sqlsrv_fetch_array($QDataPSL))
                    {
                        $ValQuote = trim($RDataPSL['Quote']);
                        $ValCostAllocation = trim($RDataPSL['ExpenseAllocation']);
                        $ValTimeSpent = trim($RDataPSL['Stabilize']);
                        $ValTotalStabilizePSL = trim($RDataPSL['TotalStabilize']);
                    
                            $TotalStabilize = $TotalStabilize + $ValTimeSpent;
                            $ValTimeSpent = number_format((float)$ValTimeSpent, 2, '.', ',');
                            $ValPercentage = (float)(trim($DataResult['Stabilize']) / trim($DataResult['TotalStabilize']))*100;
                            $TotalStablizePercentage = $TotalStablizePercentage + $ValPercentage;
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            $ValDataRowEncrypt = base64_encode(base64_encode($ValLocation."#".$ValClosedTime."#".$ValPM."#".$ValQuote."#".$ValCostAllocation."#".$ValPosition));
                        ?><tr class="FloatTT" data-float="<?php echo $ValDataRowEncrypt; ?>">
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-left"><?php echo $ValQuote; ?></td>
                            <td class="text-left"><?php echo $ValCostAllocation; ?></td>
                            <td class="text-right"><?php echo $ValTimeSpent; ?></td>
                            <td class="text-right"><?php echo $ValPercentage; ?></td>
                        </tr><?php
                            $No++;
                        $TotalStabilize = number_format((float)$TotalStabilize, 2, '.', ',');
                        $TotalStablizePercentage = number_format((float)$TotalStablizePercentage, 2, '.', ',');
                    }         
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center theadCustom"><strong>TOTAL</strong></td>
                            <td class="text-right"><?php echo $TotalStabilize; ?></td>
                            <td class="text-right"><?php echo $TotalStablizePercentage; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php
}
else
{
    ?>
    <div class="col-md-12 card" id="myHeader"></div>
    <?php
    if($ValRolesPosition == "PM")
    {
        ?>

        <div class="col-md-12">
        <br>
        <div><h5><strong>Cost Points</strong></h5></div>
        <?php
        $QListReport = GET_COST_PM($ValClosedTime,$ValPM,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $TargetCostTargetQtyPM = $RListReport['TargetCostTargetQtyPM'];
            $TargetCostActualQtyPM = $RListReport['TargetCostActualQtyPM'];
            $ActualCostActualQtyPM = $RListReport['ActualCostActualQtyPM'];
            $ValTotalCostPM = @(($TargetCostActualQtyPM+($TargetCostActualQtyPM*0.1)-$ActualCostActualQtyPM)/$TargetCostActualQtyPM*10)*100;
            $TargetCostTargetQtyPM = number_format((float)$TargetCostTargetQtyPM, 2, '.', ',');
            $TargetCostActualQtyPM = number_format((float)$TargetCostActualQtyPM, 2, '.', ',');
            $ActualCostActualQtyPM = number_format((float)$ActualCostActualQtyPM, 2, '.', ',');
            $ValTotalCostPM = number_format((float)$ValTotalCostPM, 2, '.', ',');
        }
        ?>
        <div class="table_summary">
        <table class="table table-bordered table-hover">
            <tr>
                <th class="text-center trowCustom" width = "300">Target Cost * Target Qty ($)</th>
                <td class="text-left "><?php echo $TargetCostTargetQtyPM; ?></td>
            </tr>
            <tr>
                <th class="text-center trowCustom" width = "300">Target Cost * Actual Qty ($)</th>
                <td class="text-left "><?php echo $TargetCostActualQtyPM; ?></td>
            </tr>
            <tr>
                <th class="text-center trowCustom" width = "300">Actual Cost * Actual Qty ($)</th>
                <td class="text-left "><?php echo $ActualCostActualQtyPM; ?></td>
            </tr>
            <tr>
                <th class="text-center theadCustom" width = "300"><strong>Cost Points (%)</strong></th>
                <td class="text-left "><strong><?php echo $ValTotalCostPM; ?></strong></td>
            </tr>
        </table>
        <br></br>
        <div><h5><strong>Quantity Points</strong></h5></div>
        <div class="table_summary">
        <table class="table table-bordered table-hover" id="TablePM">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width="20">No</th>
                <th class="text-center trowCustom">Quote</th>
                <th class="text-center trowCustom" width = "110">Done On Time PM (%)</th>
                <th class="text-center trowCustom" width = "120">Weight Per Product PM (%)</th>
                <th class="text-center trowCustom" width = "120">Quantity Points PM (%)</th>
                <th class="text-center trowCustom" width = "120">Quality Points PM (%)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $ValTotalWppAndDoT = 0;
        $ValTotalValQPPM = 0;
        $Num = 1;
        $QListReport = GET_WPP_TOTAL_PM($ValClosedTime,$ValPM,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $ValTotalPM = $RListReport['TotalPM'];
        }
        $QListReport = GET_WPP_DOT_PM($ValClosedTime,$ValPM,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $ValQuote = $RListReport['Quote'];
            $ValDoTPM = $RListReport['DoTPM'];
            $ValtctqPM = $RListReport['tctqPM'];
            $ValQuality = $RListReport['QPPM'];
            if(trim($ValQuality) == ""){$ValQuality = "0.00";};
            $ValWppPM = @($ValtctqPM/$ValTotalPM)*100;
            $ValQualityPointPM = ($ValQuality*$ValWppPM)/100;
            $ValQuantityPointPM = ($ValDoTPM*$ValWppPM)/100;
            $ValDoTPM = number_format((float)$ValDoTPM, 2, '.', ',');
            $ValWppPM = number_format((float)$ValWppPM, 2, '.', ',');
            $ValQualityPointPM = number_format((float)$ValQualityPointPM, 2, '.', ',');
            $ValQuantityPointPM = number_format((float)$ValQuantityPointPM, 2, '.', ',');
        ?>  
        <tr>
            <td class="text-left TargetColumn"><?php echo $Num; ?></td>
            <td class="text-left TargetColumn"><?php echo $ValQuote; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValDoTPM; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValWPPPM; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQuantityPointPM; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQualityPointPM; ?></td>
        </tr>

        <?php
        $ValTotalQuantityPointPM = $ValTotalQuantityPointPM+$ValQuantityPointPM;
        $ValTotalQualityPointPM = $ValTotalQualityPointPM+$ValQualityPointPM;
        $ValTotalQuantityPointPM = number_format((float)$ValTotalQuantityPointPM, 2, '.', ',');
        $ValTotalQualityPointPM = number_format((float)$ValTotalQualityPointPM, 2, '.', ',');
        $Num++;
        }
        ?>
        <tr>
        <td colspan="4" class="text-center theadCustom"><strong>TOTAL</strong></td>
        <td class="text-right "><strong><?php echo $ValTotalQuantityPointPM; ?></strong></td>
        <td class="text-right "><strong><?php echo $ValTotalQualityPointPM; ?></strong></td>
        </tr>
        </table>
        <br></br>
        <div><h5><strong>Quality Points</strong></h5></div>
        <div class="table_summary">
        <table class="table table-bordered table-hover">
        <thead class="theadCustom">
        <tr>
            <th class="text-center trowCustom" width="20">No</th>
            <th class="text-center trowCustom" width = "350">Quote</th>
            <?php
            $QListReport = GET_DIVISION($ValClosedTime,$linkMACHWebTrax);
            while($RListReport = sqlsrv_fetch_array($QListReport))
            {
                $ValDivision = $RListReport['Division'];
            ?>
            <td class="text-center trowCustom"  width = "80"><strong><?php echo $ValDivision; ?><strong></td>
            <?php
            }
            ?>
        </tr>
        </thead>
        <tbody>
            <?php
            $Nomor = 1;
            $QListReport = GET_QUALITY_PER_DIVISION_PM($ValClosedTime,$ValPM,$linkMACHWebTrax);
            while($RListReport = sqlsrv_fetch_array($QListReport))
            {
                $ValQuoteName = $RListReport['Quote'];
                $ValMACHINING = $RListReport['Machining'];
                $ValFABRICATION = $RListReport['Fabrication'];
                $ValINJECTION = $RListReport['Injection'];
                $ValASSEMBLY = $RListReport['Assembly'];
                $ValELECTRONICS = $RListReport['Electronics'];
                $ValQA = $RListReport['QA'];
                if(trim($ValMACHINING) == ""){$ValMACHINING = "0.00";};
                if(trim($ValFABRICATION) == ""){$ValFABRICATION = "0.00";};
                if(trim($ValINJECTION) == ""){$ValINJECTION = "0.00";};
                if(trim($ValASSEMBLY) == ""){$ValASSEMBLY = "0.00";};
                if(trim($ValELECTRONICS) == ""){$ValELECTRONICS = "0.00";};
                if(trim($ValQA) == ""){$ValQA = "0.00";};
                $ValMACHINING = number_format((float)$ValMACHINING, 2, '.', ',');
                $ValFABRICATION = number_format((float)$ValFABRICATION, 2, '.', ',');
                $ValINJECTION = number_format((float)$ValINJECTION, 2, '.', ',');
                $ValASSEMBLY = number_format((float)$ValASSEMBLY, 2, '.', ',');
                $ValELECTRONICS = number_format((float)$ValELECTRONICS, 2, '.', ',');
                $ValQA = number_format((float)$ValQA, 2, '.', ',');
            ?>
            <tr>
            <td class="text-left TargetColumn"><?php echo $Nomor; ?></td>
            <td class="text-left TargetColumn"><?php echo $ValQuoteName; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValMACHINING; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValFABRICATION; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValINJECTION; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValASSEMBLY; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValELECTRONICS; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQA; ?></td>
            </tr>
        <?php
            $Nomor++;
            }
            $ValQtyCostPM = ($ValTotalCostPM * $ValTotalQuantityPointPM)/100;
            if($ValQtyCostPM>100){$ValQtyCostPM=100;};
            $ValTotalPoints = ($ValQtyCostPM * $ValTotalQualityPointPM)/100;
            if($ValTotalPoints>100){$ValTotalPoints=100;};
            $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
            ?>
        </tbody>
        </table>
        <?php
    }
    if($ValRolesPosition == "DM")
    {

        ?>
        <div class="col-md-12">
        <br>
        <div><h5><strong>Cost Points</strong></h5></div>
        <div class="table_summary">
        <table class="table table-bordered table-hover">
        <?php
        $QListReport = GET_COST_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $TargetCostTargetQtyDM = $RListReport['TargetCostTargetQtyDM'];
            $TargetCostActualQtyDM = $RListReport['TargetCostActualQtyDM'];
            $ActualCostActualQtyDM = $RListReport['ActualCostActualQtyDM'];
            $ValTotalCostDM = @(($TargetCostActualQtyDM+($TargetCostActualQtyDM*0.1)-$ActualCostActualQtyDM)/$TargetCostActualQtyDM*10)*100;
            $TargetCostTargetQtyDM = number_format((float)$TargetCostTargetQtyDM, 2, '.', ',');
            $TargetCostActualQtyDM = number_format((float)$TargetCostActualQtyDM, 2, '.', ',');
            $ActualCostActualQtyDM = number_format((float)$ActualCostActualQtyDM, 2, '.', ',');
            $ValTotalCostDM = number_format((float)$ValTotalCostDM, 2, '.', ',');
        }
        ?>
            <tr>
                <th class="text-center trowCustom" width = "300">Target Cost * Target Qty ($)</th>
                <td class="text-left "><?php echo $TargetCostTargetQtyDM; ?></td>
            </tr>
            <tr>
                <th class="text-center trowCustom" width = "300">Target Cost * Actual Qty ($)</th>
                <td class="text-left "><?php echo $TargetCostActualQtyDM; ?></td>
            </tr>
            <tr>
                <th class="text-center trowCustom" width = "300">Actual Cost * Actual Qty ($)</th>
                <td class="text-left "><?php echo $ActualCostActualQtyDM; ?></td>
            </tr>
            <tr>
                <th class="text-center theadCustom" width = "300"><strong>Cost Points (%)</strong></th>
                <td class="text-left "><strong><?php echo $ValTotalCostDM; ?></strong></td>
            </tr>
        </table>


        <br>
        <div><h5><strong>Quantity and Quality Points</strong></h5></div>
        <div class="table_summary">
        <table class="table table-bordered table-hover">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width="20">No</th>
                <th class="text-center trowCustom">Quote</th>
                <th class="text-center trowCustom">Division</th>
                <th class="text-center trowCustom" width = "110">Done On Time DM (%)</th>
                <th class="text-center trowCustom" width = "120">Weight Per Product DM (%)</th>
                <th class="text-center trowCustom" width = "120">Quantity Points DM (%)</th>
                <th class="text-center trowCustom" width = "110">Quality Points DM (%)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $ValTotalWppAndDoTDM = 0;
        $ValTotalQPDM = 0;
        $No = 1;
        $QListReport = GET_WPP_DOT_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $ValQuoteDM = $RListReport['Quote'];
            $ValDivision = $RListReport['ExpenseAllocation'];
            $ValDoTDM = $RListReport['DoTDM'];
            $ValtctqDM = $RListReport['TotalTargetCostAndTargetQty'];
            $ValTotalDM = $RListReport['TotalDM'];
            $ValQualityDM = $RListReport['QPDM'];
            if(trim($ValQualityDM) == ""){$ValQualityDM = "0.00";};
            $ValWppDM = @($ValtctqDM/$ValTotalDM)*100;
            $ValQualityPointDM = ($ValQualityDM*$ValWppDM)/100;
            $ValQuantityPointDM = ($ValDoTDM*$ValWppDM)/100;
            $ValDoTDM = number_format((float)$ValDoTDM, 2, '.', ',');
            $ValWppDM = number_format((float)$ValWppDM, 2, '.', ',');
            $ValQualityPointDM = number_format((float)$ValQualityPointDM, 2, '.', ',');
            $ValQuantityPointDM = number_format((float)$ValQuantityPointDM, 2, '.', ',');

        ?>
        <tr>
            <td class="text-left TargetColumn"><?php echo $No; ?></td>
            <td class="text-left TargetColumn"><?php echo $ValQuoteDM; ?></td>
            <td class="text-left TargetColumn"><?php echo $ValDivision; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValDoTDM; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValWppDM; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQuantityPointDM; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQualityPointDM; ?></td>
        </tr>
        <?php
        $ValTotalQuantityPointDM = $ValTotalQuantityPointDM + $ValQuantityPointDM;
        $ValTotalQualityPointDM = $ValTotalQualityPointDM + $ValQualityPointDM;
        $ValTotalQuantityPointDM = number_format((float)$ValTotalQuantityPointDM, 2, '.', ',');
        $ValTotalQualityPointDM = number_format((float)$ValTotalQualityPointDM, 2, '.', ',');
        $No++;
        }
        $ValQtyCost = ($ValTotalCostDM * $ValTotalQuantityPointDM)/100;
        if($ValQtyCost>100){$ValQtyCost=100;};
        $ValTotalPoints = ($ValQtyCost * $ValTotalQualityPointDM)/100;
        if($ValTotalPoints>100){$ValTotalPoints=100;};
        $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
        ?>
        <tr>
        <td colspan="4" class="text-center theadCustom"><strong>TOTAL</strong></td>
        <td class="text-right "><strong><strong><?php echo $ValTotalQuantityPointDM; ?></strong></td>
        <td class="text-right "><strong><strong><?php echo $ValTotalQualityPointDM; ?></strong></td>
        </tr>
        </table>
        <?php

    }
    if($ValRolesPosition == "DIR")
    {
        ?>
        <div class="col-md-12">
        <br>
        <div><h5><strong>Cost Points</strong></h5></div>
        <div class="table_summary">
        <table class="table table-bordered table-hover">
        <?php
        $QListReport = GET_COST_DIR($ValClosedTime,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $TargetCostTargetQtyDIR = $RListReport['TargetCostTargetQtyDIR'];
            $TargetCostActualQtyDIR = $RListReport['TargetCostActualQtyDIR'];
            $ActualCostActualQtyDIR = $RListReport['ActualCostActualQtyDIR'];
            $ValTotalCostDIR = @(($TargetCostActualQtyDIR+($TargetCostActualQtyDIR*0.1)-$ActualCostActualQtyDIR)/$TargetCostActualQtyDIR*10)*100;
            $TargetCostTargetQtyDIR = number_format((float)$TargetCostTargetQtyDIR, 2, '.', ',');
            $TargetCostActualQtyDIR = number_format((float)$TargetCostActualQtyDIR, 2, '.', ',');
            $ActualCostActualQtyDIR = number_format((float)$ActualCostActualQtyDIR, 2, '.', ',');
            $ValTotalCostDIR = number_format((float)$ValTotalCostDIR, 2, '.', ',');
        }
        ?>
            <tr>
                <th class="text-center trowCustom" width = "300">Target Cost * Target Qty ($)</th>
                <td class="text-left "><?php echo $TargetCostTargetQtyDIR; ?></td>
            </tr>
            <tr>
                <th class="text-center trowCustom" width = "300">Target Cost * Actual Qty ($)</th>
                <td class="text-left "><?php echo $TargetCostActualQtyDIR; ?></td>
            </tr>
            <tr>
                <th class="text-center trowCustom" width = "300">Actual Cost * Actual Qty ($)</th>
                <td class="text-left "><?php echo $ActualCostActualQtyDIR; ?></td>
            </tr>
            <tr>
                <th class="text-center theadCustom" width = "300"><strong>Cost Points (%)</strong></th>
                <td class="text-left "><strong><?php echo $ValTotalCostDIR; ?></strong></td>
            </tr>
        </table>
        <br>
        <div><h5><strong>Quantity and Quality Points</strong></h5></div>
        <div class="table_summary">
        <table class="table table-bordered table-hover">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width="20">No</th>
                <th class="text-center trowCustom">Quote</th>
                <th class="text-center trowCustom" width = "110">Done On Time (%)</th>
                <th class="text-center trowCustom" width = "120">Weight Per Product (%)</th>
                <th class="text-center trowCustom" width = "120">Quantity Points (%)</th>
                <th class="text-center trowCustom" width = "110">Quality Points (%)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $ValTotalWppAndDoTDM = 0;
        $ValTotalQPDM = 0;
        $No = 1;
        $QListReport = GET_WPP_TOTAL_DIR($ValClosedTime,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $ValTotalDIR = $RListReport['[TotalDIR]'];
        }
        $QListReport = GET_WPP_DOT_DIR($ValClosedTime,$linkMACHWebTrax);
        while($RListReport = sqlsrv_fetch_array($QListReport))
        {
            $ValQuoteDIR = $RListReport['Quote'];
            $ValDoTDIR = $RListReport['DoTDIR'];
            $ValtctqDIR = $RListReport['tctqDIR'];
            $ValQualityDIR = $RListReport['QP'];
            if(trim($ValQualityDM) == ""){$ValQualityDM = "0.00";};
            $ValWppDIR = @($ValtctqDIR/$ValTotalDIR)*100;
            $ValQualityPointDIR = ($ValQualityDM*$ValWppDIR)/100;
            $ValQuantityPointDIR = ($ValDoTDIR*$ValWppDIR)/100;
            $ValDoTDIR = number_format((float)$ValDoTDIR, 2, '.', ',');
            $ValWppDIR = number_format((float)$ValWppDIR, 2, '.', ',');
            $ValQualityPointDIR = number_format((float)$ValQualityPointDIR, 2, '.', ',');
            $ValQuantityPointDIR = number_format((float)$ValQuantityPointDIR, 2, '.', ',');

        ?>
        <tr>
            <td class="text-left TargetColumn"><?php echo $No; ?></td>
            <td class="text-left TargetColumn"><?php echo $ValQuoteDIR; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValDoTDIR; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValWppDIR; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQuantityPointDIR; ?></td>
            <td class="text-right TargetColumn"><?php echo $ValQualityPointDIR; ?></td>
        </tr>
        <?php
        $ValTotalQuantityPointDIR = $ValTotalQuantityPointDIR + $ValQuantityPointDIR;
        $ValTotalQualityPointDIR = $ValTotalQualityPointDIR + $ValQualityPointDIR;
        $ValTotalQuantityPointDIR = number_format((float)$ValTotalQuantityPointDIR, 2, '.', ',');
        $ValTotalQualityPointDIR = number_format((float)$ValTotalQualityPointDIR, 2, '.', ',');
        $No++;
        }
        $ValQtyCostDIR = ($ValTotalCostDIR * $ValTotalQuantityPointDIR)/100;
        if($ValQtyCostDIR>100){$ValQtyCostDIR=100;};
        $ValTotalPoints = ($ValQtyCostDIR * $ValTotalQualityPointDIR)/100;
        if($ValTotalPoints>100){$ValTotalPoints=100;};
        $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
        ?>
        <tr>
        <td colspan="4" class="text-center theadCustom"><strong>TOTAL</strong></td>
        <td class="text-right "><strong><strong><?php echo $ValTotalQuantityPointDIR; ?></strong></td>
        <td class="text-right "><strong><strong><?php echo $ValTotalQualityPointDIR; ?></strong></td>
        </tr>
        </table>
        <?php    
    }
    */
    ?>
    <div class="col-md-12">
        <br>
        <div><h5><strong>Time Allocation</strong></h5></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="ListTableProjectPM">
                    <thead class="theadCustom">
                        <tr>
                            <th class="text-center trowCustom" width="20">No</th>
                            <th class="text-center trowCustom">Quote</th>
                            <th class="text-center trowCustom">Cost Allocation</th>
                            <th class="text-center trowCustom" width="100">Time Spent<br>(Hour)</th>
                            <th class="text-center trowCustom" width="100">Time Spent<br>(%)</th>
                        </tr>
                    </thead>
                    <tbody class=" PointerListTT">
                    <?php
                    
                    $No = 1;
                    $TotalStabilize = 0;
                    $TotalStablizePercentage = 0;
                    while($RDataPSL = sqlsrv_fetch_array($QDataPSL))
                    {
                        $ValQuote = trim($RDataPSL['Quote']);
                        $ValCostAllocation = trim($RDataPSL['ExpenseAllocation']);
                        $ValTimeSpent = trim($RDataPSL['Stabilize']);
                        $ValTotalStabilizePSL = trim($RDataPSL['TotalStabilize']);
                    
                            $TotalStabilize = $TotalStabilize + $ValTimeSpent;
                            $ValTimeSpent = number_format((float)$ValTimeSpent, 2, '.', ',');
                            $ValPercentage = (float)($ValTimeSpent/$ValTotalStabilizePSL)*100;
                            $TotalStablizePercentage = $TotalStablizePercentage + $ValPercentage;
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            $ValDataRowEncrypt = base64_encode(base64_encode($ValLocation."#".$ValClosedTime."#".$ValPM."#".$ValQuote."#".$ValCostAllocation."#".$ValPosition));
                        ?>
                        <tr class="FloatTT" data-float="<?php echo $ValDataRowEncrypt; ?>">
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-left"><?php echo $ValQuote; ?></td>
                            <td class="text-left"><?php echo $ValCostAllocation; ?></td>
                            <td class="text-right"><?php echo $ValTimeSpent; ?></td>
                            <td class="text-right"><?php echo $ValPercentage; ?></td>
                        </tr><?php
                            $No++;
                        $TotalStabilize = number_format((float)$TotalStabilize, 2, '.', ',');
                        $TotalStablizePercentage = number_format((float)$TotalStablizePercentage, 2, '.', ',');
                    }  
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center theadCustom"><strong>TOTAL</strong></td>
                            <td class="text-right"><?php echo $TotalStabilize; ?></td>
                            <td class="text-right"><?php echo $TotalStablizePercentage; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php
    
}
?>
<!-- <script>
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
<script>
$(document).ready(function () {
    $("#myHeader").append('<h5><strong>Season</strong> : <?php echo $ValClosedTime; ?>.<strong>  Name</strong> : <?php echo $ValPM; ?>. <strong>   Position</strong> : <?php echo $ValPosition; ?>.<strong>  <?php if($ValClosedTime != "OPEN"){?> Points</strong> : <?php echo $ValTotalPoints; ?> % <?php } else {?>Time Spent</strong> : <?php echo $TotalStabilize; ?> Hour<?php }?></h5>');
    });
</script> -->

<?php


// }


?>
