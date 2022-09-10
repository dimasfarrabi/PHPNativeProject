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
    $ValEmployee = htmlspecialchars(trim($_POST['ValEmployee']), ENT_QUOTES, "UTF-8");
    $ValRolesEncrypt = htmlspecialchars(trim($_POST['ValRoles']), ENT_QUOTES, "UTF-8");
    $ValRoles = base64_decode(base64_decode($ValRolesEncrypt));
    $ArrValRoles = explode("#",$ValRoles);
    $ValNameEmployee = $ArrValRoles[0];
    $ValLocation = $ArrValRoles[1];
    $ValRolesPosition = $ArrValRoles[2];
	// echo "$ValNameEmployee >> $ValLocation >> $ValRolesPosition";
    // $QResSum = GET_SUMMARY_TIMETRACK_EMPLOYEE_2($ValEmployee,$ValClosedTime,$ValLocation,$linkMACHWebTrax);
	
?>
<style>
    .card {padding: 10px; box-shadow: 0px 1px 3px #888888;background:#FFFFFF;width: 100%;}
    .sticky {position: sticky; top: 0; width: 100%;z-index:100;}
    .header {padding: 5px 10px;background:#FFFFFF;color: #555;}
</style>
<div class="col-md-12 card" id="myHeader"></div>
<br></br>
<br>
<div class="col-md-12"><br>
    <div><h5><strong>Time Allocation & Achievements Points</strong></h5></div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableTT">
            <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width = "70">WO Mapping ID</th>
                <th class="text-center trowCustom">Quote</th>
                <th class="text-center trowCustom">Division</th>
                <th class="text-center trowCustom" width = "110">Time Spent (Hour)</th>
                <th class="text-center trowCustom" width = "110">Time Spent (%)</th>
                <th class="text-center trowCustom">Project Points</th>
                <th class="text-center trowCustom">Individual Points</th>
            </tr>
            </thead>
            <tbody><?php
            $Total = GET_TOTAL_STABILIZE($ValClosedTime,$ValNameEmployee,$ValLocation,$linkMACHWebTrax);
            while($RTotal = sqlsrv_fetch_array($Total))
            {
                $TotalTime = trim($RTotal['TOTAL']);
            }
			$TotalHour = $TotalTime;
            $ValPoin = 0;
            $ValProjectPoints=$ValTotalIndv=$ValTotalHour=$ValPercentTime=$ValTotalPercentTime=0;
            $QListReport = CHECK_POINTS($ValClosedTime,$ValNameEmployee,$ValLocation,$linkMACHWebTrax);
            while($RListReport = sqlsrv_fetch_array($QListReport))
            {
                $ValWOMappingID = trim($RListReport['WOMapping_ID']);
                $ValStabilize = trim($RListReport['Stabilize']);
                $ValQuote = trim($RListReport['Quote']);
                $ValDivision = trim($RListReport['DivisionName']);
                $ValQCategory = trim($RListReport['QuoteCategory']);
                $Valtcaq = trim($RListReport['tcaq']);
                $Valacaq = trim($RListReport['acaq']);
                $ValDoT = @(trim($RListReport['QtyActual'])/trim($RListReport['QtyTarget']))*100;
                if($ValDoT > 100){$ValDoT = 100;}
                $ValQP = trim($RListReport['QP']);
                $ValTargetHour = trim($RListReport['TargetCost']);
                $ValActualHour = trim($RListReport['RunningTime']);
                $ValPercentTime = @($ValStabilize/$TotalHour)*100;
                $ValUnquoteProjectPoint = @($ValActualHour/$ValTargetHour)*100;
                if($ValUnquoteProjectPoint > 110){$ValUnquoteProjectPoint = 0;}
                // $ValIndvUnquotePoint = ($ValUnquoteProjectPoint*$ValResSumStabilize)/100;
                
                if($ValQCategory == 'Quote')
                {
                    $ValUnquoteProjectPoint=0;
                    if($Valtcaq > 0){ $ValCost = @(($Valtcaq+($Valtcaq*0.1)-$Valacaq)/$Valtcaq*10)*100; }
                    else{  $ValCost=""; $A = 0;}
                    if($ValCost>100){$ValCost=100;}
                    elseif($ValCost<0){$ValCost=0;}
                    $A = @($ValCost*$ValDoT)/100;
                    if($A > 100){ $A = 100; }
                    elseif($A < 0){ $A = 0; }
                    if((string)$ValCost == "")
                    {
                        $ValProjectPoints = $ValQP;
                    }
                    else{ $ValProjectPoints = @($A*$ValQP)/100; }
                }
                
                /*
                if($ValQCategory == 'Quote')
                {
                    $ValUnquoteProjectPoint=0;
                    if($Valtcaq > 0){ $ValCost = @(($Valtcaq+($Valtcaq*0.1)-$Valacaq)/$Valtcaq*10)*100; }
                    else{  $ValCost=0; }
                    if($ValCost>100){$ValCost=100;}
                    elseif($ValCost<0){$ValCost=0;}
                    $A = @($ValCost*$ValDoT)/100;
                    if($A > 100){ $A = 100; }
                    elseif($A < 0){ $A = 0; }
                    if($A > 0)
                    {
                        $ValProjectPoints = @($A*$ValQP)/100;
                    }
                    else{ $ValProjectPoints = $ValQP; }
                }*/
                elseif($ValQCategory == 'Unquote')
                {   
                    $ValProjectPoints = 0;
                    $ValUnquoteProjectPoint = 10*(110-@($ValActualHour/$ValTargetHour*100));
                    if ($ValUnquoteProjectPoint>100)
                    {
                        $ValUnquoteProjectPoint=100;
                    }
                    elseif($ValUnquoteProjectPoint<0){$ValUnquoteProjectPoint=0;}
                }
                // else{$ValUnquoteProjectPoint = 10*(110-@($ValActualHour/$ValTargetHour*100)); if ($ValUnquoteProjectPoint>100){$ValUnquoteProjectPoint=100;}}
                
                // $ValCost = @(($Valtcaq+($Valtcaq*0.1)-$Valacaq)/$Valtcaq*10)*100;
                // if($ValCost>100){$ValCost=100;}
                // elseif($ValCost<0){$ValCost=0;}
                // $ValProjectPoints = @($ValCost*$ValDoT*$ValQP)/10000;
                // elseif($ValQCategory == 'Quote'){$ValUnquoteProjectPoint=0;}
                // elseif($ValQCategory == 'Unquote'){$ValCost=0;}
                // else{$ValUnquoteProjectPoint = 10*(110-@($ValActualHour/$ValTargetHour*100)); if ($ValUnquoteProjectPoint>100){$ValUnquoteProjectPoint=100;}}
                
                // $ValCost = @(($Valtcaq+($Valtcaq*0.1)-$Valacaq)/$Valtcaq*10)*100;
                // if($ValCost>100){$ValCost=100;}
                // elseif($ValCost<0){$ValCost=0;}
                // $Val1 = @($ValCost*$ValDoT)/100;
                // if($Val1 > 100){$Val1x = 100;}
                // elseif($Val1 < 0){$Val1x = 0;}
                // else{$Val1x = $Val1;}
                // if($Val1x == 0 && $ValQCategory == 'Quote')
                // {
                    // $ValProjectPoints = $ValQP;
                // }
                // else
                // {
                    // $ValProjectPoints = @($Val1x*$ValQP)/100;
                // }

                $ValAllProjectPoint = ($ValUnquoteProjectPoint+$ValProjectPoints);
                $ValIndv = @($ValAllProjectPoint*$ValStabilize)/100;
                $ValTotalIndv = $ValTotalIndv+$ValIndv;
                // $ValTotalHour = $ValTotalHour+$ValStabilize;
                $ValTotalPercentTime = $ValTotalPercentTime+$ValPercentTime;
                $ValPoin = @($ValTotalIndv/$TotalHour)*100;

                $ValStabilize = number_format((float)$ValStabilize, 2, '.', ',');
                $ValAllProjectPoint = number_format((float)$ValAllProjectPoint, 2, '.', ',');
                $ValIndv = number_format((float)$ValIndv, 2, '.', ',');
                $ValPercentTime = number_format((float)$ValPercentTime, 1, '.', ',');  
            ?>
            <tr>
                <td class="text-center"><?php echo $ValWOMappingID; ?></td>
                <td><?php echo $ValQuote; ?></td>
                <td><?php echo $ValDivision; ?></td>
                <td class="text-right"><?php echo $ValStabilize; ?></td>
                <td class="text-right"><?php echo $ValPercentTime; ?></td>
                <td class="text-right"><?php echo $ValAllProjectPoint; ?></td>
                <td class="text-right"><?php echo $ValIndv; ?></td>

            </tr>
            <?php
            
            $ValPoin = number_format((float)$ValPoin, 2, '.', ',');
            
            $ValTotalPercentTime = number_format((float)$ValTotalPercentTime, 2, '.', ',');

            }
            $TotalHour = number_format((float)$TotalHour, 2, '.', ',');
            ?>
            </tbody>
            <tfoot>
           <tr>
                <td colspan="3" class="text-center theadCustom"><strong>Total</strong></td>
                <td class="text-right"><strong><?php echo $TotalHour; ?></strong></td>
                <td class="text-right"><strong><?php echo $ValTotalPercentTime; ?></strong></td>
                <td class="text-center theadCustom"><strong>Final Points</strong></td>
                <td class="text-right"><strong><?php echo $ValPoin; ?></strong></td>
            </tr>
            </tfoot>
            </table>
        </div>
</div>
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
    $("#myHeader").append('<h5><strong>Season</strong> : <?php echo $ValClosedTime; ?>.<strong>  Name</strong> : <?php echo $ValEmployee; ?>. <strong>   Position</strong> : <?php echo $ValRolesPosition; ?>.<strong>  <?php if($ValClosedTime != "OPEN"){?> Points</strong> : <?php echo $ValPoin; ?> % <?php } else {?>Time Spent</strong> : <?php echo $ValTotalHour; ?> Hour<?php }?></h5>');
    
    $("#TableTT").dataTable({
		"paging": false,
		"bInfo": false
	});
	$("#TableTT").css("margin-bottom","10px")

});
</script>
<?php

}
else
{
    echo "";    
}
?>