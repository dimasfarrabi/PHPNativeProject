<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");
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
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValPM = htmlspecialchars(trim($_POST['ValPM']), ENT_QUOTES, "UTF-8");
    $ValRolesEncrypt = htmlspecialchars(trim($_POST['ValRoles']), ENT_QUOTES, "UTF-8");
    $ValRoles = base64_decode(base64_decode($ValRolesEncrypt));
    $ValLocation = "SALATIGA";
    $ArrVarRoles = explode("#",$ValRoles);
    $ValRolesPosition = $ArrVarRoles[1];    
    $ArrDataResult = array();      
    if($ValRolesPosition == "PM")
    {
        $ValPosition = "PRODUCTION MANAGER";
        $QDataPSL = GET_TOTAL_TIMETRACK_LEADER($ValClosedTime,$ValPM,$ValRolesPosition,$linkMACHWebTrax);
    }
    elseif($ValRolesPosition == "DM")
    {
        $ValPosition = "DIVISION MANAGER";
        $QDataPSL = GET_TOTAL_TIMETRACK_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);
    }
    elseif($ValRolesPosition == "DIR")
	{
		$ValPosition = "DIRECTOR";
		$QDataPSL = GET_TOTAL_TIMETRACK_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);

	}
    else
    {
        $ValPosition = "CO PM";
		$QDataPSL = GET_TOTAL_TIMETRACK_LEADER($ValClosedTime,$ValPM,$ValRolesPosition,$linkMACHWebTrax);
    }
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
    $ArrDivName = array();
    $DataDiv = GET_IS_MULTI_DIVISION($ValClosedTime,$ValPM,$linkMACHWebTrax);
    while($DataRes = sqlsrv_fetch_array($DataDiv))
    {
        $DivName = trim($DataRes['ExpenseAllocation']);
        array_push($ArrDivName,$DivName);
    }
    rsort($ArrDivName);
    ?>
    <style>
        .card {padding: 15px; box-shadow: 0px 1px 3px #888888;background:#FFFFFF;width: 100%;}
        .sticky {position: sticky; top: 0; width: 100%;z-index:100;}
        .header {padding: 5px 10px;background:#FFFFFF;color: #555;}
        .tableFixHead {
        overflow-y: auto;
        height: 400px;
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
<?php
	if($ValClosedTime == "OPEN")
	{
        ?>
        <div class="col-md-12 card" id="myHeader"></div>
		<div class="col-md-12" id="TableSummary">
                
        </div>
        <div class="col-md-12">
        <div><h5><strong>Time Allocation</strong></h5></div>
            <div class="table-responsive tableFixHead">
                <table class="table table-responsive" id="ListTableProjectPM">
                    <thead class="theadCustom">
                        <tr>
                            <th class="text-center trowCustom">No</th>
                            <th class="text-center trowCustom">Quote</th>
                            <th class="text-center trowCustom">Cost Allocation</th>
                            <th class="text-center trowCustom">Time Spent<br>(Hour)</th>
                            <th class="text-center trowCustom">Time Spent<br>(%)</th>
                        </tr>
                    </thead>
                    <tbody><?php
                    $No = 1;
                    $TotalStabilize = 0;
                    $TotalStablizePercentage = 0;
                    if(count($ArrDataResult) > 0)
                    {
                        foreach($ArrDataResult as $DataResult)
                        {
                            $ValQuote = trim($DataResult['Quote']);
                            $ValCostAllocation = trim($DataResult['ExpenseAllocation']);
                            $ValTimeSpent = trim($DataResult['Stabilize']);
                            $TotalStabilize = $TotalStabilize + $ValTimeSpent;
                            $ValTimeSpent = number_format((float)$ValTimeSpent, 2, '.', ',');
                            $ValPercentage = (float)(trim($DataResult['Stabilize']) / trim($DataResult['TotalStabilize']))*100;
                            $TotalStablizePercentage = $TotalStablizePercentage + $ValPercentage;
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            $ValDataRowEncrypt = base64_encode(base64_encode($ValClosedTime."#".$ValPM."#".$ValQuote."#".$ValCostAllocation."#".$ValPosition));
                        ?>
                        <!-- <tr class="FloatTT" data-float="<?php echo $ValDataRowEncrypt; ?>"> -->
                        <tr>
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-left"><?php echo $ValQuote; ?></td>
                            <td class="text-left"><?php echo $ValCostAllocation; ?></td>
                            <td class="text-right"><?php echo $ValTimeSpent; ?></td>
                            <td class="text-right"><?php echo $ValPercentage; ?></td>
                        </tr><?php
                            $No++;
                        }
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
        <div class="col-md-12" id="TableSummary">
                    
        </div>
        <div class="col-md-12" id="CostDetail">

        </div>
        <?php
		if($ValRolesPosition == "PM" || $ValRolesPosition == "CO PM")
		{
            $QListReport = GET_COST_DETAIL_LEADER($ValPM,$ValRolesPosition,$ValClosedTime,$linkMACHWebTrax);
                $TotalTCAQ = $TotalACAQ = 0;
                while($RListReport = sqlsrv_fetch_array($QListReport))
                {
                    $TotalCost = trim($RListReport['TotalActualCost']);
                    $TotalTargetCost = trim($RListReport['TotalTargetCost']);
                    $TotalTCAQ = $TotalTCAQ + $TotalTargetCost;
                    $TotalACAQ = $TotalACAQ + $TotalCost;
                    // echo $TotalCost."<br>";
                }
                $ValTotalCostPM = @(($TotalTCAQ+($TotalTCAQ*0.1)-$TotalACAQ)/$TotalTCAQ*10)*100;
                $ValTotalCostPM = number_format((float)$ValTotalCostPM,2,'.',',');
                ?>
                
            <div class="col-md-12">
            <div><h5><strong>Quantity Points</strong></h5></div>
            <i>*) DoT x WPP</i>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center trowCustom" width="20">No</th>
                                <th class="text-center trowCustom">Quote</th>
                                <th class="text-center trowCustom" width = "110">Done On Time PM (%)</th>
                                <th class="text-center trowCustom" width = "120">Weight Per Product PM (%)</th>
                                <th class="text-center trowCustom" width = "120">Quantity Points PM (%)*</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ValTotalWppAndDoT  = $ValTotaltctqPM = $ValQuantityPointPM  = $ValTotalQuantityPointPM = $ValTotalQualityPointPM  = 0;
                        $ValTotalValQPPM = 0;
                        $Num = 1;
                        $QListReport = GET_WPP_TOTAL_PM($ValClosedTime,$ValPM,$ValRolesPosition,$linkMACHWebTrax);
                        while($RListReport = sqlsrv_fetch_array($QListReport))
                        {
                            $ValTotalPM = $RListReport['TotalPM'];
                        }
                        $QListReport = GET_WPP_DOT_PM($ValClosedTime,$ValPM,$ValRolesPosition,$linkMACHWebTrax);
                        while($RListReport = sqlsrv_fetch_array($QListReport))
                        {
                            $ValQuote = $RListReport['Quote'];
                            $QtyActual = $RListReport['QtyActual'];
                            $QtyTarget = $RListReport['QtyTarget'];
                            $DoT = @($QtyActual/$QtyTarget)*100;
                            if($DoT > 100){$DoT = 100;}
                            // $ValtctqPM = $RListReport['TargetCostTargetQty'];
                            $ValQuality = $RListReport['QPPM'];
                            if(trim($ValQuality) == ''){$ValQuality = 0;};
                            // $ValWPPPM = @($ValtctqPM/$ValTotalPM)*100;
                            $ValWPPPM = $RListReport['WPP'];
                            $ValQuantityPointPM = ($DoT*$ValWPPPM)/100;
                            $ValQualityPointPM = ($ValQuality*$ValWPPPM)/100;
                            // array_push($arrayQP,$ValQualityPointPM);
                            $ValWPPPM = number_format((float)$ValWPPPM, 2, '.', ',');
                            $ValQualityPointPM = number_format((float)$ValQualityPointPM, 2, '.', ',');
                            $ValQuantityPointPM = number_format((float)$ValQuantityPointPM, 2, '.', ',');
                            $DoT = number_format((float)$DoT, 2, '.', ',');
                        ?>  
                        <tr>
                            <td class="text-left TargetColumn"><?php echo $Num; ?></td>
                            <td class="text-left TargetColumn"><?php echo $ValQuote; ?></td>
                            <td class="text-right TargetColumn"><?php echo $DoT; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValWPPPM; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValQuantityPointPM; ?></td>
                        </tr>
                        <?php
                        $ValTotalQuantityPointPM = @($ValTotalQuantityPointPM+$ValQuantityPointPM);
                        $ValTotalQualityPointPM = @($ValTotalQualityPointPM+$ValQualityPointPM);
                        $ValTotalQuantityPointPM = number_format((float)$ValTotalQuantityPointPM, 2, '.', ',');
                        $ValTotalQualityPointPM = number_format((float)$ValTotalQualityPointPM, 2, '.', ',');
                        $Num++;
                        }
                        ?>
                        </tbody>
                        <tfoot class="theadCustom">
                            <tr>
                                <td colspan = "4" class="text-center"><strong>TOTAL</strong></td>
                                <td class="text-right"><strong><?php echo $ValTotalQuantityPointPM; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
            <div><h5><strong>Quality Points</strong></h5></div>
            <i>**) Quality Points x WPP</i>
                <div class="table-responsive">
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
                            <th class="text-center trowCustom" width = "80">Quality Points PM (%)**</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Nomor = 1; $ValTotalQualityPointPM = "";
                            $QListReport = GET_QUALITY_PER_DIVISION_PM($ValClosedTime,$ValPM,$ValRolesPosition,$linkMACHWebTrax);
                            while($RListReport = sqlsrv_fetch_array($QListReport))
                            {
                                $ValQuoteName = $RListReport['Quote'];
                                $ValMACHINING = $RListReport['Machining'];
                                $ValFABRICATION = $RListReport['Fabrication'];
                                $ValINJECTION = $RListReport['Injection'];
                                $ValASSEMBLY = $RListReport['Assembly'];
                                $ValELECTRONICS = $RListReport['Electronics'];
                                $ValQA = $RListReport['QA'];
                                // $ValtctqPM = $RListReport['TargetCostTargetQty'];
                                $ValWPPPM = $RListReport['WPP'];
                                $ValQualityPointPM = ($ValQA*$ValWPPPM)/100;
                                $ValTotalQualityPointPM = @($ValTotalQualityPointPM + $ValQualityPointPM);
								/*
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
                                $ValQualityPointPM = number_format((float)$ValQualityPointPM, 2, '.', ','); */
								$ValQualityPointPM = number_format((float)$ValQualityPointPM, 2, '.', ','); 
                                if(trim($ValMACHINING) == ""){$ValMACHINING = "";} else {$ValMACHINING = number_format((float)$ValMACHINING, 2, '.', ',');};   
                                if(trim($ValFABRICATION) == ""){$ValFABRICATION = "";} else {$ValFABRICATION = number_format((float)$ValFABRICATION, 2, '.', ',');};   
                                if(trim($ValINJECTION) == ""){$ValINJECTION = "";} else {$ValINJECTION = number_format((float)$ValINJECTION, 2, '.', ',');};   
                                if(trim($ValASSEMBLY) == ""){$ValASSEMBLY = "";} else {$ValASSEMBLY = number_format((float)$ValASSEMBLY, 2, '.', ',');};   
                                if(trim($ValELECTRONICS) == ""){$ValELECTRONICS = "";} else {$ValELECTRONICS = number_format((float)$ValELECTRONICS, 2, '.', ',');};   
                                if(trim($ValQA) == ""){$ValQA = "";} else {$ValQA = number_format((float)$ValQA, 2, '.', ',');};								
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
                                <td class="text-right TargetColumn"><?php echo $ValQualityPointPM; ?></td>
                            </tr>
                            <?php
                            $Nomor++;
                            }
                            $ValTotalQualityPointPM = number_format((float)$ValTotalQualityPointPM, 2, '.', ',');  
                            ?>
                            </tbody>
                            <tfoot class="theadCustom">
                                <tr>
                                    <td colspan = "8" class="text-center"><strong>TOTAL</strong></td>
                                    <td class="text-right"><strong><?php echo $ValTotalQualityPointPM; ?></strong></td>
                                </tr>
                            </tfoot>
                            <?php
                            $ValQtyCostPM = ($ValTotalCostPM * $ValTotalQuantityPointPM)/100;
                            if($ValQtyCostPM>100){$ValQtyCostPM=100;};
                            $ValTotalPoints = ($ValQtyCostPM * $ValTotalQualityPointPM)/100;
                            if($ValTotalPoints>100){$ValTotalPoints=100;};
                            $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
                            $CostPoints = $ValTotalCostPM;
                            $QtyPoints = $ValTotalQuantityPointPM;
                            $QP = $ValTotalQualityPointPM;
                            ?>
                        
                    </table>
                </div>
            </div>
            <?php
		}
		if($ValRolesPosition == "DM")
        {
            
            $QListReport = GET_COST_DETAIL_LEADER($ValPM,$ValRolesPosition,$ValClosedTime,$linkMACHWebTrax);
                $TotalTCAQ = $TotalACAQ = 0;
                while($RListReport = sqlsrv_fetch_array($QListReport))
                {
                    $TotalCost = trim($RListReport['TotalActualCost']);
                    $TotalTargetCost = trim($RListReport['TotalTargetCost']);
                    $TotalTCAQ = $TotalTCAQ + $TotalTargetCost;
                    $TotalACAQ = $TotalACAQ + $TotalCost;
                    // echo $TotalCost."<br>";
                }
                $ValTotalCostDM = @(($TotalTCAQ+($TotalTCAQ*0.1)-$TotalACAQ)/$TotalTCAQ*10)*100;
                $ValTotalCostDM = number_format((float)$ValTotalCostDM,2,'.',',');

            if($ValPM != 'YODHA PRADANA')
            {
            ?>
            <div class="col-md-12">
            <div><h5><strong>Quantity and Quality Points</strong></h5></div>
            <i>*) DoT x WPP<br>**) Quality Points x WPP</i>
                <div class="table-responsive tableFixHead">
                    <table class="table table-hover" id="ListTableR">
                        <thead class="theadCustom">
                            <tr>
                                <th>No</th>
                                <th>Quote</th>
                                <th>Division</th>
                                <th>Done On Time DM (%)</th>
                                <th>Weight Per Product DM (%)</th>
                                <th>Target Cost Target Qty ($)</th>
                                <th>Quantity Points DM (%)*</th>
                                <th>Quality Points DM (%)**</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ValTotalWppAndDoTDM = $ValTotaltctqDM = $ValTotalQuantityPointDM = $ValTotalQualityPointDM = 0;
                        $ValTotalQPDM = $TotalTCTQ = 0;
                        $No = 1;
                        $QListReport = GET_WPP_DOT_DM($ValClosedTime,$ValPM,"",$linkMACHWebTrax);
                        while($RListReport = sqlsrv_fetch_array($QListReport))
                        {
                            $ValQuoteDM = $RListReport['Quote'];
                            $ValDivision = $RListReport['ExpenseAllocation'];
                            $QtyTarget = $RListReport['QtyTarget'];
                            $QtyQuote = $RListReport['QtyQuote'];
                            $ValDoTDM = @($QtyQuote/$QtyTarget)*100;
                            if($ValDoTDM > 100){ $ValDoTDM = 100; }
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
                            $TotalTCTQ = @($TotalTCTQ + $ValtctqDM);
                            $ValtctqDM = number_format((float)$ValtctqDM, 2, '.', ',');

                        ?>
                        <tr>
                            <td class="text-center TargetColumn"><?php echo $No; ?></td>
                            <td class="text-left TargetColumn"><?php echo $ValQuoteDM; ?></td>
                            <td class="text-left TargetColumn"><?php echo $ValDivision; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValDoTDM; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValWppDM; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValtctqDM; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValQuantityPointDM; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValQualityPointDM; ?></td>
                        </tr>
                        <?php
                        $ValTotalQuantityPointDM = @($ValTotalQuantityPointDM + $ValQuantityPointDM);
                        $ValTotalQualityPointDM = @($ValTotalQualityPointDM + $ValQualityPointDM);
                        $ValTotalQuantityPointDM = number_format((float)$ValTotalQuantityPointDM, 2, '.', ',');
                        $ValTotalQualityPointDM = number_format((float)$ValTotalQualityPointDM, 2, '.', ',');
                        $No++;
                        }
                        $TotalTCTQ = number_format((float)$TotalTCTQ, 2, '.', ',');
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5" class="text-center theadCustom"><strong>TOTAL</strong></td>
                            <td class="text-right "><strong><strong><?php echo $TotalTCTQ; ?></strong></td>
                            <td class="text-right "><strong><strong><?php echo $ValTotalQuantityPointDM; ?></strong></td>
                            <td class="text-right "><strong><strong><?php echo $ValTotalQualityPointDM; ?></strong></td>
                        </tr>
                        <tfoot>
                    </table>
                </div>
            </div>
            <?php
    
                $ValQtyCost = ($ValTotalCostDM * $ValTotalQuantityPointDM)/100;
                if($ValQtyCost>100){$ValQtyCost=100;};
                $ValTotalPoints = ($ValQtyCost * $ValTotalQualityPointDM)/100;
                if($ValTotalPoints>100){$ValTotalPoints=100;};
                $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
            }
            else
            {
                $ArrPointDM = array();
                foreach($ArrDivName as $NamaDivisi)
                {
                ?>
                <div class="col-md-12">
                <div><h5><strong>Quantity and Quality Points <?php echo $NamaDivisi; ?></strong></h5></div>
                <i>*) DoT x WPP<br>**) Quality Points x WPP</i>
                    <div class="table-responsive tableFixHead">
                        <table class="table table-hover" id="ListTableR">
                            <thead class="theadCustom">
                                <tr>
                                    <th>No</th>
                                    <th>Quote</th>
                                    <th>Division</th>
                                    <th>Done On Time DM (%)</th>
                                    <th>Weight Per Product DM (%)</th>
                                    <th>Target Cost Target Qty ($)</th>
                                    <th>Quantity Points DM (%)*</th>
                                    <th>Quality Points DM (%)**</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $ValTotalWppAndDoTDM = $ValTotaltctqDM = $ValTotalQuantityPointDM = $ValTotalQualityPointDM = 0;
                            $ValTotalQPDM = $TotalTCTQ = 0;
                            $No = 1;
                            $QListReport = GET_WPP_DOT_DM($ValClosedTime,$ValPM,$NamaDivisi,$linkMACHWebTrax);
                            while($RListReport = sqlsrv_fetch_array($QListReport))
                            {
                                $ValQuoteDM = $RListReport['Quote'];
                                $ValDivision = $RListReport['ExpenseAllocation'];
                                $QtyTarget = $RListReport['QtyTarget'];
                                $QtyQuote = $RListReport['QtyQuote'];
                                $ValDoTDM = @($QtyQuote/$QtyTarget)*100;
                                if($ValDoTDM > 100){ $ValDoTDM = 100; }
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
                                $TotalTCTQ = @($TotalTCTQ + $ValtctqDM);
                                $ValtctqDM = number_format((float)$ValtctqDM, 2, '.', ',');

                            ?>
                            <tr>
                                <td class="text-center TargetColumn"><?php echo $No; ?></td>
                                <td class="text-left TargetColumn"><?php echo $ValQuoteDM; ?></td>
                                <td class="text-left TargetColumn"><?php echo $ValDivision; ?></td>
                                <td class="text-right TargetColumn"><?php echo $ValDoTDM; ?></td>
                                <td class="text-right TargetColumn"><?php echo $ValWppDM; ?></td>
                                <td class="text-right TargetColumn"><?php echo $ValtctqDM; ?></td>
                                <td class="text-right TargetColumn"><?php echo $ValQuantityPointDM; ?></td>
                                <td class="text-right TargetColumn"><?php echo $ValQualityPointDM; ?></td>
                            </tr>
                            <?php
                            $ValTotalQuantityPointDM = @($ValTotalQuantityPointDM + $ValQuantityPointDM);
                            $ValTotalQualityPointDM = @($ValTotalQualityPointDM + $ValQualityPointDM);
                            $ValTotalQuantityPointDM = number_format((float)$ValTotalQuantityPointDM, 2, '.', ',');
                            $ValTotalQualityPointDM = number_format((float)$ValTotalQualityPointDM, 2, '.', ',');
                            $No++;
                            }
                            $TotalTCTQ = number_format((float)$TotalTCTQ, 2, '.', ',');
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5" class="text-center theadCustom"><strong>TOTAL</strong></td>
                                <td class="text-right "><strong><strong><?php echo $TotalTCTQ; ?></strong></td>
                                <td class="text-right "><strong><strong><?php echo $ValTotalQuantityPointDM; ?></strong></td>
                                <td class="text-right "><strong><strong><?php echo $ValTotalQualityPointDM; ?></strong></td>
                            </tr>
                            <tfoot>
                        </table>
                    </div>
                </div>
                <?php
                    $ArrTemp = array(
                        "TotalTCTQ" => $TotalTCTQ,
                        "ValTotalQuantityPointDM" => $ValTotalQuantityPointDM,
                        "ValTotalQualityPointDM" => $ValTotalQualityPointDM
                    );
                    array_push($ArrPointDM,$ArrTemp);
                }
                $Val4 = $TotQP = 0;
                if(count($ArrPointDM) > 1)
                {
                    foreach($ArrPointDM as $Point)
                    {
                        $ValTCTQ = trim($Point['TotalTCTQ']);
                        $QuantityPoint = trim($Point['ValTotalQuantityPointDM']);
                        $QualityPoint = trim($Point['ValTotalQualityPointDM']);
                        $Val1 = @($ValTCTQ*$QuantityPoint);
                        $Val2 = $ValTCTQ;
                        $Val3 = @($Val1/$Val2);
                        $Val4 = $Val4 + $Val3;
                        $TotQP = $TotQP + $QualityPoint;
                        
                    }
                    if(count($ArrPointDM)>2)
                    {
                        $ValTotalQualityPointDM = $TotQP/(count($ArrPointDM));
                    }
                    else
                    {
                        $ValTotalQualityPointDM = $TotQP/1;
                    }
                    $ValQtyCost = ($ValTotalCostDM * $Val4)/100;
                    if($ValQtyCost>100){$ValQtyCost=100;};
                    $ValTotalPoints = ($ValQtyCost * $TotQP)/100;
                    if($ValTotalPoints>100){$ValTotalPoints=100;};
                    $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
                }
            }
			$CostPoints = $ValTotalCostDM;
            $QtyPoints = $ValTotalQuantityPointDM;
            $QP = $ValTotalQualityPointDM;
		}
		if($ValRolesPosition == "DIR")
		{
            $QListReport = GET_COST_DETAIL_LEADER($ValPM,$ValRolesPosition,$ValClosedTime,$linkMACHWebTrax);
                $TotalTCAQ = $TotalACAQ = 0;
                while($RListReport = sqlsrv_fetch_array($QListReport))
                {
                    $TotalCost = trim($RListReport['TotalActualCost']);
                    $TotalTargetCost = trim($RListReport['TotalTargetCost']);
                    $TotalTCAQ = $TotalTCAQ + $TotalTargetCost;
                    $TotalACAQ = $TotalACAQ + $TotalCost;
                    // echo $TotalCost."<br>";
                }
                $ValTotalCostDIR = @(($TotalTCAQ+($TotalTCAQ*0.1)-$TotalACAQ)/$TotalTCAQ*10)*100;
                $ValTotalCostDIR = number_format((float)$ValTotalCostDIR,2,'.',',');
                        ?>
                        
            <div class="col-md-12">
            <div><h5><strong>Quantity and Quality Points</strong></h5></div>
            <i>*) DoT x WPP<br>**) Quality Points x WPP</i>
                <div class="table-responsive tableFixHead">
                    <table class="table table-hover" id="ListTableR">
                        <thead class="theadCustom">
                            <tr>
                                <th width="20">No</th>
                                <th>Quote</th>
                                <th>Done On Time (%)</th>
                                <th>Weight Per Product (%)</th>
                                <th>Quantity Points (%)*</th>
                                <th>Quality Points (%)**</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ValTotalWppAndDoTDM = $ValTotaltctqDIR = $ValTotalQuantityPointDIR = $ValTotalQualityPointDIR = 0;
                        $ValTotalQPDM = 0;
                        $No = 1;
                        $QListReport = GET_WPP_TOTAL_DIR($ValClosedTime,$linkMACHWebTrax);
                        while($RListReport = sqlsrv_fetch_array($QListReport))
                        {
                            $ValTotalDIR = $RListReport['TotalDIR'];
                        }
                        $QListReport = GET_WPP_DOT_DIR($ValClosedTime,$linkMACHWebTrax);
                        while($RListReport = sqlsrv_fetch_array($QListReport))
                        {
                            $ValQuote = $RListReport['Quote'];
                            $QtyActual = $RListReport['QtyActual'];
                            $QtyTarget = $RListReport['QtyTarget'];
                            $DoT = @($QtyActual/$QtyTarget)*100;
                            if($DoT > 100){$DoT = 100;}
                            $ValtctqDIR = $RListReport['TargetCostTargetQty'];
                            $ValQuality = $RListReport['QP'];
                            if(trim($ValQuality) == ''){$ValQuality = 0;};
                            $ValWPP = @($ValtctqDIR/$ValTotalDIR)*100;
                            $ValQuantityPoint = ($DoT*$ValWPP)/100;
                            $ValQualityPoint = ($ValQuality*$ValWPP)/100;
                            $ValTotalQuantityPointDIR = @($ValTotalQuantityPointDIR + $ValQuantityPoint);
                            $ValTotalQualityPointDIR = @($ValTotalQualityPointDIR + $ValQualityPoint);
                            $ValWPP = number_format((float)$ValWPP, 2, '.', ',');
                            $ValQualityPoint = number_format((float)$ValQualityPoint, 2, '.', ',');
                            $ValQuantityPoint = number_format((float)$ValQuantityPoint, 2, '.', ',');
                            $DoT = number_format((float)$DoT, 2, '.', ',');

                        ?>
                            <tr>
                            <td class="text-center TargetColumn"><?php echo $No; ?></td>
                            <td class="text-left TargetColumn"><?php echo $ValQuote; ?></td>
                            <td class="text-right TargetColumn"><?php echo $DoT; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValWPP; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValQuantityPoint; ?></td>
                            <td class="text-right TargetColumn"><?php echo $ValQualityPoint; ?></td>
                            </tr>
                        <?php
                        $No++;
                        }
                        
                        $ValQtyCostDIR = ($ValTotalCostDIR * $ValTotalQuantityPointDIR)/100;
                        if($ValQtyCostDIR>100){$ValQtyCostDIR=100;};
                        $ValTotalPoints = ($ValQtyCostDIR * $ValTotalQualityPointDIR)/100;
                        if($ValTotalPoints>100){$ValTotalPoints=100;};
                        $ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
                        $ValTotalQuantityPointDIR = number_format((float)$ValTotalQuantityPointDIR, 2, '.', ',');
                        $ValTotalQualityPointDIR = number_format((float)$ValTotalQualityPointDIR, 2, '.', ',');
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center theadCustom"><strong>TOTAL</strong></td>
                                <td class="text-right "><strong><strong><?php echo $ValTotalQuantityPointDIR; ?></strong></td>
                                <td class="text-right "><strong><strong><?php echo $ValTotalQualityPointDIR; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php 
			$CostPoints = $ValTotalCostDIR;
            $QtyPoints = $ValTotalQuantityPointDIR;
            $QP = $ValTotalQualityPointDIR; 		
		}
		/*
		?>
        <div class="col-md-12">
            <div><h5><strong>Time Allocation</strong></h5></div>
            <div class="table-responsive tableFixHead">
                <table class="table table-responsive" id="ListTableProjectPM">
                    <thead class="theadCustom">
                        <tr>
                            <th width = "25">No</th>
                            <th>Quote</th>
                            <th>Cost Allocation</th>
                            <th>Time Spent<br>(Hour)</th>
                            <th>Time Spent<br>(%)</th>
                        </tr>
                    </thead>
                    <tbody><?php
                    $No = 1;
                    $TotalStabilize = 0;
                    $TotalStablizePercentage = 0;
                    if(count($ArrDataResult) > 0)
                    {
                        foreach($ArrDataResult as $DataResult)
                        {
                            $ValQuote = trim($DataResult['Quote']);
                            $ValCostAllocation = trim($DataResult['ExpenseAllocation']);
                            $ValTimeSpent = trim($DataResult['Stabilize']);
                            $TotalStabilize = $TotalStabilize + $ValTimeSpent;
                            $ValTimeSpent = number_format((float)$ValTimeSpent, 2, '.', ',');
                            $ValPercentage = (float)(trim($DataResult['Stabilize']) / trim($DataResult['TotalStabilize']))*100;
                            $TotalStablizePercentage = $TotalStablizePercentage + $ValPercentage;
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            $ValDataRowEncrypt = base64_encode(base64_encode($ValClosedTime."#".$ValPM."#".$ValQuote."#".$ValCostAllocation."#".$ValPosition));
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-left"><?php echo $ValQuote; ?></td>
                            <td class="text-left"><?php echo $ValCostAllocation; ?></td>
                            <td class="text-right"><?php echo $ValTimeSpent; ?></td>
                            <td class="text-right"><?php echo $ValPercentage; ?></td>
                        </tr><?php
                            $No++;
                        }
                        $TotalStabilize = number_format((float)$TotalStabilize, 2, '.', ',');
                        $TotalStablizePercentage = number_format((float)$TotalStablizePercentage, 2, '.', ',');
                    }            
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="3"><strong>Total</strong></td>
                            <td class="text-right"><?php echo $TotalStabilize; ?></td>
                            <td class="text-right"><?php echo $TotalStablizePercentage; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
		<?php
		*/
		?>
        
        <?php
	}
	$value1 = @($CostPoints*$QtyPoints)/100;
	if($value1 > 100){ $value1 = 100; }
	$ValTotalPoints = @($value1*$QP)/100;
	if($ValTotalPoints > 100) { $ValTotalPoints = 100;}
	$ValTotalPoints = number_format((float)$ValTotalPoints, 2, '.', ',');
}
?>
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
<script>
    $(document).ready(function () {
        $("#myHeader").append('<h5><strong>Season</strong> : <?php echo $ValClosedTime; ?>.<strong>  Name</strong> : <?php echo $ValPM; ?>. <strong>   Position</strong> : <?php echo $ValPosition; ?>.<strong>  <?php if($ValClosedTime != "OPEN"){?> Points</strong> : <?php echo $ValTotalPoints; ?> % <?php } else {?>Time Spent</strong> : <?php echo $TotalStabilize; ?> Hour<?php }?></h5>');
        
        $("#TableSummary").append('<?php if($ValClosedTime == "OPEN"){ } else{?><div><h5><strong>Summary Points</strong></h5></div><div class="table_summary"><table class="table table-bordered table-hover"><tr><th class="text-center trowCustom" width = "300">Cost Points (%)</th><td class="text-left "><strong><?php echo $CostPoints; ?></strong></td></tr><tr><th class="text-center trowCustom" width = "300">Quantity Points (%)</th><td class="text-left "><strong><?php echo $QtyPoints; ?></strong></td></tr><tr><th class="text-center trowCustom" width = "300">Quality Points (%)</th><td class="text-left "><strong><?php echo $QP; ?></strong></td></tr></table></div><?php } ?>');
        
        $("#ListTableR").dataTable({
            "paging": false,
            "bInfo": false,
            "searching": false
        });
        $("#ListTableR").css("margin-bottom","10px")
        
        var ValName = '<?php echo $ValPM ?>';
        var Role = '<?php echo $ValRolesPosition ?>';
        var ClosedTime = '<?php echo $ValClosedTime ?>';
        var formdata = new FormData();
        formdata.append('Name', ValName);
        formdata.append('Role', Role);
        formdata.append('ClosedTime', ClosedTime);
        $.ajax({
                url: 'project/pointachievement/CostDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                $('#CostDetail').html("");
                $('#CostDetail').html("");
                $('#CostDetail').html("");
                $("#CostDetail").html("");
                $('#CostDetail').html("");
                },
                success: function (xaxa) {
                $('#CostDetail').html("");
                $('#CostDetail').hide();
                $('#CostDetail').html(xaxa);
                $('#CostDetail').fadeIn('fast');
                },
                error: function () {
                alert("Request cannot proceed!");
                $('#CostDetail').html("");
                }
            });
    });
</script>