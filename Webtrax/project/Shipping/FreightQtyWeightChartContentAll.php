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
    $QListYear = GET_LIST_FILTER_SHIPPING($linkMACHWebTrax);
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
        $QGetData = FREIGHT_PER_YEAR("YEARLY","",$ListYear['YearShipment'],$linkMACHWebTrax);
        while($res = sqlsrv_fetch_array($QGetData))
        {
            $FromSubject = trim($res['LocationCode']);
            $Qty = trim($res['Qty']);
            $Freight = trim($res['Freight']);
            $Weight = trim($res['Weight']);
            if($Qty == ''){ $Qty = 0; }
            if($Freight == ''){ $Freight = 0; }
            if($Weight == ''){ $Weight = 0; }
            $TempArr = array(
                "Year" => $ListYear['YearShipment'],
                "FromSubject" => $FromSubject,
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
                    $Subject = trim($Main['FromSubject']);
                    if($Year2 == $ValYear && $Subject == $loc)
                    { 
                        $ValFreight = trim($Main['Freight']); 
                        echo ",".$ValFreight;
                    }
                }
            } 
            echo "]";
        }
        ?>
    ]);

    var options = {title: 'Total Of Freight Cost (All Season)'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Cost ($)', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 }}
    };  

    
    var chart = new google.visualization.ColumnChart(document.getElementById('Chart1'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    
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
                    $Subject = trim($Main['FromSubject']);
                    if($Year2 == $ValYear && $Subject == $loc)
                    { 
                        $ValFreight = trim($Main['Qty']); 
                        echo ",".$ValFreight;
                    }
                }
            } 
            echo "]";
        }
        ?>
    ]);

    var options = {title: 'Received Qty From Shipment (All Season)'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Qty', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 }}
    };  

    
    var chart = new google.visualization.ColumnChart(document.getElementById('Chart2'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    
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
                    $Subject = trim($Main['FromSubject']);
                    if($Year2 == $ValYear && $Subject == $loc)
                    { 
                        $ValFreight = trim($Main['Weight']); 
                        echo ",".$ValFreight;
                    }
                }
            } 
            echo "]";
        }
        ?>
    ]);

    var options = {title: 'Received Weight From Shipment (All Season)'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Weight', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 }}
    };  

    
    var chart = new google.visualization.ColumnChart(document.getElementById('Chart3'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<style>
    .ColumnContent{width:100%; height: 400px;}
</style>
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
                    foreach($arrLoc as $comp)
                    {
                        ?>
                        <th class="text-center"><?php echo $comp; ?> Freight ($)</th>
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
                foreach($ArrListYear as $Year)
                {
                ?>
                    <tr>
                        <td class="text-center"><?php echo trim($Year['YearShipment']); ?></td>
                        <?php
                        $TotalByComp = 0;
                        foreach($arrLoc as $company)
                        {
                            $freightValue = FREIGHT_PER_YEAR2("YEARLY",$company,$Year['YearShipment'],$linkMACHWebTrax);
                            $TotalByComp = @($TotalByComp + $freightValue);
                            $FreightCost = number_format((float)$freightValue, 2, '.', ',');
                            ?>
                            <td class="text-right"><?php echo $FreightCost; ?></td>
                            <?php
                        }
                        $ValYear = trim($Year['YearShipment']);
                        $enc = "YEARLY*".$ValYear;
                        $ValEnc = base64_encode($enc);
                        $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$ValEnc.'" data-target="#DetailAll" title="Detail"></span>';
                        $TotalByYear = @($TotalByYear + $TotalByComp);
                        $TotalByCompx = number_format((float)$TotalByComp, 2, '.', ',');
                        ?>
                        <td class="text-right"><?php echo $TotalByCompx; ?></td>
                        <td class="text-center"><?php echo $opt; ?></td>
                    </tr>
                <?php
                }
                /*
                $TotalFreight = $TotalQty = $TotalWeight = 0;
                foreach($arrData as $res)
                {
                    $Tahun = trim($res['Year']);
                    $Tujuan = trim($res['FromSubject']);
                    $FreightCost = trim($res['Freight']);
                    $QtyShipping = trim($res['Qty']);
                    $ValWeight = trim($res['Weight']);
                    $TotalFreight = @($TotalFreight + $FreightCost);
                    $TotalQty = @($TotalQty + $QtyShipping);
                    $TotalWeight = @($TotalWeight + $ValWeight);
                    $FreightCost = number_format((float)$FreightCost, 2, '.', ',');
                    $QtyShipping = number_format((float)$QtyShipping, 2, '.', ',');
                    $ValWeight = number_format((float)$ValWeight, 2, '.', ',');
                    $enc = "YEARLY*".$Tahun."*".$Tujuan;
                    $ValEnc = base64_encode($enc);
                    $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$ValEnc.'" data-target="#DetailAll" title="Detail"></span>';
                ?>
                <tr>
                    <td class="text-center"><?php echo $Tahun; ?></td>
                    <td class="text-center"><?php echo $Tujuan; ?></td>
                    <td class="text-right" width="200"><?php echo $FreightCost; ?></td>
                    <td class="text-right" width="200"><?php echo $QtyShipping; ?></td>
                    <td class="text-right" width="200"><?php echo $ValWeight; ?></td>
                    <td class="text-center" width="80"><?php echo $opt; ?></td>
                </tr>
                <?php
                }
                $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                $TotalWeight = number_format((float)$TotalWeight, 2, '.', ',');
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
