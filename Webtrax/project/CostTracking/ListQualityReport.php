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
    $LinkWT = $linkDBWebTrax;
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
<div><h5><strong>Achievements</strong></h5></div>
<div class="table_summary">
<table class="table table-bordered table-hover" id="TableListReport">
    <thead class="theadCustom">
        <tr>
            <th width="210">Division</th>
            <th>Points</th>
        </tr>
    </thead>
    <tbody>
            <?php
            $QListReport = GET_QUALITY_REPORT($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$ValType,$LinkWT);
            while($RListReport = sqlsrv_fetch_array($QListReport))
            {
            $ValDivision = $RListReport['Division'];
            $ValPoints = $RListReport['Points'];
            $ValPoints = number_format((float)$ValPoints,2,'.',',');
            ?>
        <tr>
            <td><?php echo $ValDivision; ?></td>
            <td><?php echo $ValPoints; ?></td>
            <?php
            }
            ?>
        </tr>
    </tbody>
    <tfoot class="theadCustom">
        <tr>
            <td class="text-center"><strong>AVERAGE</strong></td>
            <td><strong> - % </strong></td>
        </tr>
    </tfoot>
</Table>


<?php
            
}
else
{
    echo '';    
}
?>