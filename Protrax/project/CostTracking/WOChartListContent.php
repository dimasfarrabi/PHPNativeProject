<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");

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
    $ValCategoryEnc = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValProjectEnc = htmlspecialchars(trim($_POST['ValProject']), ENT_QUOTES, "UTF-8");
    $CategoryDec = base64_decode(base64_decode($ValCategoryEnc));
    $ProjectDec = base64_decode(base64_decode($ValProjectEnc));
    $ArrCategory = explode("#",$CategoryDec);
    $ArrProject = explode("#",$ProjectDec);
    $ValQuoteCategory = trim($ArrCategory[1]);
    $ValStatusCategory = trim($ArrCategory[2]);
    $ValProjectName = trim($ArrProject[0]);
    $ValFirstLocation = trim($ArrProject[1]);
    // echo "Status : $ValStatusCategory >> Category : $ValQuoteCategory >> ProjectName : $ValProjectName >> Location : $ValFirstLocation";

    # data session
    $ArrSession = array();
    $QListSession = GET_ALL_TYPE_CLOSED_TIME($linkMACHWebTrax);
    while($RListSession = mssql_fetch_assoc($QListSession))
    {
        $ValListSession = trim($RListSession['ClosedTime']);
        $BolLoopSession = FALSE;
        foreach($ArrSession as $ListSession)
        {
            $ValArrSession = trim($ListSession['ClosedTime']);
            if($ValArrSession == $ValListSession)
            {
                $BolLoopSession = TRUE;
            }
        }
        if($BolLoopSession == FALSE)
        {
            $TempNewSession = array(
                'ClosedTime' => $ValListSession
            );
            array_push($ArrSession,$TempNewSession);
        }
    }
    if($ValStatusCategory == "CLOSE")
    {
        # chart 1 : compare total target cost & total actual cost
        $ArrResultChart1 = array();
        foreach ($ArrSession as $ListSession) 
        {
            $ValSession = trim($ListSession['ClosedTime']);
            $QDataChart1 = DATA_CHART_TOTAL_TARGET_COST_VS_TOTAL_ACTUAL_COST($ValSession,$ValProjectName,$linkMACHWebTrax);
            if(mssql_num_rows($QDataChart1) != "0")
            {
                while($RDataChart1 = mssql_fetch_assoc($QDataChart1))
                {
                    $ValTotalTargetCostChart1 = trim($RDataChart1['TotalTargetCost']);
                    if($ValTotalTargetCostChart1 == ""){$ValTotalTargetCostChart1 = "0";}
                    $ValTotalActualCostChart1 = trim($RDataChart1['TotalActualCost']);
                    if($ValTotalActualCostChart1 == ""){$ValTotalActualCostChart1 = "0";}
                    $TempArrayChart1 = array(
                        "Half" => $ValSession,
                        "TotalTargetCost" => $ValTotalTargetCostChart1,
                        "TotalActualCost" => $ValTotalActualCostChart1
                    );
                    array_push($ArrResultChart1,$TempArrayChart1);
                }
            }
            else
            {
                $TempArrayChart1 = array(
                    "Half" => $ValSession,
                    "TotalTargetCost" => "0",
                    "TotalActualCost" => "0"
                );
                array_push($ArrResultChart1,$TempArrayChart1);
            }
        }
        # chart 2 : compare qty build per half
        $ArrResultChart2 = array();
        foreach ($ArrSession as $ListSession)
        {
            $ValSession = trim($ListSession['ClosedTime']);
            $QDataChart2 = GET_QTY_BUILT_PER_HALF($ValProjectName,$ValSession,$linkMACHWebTrax);
            if(mssql_num_rows($QDataChart2) != "0")
            {
                while($RDataChart2 = mssql_fetch_assoc($QDataChart2))
                {
                    $ValQtyBuiltChart2 = trim($RDataChart2['QtyBuilt']);
                    if($ValQtyBuiltChart2 == ""){$ValQtyBuiltChart2 = "0";}
                    $ValQtyTarget2 = trim($RDataChart2['QtyTarget']);
                    if($ValQtyTarget2 == ""){$ValQtyTarget2 = "0";}
                    $TempArrayChart2 = array(
                        "Half" => $ValSession,
                        "TotalQtyBuilt" => $ValQtyBuiltChart2,
                        "TotalQtyTarget" => $ValQtyTarget2
                    );
                    array_push($ArrResultChart2,$TempArrayChart2);
                }
            }
            else
            {
                $TempArrayChart2 = array(
                    "Half" => $ValSession,
                    "TotalQtyBuilt" => "0",
                    "TotalQtyTarget" => "0"
                );
                array_push($ArrResultChart2,$TempArrayChart2);
            }
        }
        # chart 3 : compare (Total OTS + (Qty Built x Total Actual Cost))
        $ArrResultChart3 = array();
        foreach ($ArrSession as $ListSession)
        {
            $ValSession = trim($ListSession['ClosedTime']);
            $QDataChart3 = GET_TOTAL_OTS_AND_ACTUAL_COST_PER_HALF($ValProjectName,$ValSession,$linkMACHWebTrax);
            if(mssql_num_rows($QDataChart3) != "0")
            {
                while($RDataChart3 = mssql_fetch_assoc($QDataChart3))
                {
                    $ValTotalActualCostChart3 = trim($RDataChart3['TotalActualCost']);
                    if(trim($ValTotalActualCostChart3 == "")){$ValTotalActualCostChart3 = "0";}
                    if($ValTotalActualCostChart3 == ""){$ValTotalActualCostChart3 = "0";}
                    $ValTotalOTSChart3 = trim($RDataChart3['TotalOTS']);
                    if(trim($ValTotalOTSChart3 == "")){$ValTotalOTSChart3 = "0";}
                    if($ValTotalOTSChart3 == ""){$ValTotalOTSChart3 = "0";}
                    $ValTotalQtyBuiltChart3 = trim($RDataChart3['TotalQtyBuilt']);
                    if(trim($ValTotalQtyBuiltChart3 == "")){$ValTotalQtyBuiltChart3 = "0";}
                    if($ValTotalQtyBuiltChart3 == ""){$ValTotalQtyBuiltChart3 = "0";}
                    $ValTotalCalculateChart3 = $ValTotalOTSChart3 + ($ValTotalQtyBuiltChart3 * $ValTotalActualCostChart3);
                    $TempArrayChart3 = array(
                        "Half" => $ValSession,
                        "TotalOTS" => $ValTotalOTSChart3,
                        "TotalActualCost" => $ValTotalActualCostChart3,
                        "TotalQtyBuilt" => $ValTotalQtyBuiltChart3,
                        "TotalCalculate" => $ValTotalCalculateChart3
                    );
                    array_push($ArrResultChart3,$TempArrayChart3);
                }
            }
            else
            {
                $TempArrayChart3 = array(
                    "Half" => $ValSession,
                    "TotalOTS" => "0",
                    "TotalActualCost" => "0",
                    "TotalQtyBuilt" => "0",
                    "TotalCalculate" => "0"
                );
                array_push($ArrResultChart3,$TempArrayChart3); 
            }
        }
    }
    else
    {
        # chart 1 : compare total target cost & total actual cost
        $ArrResultChart1 = array();
        $ValSession = "OPEN";
        $QDataChart1 = DATA_CHART_TOTAL_TARGET_COST_VS_TOTAL_ACTUAL_COST_OPEN($ValProjectName,$linkMACHWebTrax);
        if(mssql_num_rows($QDataChart1) != "0")
        {
            while($RDataChart1 = mssql_fetch_assoc($QDataChart1))
            {
                $ValTotalTargetCostChart1 = trim($RDataChart1['TotalTargetCost']);
                if($ValTotalTargetCostChart1 == ""){$ValTotalTargetCostChart1 = "0";}
                $ValTotalActualCostChart1 = trim($RDataChart1['TotalActualCost']);
                if($ValTotalActualCostChart1 == ""){$ValTotalActualCostChart1 = "0";}
                $TempArrayChart1 = array(
                    "Half" => $ValSession,
                    "TotalTargetCost" => $ValTotalTargetCostChart1,
                    "TotalActualCost" => $ValTotalActualCostChart1
                );
                array_push($ArrResultChart1,$TempArrayChart1);
            }
        }
        else
        {
            $TempArrayChart1 = array(
                "Half" => $ValSession,
                "TotalTargetCost" => "0",
                "TotalActualCost" => "0"
            );
            array_push($ArrResultChart1,$TempArrayChart1);
        }
        # chart 2 : compare qty build per half        
        $ArrResultChart2 = array();
        $QDataChart2 = GET_QTY_BUILT_PER_HALF($ValProjectName,$ValSession,$linkMACHWebTrax);
        if(mssql_num_rows($QDataChart2) != "0")
        {
            while($RDataChart2 = mssql_fetch_assoc($QDataChart2))
            {
                $ValQtyBuiltChart2 = trim($RDataChart2['QtyBuilt']);
                if($ValQtyBuiltChart2 == ""){$ValQtyBuiltChart2 = "0";}
                $ValQtyTarget2 = trim($RDataChart2['QtyTarget']);
                if($ValQtyTarget2 == ""){$ValQtyTarget2 = "0";}
                $TempArrayChart2 = array(
                    "Half" => $ValSession,
                    "TotalQtyBuilt" => $ValQtyBuiltChart2,
                    "TotalQtyTarget" => $ValQtyTarget2
                );
                array_push($ArrResultChart2,$TempArrayChart2);
            }
        }
        else
        {
            $TempArrayChart2 = array(
                "Half" => $ValSession,
                "TotalQtyBuilt" => "0",
                "TotalQtyTarget" => "0"
            );
            array_push($ArrResultChart2,$TempArrayChart2);
        }
        # chart 3 : compare (Total OTS + (Qty Built x Total Actual Cost))
        $ArrResultChart3 = array();
        $QDataChart3 = GET_TOTAL_OTS_AND_ACTUAL_COST_PER_HALF_OPEN($ValProjectName,$linkMACHWebTrax);
        if(mssql_num_rows($QDataChart3) != "0")
        {
            while($RDataChart3 = mssql_fetch_assoc($QDataChart3))
            {
                $ValTotalActualCostChart3 = trim($RDataChart3['TotalActualCost']);
                if(trim($ValTotalActualCostChart3 == "")){$ValTotalActualCostChart3 = "0";}
                if($ValTotalActualCostChart3 == ""){$ValTotalActualCostChart3 = "0";}
                $ValTotalOTSChart3 = trim($RDataChart3['TotalOTS']);
                if(trim($ValTotalOTSChart3 == "")){$ValTotalOTSChart3 = "0";}
                if($ValTotalOTSChart3 == ""){$ValTotalOTSChart3 = "0";}
                $ValTotalQtyBuiltChart3 = trim($RDataChart3['TotalQtyBuilt']);
                if(trim($ValTotalQtyBuiltChart3 == "")){$ValTotalQtyBuiltChart3 = "0";}
                if($ValTotalQtyBuiltChart3 == ""){$ValTotalQtyBuiltChart3 = "0";}
                $ValTotalCalculateChart3 = $ValTotalOTSChart3 + ($ValTotalQtyBuiltChart3 * $ValTotalActualCostChart3);
                $TempArrayChart3 = array(
                    "Half" => $ValSession,
                    "TotalOTS" => $ValTotalOTSChart3,
                    "TotalActualCost" => $ValTotalActualCostChart3,
                    "TotalQtyBuilt" => $ValTotalQtyBuiltChart3,
                    "TotalCalculate" => $ValTotalCalculateChart3
                );
                array_push($ArrResultChart3,$TempArrayChart3);
            }
        }
        else
        {
            $TempArrayChart3 = array(
                "Half" => $ValSession,
                "TotalOTS" => "0",
                "TotalActualCost" => "0",
                "TotalQtyBuilt" => "0",
                "TotalCalculate" => "0"
            );
            array_push($ArrResultChart3,$TempArrayChart3); 
        }

    }

    


    ?>
