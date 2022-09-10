<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleKPI.php"); 
date_default_timezone_set("Asia/Jakarta");

$Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
$LastYear = date("m/d/Y H:i:s",strtotime("-1 year"));
# get date
$StartTime = date("m/d/Y 00:00:00",strtotime($LastYear));
$EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));

$ArrMonth = array();
for ($i = 0; $i <= 12; $i++) 
{
   $months =array("Month" => date("Y-m", strtotime( $Yesterday." -$i months")), 
   "Month2" => date("M,Y", strtotime($Yesterday." -$i months")));
   array_push($ArrMonth,$months);
}
asort($ArrMonth);
$ValDummy = "12";
?>
    <div class="col-md-9">
        <div class="ColumnContent" id="ChartKPI"></div>
    </div>
    
    <script type="text/javascript">
        google.charts.load('current', {
        callback: function () {
            drawChart();
            window.addEventListener('resize', drawChart, false);
        },
        packages:['corechart']
        });

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Fastest', 'Median', 'Longest']
                <?php
                foreach($ArrMonth as $ArrResult)
                {
                $ValMonth = trim($ArrResult['Month']);
                $ValMonth2 = trim($ArrResult['Month2']);
                $Data = GET_DATA_CHART_INJECTION_KPI2($ValMonth,$linkMACHWebTrax);
                while($DataRes = mssql_fetch_assoc($Data))
                {
                    $ValFast = abs(trim($DataRes['MinDiff']));
                    $ValAvg = abs(trim($DataRes['AvgDiff']));
                    $ValLong = abs(trim($DataRes['MaxDiff']));
                }
                ?>
                ,['<?php echo $ValMonth2; ?>', <?php echo $ValFast; ?>, <?php echo $ValAvg; ?>, <?php echo $ValLong; ?>]
                <?php
                }
                ?>
                
            ]);

            var options = {
                animation:{
                duration: 300,
                easing: 'linear',
                startup: true
                },
                height: 450,
                hAxis: {
                title: data.getColumnLabel(0), textStyle: { fontSize: 12 }
                },
                series: {
                        0: {color:'#3366CC'},
                        1: {color:'#DC3912'},
                        2: {color:'#FF9900'}
                    },
                title: 'Injection Process',
                titleTextStyle: {fontSize: 18, bold: true},
                vAxis: { minValue: 0, title : 'Days', textStyle: { fontSize: 12 }},
                chartArea: {top:50,height:"80%",width:"80%"},
                legend: { textStyle: { fontSize: 12 }}
            };
        var chart = new google.visualization.ColumnChart(document.getElementById('ChartKPI'));
        chart.draw(data, options);
        }
    </script>
    <div class="col-md-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableCutter">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center trowCustom">Month</th>
                        <th class="text-center trowCustom">Fastest (Day)</th>
                        <th class="text-center trowCustom">Median (Day)</th>
                        <th class="text-center trowCustom">Longest (Day)</th>
                        <th class="text-center trowCustom">#</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach($ArrMonth as $ArrResult)
                    {
                    $ValMonth = trim($ArrResult['Month']);
                    $ValMonth2 = trim($ArrResult['Month2']);
                        $Data = GET_DATA_CHART_INJECTION_KPI2($ValMonth,$linkMACHWebTrax);
                        while($DataRes = mssql_fetch_assoc($Data))
                        {
                            $ValFast = abs(trim($DataRes['MinDiff']));
                            $ValAvg = abs(trim($DataRes['AvgDiff']));
                            $ValLong = abs(trim($DataRes['MaxDiff']));
                            $RowEnc = base64_encode($ValMonth."*".$ValMonth2);
                            $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#DetailInjection2" title="Detail"></span>'
                            ?>
                            <tr class="RowRecon" data-cookies="<?php echo $RowEnc; ?>">
                                <td class="text-center"><?php echo $ValMonth2; ?></td>
                                <td class="text-center"><?php echo $ValFast; ?></td>
                                <td class="text-center"><?php echo $ValAvg; ?></td>
                                <td class="text-center"><?php echo $ValLong; ?></td>
                                <td class="text-center"><?php echo $ValOptForm; ?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
