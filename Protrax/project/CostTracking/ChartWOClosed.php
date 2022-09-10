<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");

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
    $ValFilter = htmlspecialchars(trim($_POST['ValFilter']), ENT_QUOTES, "UTF-8");
    $ArrFilter = explode("*",$ValFilter);
    $ValSeason = trim($ArrFilter[0]);
    $ValCategory = trim($ArrFilter[1]);    
    # data project
    $QDataProject = GET_TOTAL_ACTUAL_COST_CLOSED_PER_PROJECT($ValSeason,$ValCategory,$linkMACHWebTrax);
    $QDataTotal = GET_TOTAL_ACTUAL_COST_CLOSED_ALL_PROJECT($ValSeason,$ValCategory,$linkMACHWebTrax);
    $ArrResultChart = array();
    if(mssql_num_rows($QDataProject) > 0)
    {
        $TotalData = mssql_fetch_assoc($QDataTotal);
        $ValTotalData = $TotalData['TotalActualCost'];
        $TempArrayChart = array();
        $ResultChart = array();
        $RankCategory = 1;
        if($ValTotalData > 0)
        {
            while($RDataProject = mssql_fetch_assoc($QDataProject))
            {
                $ValProject = trim($RDataProject['Quote']);
                $ValActualCost = trim($RDataProject['ActualCostPerDivision']);
                if($ValActualCost == "0" || $ValTotalData == "0")
                {
                    $ValPercentage = "0";
                }
                else
                {
                    $ValPercentage = ($ValActualCost/$ValTotalData)*100;
                }
                $TempArray = array(
                    "ProjectName" => $ValProject,
                    "ActualCost" => $ValActualCost,
                    "Percentage" => $ValPercentage,
                    "Rank" => $RankCategory
                );
                array_push($TempArrayChart,$TempArray);
                $RankCategory++;
            }
            // echo "<pre>";print_r($TempArrayChart);echo "</pre>";
            $TotalRowRank = count($TempArrayChart);
            if($TotalRowRank > 3)
            {
                $NoLoop = 1;
                $TotalPercentageOthers = 0;
                $TotalActualCostOthers = 0;
                foreach ($TempArrayChart as $TempArrayChart2)
                {
                    $ValProjectName = $TempArrayChart2['ProjectName'];
                    $ValActualCost = $TempArrayChart2['ActualCost'];
                    $ValPercentage = $TempArrayChart2['Percentage'];
                    $ValRank = $TempArrayChart2['Rank'];
                    if($NoLoop < 4)
                    {
                        $TempArray = array(
                            "ProjectName" => $ValProjectName,
                            "ActualCost" => $ValActualCost,
                            "Percentage" => $ValPercentage
                        );
                        array_push($ArrResultChart,$TempArray);
                    }
                    elseif ($NoLoop == $TotalRowRank)
                    {
                        $TotalActualCostOthers = $TotalActualCostOthers + $ValActualCost;
                        $TotalPercentageOthers = $TotalPercentageOthers + $ValPercentage;
                        $TempArray = array(
                            "ProjectName" => "Others",
                            "ActualCost" => $TotalActualCostOthers,
                            "Percentage" => $TotalPercentageOthers
                        );
                        array_push($ArrResultChart,$TempArray);
                    }
                    else
                    {
                        $TotalActualCostOthers = $TotalActualCostOthers + $ValActualCost;
                        $TotalPercentageOthers = $TotalPercentageOthers + $ValPercentage;                    
                    }
                    $NoLoop++;
                }
            }
            else
            {
                foreach ($TempArrayChart as $TempArrayChart2)
                {
                    $ValProjectName = $TempArrayChart2['ProjectName'];
                    $ValActualCost = $TempArrayChart2['ActualCost'];
                    $ValPercentage = $TempArrayChart2['Percentage'];
                    $TempArray = array(
                        "ProjectName" => $ValProjectName,
                        "ActualCost" => $ValActualCost,
                        "Percentage" => $ValPercentage
                    );
                    array_push($ArrResultChart,$TempArray);
                }
            }
            // echo "<pre>";print_r($TempArrayChart);echo "</pre>";
        }
        else 
        {
            $TempArray = array(
                "ProjectName" => "No Data",
                "ActualCost" => "1",
                "Percentage" => "100"
            );
            array_push($ArrResultChart,$TempArray);
        }
    }
    else
    {
        $TempArray = array(
            "ProjectName" => "No Project",
            "ActualCost" => "1",
            "Percentage" => "100"
        );
        array_push($ArrResultChart,$TempArray);
    }
