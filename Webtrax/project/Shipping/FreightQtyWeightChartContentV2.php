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
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $InputYear = htmlspecialchars(trim($_POST['InputYear']), ENT_QUOTES, "UTF-8");
    
    $Yesterday = "01/12/".$InputYear;
    $ArrMonth = array();
    for ($i = 0; $i <= 11; $i++) 
    {
    $months =array("Month" => date("Y-m", strtotime( $Yesterday." +$i months")), 
    "Month2" => date("M,Y", strtotime($Yesterday." +$i months")));
    array_push($ArrMonth,$months);
    }
    asort($ArrMonth);
    $arrLoc = array("PSM","PSL","FOR");
    $arrDataALL = array();
    $arrData = array();
    $arrtemploc = array();
    $Nol = 0;
    foreach($ArrMonth as $ListMonth)
    {
        $arrm = explode(",",$ListMonth['Month2']);
        $DumMonth = $arrm[0];
        $QGetData = FREIGHT_PER_YEAR("MONTHLY",$ListMonth['Month'],"",$linkMACHWebTrax);
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
                    "Month" => $ListMonth['Month'],
                    "MonthName" => $ListMonth['Month2'],
                    "MonthName2" => $DumMonth,
                    "FromSubject" => $FromSubject,
                    "Qty" => $Qty,
                    "Freight" => $Freight,
                    "Weight" => $Weight,
                    "Bulan" => $Nol
                );
            array_push($arrData,$TempArr);
        }
    }
}
?>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    
    var data = google.visualization.arrayToDataTable([
        ['Month', 'PSM', 'PSL','FOR']
        <?php
        foreach($ArrMonth as $ListMonth)
        {
            $MonthYear = trim($ListMonth['Month']);
            $MonthName = trim($ListMonth['Month2']);
            $arr = explode(",",$MonthName);
            $ValMonth = $arr[0];
            echo ",['".$ValMonth."'";
            foreach($arrLoc as $loc)
            {
                foreach($arrData as $Main)
                {
                    $ValBulan = trim($Main['MonthName2']);
                    $Subject = trim($Main['FromSubject']);
                    if($ValBulan == $ValMonth && $Subject == $loc)
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

    var options = {title: 'Total Of Freight Cost in <?php echo $InputYear;?>'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Cost ($)', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    };  

    
    var chart = new google.visualization.ColumnChart(document.getElementById('ChartMonth1'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    
    var data = google.visualization.arrayToDataTable([
        ['Month', 'PSM', 'PSL','FOR']
        <?php
        foreach($ArrMonth as $ListMonth)
        {
            $MonthYear = trim($ListMonth['Month']);
            $MonthName = trim($ListMonth['Month2']);
            $arr = explode(",",$MonthName);
            $ValMonth = $arr[0];
            echo ",['".$ValMonth."'";
            foreach($arrLoc as $loc)
            {
                foreach($arrData as $Main)
                {
                    $ValBulan = trim($Main['MonthName2']);
                    $Subject = trim($Main['FromSubject']);
                    if($ValBulan == $ValMonth && $Subject == $loc)
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

    var options = {title: 'Received Qty From Shipment in <?php echo $InputYear;?>'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Qty', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    };  

    
    var chart = new google.visualization.ColumnChart(document.getElementById('ChartMonth2'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    
    var data = google.visualization.arrayToDataTable([
        ['Month', 'PSM', 'PSL','FOR']
        <?php
        foreach($ArrMonth as $ListMonth)
        {
            $MonthYear = trim($ListMonth['Month']);
            $MonthName = trim($ListMonth['Month2']);
            $arr = explode(",",$MonthName);
            $ValMonth = $arr[0];
            echo ",['".$ValMonth."'";
            foreach($arrLoc as $loc)
            {
                foreach($arrData as $Main)
                {
                    $ValBulan = trim($Main['MonthName2']);
                    $Subject = trim($Main['FromSubject']);
                    if($ValBulan == $ValMonth && $Subject == $loc)
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

    var options = {title: 'Received Weight From Shipment in <?php echo $InputYear;?>'
        , isStacked:true
        , vAxis: { minValue: 0, title : 'Total Weight', textStyle: { fontSize: 12 }}
        , hAxis: { title: data.getColumnLabel(0), textStyle: { fontSize: 12 },slantedText: true,slantedTextAngle: 90}
    };  

    
    var chart = new google.visualization.ColumnChart(document.getElementById('ChartMonth3'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<style>
    .ColumnContent{width:100%; height: 400px;}
</style>
<div class="col-md-4">
    <div class="ColumnContent" id="ChartMonth1"></div>
</div>
<div class="col-md-4">
    <div class="ColumnContent" id="ChartMonth2"></div>
</div>
<div class="col-md-4">
    <div class="ColumnContent" id="ChartMonth3"></div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table id="" class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">Month</th>
                    <?php
                    foreach($arrLoc as $Loc)
                    {
                        ?>
                    <th class="text-center"><?php echo $Loc; ?> Freight ($)</th>
                        <?php
                    }
                    ?>
                    <th class="text-center">Total Freight ($)</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalByYear = 0;
            foreach($ArrMonth as $List)
            {
                $Month2 = trim($List['Month2']);
                $Month1 = trim($List['Month']);
                ?>
                <tr>
                    <td class="text-center"><?php echo $Month2; ?></td>
                    <?php
                    $TotalByComp = 0;
                    foreach($arrLoc as $comp)
                    {
                        $freightValue = FREIGHT_PER_YEAR2("MONTHLY",$comp,$Month1,$linkMACHWebTrax);
                        $TotalByComp = @($TotalByComp + $freightValue);
                        $FreightCost = number_format((float)$freightValue, 2, '.', ',');
                    ?>
                    <td class="text-right"><?php echo $FreightCost; ?></td>
                    <?php
                    }
                    $TotalByCompx = number_format((float)$TotalByComp, 2, '.', ',');
                    $enc = "MONTHLY*".$Month1;
                    $ValEnc = base64_encode($enc);
                    $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$ValEnc.'" data-target="#DetailAll" title="Detail"></span>';
                    $TotalByYear = @($TotalByYear + $TotalByComp);
                    ?>
                    <td class="text-right"><?php echo $TotalByCompx; ?></td>
                    <td class="text-center"><?php echo $opt; ?></td>
                </tr>
                <?php
            }
            ?>
            <?php
            /*
            $no = 1;
            $TotalFreight = $TotalQty = $TotalWeight = 0;
            foreach($arrData as $res)
            {
                $Bulan = trim($res['MonthName']);
                $Monthx = trim($res['Month']);
                $From = trim($res['FromSubject']);
                $Freight = trim($res['Freight']);
                $Qty = trim($res['Qty']);
                $Weight = trim($res['Weight']);
                $TotalFreight = @($TotalFreight + $Freight);
                $TotalQty = @($TotalQty + $Qty);
                $TotalWeight = @($TotalWeight + $Weight);
                $Freight = number_format((float)$Freight, 2, '.', ',');
                $Qty = number_format((float)$Qty, 2, '.', ',');
                $Weight = number_format((float)$Weight, 2, '.', ',');
                $data = "MONTHLY*".$Monthx."*".$From;
                $ValEnc = base64_encode($data);
                $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$ValEnc.'" data-target="#DetailAll" title="Detail"></span>';
            ?>
            <tr>
                <td class="text-center" width="20"><?php echo $no; ?></td>
                <td class="text-center"><?php echo $Bulan; ?></td>
                <td class="text-center"><?php echo $From; ?></td>
                <td class="text-right" width="200"><?php echo $Freight; ?></td>
                <td class="text-right" width="200"><?php echo $Qty; ?></td>
                <td class="text-right" width="200"><?php echo $Weight; ?></td>
                <td class="text-center" width="80"><?php echo $opt; ?></td>
            </tr>
            <?php
            $no++;
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
<div class="modal fade" id="DetailMonth" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Shipment Details</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="PerMonthDetail"></div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg2" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>