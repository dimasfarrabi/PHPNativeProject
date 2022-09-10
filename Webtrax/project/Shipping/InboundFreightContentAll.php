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
    $ArrListYear = array();
    $QListYear = GET_LIST_FILTER_SHIPPING2($linkMACHWebTrax);
    while($RListYear = sqlsrv_fetch_array($QListYear))
    {
        array_push($ArrListYear,array("YearShipment" => trim($RListYear['YearShipment'])));
    }
    krsort($ArrListYear);
    $arrData = array();
    $ArrDataDefault = array();
    $arrLoc = array("PSM","PSL","FOR");
    foreach($ArrListYear as $ListYear)
    {
        $QGetData = GET_INBOUND_SHIPMENT_BY_YEAR("CHART",$ListYear['YearShipment'],$linkMACHWebTrax);
        while($res = sqlsrv_fetch_array($QGetData))
        {
            $ToSubject = trim($res['ToSubject2']);
            $Qty = trim($res['Qty']);
            $Freight = trim($res['Freight']);
            $Weight = trim($res['Weight']);
            if($Qty == ''){ $Qty = 0; }
            if($Freight == ''){ $Freight = 0; }
            if($Weight == ''){ $Weight = 0; }
            $TempArr = array(
                "Year" => $ListYear['YearShipment'],
                "ToSubject" => $ToSubject,
                "Qty" => $Qty,
                "Freight" => $Freight,
                "Weight" => $Weight
            );
            array_push($arrData,$TempArr);
        }
    }
