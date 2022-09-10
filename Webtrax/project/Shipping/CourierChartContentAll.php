<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php");
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
$InputYear = "ALL";
$ArrData = array();
$Data = COURIER_DATA_BY_YEAR($InputYear,"none",$linkMACHWebTrax);
while($res=sqlsrv_fetch_array($Data))
{
    $Courier = trim($res['Courier']);
    $Freight = trim($res['TotalFreight']);
    $Qty = trim($res['Qty']);
    $TempArray1 = array(
        "Courier" => $Courier,
        "Freight" => $Freight,
        "Qty" => $Qty
    );
    array_push($ArrData,$TempArray1);
}
?>
<style>
    .ColumnContent{width:100%; height: 400px;}
</style>
<div class="row">
    <div class="col-md-6">
    <h4>Shipment Courier (All Years)</h4>
        <div class="table-responsive">
            <table class="table table-responsive table-bordered table-hover">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Courier</th>
                        <th class="text-center">Freight Cost($)</th>
                        <th class="text-center">Freight (%)</th>
                        <th class="text-center">Qty Shipment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $TotalFreight = 0;
                    foreach($ArrData as $xMain)
                    {
                        $Cost = trim($xMain['Freight']);
                        $TotalFreight = @($TotalFreight + $Cost);
                    }
                    foreach($ArrData as $main2)
                    {
                        $ValCourier = trim($main2['Courier']);
                        $ValFreight = trim($main2['Freight']);
                        $ValQty = trim($main2['Qty']);
                        $Persen =  @($ValFreight/$TotalFreight)*100;
                        $ValFreight = number_format((float)$ValFreight, 2, '.', ',');
                        $ValQty = number_format((float)$ValQty, 2, '.', ',');
                        $Persen = number_format((float)$Persen, 1, '.', ',');
                    ?>
                    <tr>
                        <td class="text-left"><?php echo $ValCourier; ?></td>
                        <td class="text-right"><?php echo $ValFreight; ?></td>
                        <td class="text-right"><?php echo $Persen; ?></td>
                        <td class="text-right"><?php echo $ValQty; ?></td>
                    </tr>
                    <?php
                    }
                    $NULL = "-";
                    $arrTopCountry = array();
                    $arrMain = array();
                    $xdata = COURIER_DATA_BY_YEAR($InputYear,$NULL,$linkMACHWebTrax);
                    while($xres=sqlsrv_fetch_array($xdata))
                    {
                        $Kurir = trim($xres['Courier']);
                        $Freight = trim($xres['TotalFreight']);
                        $Qty = trim($xres['Qty']);
                        $TempArr = array("Courier" => $Kurir, "Freight" => $Freight,"Qty" => $Qty);
                        array_push($arrTopCountry,$Kurir);
                        array_push($arrMain,$TempArr);
                    }
                    $CountryException = $arrTopCountry[0]."*".$arrTopCountry[1]."*".$arrTopCountry[2]."*".$arrTopCountry[3]."*".$arrTopCountry[4];
                    $ydata = COURIER_DATA_BY_YEAR($InputYear,$CountryException,$linkMACHWebTrax);
                    while($yres=sqlsrv_fetch_array($ydata))
                    {
                        $Kurir = trim($yres['Courier']);
                        $Freight = trim($yres['TotalFreight']);
                        $Qty = trim($yres['Qty']);
                        $TempArr = array("Courier" => $Kurir, "Freight" => $Freight,"Qty" => $Qty);
                        array_push($arrMain,$TempArr);
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Courier', 'Freight($)']
            <?php
            
            $count = 1;
            foreach($arrMain as $main2)
            {
                $ValCourier = trim($main2['Courier']);
                $ValFreight = trim($main2['Freight']);
            ?>
			,['<?php echo $ValCourier; ?>',<?php echo $ValFreight; ?>]
            <?php
            }
            ?>
		]);
		var options = {
			is3D: true,            
			chartArea: {top:0,height:"100%",width:"100%",bottom:0},
			isStacked:true
			,focusTarget: 'category'
		};
		var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
		chart.draw(data, options);
		}
	</script>
    <div class="col-md-6">
        <div id="piechart_3d" style="height: 400px;"></div>
    </div>
</div>
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Freight ($)']
        <?php
        $datax = GET_TOP_COURIER_FREIGHT("YEAR",$arrTopCountry[0],$InputYear,$linkMACHWebTrax);
        while($resx=sqlsrv_fetch_array($datax))
        {
            $ValYear = trim($resx['ShipDate']);
            $ValCost = trim($resx['TotalFreight']);
        ?>
        ,['<?php echo $ValYear; ?>',<?php echo $ValCost; ?>]
        <?php
        }
        ?>
    ]);

    var options = {title: 'Yearly TOP 1 Courier Chart: <?php echo $arrTopCountry[0]; ?>'
    , titleTextStyle: { color: "black", bold: true }
    , vAxis: { minValue: 0, title : 'Total Cost ($)', textStyle: { fontSize: 12 }, format: 'decimal'}
    , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    , legend: {position: 'none'}
    };

    var chart = new google.charts.Bar(document.getElementById('ChartNo1'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Freight ($)']
        <?php
        $datax = GET_TOP_COURIER_FREIGHT("YEAR",$arrTopCountry[1],$InputYear,$linkMACHWebTrax);
        while($resx=sqlsrv_fetch_array($datax))
        {
            $ValYear = trim($resx['ShipDate']);
            $ValCost = trim($resx['TotalFreight']);
        ?>
        ,['<?php echo $ValYear; ?>',<?php echo $ValCost; ?>]
        <?php
        }
        ?>
    ]);

    var options = {title: 'Yearly TOP 2 Courier Chart: <?php echo $arrTopCountry[1]; ?>'
    , titleTextStyle: { color: "black", bold: true }
    , vAxis: { minValue: 0, title : 'Total Cost ($)', textStyle: { fontSize: 12 }, format: 'decimal'}
    , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    , legend: {position: 'none'}
    , colors: ['#e0440e']
    };

    var chart = new google.charts.Bar(document.getElementById('ChartNo2'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Freight ($)']
        <?php
        $datax = GET_TOP_COURIER_FREIGHT("YEAR",$arrTopCountry[2],$InputYear,$linkMACHWebTrax);
        while($resx=sqlsrv_fetch_array($datax))
        {
            $ValYear = trim($resx['ShipDate']);
            $ValCost = trim($resx['TotalFreight']);
        ?>
        ,['<?php echo $ValYear; ?>',<?php echo $ValCost; ?>]
        <?php
        }
        ?>
    ]);

    var options = {title: 'Yearly TOP 3 Courier Chart: <?php echo $arrTopCountry[2]; ?>'
    , titleTextStyle: { color: "black", bold: true }
    , vAxis: { minValue: 0, title : 'Total Cost ($)', textStyle: { fontSize: 12 }, format: 'decimal'}
    , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    , legend: {position: 'none'}
    , colors: ['#EF9823']
    };

    var chart = new google.charts.Bar(document.getElementById('ChartNo3'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
<div class="row" style="margin-top:20px;">
    <div class="col-md-4">
        <div id="ChartNo1" class="ColumnContent"></div>
    </div>
    <div class="col-md-4">
        <div id="ChartNo2" class="ColumnContent"></div>
    </div>
    <div class="col-md-4">
        <div id="ChartNo3" class="ColumnContent"></div>
    </div>
</div>