<style>
.ColumnContent{width:800px; height: 500px;}
.ResponsiveContent{overflow-y: hidden;}
.HorizontalLine{margin: 0em;}
</style>
<script type="text/javascript">
    function ToolTipTotalTargetCost(ValueTotal,Session)
    {
        return '<div><strong>Total Target Cost ('+Session+') : $'+ValueTotal+'</strong></div>';
    }
    function ToolTipTotalActualCost(ValueTotal,Session)
    {
        return '<div><strong>Total Actual Cost ('+Session+') : $'+ValueTotal+'</strong></div>';
    }

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart1);

    function drawChart1() {
    var data1 = new google.visualization.DataTable();
    data1.addColumn('string', 'Half');
    data1.addColumn('number', 'Target Cost');
    data1.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
    data1.addColumn('number', 'Actual Cost');
    data1.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
    data1.addRows([
    // ['2010',1000,'<strong>Total Target Cost : $1000</strong>',400,'<strong>Total Actual Cost  : $400</strong>'],
    // ['2011',1500,'<strong>Total Target Cost : $1500</strong>',460,'<strong>Total Actual Cost : $460</strong>'],
    // ['2012',800,'<strong>Total Target Cost : $800</strong>',1120,'<strong>Total Actual Cost : $1120</strong>'],
    // ['2013',1000,'<strong>Total Target Cost : $1000 </strong>',540,'<strong>Total Actual Cost : $540</strong>'],
    // ['2015',1500,'<strong>Total Target Cost : $1500</strong>',460,'<strong>Total Actual Cost : $460</strong>'],
    // ['2016',800,'<strong>Total Target Cost : $800</strong>',1120,'<strong>Total Actual Cost : $1120</strong>'],
    // ['2017',1000,'<strong>Total Target Cost : $1000 </strong>',540,'<strong>Total Actual Cost : $540</strong>']
    <?php
    $Loop1 = 1;
    foreach ($ArrResultChart1 as $DataResultChart1)
    {
        $ValResultChart1Half = trim($DataResultChart1['Half']);
        $ValResultChart1TotalTargetCost = trim($DataResultChart1['TotalTargetCost']);
        $ValResultChart1TotalActualCost = trim($DataResultChart1['TotalActualCost']);
        $ValResultChart1TotalTargetCost2 = number_format((float)$ValResultChart1TotalTargetCost, 2, '.', ',');
        $ValResultChart1TotalActualCost2 = number_format((float)$ValResultChart1TotalActualCost, 2, '.', ',');
        if($Loop1 == 1)
        {
            ?>
            ['<?php echo $ValResultChart1Half; ?>',<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalTargetCost(<?php echo "'".$ValResultChart1TotalTargetCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>'),<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalActualCost(<?php echo "'".$ValResultChart1TotalActualCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>')]
            <?php
        }
        else
        {
            ?>
            ,['<?php echo $ValResultChart1Half; ?>',<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalTargetCost(<?php echo "'".$ValResultChart1TotalTargetCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>'),<?php echo $ValResultChart1TotalActualCost; ?>,ToolTipTotalActualCost(<?php echo "'".$ValResultChart1TotalActualCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>')]
            <?php
        }
        $Loop1++;
    }

?>
]);
    var options1 = {
            title: 'Chart Target Cost Vs Actual Cost Per Unit'
            ,titleTextStyle: {fontSize: 18, bold: true}
            <?php 
            if($ValStatusCategory == "CLOSE")
            {
                echo ",hAxis: {slantedText:true,slantedTextAngle:-45}";
                // echo ",hAxis: {slantedText:true,slantedTextAngle:-45,title: 'Usage',textPosition: 'none'}";
            }
            ?>
            ,vAxis: { minValue: 0}
            ,tooltip: { isHtml: true }
            ,legend: { position: 'top', maxLines: 3 }
            ,chartArea: {top:50,height:"80%",width:"80%"}
            // ,hAxis: { textPosition: 'none' }
    };
    var chart1 = new google.visualization.ColumnChart(document.getElementById('columnchart_material1'));
    chart1.draw(data1, options1);
}
</script>

<script type="text/javascript">
    function ToolTipTotalQtyBuilt(ValueTotal,Session)
    {
        return '<div><strong>Total Qty Built ('+Session+') : '+ValueTotal+'</strong></div>';
    }
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart2);

    function drawChart2() {
        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'Half');
        data2.addColumn('number', 'Total Qty Built');
        data2.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
        data2.addRows([
        <?php
        $Loop2 = 1;
        foreach ($ArrResultChart2 as $DataResultChart2)
        {
            $ValResultChart2Half = trim($DataResultChart2['Half']);
            $ValResultChart2TotalQtyBuilt = trim($DataResultChart2['TotalQtyBuilt']);
            $ValResultChart2TotalQtyTarget = trim($DataResultChart2['TotalQtyTarget']);
            $ValResultChart2TotalQtyBuilt2 = number_format((float)$ValResultChart2TotalQtyBuilt, 0, '.', ',');
            $ValResultChart2TotalQtyTarget2 = number_format((float)$ValResultChart2TotalQtyTarget, 0, '.', ',');
            if($Loop2 == 1)
            {
                ?>
                ['<?php echo $ValResultChart2Half; ?>',<?php echo $ValResultChart2TotalQtyBuilt; ?>,ToolTipTotalQtyBuilt(<?php echo "'".$ValResultChart2TotalQtyBuilt2."'"; ?>,'<?php echo $ValResultChart2Half; ?>')]
                <?php
            }
            else
            {
                ?>
                ,['<?php echo $ValResultChart2Half; ?>',<?php echo $ValResultChart2TotalQtyBuilt; ?>,ToolTipTotalQtyBuilt(<?php echo "'".$ValResultChart2TotalQtyBuilt2."'"; ?>,'<?php echo $ValResultChart2Half; ?>')]
                <?php
            }
            $Loop2++;
        }

    ?>
    ]);
    var options2 = {
            title: 'Chart Total Qty Built QA Per Half'
            ,titleTextStyle: {fontSize: 18, bold: true}
            <?php 
            if($ValStatusCategory == "CLOSE")
            {
                echo ',hAxis: {slantedText:true,slantedTextAngle:-45}';
            }
            ?>
            ,vAxis: { minValue: 0}
            ,tooltip: { isHtml: true }
            ,legend: { position: 'top', maxLines: 3 }
            ,chartArea: {top:50,height:"80%",width:"80%"}
    };
    var chart2 = new google.visualization.ColumnChart(document.getElementById('columnchart_material2'));
    chart2.draw(data2, options2);
}
</script>

<script type="text/javascript">
    function ToolTipTotalCombine(ValueTotal,Session)
    {
        return '<div><strong>Total Cost ('+Session+') : $'+ValueTotal+'</strong></div>';
    }
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart3);

    function drawChart3() {
        var data3 = new google.visualization.DataTable();
        data3.addColumn('string', 'Half');
        data3.addColumn('number', 'Total OTS + (Qty Built x Total Actual Cost)');
        data3.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
        data3.addRows([<?php
        $Loop3 = 1;
        foreach ($ArrResultChart3 as $DataResultChart3)   
        {
            $ValResultChart3Half = trim($DataResultChart3['Half']);
            $ValResultChart3TotalOTS = trim($DataResultChart3['TotalOTS']);
            $ValResultChart3TotalActualCost = trim($DataResultChart3['TotalActualCost']);
            $ValResultChart3TotalQtyBuilt = trim($DataResultChart3['TotalQtyBuilt']);
            $ValResultChart3TotalCalculate = trim($DataResultChart3['TotalCalculate']);
            $ValResultChart3TotalOTS2 = number_format((float)$ValResultChart3TotalOTS, 2, '.', ',');
            $ValResultChart3TotalActualCost2 = number_format((float)$ValResultChart3TotalActualCost, 2, '.', ',');
            $ValResultChart3TotalQtyBuilt = number_format((float)$ValResultChart3TotalQtyBuilt, 2, '.', ',');
            $ValResultChart3TotalCalculate2 = number_format((float)$ValResultChart3TotalCalculate, 2, '.', ',');
            if($Loop3 == 1)
            {
                ?>
                ['<?php echo $ValResultChart3Half; ?>',<?php echo $ValResultChart3TotalCalculate; ?>,ToolTipTotalCombine(<?php echo "'".$ValResultChart3TotalCalculate2."'"; ?>,'<?php echo $ValResultChart3Half; ?>')]
                <?php
            }
            else
            {
                ?>
                ,['<?php echo $ValResultChart3Half; ?>',<?php echo $ValResultChart3TotalCalculate; ?>,ToolTipTotalCombine(<?php echo "'".$ValResultChart3TotalCalculate2."'"; ?>,'<?php echo $ValResultChart3Half; ?>')]
                <?php
            }
            $Loop3++;
        }            
        ?>]);
        var options3 = {
                title: 'Chart (Total OTS + (Qty Built x Total Actual Cost)) Per Half'
                ,titleTextStyle: {fontSize: 18, bold: true}
                <?php 
                if($ValStatusCategory == "CLOSE")
                {
                    echo ',hAxis: {slantedText:true,slantedTextAngle:-45}';
                }
                ?>
                ,vAxis: { minValue: 0}
                ,tooltip: { isHtml: true }
                ,legend: { position: 'top', maxLines: 3 }
                ,chartArea: {top:50,height:"80%",width:"80%"}
        };
        var chart3 = new google.visualization.ColumnChart(document.getElementById('columnchart_material3'));
        chart3.draw(data3, options3);
    }
</script>



<div class="col-md-12"><h4>Project : <?php echo  '<strong>'.$ValProjectName.'</strong>'; ?></h4></div>
<div class="col-md-12"><hr class="HorizontalLine"></div>
<div class="col-md-12"><div class="table-responsive ResponsiveContent"><div class="ColumnContent" id="columnchart_material1"></div></div></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><div class="table-responsive ResponsiveContent"><div class="ColumnContent" id="columnchart_material2"></div></div></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12"><div class="table-responsive ResponsiveContent"><div class="ColumnContent" id="columnchart_material3"></div></div></div>
<div class="col-md-12">&nbsp;</div>


    
    <?php

}
else
{
    echo "";    
}
?>