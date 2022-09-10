<?php  
require_once("project/Employee/Modules/ModuleEmployee.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}
*/
    $ValGroup = "ALL DEPARTMENT";
    $QDataA = GET_DATA_PTO_CLASS_ALL_A($linkMACHWebTrax);
    $QDataB = GET_DATA_PTO_CLASS_ALL_B($linkMACHWebTrax);
    $QDataC = GET_DATA_PTO_CLASS_ALL_C($linkMACHWebTrax);
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

?><script src="project/employee/lib/libempoyeepto.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=22">Employee : Day Off Balance</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="ListTableGroup">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Group</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="PointerListGroup PointerListSelected" data-roles="<?php echo base64_encode(base64_encode("ALL DEPARTMENT")); ?>">
                        <td class="text-left">ALL DEPARTMENT</td>
                    </tr>
                    <tr class="PointerListGroup" data-roles="<?php echo base64_encode(base64_encode("ADMINISTRATION")); ?>">
                        <td class="text-left">ADMINISTRATION</td>
                    </tr>
                    <tr class="PointerListGroup" data-roles="<?php echo base64_encode(base64_encode("ENGINEERING")); ?>">
                        <td class="text-left">ENGINEERING</td>
                    </tr>
                    <tr class="PointerListGroup" data-roles="<?php echo base64_encode(base64_encode("PRODUCTION")); ?>">
                        <td class="text-left">PRODUCTION</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultChart">
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
                var chart = new google.visualization.PieChart(document.getElementById('DataCharts'));
                chart.draw(data, options);
                }
            </script>
            <div class="col-md-12"><h5>Category : <strong><?php echo $ValGroup; ?></strong></h5></div>
            <div class="col-md-12"><div id="DataCharts" style="width: 600px; height: 300px;"></div></div>
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
        </div>
        <div class="row" id="DetailChart"></div>
    </div>
</div>