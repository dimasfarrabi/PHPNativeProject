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
    foreach($ArrMonth as $ListMonth)
    {
        $arrm = explode(",",$ListMonth['Month2']);
        $DumMonth = $arrm[0];
        $QGetData = GET_INBOUND_SHIPMENT_BY_MONTH("GRAPH",$ListMonth['Month'],$linkMACHWebTrax);
        $Nol = 0;
        if(sqlsrv_num_rows($QGetData) == 3)
        {
            while($res = sqlsrv_fetch_array($QGetData))
            {
                $ToSubject = trim($res['ToSubject2']);
                $Qty = trim($res['Qty']);
                $Freight = trim($res['Freight']);
                $Weight = trim($res['Weight']);
                $Bulan = trim($res['Bulan']);
                if($Qty == ''){ $Qty = 0; }
                if($Freight == ''){ $Freight = 0; }
                if($Weight == ''){ $Weight = 0; }
                    $TempArr = array(
                        "Month" => $ListMonth['Month'],
                        "MonthName" => $ListMonth['Month2'],
                        "MonthName2" => trim($res['MonthName']),
                        "ToSubject" => $ToSubject,
                        "Qty" => $Qty,
                        "Freight" => $Freight,
                        "Weight" => $Weight,
                        "Bulan" => $Bulan
                    );
                array_push($arrData,$TempArr);
            }
        }
        elseif(sqlsrv_num_rows($QGetData) < 3 && sqlsrv_num_rows($QGetData) != 0)
        {
            $Data2 = GET_INBOUND_SHIPMENT_BY_MONTH2($ListMonth['Month'],$linkMACHWebTrax);
            while($res = sqlsrv_fetch_array($Data2))
            {
                $ToSubject = trim($res['ToSubject2']);
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
                        "ToSubject" => $ToSubject,
                        "Qty" => $Qty,
                        "Freight" => $Freight,
                        "Weight" => $Weight,
                        "Bulan" => $Nol
                    );
                array_push($arrData,$TempArr);
            }
        }
        else
        {
            foreach($arrLoc as $loc)
            {
                $TempArr = array(
                    "Month" => $ListMonth['Month'],
                    "MonthName" => $ListMonth['Month2'],
                    "MonthName2" => $DumMonth,
                    "ToSubject" => $loc,
                    "Qty" => $Nol,
                    "Freight" => $Nol,
                    "Weight" => $Nol,
                    "Bulan" => $Nol
                );
                array_push($arrData,$TempArr);
            }
        }
    }
        // foreach($ArrMonth as $ListMonth)
        // {
        //     $MonthYear = trim($ListMonth['Month']);
        //     $MonthName = trim($ListMonth['Month2']);
        //     $arr = explode(",",$MonthName);
        //     $ValMonth = $arr[0];
        //     echo ",['".$MonthName."'";
        //     foreach($arrLoc as $loc)
        //     {
        //         foreach($arrData as $Main)
        //         {
        //             $ValBulan = trim($Main['MonthName2']);
        //             $Subject = trim($Main['ToSubject']);
        //             if($ValBulan == $ValMonth && $Subject == $loc)
        //             { 
        //                 $ValFreight = trim($Main['Freight']); 
        //                 echo ",".$ValFreight;
        //             }
        //         }
        //     } 
        //     echo "]";
        // }  
?>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    // Define the chart to be drawn.
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
                    $Subject = trim($Main['ToSubject']);
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

    // Instantiate and draw the chart.
    var chart = new google.visualization.ColumnChart(document.getElementById('ChartMonth1'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    // Define the chart to be drawn.
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
                    $Subject = trim($Main['ToSubject']);
                    if($ValBulan == $ValMonth && $Subject == $loc)
                    { 
                        $Qty = trim($Main['Qty']); 
                        echo ",".$Qty;
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

    // Instantiate and draw the chart.
    var chart = new google.visualization.ColumnChart(document.getElementById('ChartMonth2'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart']});
function drawChart() {
    // Define the chart to be drawn.
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
                    $Subject = trim($Main['ToSubject']);
                    if($ValBulan == $ValMonth && $Subject == $loc)
                    { 
                        $Weight = trim($Main['Weight']); 
                        echo ",".$Weight;
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

    // Instantiate and draw the chart.
    var chart = new google.visualization.ColumnChart(document.getElementById('ChartMonth3'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<div class="col-md-12">
    <i><strong>*) Inbound From FUSA</strong></i>
</div>
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
                foreach($ArrMonth as $list)
                {
                    $Month = trim($list['Month']);
                    $Month2 = trim($list['Month2']);
                ?>
                <tr>
                    <td class="text-center"><?php echo $Month2; ?></td>
                    <?php
                    $TotalByComp = 0;
                    foreach($arrLoc as $Company)
                    {
                        $FreightData = GET_INBOUND_SHIPMENT_BY_YEAR2("MONTHLY",$Month,$Company,$linkMACHWebTrax);
                        $TotalByComp = @($TotalByComp + $FreightData);
                        $FreightDatax = number_format((float)$FreightData, 2, '.', ',');
                        ?>
                        <td class="text-right"><?php echo $FreightDatax; ?></td>
                        <?php
                    }
                    $enc = "MONTHLY*".$Month;
                    $ValEnc = base64_encode($enc);
                    $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$ValEnc.'" data-target="#AllDetail" title="Detail"></span>';
                    $TotalByYear = @($TotalByYear + $TotalByComp);
                    $TotalByCompx = number_format((float)$TotalByComp, 2, '.', ',');
                    ?>
                    <td class="text-right"><?php echo $TotalByCompx; ?></td>
                    <td class="text-center"><?php echo $opt; ?></td>
                <tr>
                <?php
                }
                /*
                $DataTab = GET_INBOUND_SHIPMENT_BY_MONTH("TABLE",$InputYear,$linkMACHWebTrax);
                while($restab=sqlsrv_fetch_array($DataTab))
                {
                    $BulanAngka = trim($restab['Bulan']);
                    $Location = trim($restab['ToSubject2']);
                    $BulanNama = trim($restab['MonthName']);
                    $FreightCost = trim($restab['Freight']);
                    $TotalQty = trim($restab['Qty']);
                    $TotalWeight = trim($restab['Weight']);
                    $BulanTahun = $BulanNama.",".$InputYear;
                    $FreightCost = number_format((float)$FreightCost, 2, '.', ',');
                    $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                    $TotalWeight = number_format((float)$TotalWeight, 2, '.', ',');
                    $ValEnc = $BulanAngka."+".$InputYear."+".$Location."+".$BulanNama;
                    $Enc = base64_encode($ValEnc);
                    $Opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$Enc.'" data-target="#InboundDetail" title="Detail"></span>';
                ?>
                <tr>
                    <td class="text-left"><?php echo $BulanTahun; ?></td>
                    <td class="text-center"><?php echo $Location; ?></td>
                    <td class="text-right"><?php echo $FreightCost; ?></td>
                    <td class="text-right"><?php echo $TotalQty; ?></td>
                    <td class="text-right"><?php echo $TotalWeight; ?></td>
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
<?php  
}
?>