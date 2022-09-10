<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

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
    $ValDataCookies = htmlspecialchars(trim($_POST['ValData']), ENT_QUOTES, "UTF-8");
    $ValDataCookies = base64_decode($ValDataCookies);
    $ArrDataCookies = explode("*",$ValDataCookies);
    $ValHalf = $ArrDataCookies[0];
    $ValProjectID = $ArrDataCookies[1];
    $ValDivisionID = $ArrDataCookies[2];
    $ValDivisionName = $ArrDataCookies[3];
    $ValLocation = $ArrDataCookies[4];
    $ArrListConst = array();
    $ArrDetail = array();
    $ArrResult = array();
    # data list const
    $QListConst = GET_LIST_CONST_QUALITY_VALUE($ValHalf,$linkMACHWebTrax);
    while($RListConst = mssql_fetch_assoc($QListConst))
    {
        $ValLCLocation = trim($RListConst['Location']);
        $ValLCClosingHalf = trim($RListConst['ClosingHalf']);
        $ValLCProjectID = trim($RListConst['Project_ID']);
        $ValLCCostAllocation = trim($RListConst['CostAllocation']);
        $ValLCConstValue = trim($RListConst['ConstValue']);   
        $TempArray = array(
            "Location" => $ValLCLocation,
            "ClosingHalf" => $ValLCClosingHalf,
            "ProjectID" => $ValLCProjectID,
            "CostAllocation" => $ValLCCostAllocation,
            "ConstValue" => $ValLCConstValue,
        );
        array_push($ArrListConst,$TempArray);
    }
    # data quality point
    $QDataDetail = DETAILS_QUALITY_POINTS($ValLocation,$ValProjectID,$ValDivisionID,$ValHalf,$linkMACHWebTrax);
    while($RDataDetail = mssql_fetch_assoc($QDataDetail))
    {
        $ValQPLocation = trim($RDataDetail['Location']);
        $ValQPProjectID = trim($RDataDetail['ProjectID']);
        $ValQPDivisionID = trim($RDataDetail['Division_ID']);
        $ValQPClosingHalf = trim($RDataDetail['ClosingHalf']);
        $ValQPRejectRate = trim($RDataDetail['RejectRate']);
        $ValQPTotalQtyIn = trim($RDataDetail['TotalQtyIn']);
        $ValQPTotalQtyOut = trim($RDataDetail['TotalQtyOut']);
        $ValQPLastUpdate = trim($RDataDetail['LastUpdated']);
        $ValQPActual = trim($RDataDetail['Actual']);
        $ValQPTargetMin = trim($RDataDetail['TargetMin']);
        $ValQPTargetMax = trim($RDataDetail['TargetMax']);
        $ValQPGoalAchievement = trim($RDataDetail['GoalAchievement']);
        $ValQPTotalQtyIn = number_format((float)$ValQPTotalQtyIn, 0, '.', ',');
        $ValQPTotalQtyOut = number_format((float)$ValQPTotalQtyOut, 0, '.', ',');

        $TempArray = array(
            "Location" => $ValQPLocation,
            "ProjectID" => $ValQPProjectID,
            "DivisionID" => $ValQPDivisionID,
            "DivisionName" => $ValDivisionName,
            "ClosingHalf" => $ValQPClosingHalf,
            "RejectRate" => $ValQPRejectRate,
            "TotalQtyIn" => $ValQPTotalQtyIn,
            "TotalQtyOut" => $ValQPTotalQtyOut,
            "LastUpdate" => $ValQPLastUpdate,
            "Actual" => $ValQPActual,
            "TargetMin" => $ValQPTargetMin,
            "TargetMax" => $ValQPTargetMax,
            "GoalAchievement" => $ValQPGoalAchievement
        );
        array_push($ArrDetail,$TempArray);
    }
    # result data
    foreach($ArrDetail as $DataDetail)
    {
        $ValDataLocation = trim($DataDetail['Location']);
        $ValDataProjectID = trim($DataDetail['ProjectID']);
        $ValDataDivisionID = trim($DataDetail['DivisionID']);
        $ValDataDivisionName = trim($DataDetail['DivisionName']);
        $ValDataClosingHalf = trim($DataDetail['ClosingHalf']);
        $ValDataRejectRate = trim($DataDetail['RejectRate']);
        $ValDataTotalQtyIn = trim($DataDetail['TotalQtyIn']);
        $ValDataTotalQtyOut = trim($DataDetail['TotalQtyOut']);
        $ValDataLastUpdate = trim($DataDetail['LastUpdate']);
        $ValDataActual = trim($DataDetail['Actual']);
        $ValDataTargetMin = trim($DataDetail['TargetMin']);
        $ValDataTargetMax = trim($DataDetail['TargetMax']);
        $ValDataGoalAchievement = trim($DataDetail['GoalAchievement']);
        $ValResConst = "0.00";

        foreach ($ArrListConst as $ListConst)
        {
            $ValLCLocation = trim($ListConst['Location']);
            $ValLCClosingHalf = trim($ListConst['ClosingHalf']);
            $ValLCProjectID = trim($ListConst['ProjectID']);
            $ValLCCostAllocation = trim($ListConst['CostAllocation']);
            $ValLCConstValue = trim($ListConst['ConstValue']);

            if(($ValLCLocation == $ValDataLocation) && ($ValLCClosingHalf == $ValDataClosingHalf) && ($ValLCProjectID == $ValDataProjectID) && ($ValLCCostAllocation == $ValDataDivisionName))
            {
                $ValResConst = $ValLCConstValue;
            }
        }        
        $TempArray = array(
            "Location" => $ValDataLocation,
            "ProjectID" => $ValDataProjectID,
            "DivisionID" => $ValDataDivisionID,
            "DivisionName" => $ValDataDivisionName,
            "ClosingHalf" => $ValDataClosingHalf,
            "RejectRate" => $ValDataRejectRate,
            "TotalQtyIn" => $ValDataTotalQtyIn,
            "TotalQtyOut" => $ValDataTotalQtyOut,
            "LastUpdate" => $ValDataLastUpdate,
            "Actual" => $ValDataActual,
            "TargetMin" => $ValDataTargetMin,
            "TargetMax" => $ValDataTargetMax,
            "GoalAchievement" => $ValDataGoalAchievement,
            "ConstValue" => $ValResConst
        );
        array_push($ArrResult,$TempArray);
    }
    if(count($ArrResult) != "0")
    {
        foreach ($ArrResult as $ArrResult2)
        {
            $LastRecalculate = trim($ArrResult2['LastUpdate']);
            $LastRecalculate = date("m/d/Y H:i A",strtotime($LastRecalculate));
        }
    }
    else
    {
        $LastRecalculate = "-";
    }

