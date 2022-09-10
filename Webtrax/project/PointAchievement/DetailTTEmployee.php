<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFloat = htmlspecialchars(trim($_POST['ValFloat']), ENT_QUOTES, "UTF-8");
    $ValFloat = base64_decode(base64_decode($ValFloat));
    $ArrValFloat = explode("#",$ValFloat);
    $ValLocation = $ArrValFloat[0];
    $ValClosedTime = $ArrValFloat[1];
    $ValEmployee = $ArrValFloat[2];
    $ValQuote = $ArrValFloat[3];
    $ValProduct = $ArrValFloat[4];
    $ValCategory = $ArrValFloat[5];

    if($ValLocation == "SALATIGA")
    {
        $QResult = GET_DETAIL_TIMETRACK_EMPLOYEE($ValClosedTime,$ValEmployee,$ValQuote,$ValProduct,$linkMACHWebTrax);
    }
    else
    {
        $QResult = GET_DETAIL_TIMETRACK_EMPLOYEE_PSM($ValClosedTime,$ValEmployee,$ValQuote,$ValProduct);
    }
?>
<div class="col-md-12"><strong>Details - <?php echo $ValQuote; ?></strong></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="ListDetailTableTT">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="20">No</th>
                    <th class="text-center trowCustom" width="40">WOMappingID</th>
                    <th class="text-center trowCustom">WOParent</th>
                    <th class="text-center trowCustom" width="200">Division</th>
                    <th class="text-center trowCustom" width="100">Time Spent<br>(Hour)</th>
                </tr>
            </thead>
            <tbody><?php
            $No = 1;
            $TotalSpentHour = 0;
            while($RResult = sqlsrv_fetch_array($QResult))
            {
                $ValWOMappingID = trim($RResult['WOMapping_ID']);
                $ValWOParent = trim($RResult['WOParent']);
                $ValCostAllocation = trim($RResult['ExpenseAllocation']);
                $ValTimeSpent = trim($RResult['Stabilize']);
                $TotalSpentHour = $TotalSpentHour + $ValTimeSpent;
                $ValTimeSpent = number_format((float)$ValTimeSpent, 2, '.', ',');
            ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $ValWOMappingID; ?></td>
                    <td class="text-left"><?php echo $ValWOParent; ?></td>
                    <td class="text-left"><?php echo $ValCostAllocation; ?></td>
                    <td class="text-right"><?php echo $ValTimeSpent; ?></td>
                </tr>
            <?php 
                $No++; 
            } 
                $TotalSpentHour = number_format((float)$TotalSpentHour, 2, '.', ',');
            ?></tbody>
                <tr>
                    <td class="text-right" colspan="4"><strong>Total</strong></td>
                    <td class="text-right"><?php echo $TotalSpentHour; ?></td>
                </tr>
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