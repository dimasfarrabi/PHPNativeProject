<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleEmployee.php");
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
    $EncValGroup = htmlspecialchars(trim($_POST['ValGroup']), ENT_QUOTES, "UTF-8");
    $ValGroup = base64_decode(base64_decode($EncValGroup));
    if($ValGroup != "ALL DEPARTMENT")
    {
        $QDataA = GET_DATA_PTO_CLASS_A($ValGroup,$linkMACHWebTrax);
        $QDataB = GET_DATA_PTO_CLASS_B($ValGroup,$linkMACHWebTrax);
        $QDataC = GET_DATA_PTO_CLASS_C($ValGroup,$linkMACHWebTrax);
        
    }
    else
    {
        $QDataA = GET_DATA_PTO_CLASS_ALL_A($linkMACHWebTrax);
        $QDataB = GET_DATA_PTO_CLASS_ALL_B($linkMACHWebTrax);
        $QDataC = GET_DATA_PTO_CLASS_ALL_C($linkMACHWebTrax);
    }


    $TotalA = sqlsrv_num_rows($QDataA);
    $TotalB = sqlsrv_num_rows($QDataB);
    $TotalC = sqlsrv_num_rows($QDataC);
    $TotalRow = $TotalA + $TotalB + $TotalC;
    $PercentageA = "0";
    if($TotalA == "0"){$PercentageA = "0";}else{$PercentageA = ($TotalA/$TotalRow)*100;}
    $PercentageB = "0";
    if($TotalB == "0"){$PercentageB = "0";}else{$PercentageB = ($TotalB/$TotalRow)*100;}
    $PercentageC = "0";
    if($TotalC == "0"){$PercentageC = "0";}else{$PercentageC = ($TotalC/$TotalRow)*100;}
    $TotalPercentage = $PercentageA + $PercentageB + $PercentageC;
    $TotalPercentage = number_format((float)$TotalPercentage, 0, '.', ',');
    $PercentageA = number_format((float)$PercentageA, 2, '.', ',');
    $PercentageB = number_format((float)$PercentageB, 2, '.', ',');
    $PercentageC = number_format((float)$PercentageC, 2, '.', ',');
    $ArrChart = array();
    $TempArrayA = array(
        "Info" => "PTO more than 10",
        "TotalEmployee" => "".$TotalA."",
        "Percentage" => "".$PercentageA.""
    );
    array_push($ArrChart,$TempArrayA);
    $TempArrayB = array(
        "Info" => "PTO less than 10",
        "TotalEmployee" => "".$TotalB."",
        "Percentage" => "".$PercentageB.""
    );
    array_push($ArrChart,$TempArrayB);
    $TempArrayC = array(
        "Info" => "Negative PTO",
        "TotalEmployee" => "".$TotalC."",
        "Percentage" => "".$PercentageC.""
    );
    array_push($ArrChart,$TempArrayC);

?>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Info', 'TotalEmployee',{type: 'string', role: 'tooltip'}]
        <?php 
        foreach ($ArrChart as $DataChart)
        {
            $ValInfo = $DataChart['Info'];
            $ValTotalEmployee = $DataChart['TotalEmployee'];
            $ValPercentage = $DataChart['Percentage'];
            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
            echo ",['$ValInfo',".$ValTotalEmployee.",'Total (Employee) : $ValTotalEmployee  ($ValPercentage%)']";
        }
        ?>
    ]);
    var options = {
        is3D: true,            
        chartArea: {top:0,height:"80%",width:"100%",bottom:0},
        isStacked:true
                ,focusTarget: 'category'
    };
    var chart = new google.visualization.PieChart(document.getElementById('DataChart'));
    chart.draw(data, options);
    }
</script>
<div class="col-md-12"><h5>Category : <strong><?php echo $ValGroup; ?></strong></h5></div>
<div class="col-md-12"><div id="DataChart" style="width: 600px; height: 300px;"></div></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="ListTableResult">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="10">No</th>
                    <th class="text-center trowCustom">Description</th>
                    <th class="text-center trowCustom" width="100">Total<br>(Employee)</th>
                    <th class="text-center trowCustom" width="100">Percentage<br>(%)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="PointerDataDetail" data-result="<?php echo base64_encode(base64_encode($ValGroup."#A")); ?>">
                    <td class="text-center">1</td>
                    <td class="text-left">PTO more than 10</td>
                    <td class="text-center"><?php echo $TotalA; ?></td>
                    <td class="text-center"><?php echo $PercentageA; ?></td>
                </tr>
                <tr class="PointerDataDetail" data-result="<?php echo base64_encode(base64_encode($ValGroup."#B")); ?>">
                    <td class="text-center">2</td>
                    <td class="text-left">PTO less than 10</td>
                    <td class="text-center"><?php echo $TotalB; ?></td>
                    <td class="text-center"><?php echo $PercentageB; ?></td>
                </tr>
                <tr class="PointerDataDetail" data-result="<?php echo base64_encode(base64_encode($ValGroup."#C")); ?>">
                    <td class="text-center">3</td>
                    <td class="text-left">Negative PTO</td>
                    <td class="text-center"><?php echo $TotalC; ?></td>
                    <td class="text-center"><?php echo $PercentageC; ?></td>
                </tr>
				<tr>
					<td colspan="2" class="text-right"><strong>Total</strong></td>
					<td class="text-center"><?php echo "<strong>".$TotalRow."</strong>"; ?></td>
					<td class="text-center"><?php echo "<strong>".$TotalPercentage."</strong>"; ?></td>
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