?>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableActualDetail">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="30">No</th>
                    <th class="text-center trowCustom">Division</th>
                    <th class="text-center trowCustom" width="80">TotalQtyIn</th>
                    <th class="text-center trowCustom" width="80">TotalQtyOut</th>
                    <th class="text-center trowCustom" width="80">RejectRate</th>
                    <th class="text-center trowCustom" width="80">ConstValue</th>
                    <th class="text-center trowCustom" width="80">Actual(%)</th>
                    <th class="text-center trowCustom" width="80">TargetMin(%)</th>
                    <th class="text-center trowCustom" width="80">TargetMax(%)</th>
                    <th class="text-center trowCustom" width="80">Goal<br>Achievement<br>(%)</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            if(count($ArrResult) != "0")
            {
                foreach ($ArrResult as $DataResult)
                {
                    $ValResLocation = trim($DataResult['Location']);
                    $ValResProjectID = trim($DataResult['ProjectID']);
                    $ValResDivisionID = trim($DataResult['DivisionID']);
                    $ValResDivisionName = trim($DataResult['DivisionName']);
                    $ValResClosingHalf = trim($DataResult['ClosingHalf']);
                    $ValResRejectRate = trim($DataResult['RejectRate']);
                    $ValResTotalQtyIn = trim($DataResult['TotalQtyIn']);
                    $ValResTotalQtyOut = trim($DataResult['TotalQtyOut']);
                    $ValResLastUpdate = trim($DataResult['LastUpdate']);
                    $ValResActual = trim($DataResult['Actual']);
                    $ValResTargetMin = trim($DataResult['TargetMin']);
                    $ValResTargetMax = trim($DataResult['TargetMax']);
                    $ValResGoalAchievement = trim($DataResult['GoalAchievement']);
                    $ValResConstValue = trim($DataResult['ConstValue']);
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-left"><?php echo $ValResDivisionName; ?></td>
                        <td class="text-center"><?php echo $ValResTotalQtyIn; ?></td>
                        <td class="text-center"><?php echo $ValResTotalQtyOut; ?></td>
                        <td class="text-center"><?php echo $ValResRejectRate; ?></td>
                        <td class="text-center"><?php echo $ValResConstValue; ?></td>
                        <td class="text-center"><?php echo $ValResActual; ?></td>
                        <td class="text-center"><?php echo $ValResTargetMin; ?></td>
                        <td class="text-center"><?php echo $ValResTargetMax; ?></td>
                        <td class="text-center"><?php echo $ValResGoalAchievement; ?></td>
                    </tr>
                    <?php
                    $No++;
                }
            }
            else
            {
                ?>
                <tr>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr><?php
            }
            ?></tbody>
        </table>
    </div>
</div>
<div class="col-md-12">*) Note : <br><strong>Reject Rate</strong> = (TotalQtyIn - TotalQtyOut) / TotalQtyIn.<br><strong>Actual</strong> = ((ConstValue-(RejectRate/100))/ConstValue)*100.<br><strong>TargetMin</strong> = 100-(100*ConstValue).<br><strong>GoalAchievement</strong> = ((Actual-TargetMin)/(100-TargetMin))*100.</div>
<div class="col-md-12">*) Last Recalculate : <?php echo "<strong>".$LastRecalculate."</strong>"; ?></div> 
<?php
}
else
{
    echo "";    
}
?>