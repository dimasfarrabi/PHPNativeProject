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
    $CodeCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = base64_decode(base64_decode($CodeCategory));
    $arr = explode("#",$ValQuoteCategory);
    $Category = $arr[1];
    $ArrSession = array();
    $QListSession = GET_ALL_TYPE_CLOSED_TIME($linkMACHWebTrax);
    while($RListSession = sqlsrv_fetch_array($QListSession))
    {
        $arr = explode("-",trim($RListSession['ClosedTime']));
        $ValListSession = $arr[0];
        array_push($ArrSession,$ValListSession);
    }
    $ArrayYear = array_unique($ArrSession);
    if($Category == 'Quote')
    {
        $Name = "Total OTS + Total Actual Cost";
    }
    else
    {
        $Name = "Total OTS + Total Actual Cost";
    }
?>
<style>
    .ColumnContent{width:800px; height: 500px;}
    .ResponsiveContent{overflow-y: hidden;}
    .HorizontalLine{margin: 0em;}
</style>
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
        data3.addColumn('number', '<?php echo $Name; ?>');
        data3.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
        data3.addRows([<?php
        $Loop3 = 1;
        $Bol3 = FALSE;
        foreach ($ArrayYear as $Year)   
        {
            $data = GET_TOTAL_EXPENSE_PER_YEAR($Year,$Category,$linkMACHWebTrax);
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
                ,tooltip: { isHtml: true }
                ,legend: { position: 'top', maxLines: 3 }
                ,chartArea: {top:50,height:"80%",width:"80%"}
        };
        var chart3 = new google.visualization.ColumnChart(document.getElementById('ChartSum'));
        chart3.draw(data3, options3);
        google.visualization.events.addListener(chart3, 'select', selectHandler);
        function selectHandler() {
            var selection = chart3.getSelection();
            var year = data3.getValue(chart3.getSelection()[0].row, 0);
            var category = '<?php echo $Category; ?>';
            DETAIL_CHART(year,category);
        }
    }
</script>
<div class="col-md-12"><h4>Quote Category : <?php echo  '<strong>'.$Category.'</strong>'; ?></h4></div>
<div class="col-md-12" style="margin-bottom:20px"><hr class="HorizontalLine"></div>
<div class="col-md-12">
    <div class="table-responsive ResponsiveContent">
        <div class="ColumnContent" id="ChartSum">

        </div>
    </div>
</div>
<div class="col-md-7" style="margin-top:20px" id="ChartSumDetail">
    
</div>

<?php
}
?>