?>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Project', 'Total Actual Cost',{type: 'string', role: 'tooltip'}]
        <?php 
        foreach ($ArrResultChart as $DataChart)
        {
            $ValProjectName = $DataChart['ProjectName'];
            $ValActualCost = $DataChart['ActualCost'];
            $ValPercentage = $DataChart['Percentage'];
            $ValPercentage = number_format((float)$ValPercentage, 1, '.', ',');
            $ValActualCost2 = number_format((float)$ValActualCost, 2, '.', ',');
            echo ",['$ValProjectName',".$ValActualCost.",'$ValProjectName \\n $$ValActualCost2 ($ValPercentage%)']";
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
<div class="col-md-12"><h4>Total Actual Cost (Season : <?php echo $ValSeason; ?>. Category : <?php echo $ValCategory; ?>)</h4></div>
<div class="col-md-12"><div id="DataChart" style="width: 700px; height: 400px;"></div></div>
<div class="col-md-12"><i>*)Total Actual Cost = Labor Cost + Machine Cost + Material Cost + OTS Cost</i></div>
<div class="col-md-12"><h4><strong>Table Total Actual Cost</strong></h4></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableTotalActual">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="30">No</th>
                    <th class="text-center trowCustom">Quote</th>
                    <th class="text-center TargetColumn trowCustom" width="150">Total Actual Cost<br>($)</th>
                    <th class="text-center ActualColumn trowCustom" width="150">Total Actual Cost<br>(%)</th>
                </tr>
            </thead>
            <tbody><?php 
            $NoList = 1;
            $ValTotalCostDigit = 0;
            $ValTotalCostPercentage = 0;
            $QDataProject2 = GET_TOTAL_ACTUAL_COST_CLOSED_PER_PROJECT($ValSeason,$ValCategory,$linkMACHWebTrax);
            $QDataTotal2 = GET_TOTAL_ACTUAL_COST_CLOSED_ALL_PROJECT($ValSeason,$ValCategory,$linkMACHWebTrax);
            $TotalData2 = mssql_fetch_assoc($QDataTotal2);
            $ValTotalData2 = $TotalData2['TotalActualCost'];
            while($RDataProject2 = mssql_fetch_assoc($QDataProject2))
            {
                $ValQuoteRes = trim($RDataProject2['Quote']);
                $ValTotalActualCostRes = trim($RDataProject2['ActualCostPerDivision']);
                if(trim($ValTotalData2)!="")
                {
                    $ValPercentageRes = ($ValTotalActualCostRes/$ValTotalData2)*100;   
                }
                else
                {
                    $ValPercentageRes = "0";
                }
                $ValTotalCostDigit = $ValTotalCostDigit + $ValTotalActualCostRes;
                $ValTotalCostPercentage = $ValTotalCostPercentage + $ValPercentageRes;
                $ValTotalActualCostRes = number_format((float)$ValTotalActualCostRes, 2, '.', ',');
                $ValPercentageRes = number_format((float)$ValPercentageRes, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $NoList; ?></td>
                    <td class="text-left"><?php echo $ValQuoteRes; ?></td>
                    <td class="text-right"><?php echo $ValTotalActualCostRes; ?></td>
                    <td class="text-right"><?php echo $ValPercentageRes; ?></td>
                </tr>
                <?php
                $NoList++;
            }
                $ValTotalCostDigit = number_format((float)$ValTotalCostDigit, 2, '.', ',');
                $ValTotalCostPercentage = number_format((float)$ValTotalCostPercentage, 2, '.', ',');
            ?>
                <tr>
                    <td class="text-right" colspan="2"><strong>Total</strong></td>
                    <td class="text-right"><strong><?php echo $ValTotalCostDigit; ?></strong></td>
                    <td class="text-right"><strong><?php echo $ValTotalCostPercentage; ?></strong></td>
                </tr></tbody>
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