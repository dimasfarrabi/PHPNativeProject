<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");
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
    $arrTop = array();
    $arrChart = array();
    $ValCategory = htmlspecialchars(trim($_POST['ValFilter']), ENT_QUOTES, "UTF-8");
    $ClosedTime = "OPEN";
    $Data = GET_UNQUOTE_OPEN_DATA($ValCategory,$ClosedTime,"CHART","TOP",$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($Data))
    {
        $Quote = trim($res['Quote']);
        array_push($arrTop,$Quote);
        $TotalRun = trim($res['RunTotalCost']);
        $Total = trim($res['Total']);
        $tempArray = array("Quote" => $Quote, "TotalRun" => $TotalRun, "Total" => $Total);
        array_push($arrChart,$tempArray);
    }
    $QNonTop = $arrTop[0]."*".$arrTop[1]."*".$arrTop[2];
    $Data2 = GET_UNQUOTE_OPEN_DATA($ValCategory,$ClosedTime,$QNonTop,"OTHERS",$linkMACHWebTrax);
    while($res2=sqlsrv_fetch_array($Data2))
    {
        $Quote = trim($res2['Quote']);
        $TotalRun = trim($res2['RunTotalCost']);
        $Total = trim($res2['Total']);
        $tempArray = array("Quote" => $Quote, "TotalRun" => $TotalRun, "Total" => $Total);
        array_push($arrChart,$tempArray);
    }
}
foreach($arrChart as $main)
{
    $ValQuote = trim($main['Quote']);
    $ValActualCost = trim($main['TotalRun']);
    $ValTotal = trim($main['Total']);
}
?>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Project', 'Total Actual Cost',{type: 'string', role: 'tooltip'}]
        <?php 
        foreach($arrChart as $main)
        {
            $ValQuote = trim($main['Quote']);
            $ValActualCost = trim($main['TotalRun']);
            $ValTotal = trim($main['Total']);
            $ValPercentage = @($ValActualCost/$ValTotal)*100;
            $ValPercentage = number_format((float)$ValPercentage, 1, '.', ',');
            $ValActualCost2 = number_format((float)$ValActualCost, 2, '.', ',');
            echo ",['$ValQuote',".$ValActualCost.",'$ValQuote \\n $$ValActualCost2 ($ValPercentage%)']";
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
<div class="col-md-12"><h4>Total Actual Cost WO Open (<?php echo $ValCategory; ?>)</h4></div>
<div class="col-md-12"><div id="DataChart" style="width: 700px; height: 400px;"></div></div>
<div class="col-md-12"><i>*)Total Actual Cost = (Labor Cost + Machine Cost + Material Cost) + OTS Cost</i></div>
<div class="col-md-12"><h4><strong>Table Total Actual Cost</strong></h4></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableTotalActual">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="30">No</th>
                    <th class="text-center trowCustom" width="450">Quote</th>
                    <th class="text-center trowCustom">Labor Cost</th>
                    <th class="text-center trowCustom">Machine Cost</th>
                    <th class="text-center trowCustom">Material Cost</th>
                    <th class="text-center trowCustom">OTS Cost</th>
                    <th class="text-center trowCustom">Total Cost</th>
                    <th class="text-center trowCustom" width="100">Qty WO Created</th>
                </tr>
                <tbody>
                    <?php
                    $TotalALL = 0;
                    $no=1;
                    $data3 = GET_UNQUOTE_OPEN_DATA($ValCategory,$ClosedTime,"TABLE","-",$linkMACHWebTrax);
                    while($res3=sqlsrv_fetch_array($data3))
                    {
                        $QuoteName = trim($res3['Quote']);
                        $LaborCost = trim($res3['RunManCost']);
                        $MachCost = trim($res3['RunMachCost']);
                        $MatCost = trim($res3['RunMatCost']);
                        $OTSCost = trim($res3['RunOTSCost']);
                        $TotalActualCost = trim($res3['RunTotalCost']);
                        $TotalALL = @($TotalALL + $TotalActualCost);
                        $LaborCost = number_format((float)$LaborCost, 2, '.', ',');
                        $MachCost = number_format((float)$MachCost, 2, '.', ',');
                        $MatCost = number_format((float)$MatCost, 2, '.', ',');
                        $OTSCost = number_format((float)$OTSCost, 2, '.', ',');
                        $TotalActualCost = number_format((float)$TotalActualCost, 2, '.', ',');
                        $CountWO = COUNT_WO_OPEN("",$QuoteName,$ValCategory,$ClosedTime,$linkMACHWebTrax);
                        $enc = $QuoteName."*".$ClosedTime."*".$ValCategory;
                        ?>
                        <tr class="DataParent" data-float="<?php echo $enc; ?>">
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-left"><?php echo $QuoteName; ?></td>
                            <td class="text-right"><?php echo $LaborCost; ?></td>
                            <td class="text-right"><?php echo $MachCost; ?></td>
                            <td class="text-right"><?php echo $MatCost; ?></td>
                            <td class="text-right"><?php echo $OTSCost; ?></td>
                            <td class="text-right"><?php echo $TotalActualCost; ?></td>
                            <td class="text-right"><?php echo $CountWO; ?></td>
                        </tr>
                        <?php
                        $no++;
                    }
                    ?>
                </tbody>
            </thead>
        </table>
    </div>
</div>
