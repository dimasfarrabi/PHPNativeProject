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
    $Name = htmlspecialchars(trim($_POST['Name']), ENT_QUOTES, "UTF-8");
    $Role = htmlspecialchars(trim($_POST['Role']), ENT_QUOTES, "UTF-8");
    $ClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");

    // echo "$Name >> $Role >> $ClosedTime";
    $ArrDataResult = array();      
    if($Role == "PM")
    {
        $ValPosition = "PRODUCTION MANAGER";
        $QDataPSL = GET_TOTAL_TIMETRACK_LEADER($ValClosedTime,$ValPM,$linkMACHWebTrax);
    }
    elseif($Role == "DM")
    {
        $ValPosition = "DIVISION MANAGER";
        $QDataPSL = GET_TOTAL_TIMETRACK_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);
    }
    else
	{
		$ValPosition = "DIRECTOR";
		$QDataPSL = GET_TOTAL_TIMETRACK_DM($ValClosedTime,$ValPM,$linkMACHWebTrax);

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
    $DataRes = GET_COST_DETAIL_LEADER($Name, $Role, $ClosedTime, $linkMACHWebTrax);

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
<div class="col-md-12 card" id="myHeader2"></div>
    <div><h5><strong>Time Allocation</strong></h5></div></div>
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
                        <td class="text-right" colspan="3"><strong>Total</strong></td>
                        <td class="text-right"><?php echo $TotalStabilize; ?></td>
                        <td class="text-right"><?php echo $TotalStablizePercentage; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
<?php
}
?>
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader2");
var sticky = header.offsetTop;
function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
$(document).ready(function () {
    $("#myHeader2").append('<h5><strong>Season</strong> : <?php echo $ValClosedTime; ?>.<strong>  Name</strong> : <?php echo $ValPM; ?>. <strong>   Position</strong> : <?php echo $ValPosition; ?>.<strong>Time Spent</strong> : <?php echo $TotalStabilize; ?> % </h5>');
});
</script>