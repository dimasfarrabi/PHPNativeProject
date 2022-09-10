<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");
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
    $ValCategoryEnc = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValProjectEnc = htmlspecialchars(trim($_POST['ValProject']), ENT_QUOTES, "UTF-8");
    $CategoryDec = base64_decode(base64_decode($ValCategoryEnc));
    $ProjectDec = base64_decode(base64_decode($ValProjectEnc));
    $ArrCategory = explode("#",$CategoryDec);
    $ArrProject = explode("#",$ProjectDec);
    $ValQuoteCategory = trim($ArrCategory[1]);
    $ValStatusCategory = trim($ArrCategory[2]);
    $ValProjectName = trim($ArrProject[0]);
    $ValQuoteID = trim($ArrProject[1]);
    $ValArrQuoteCategory = trim($ArrProject[2]);
    // echo "$ValQuoteCategory >> $ValStatusCategory >> $ValProjectName >> $ValQuoteID >> $ValArrQuoteCategory";
    # data session
    $ArrSession = array();
    $ArrSession2 = array();
    $QListSession = GET_ALL_TYPE_CLOSED_TIME($linkMACHWebTrax);
    while($RListSession = sqlsrv_fetch_array($QListSession))
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
    $QListSession = GET_ALL_TYPE_CLOSED_TIME($linkMACHWebTrax);
    while($RListSession = sqlsrv_fetch_array($QListSession))
    {
        $arr = explode("-",trim($RListSession['ClosedTime']));
        $ValListSession = $arr[0];
        array_push($ArrSession2,$ValListSession);
    }
    $ArrayYear = array_unique($ArrSession2);
    # data chart
    $ArrAllData = array();
    $QDataAll = GET_DATA_TOTAL_WO_CLOSED_COST_WEBTRAX($ValQuoteID,$ValQuoteCategory,$linkMACHWebTrax);
    while($RDataAll = sqlsrv_fetch_array($QDataAll))
    {
        $ArrTemp = array(
            "QuoteID" => trim($RDataAll['QuoteID']),
            "Quote" => trim($RDataAll['Quote']),
            "QuoteCategory" => trim($RDataAll['QuoteCategory']),
            "TargetHalfClosed" => trim($RDataAll['TargetHalfClosed']),
            "TotalTargetCost" => trim($RDataAll['TotalTargetCost']),
            "TotalActualCost" => trim($RDataAll['TotalActualCost']),
            "TotalQtyBuilt" => trim($RDataAll['TotalQtyBuilt']),
            "TotalQtyTarget" => trim($RDataAll['TotalQtyTarget']),
            "TotalOTS" => trim($RDataAll['TotalOTS']),
            "TotalCalculate" => trim($RDataAll['TotalCalculate'])
        );
        array_push($ArrAllData,$ArrTemp);
    }
    $ArrAllData2 = array();
    foreach($ArrSession as $Session)
    {
        $ValSession = trim($Session['ClosedTime']);
        $ValHalf = $ValSession;
        $ValTotalTargetCost = 0;
        $ValTotalActualCost = 0;
        $ValTotalQtyBuilt = 0;
        $ValTotalQtyTarget = 0;
        $ValTotalOTS = 0;
        $ValTotalCalculate = 0;
        foreach($ArrAllData as $AllData)
        {
            if($ValSession == $AllData['TargetHalfClosed'])
            {
                $ValHalf = trim($AllData['TargetHalfClosed']);
                $ValTotalTargetCost = $ValTotalTargetCost + trim($AllData['TotalTargetCost']);
                $ValTotalActualCost = $ValTotalActualCost + trim($AllData['TotalActualCost']);
                $ValTotalQtyBuilt = $ValTotalQtyBuilt + trim($AllData['TotalQtyBuilt']);
                $ValTotalQtyTarget = $ValTotalQtyTarget + trim($AllData['TotalQtyTarget']);
                $ValTotalOTS = $ValTotalOTS + trim($AllData['TotalOTS']);
                $ValTotalCalculate = $ValTotalCalculate + trim($AllData['TotalCalculate']);
            }
        }        
        $ValTotalTargetCost = round($ValTotalTargetCost,6);
	    $ValTotalTargetCost = sprintf('%.2f',floatval($ValTotalTargetCost));
        $ValTotalActualCost = round($ValTotalActualCost,6);
	    $ValTotalActualCost = sprintf('%.2f',floatval($ValTotalActualCost));
        $ValTotalQtyBuilt = round($ValTotalQtyBuilt,6);
	    $ValTotalQtyBuilt = sprintf('%.2f',floatval($ValTotalQtyBuilt));
        $ValTotalQtyTarget = round($ValTotalQtyTarget,6);
	    $ValTotalQtyTarget = sprintf('%.2f',floatval($ValTotalQtyTarget));
        $ValTotalOTS = round($ValTotalOTS,6);
	    $ValTotalOTS = sprintf('%.2f',floatval($ValTotalOTS));
        $ValTotalCalculate = round($ValTotalCalculate,6);
	    $ValTotalCalculate = sprintf('%.2f',floatval($ValTotalCalculate));

        $TempArray2 = array(
            "Half" => $ValHalf,
            "TotalTargetCost" => $ValTotalTargetCost,
            "TotalActualCost" => $ValTotalActualCost,
            "TotalQtyBuilt" => $ValTotalQtyBuilt,
            "TotalQtyTarget" => $ValTotalQtyTarget,
            "TotalOTS" => $ValTotalOTS,
            "TotalCalculate" => $ValTotalCalculate
        );
        array_push($ArrAllData2,$TempArray2);
    }
    $ArrResultChart1 = array();
    $ArrResultChart2 = array();
    $ArrResultChart3 = array();
    foreach($ArrAllData2 as $AllData2)
    {
        # chart 1
        $ArrayTemp1 = array(
            "Half" => $AllData2['Half'],
            "TotalTargetCost" => $AllData2['TotalTargetCost'],
            "TotalActualCost" => $AllData2['TotalActualCost']
        );
        array_push($ArrResultChart1,$ArrayTemp1);
        # chart 2
        $ArrayTemp2 = array(
            "Half" => $AllData2['Half'],
            "TotalQtyBuilt" => $AllData2['TotalQtyBuilt'],
            "TotalQtyTarget" => $AllData2['TotalQtyTarget']
        );
        array_push($ArrResultChart2,$ArrayTemp2);
        # chart 3
        $ArrayTemp3 = array(
            "Half" => $AllData2['Half'],
            "TotalOTS" => $AllData2['TotalOTS'],
            "TotalActualCost" => $AllData2['TotalActualCost'],
            "TotalQtyBuilt" => $AllData2['TotalQtyBuilt'],
            "TotalCalculate" => $AllData2['TotalCalculate']
        );
        array_push($ArrResultChart3,$ArrayTemp3);
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
    <?php
    $Loop1 = 1;
    $Bol1 = FALSE;
    foreach ($ArrResultChart1 as $DataResultChart1)
    {
        $ValResultChart1Half = trim($DataResultChart1['Half']);
        $ValResultChart1TotalTargetCost = trim($DataResultChart1['TotalTargetCost']);
        $ValResultChart1TotalActualCost = trim($DataResultChart1['TotalActualCost']);
        $ValResultChart1TotalTargetCost2 = number_format((float)$ValResultChart1TotalTargetCost, 2, '.', ',');
        $ValResultChart1TotalActualCost2 = number_format((float)$ValResultChart1TotalActualCost, 2, '.', ',');
        if($Loop1 == 1)
        {
            if($ValResultChart1TotalTargetCost2 != 0 || $ValResultChart1TotalActualCost2 != 0)
            {
                ?>
                ['<?php echo $ValResultChart1Half; ?>',<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalTargetCost(<?php echo "'".$ValResultChart1TotalTargetCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>'),<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalActualCost(<?php echo "'".$ValResultChart1TotalActualCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>')]
                <?php    
                $Bol1 = TRUE;
            }
        }
        else
        {
            if($Bol1 == FALSE)
            {
                if($ValResultChart1TotalTargetCost2 != 0 || $ValResultChart1TotalActualCost2 != 0)
                {
                    ?>
                    ['<?php echo $ValResultChart1Half; ?>',<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalTargetCost(<?php echo "'".$ValResultChart1TotalTargetCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>'),<?php echo $ValResultChart1TotalActualCost; ?>,ToolTipTotalActualCost(<?php echo "'".$ValResultChart1TotalActualCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>')]
                    <?php
                    $Bol1 = TRUE;
                }
            }
            else
            {
                ?>
                ,['<?php echo $ValResultChart1Half; ?>',<?php echo $ValResultChart1TotalTargetCost; ?>,ToolTipTotalTargetCost(<?php echo "'".$ValResultChart1TotalTargetCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>'),<?php echo $ValResultChart1TotalActualCost; ?>,ToolTipTotalActualCost(<?php echo "'".$ValResultChart1TotalActualCost2."'"; ?>,'<?php echo $ValResultChart1Half; ?>')]
                <?php
            }           
        }
        $Loop1++;
    }
?>
]);
    var options1 = {
            title: 'Total Actual Cost Per Unit'
            ,titleTextStyle: {fontSize: 18, bold: true}
            <?php 
            if($ValStatusCategory == "CLOSE")
            {
                echo ",hAxis: {slantedText:true,slantedTextAngle:-45}";
            }
            ?>
            ,vAxis: { minValue: 0}
            ,tooltip: { isHtml: true }
            ,legend: { position: 'top', maxLines: 3 }
            ,chartArea: {top:50,height:"80%",width:"80%"}
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
        $Bol2 = FALSE;
        foreach ($ArrResultChart2 as $DataResultChart2)
        {
            $ValResultChart2Half = trim($DataResultChart2['Half']);
            $ValResultChart2TotalQtyBuilt = trim($DataResultChart2['TotalQtyBuilt']);
            $ValResultChart2TotalQtyTarget = trim($DataResultChart2['TotalQtyTarget']);
            $ValResultChart2TotalQtyBuilt2 = number_format((float)$ValResultChart2TotalQtyBuilt, 0, '.', ',');
            $ValResultChart2TotalQtyTarget2 = number_format((float)$ValResultChart2TotalQtyTarget, 0, '.', ',');
            if($Loop2 == 1)
            {
                if($ValResultChart2TotalQtyBuilt2 != 0 || $ValResultChart2TotalQtyTarget2 != 0)
                {
                    ?>
                    ['<?php echo $ValResultChart2Half; ?>',<?php echo $ValResultChart2TotalQtyBuilt; ?>,ToolTipTotalQtyBuilt(<?php echo "'".$ValResultChart2TotalQtyBuilt2."'"; ?>,'<?php echo $ValResultChart2Half; ?>')]
                    <?php
                    $Bol2 = TRUE;
                }
            }
            else
            {
                if($Bol2 == FALSE)
                {
                    if($ValResultChart2TotalQtyBuilt2 != 0 || $ValResultChart2TotalQtyTarget2 != 0)
                    {
                        ?>
                        ['<?php echo $ValResultChart2Half; ?>',<?php echo $ValResultChart2TotalQtyBuilt; ?>,ToolTipTotalQtyBuilt(<?php echo "'".$ValResultChart2TotalQtyBuilt2."'"; ?>,'<?php echo $ValResultChart2Half; ?>')]
                        <?php
                        $Bol2 = TRUE;
                    }
                }
                else
                {
                    ?>
                    ,['<?php echo $ValResultChart2Half; ?>',<?php echo $ValResultChart2TotalQtyBuilt; ?>,ToolTipTotalQtyBuilt(<?php echo "'".$ValResultChart2TotalQtyBuilt2."'"; ?>,'<?php echo $ValResultChart2Half; ?>')]
                    <?php
                }
            }
            $Loop2++;
        }

    ?>
    ]);
    var options2 = {
            title: 'Total Qty Built QA Per Half'
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
<?php
/*
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
        $Bol3 = FALSE;
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
                if($ValResultChart3TotalCalculate != 0)
                {
                    ?>
                    ['<?php echo $ValResultChart3Half; ?>',<?php echo $ValResultChart3TotalCalculate; ?>,ToolTipTotalCombine(<?php echo "'".$ValResultChart3TotalCalculate2."'"; ?>,'<?php echo $ValResultChart3Half; ?>')]
                    <?php
                    $Bol3 = TRUE;
                }                
            }
            else
            {
                if($Bol3 == FALSE)
                {
                    if($ValResultChart3TotalCalculate != 0 )
                    {
                        ?>
                        ['<?php echo $ValResultChart3Half; ?>',<?php echo $ValResultChart3TotalCalculate; ?>,ToolTipTotalCombine(<?php echo "'".$ValResultChart3TotalCalculate2."'"; ?>,'<?php echo $ValResultChart3Half; ?>')]
                        <?php
                        $Bol3 = TRUE;
                    }
                }
                else
                {
                    ?>
                    ,['<?php echo $ValResultChart3Half; ?>',<?php echo $ValResultChart3TotalCalculate; ?>,ToolTipTotalCombine(<?php echo "'".$ValResultChart3TotalCalculate2."'"; ?>,'<?php echo $ValResultChart3Half; ?>')]
                    <?php
                }
            }
            $Loop3++;
        }            
        ?>]);
        var options3 = {
                title: 'Total Expense Per Half'
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
*/
?>
<script type="text/javascript">
    function ToolTipTotalCombine(ValueTotal,Session)
    {
        return '<div><strong>Total Cost ('+Session+') : $'+ValueTotal+'</strong></div>';
    }
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart3);

    function drawChart3() {
        var data3 = new google.visualization.DataTable();
        data3.addColumn('string', 'Year');
        data3.addColumn('number', 'Total OTS + Total Actual Cost');
        data3.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
        data3.addRows([<?php
        $Loop3 = 1;
        $Bol3 = FALSE;
        foreach ($ArrayYear as $Year)   
        {
            $data = UNQUOTE_EXPENSE_PER_HALF($Year,$ValQuoteCategory,$ValQuoteID,$linkMACHWebTrax);
            $data2 = number_format((float)$data, 2, '.', ',');
            if($Loop3 == 1)
            {
                if($data != 0)
                {
                    ?>
                    ['<?php echo $Year; ?>',<?php echo $data; ?>,ToolTipTotalCombine(<?php echo "'".$data2."'"; ?>,'<?php echo $Year; ?>')]
                    <?php
                    $Bol3 = TRUE;
                }                
            }
            else
            {
                if($Bol3 == FALSE)
                {
                    if($data != 0 )
                    {
                        ?>
                        ['<?php echo $Year; ?>',<?php echo $data; ?>,ToolTipTotalCombine(<?php echo "'".$data2."'"; ?>,'<?php echo $Year; ?>')]
                        <?php
                        $Bol3 = TRUE;
                    }
                }
                else
                {
                    ?>
                    ,['<?php echo $Year; ?>',<?php echo $data; ?>,ToolTipTotalCombine(<?php echo "'".$data2."'"; ?>,'<?php echo $Year; ?>')]
                    <?php
                }
            }
            $Loop3++;
        }            
        ?>]);
        var options3 = {
                title: 'Total Expense Per Year'
                ,titleTextStyle: {fontSize: 18, bold: true}
                ,vAxis: { minValue: 0}
                ,hAxis: {slantedText:true,slantedTextAngle:-45}
                ,tooltip: { isHtml: true }
                ,legend: { position: 'top', maxLines: 3 }
                ,chartArea: {top:50,height:"80%",width:"80%"}
        };
        var chart3 = new google.visualization.ColumnChart(document.getElementById('columnchart_material4'));
        chart3.draw(data3, options3);
        google.visualization.events.addListener(chart3, 'select', selectHandler);
        function selectHandler() {
            var selection = chart3.getSelection();
            var year = data3.getValue(chart3.getSelection()[0].row, 0);
            var category = '<?php echo $ValQuoteCategory; ?>';
            var QuoteID = '<?php echo $ValQuoteID; ?>';
            DETAIL_PROJECT_CHART(year,category,QuoteID);
        }
    }
</script>
<div class="col-md-12"><h4>Project : <?php echo  '<strong>'.$ValQuoteID.'</strong>'; ?></h4></div>
<div class="col-md-12"><hr class="HorizontalLine"></div>
<?php
    if($ValQuoteCategory == 'Quote')
    {
    ?>
    <div class="col-md-12">
        <div class="table-responsive ResponsiveContent">
            <div class="ColumnContent" id="columnchart_material1">

            </div>
        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">
        <div class="table-responsive ResponsiveContent">
            <div class="ColumnContent" id="columnchart_material2">

            </div>
        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12"><div class="table-responsive ResponsiveContent">
        <div class="ColumnContent" id="columnchart_material4">

        </div>
    </div>
    <div class="col-md-12">&nbsp;</div>    
        <?php
    }
    else
    {
    ?>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12"><div class="table-responsive ResponsiveContent">
        <div class="ColumnContent" id="columnchart_material4">

        </div>
    </div>
    <div class="col-md-12">&nbsp;</div> 
    <?php
    }
}
else
{
    echo "";     
}
?>