?>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    // Define the chart to be drawn.
    var data = google.visualization.arrayToDataTable([
        ['Year', 'PSM', 'PSL','FOR']
        <?php
        foreach($ArrListYear as $ListYear)
        {
            $ValYear = trim($ListYear['YearShipment']);
            echo ",['".$ValYear."'";
            foreach($arrLoc as $loc)
            {
                foreach($arrData as $Main)
                {
                    $Year2 = trim($Main['Year']);
                    $Subject = trim($Main['ToSubject']);
                    if($Year2 == $ValYear && $Subject == $loc)
                    { 
                        $ValFreight = trim($Main['Freight']); 
                        echo ",".$ValFreight;
                    }
                    // elseif {  echo ","; }
                    // $ValFreight = number_format((float)$ValFreight, 2, '.', ',');
                
                }
            } 
            echo "]";
        }
        ?>
    ]);

    var options = {title: 'Total Of Freight Cost (All Season)'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Cost ($)', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    };  

    // Instantiate and draw the chart.
    var chart = new google.visualization.ColumnChart(document.getElementById('Chart1'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    // Define the chart to be drawn.
    var data = google.visualization.arrayToDataTable([
        ['Year', 'PSM', 'PSL','FOR']
        <?php
        foreach($ArrListYear as $ListYear)
        {
            $ValYear = trim($ListYear['YearShipment']);
            echo ",['".$ValYear."'";
            foreach($arrLoc as $loc)
            {
                foreach($arrData as $Main)
                {
                    $Year2 = trim($Main['Year']);
                    $Subject = trim($Main['ToSubject']);
                    if($Year2 == $ValYear && $Subject == $loc)
                    { 
                        $ValFreight = trim($Main['Qty']); 
                        echo ",".$ValFreight;
                    }
                    // elseif {  echo ","; }
                    // $ValFreight = number_format((float)$ValFreight, 2, '.', ',');
                }
            } 
            echo "]";
        }
        ?>
    ]);

    var options = {title: 'Received Qty From Shipment (All Season)'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Qty', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    };  

    // Instantiate and draw the chart.
    var chart = new google.visualization.ColumnChart(document.getElementById('Chart2'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    // Define the chart to be drawn.
    var data = google.visualization.arrayToDataTable([
        ['Year', 'PSM', 'PSL','FOR']
        <?php
        foreach($ArrListYear as $ListYear)
        {
            $ValYear = trim($ListYear['YearShipment']);
            echo ",['".$ValYear."'";
            foreach($arrLoc as $loc)
            {
                foreach($arrData as $Main)
                {
                    $Year2 = trim($Main['Year']);
                    $Subject = trim($Main['ToSubject']);
                    if($Year2 == $ValYear && $Subject == $loc)
                    { 
                        $ValFreight = trim($Main['Weight']); 
                        echo ",".$ValFreight;
                    }
                    // elseif {  echo ","; }
                    // $ValFreight = number_format((float)$ValFreight, 2, '.', ',');
                }
            } 
            echo "]";
        }
        ?>
    ]);

    var options = {title: 'Received Weight From Shipment (All Season)'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Weight', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    };  

    // Instantiate and draw the chart.
    var chart = new google.visualization.ColumnChart(document.getElementById('Chart3'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<div class="col-md-12">
    <i><strong>*) Inbound From FUSA</strong></i>
</div>
<div class="col-md-4">
    <div class="ColumnContent" id="Chart1"></div>
</div>
<div class="col-md-4">
    <div class="ColumnContent" id="Chart2"></div>
</div>
<div class="col-md-4">
    <div class="ColumnContent" id="Chart3"></div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table id="" class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">Year</th>
                    <?php
                    foreach($arrLoc as $loc)
                    {
                    ?>
                    <th class="text-center"><?php echo $loc; ?> Freight ($)</th>
                    <?php
                    }
                    ?>
                    <th class="text-center">Total Freight ($)</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                ksort($ArrListYear);
                $TotalByYear = 0;
                foreach($ArrListYear as $List)
                {
                    $Year = $List['YearShipment'];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $Year; ?></td>
                    <?php
                    $TotalByComp = 0;
                    foreach($arrLoc as $Company)
                    {
                        $FreightData = GET_INBOUND_SHIPMENT_BY_YEAR2("YEARLY",$Year,$Company,$linkMACHWebTrax);
                        $TotalByComp = @($TotalByComp + $FreightData);
                        $FreightDatax = number_format((float)$FreightData, 2, '.', ',');
                        ?>
                        <td class="text-right"><?php echo $FreightDatax; ?></td>
                        <?php
                    }
                    $enc = "YEARLY*".$Year;
                    $ValEnc = base64_encode($enc);
                    $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$ValEnc.'" data-target="#AllDetail" title="Detail"></span>';
                    $TotalByYear = @($TotalByYear + $TotalByComp);
                    $TotalByCompx = number_format((float)$TotalByComp, 2, '.', ',');
                    ?>
                        <td class="text-right"><?php echo $TotalByCompx; ?></td>
                        <td class="text-center"><?php echo $opt; ?></td>
                    </tr>
                    <?php
                }
                /*
                $datax = GET_INBOUND_SHIPMENT_BY_YEAR("TABLE",$ListYear['YearShipment'],$linkMACHWebTrax);
                while($resx=sqlsrv_fetch_array($datax))
                {
                    $Tahun = trim($resx['Year']);
                    $Location = trim($resx['ToSubject2']);
                    $TotFreight = trim($resx['Freight']);
                    $TotWeight = trim($resx['Weight']);
                    $TotQty = trim($resx['Qty']);
                    $TotFreight = number_format((float)$TotFreight, 2, '.', ',');
                    $TotWeight = number_format((float)$TotWeight, 2, '.', ',');
                    $TotQty = number_format((float)$TotQty, 2, '.', ',');
                    $ValEnc = $Tahun."+".$Location;
                    $Enc = base64_encode($ValEnc);
                    $Opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$Enc.'" data-target="#AllDetail" title="Detail"></span>';
                ?>
                <tr>
                    <td class="text-center"><?php echo $Tahun; ?></td>
                    <td class="text-center"><?php echo $Location; ?></td>
                    <td class="text-right"><?php echo $TotFreight; ?></td>
                    <td class="text-right"><?php echo $TotQty; ?></td>
                    <td class="text-right"><?php echo $TotWeight; ?></td>
                    <td class="text-center"><?php echo $Opt; ?></td>
                </tr>
                <?php
                }
                */
                ?>
            </tbody>
            <tfoot>
                <?php
                $TotalByYearx = number_format((float)$TotalByYear, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center" colspan="4"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalByYearx; ?></strong></td>
                    <td class="text-right"><strong></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
    .ColumnContent{width:100%; height: 400px;}
</style>