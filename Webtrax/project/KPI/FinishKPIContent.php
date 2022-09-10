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
$UpData = GET_LAST_UPDATE($linkMACHWebTrax);
while($UpDataRes = sqlsrv_fetch_array($UpData))
{
    $DateRecalculateLog = trim($UpDataRes['DateCreated']);
}
?>
    <div class="col-md-9">
	<span><i> Last Updated : <?php echo $DateRecalculateLog; ?></i></span>
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
                ['Month', 'Fastest', 'Median', 'Longest',{type: 'string', role: 'tooltip'}]
                <?php
                foreach($ArrMonth as $ArrResult)
                {
                $ValMonth = trim($ArrResult['Month']);
                $ValMonth2 = trim($ArrResult['Month2']);
                $Data = GET_DATA_CHART_FINISHING_KPI("PSL",$ValMonth,$linkMACHWebTrax);
                while($DataRes = sqlsrv_fetch_array($Data))
                {
                    $ValFast = abs(trim($DataRes['DiffMin']));
                    $ValAvg = abs(trim($DataRes['DiffAvg']));
                    $ValLong = abs(trim($DataRes['DiffMax']));
                    $ValLong3 = abs(trim($DataRes['DiffMax']));
                    if($ValLong > 30){$ValLong = 29.3;}
                }
                ?>
                ,['<?php echo $ValMonth2; ?>', <?php echo $ValFast; ?>, <?php echo $ValAvg; ?>, <?php echo $ValLong; ?>,'<?php echo "$ValMonth2 \\n Longest : $ValLong3"; ?>']
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
                title: 'Finishing Process - PSL',
                titleTextStyle: {fontSize: 18, bold: true},
                vAxis: { minValue: 0, title : 'Days', textStyle: { fontSize: 12 }},
                chartArea: {top:50,height:"80%",width:"80%"},
                legend: { textStyle: { fontSize: 12 }}
            };
        var chart = new google.visualization.ColumnChart(document.getElementById('ChartKPI'));
        chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {
        callback: function () {
            drawChart2();
            window.addEventListener('resize', drawChart, false);
        },
        packages:['corechart']
        });

        function drawChart2() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Fastest', 'Median', 'Longest',{type: 'string', role: 'tooltip'}]
                <?php
                foreach($ArrMonth as $ArrResult)
                {
                $ValMonth = trim($ArrResult['Month']);
                $ValMonth2 = trim($ArrResult['Month2']);
                $Data = GET_DATA_CHART_FINISHING_KPI("PSM",$ValMonth,$linkMACHWebTrax);
                while($DataRes = sqlsrv_fetch_array($Data))
                {
                    $ValFast = abs(trim($DataRes['DiffMin']));
                    $ValAvg = abs(trim($DataRes['DiffAvg']));
                    $ValLong = abs(trim($DataRes['DiffMax']));
                    $ValLong3 = abs(trim($DataRes['DiffMax']));
                    if($ValLong > 30){$ValLong = 29.3;}
                }
                ?>
                ,['<?php echo $ValMonth2; ?>', <?php echo $ValFast; ?>, <?php echo $ValAvg; ?>, <?php echo $ValLong; ?>,'<?php echo "$ValMonth2 \\n Longest : $ValLong3"; ?>']
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
                title: 'Finishing Process - PSM',
                titleTextStyle: {fontSize: 18, bold: true},
                vAxis: { minValue: 0, title : 'Days' , textStyle: { fontSize: 12 }},
                chartArea: {top:50,height:"80%",width:"80%"},
                legend: { textStyle: { fontSize: 12 }}
            };
        var chart2 = new google.visualization.ColumnChart(document.getElementById('ChartKPI_PSM'));
        chart2.draw(data, options);
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
                    $Data = GET_DATA_CHART_FINISHING_KPI("PSL",$ValMonth,$linkMACHWebTrax);
                    while($DataRes = sqlsrv_fetch_array($Data))
                        {
                            $ValFast = abs(trim($DataRes['DiffMin']));
                            $ValAvg = abs(trim($DataRes['DiffAvg']));
                            $ValLong = abs(trim($DataRes['DiffMax']));
                            $RowEnc = base64_encode($ValMonth."*PSL");
                            $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#DetailFinProcess" title="Detail"></span>'
                
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
    <div class="col-md-9">
        <div class="ColumnContent" id="ChartKPI_PSM"></div>
    </div>
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
                    $Data = GET_DATA_CHART_FINISHING_KPI("PSM",$ValMonth,$linkMACHWebTrax);
                    while($DataRes = sqlsrv_fetch_array($Data))
                        {
                            $ValFast = abs(trim($DataRes['DiffMin']));
                            $ValAvg = abs(trim($DataRes['DiffAvg']));
                            $ValLong = abs(trim($DataRes['DiffMax']));
                            $RowEnc = base64_encode($ValMonth."*PSM");
                            $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#DetailFinProcess" title="Detail"></span>'
                
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