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
if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $InputYear = htmlspecialchars(trim($_POST['InputYear']), ENT_QUOTES, "UTF-8");
    if($InputYear == "All") # semua tahun
    {
        $ArrListYear = array();
        $QListYear = GET_LIST_FILTER_SHIPPING($linkMACHWebTrax);
        while($RListYear = sqlsrv_fetch_array($QListYear))
        {
            array_push($ArrListYear,array("YearShipment" => trim($RListYear['YearShipment'])));
        }
        $Year1Idx = end(array_keys($ArrListYear));
        $Year1 = $ArrListYear[$Year1Idx]['YearShipment'];
        $Year2 = $ArrListYear[0]['YearShipment'];
        krsort($ArrListYear);
        # list data
        $ArrDataPerYear = array();
        foreach($ArrListYear as $ListYear)
        {
            $ArrDataDefault = array();
            $QGetData = GET_DATA_SHIPMENT_COUNTRY_BY_YEAR($ListYear['YearShipment'],$linkMACHWebTrax);
            while($RGetData = sqlsrv_fetch_array($QGetData))
            {
                switch (trim($RGetData['FromSubject'])) {
                    case 'PT. Promanufacture Indonesia':
                        $NewName = 'PSM';
                        break;
                    case 'PT. Promanufacture Indonesia (Salatiga)':
                        $NewName = 'PSL';
                        break;
                    default:
                        $NewName = 'FOR';
                        break;
                }
                $TempArray1 = array(
                    "MonthNo" => (int)trim($RGetData['MonthNo']),
                    "Country" => trim($RGetData['AccShippingCountry']),
                    "From" => trim($NewName),
                    "YearShipment" => $ListYear['YearShipment']
                );
                array_push($ArrDataDefault,$TempArray1);
            }             
            $QGetData2 = GET_DATA_SHIPMENT_FOR_COUNTRY_BY_YEAR($ListYear['YearShipment'],$linkMACHWebTrax);
            while($RGetData2 = sqlsrv_fetch_array($QGetData2))
            {
                switch (trim($RGetData2['FromSubject'])) {
                    case 'PT. Promanufacture Indonesia':
                        $NewName = 'PSM';
                        break;
                    case 'PT. Promanufacture Indonesia (Salatiga)':
                        $NewName = 'PSL';
                        break;
                    default:
                        $NewName = 'FOR';
                        break;
                }
                $TempArray1 = array(
                    "MonthNo" => (int)trim($RGetData2['MonthNo']),
                    "Country" => trim($RGetData2['AccShippingCountry']),
                    "From" => trim($NewName),
                    "YearShipment" => $ListYear['YearShipment']
                );
                array_push($ArrDataDefault,$TempArray1);
            }     
            foreach ($ArrDataDefault as $DataDefault1)
            {
                $ValCountryT1 = $DataDefault1['Country'];
                $ValFromT1 = $DataDefault1['From'];
                $ValYearT1 = $DataDefault1['YearShipment'];
                $ValTotal1 = 0;
                # check data array
                $BolCheck1 = TRUE;
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    if($DataPerYear['Country'] == $ValCountryT1 && $DataPerYear['From'] == $ValFromT1 && $DataPerYear['YearShipment'] == $ValYearT1)
                    {
                        $BolCheck1 = FALSE;
                        break;
                    }
                }
                if($BolCheck1 == TRUE)
                {
                    $NoLoop1 = 1;
                    foreach ($ArrDataDefault as $DataDefault2)
                    {
                        if($DataDefault2['Country'] == $ValCountryT1 && $DataDefault2['From'] == $ValFromT1 && $DataDefault2['YearShipment'] == $ValYearT1)
                        {
                            if($NoLoop1 == 1)
                            {
                                $ValTotal1 = 1;
                                $NoLoop1++;
                            }
                            else
                            {
                                if($DataDefault2['Country'] == $ValCountryT1 && $DataDefault2['From'] == $ValFromT1 && $DataDefault2['YearShipment'] == $ValYearT1)
                                {
                                    $ValTotal1 = $ValTotal1 + 1;
                                }
                                $NoLoop1++;
                            }
                        }
                    }
                    # add data to array
                    array_push($ArrDataPerYear,array("Total" => $ValTotal1,"Country" => $ValCountryT1,"From" => $ValFromT1,"YearShipment" => $ValYearT1));
                }
            }
        }
        # Array location
        $ArrLocation = array();
        $TempLocation = "";
        array_push($ArrLocation,array("Location" => "PSM"));
        array_push($ArrLocation,array("Location" => "PSL"));
        array_push($ArrLocation,array("Location" => "FOR"));
        # total location
        $TotalLocation = count($ArrLocation);
        $ArrDataPerLocationHighest = array();
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            # set array 
            $ArrDataPerLocation[$i] = array();
        }
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            foreach($ArrDataPerYear as $DataPerYear)
            {
                if($DataPerYear['From'] == $Location)
                {
                    array_push($ArrDataPerLocation[$i],array("Total" => $DataPerYear['Total'],"Country" => $DataPerYear['Country'],"From" => $DataPerYear['From'],"YearShipment" => $DataPerYear['YearShipment']));
                }
            }
            foreach($ArrListYear as $ListYear)
            {
                $YearShipment = $ListYear['YearShipment'];
                $TTotal = 0;
                $TCountry = "N/A";
                $NoLoop = 1;
                foreach($ArrDataPerLocation[$i] as $DataPerLocation)
                {
                    if($DataPerLocation['From'] == $Location && $DataPerLocation['YearShipment'] == $YearShipment)
                    {
                        if($NoLoop == 1)
                        {
                            $TTotal = $DataPerLocation['Total'];
                            $TCountry = $DataPerLocation['Country'];
                            $NoLoop++;
                        }
                        else
                        {
                            if($DataPerLocation['Total'] > $TTotal)
                            {
                                $TTotal = $DataPerLocation['Total'];
                                $TCountry = $DataPerLocation['Country'];
                            }
                            $NoLoop++;
                        }
                    }
                }
                array_push($ArrDataPerLocationHighest,array("Total" => $TTotal,"Country" => $TCountry,"From" => $Location,"YearShipment" => $YearShipment));
            }
        }
        # rank qty
        $ArrRestRankQtyAll = array();
        for($loopLocation = 1;$loopLocation <= $TotalLocation;$loopLocation++)
        {
            $NewID = $loopLocation - 1;
            $Location = $ArrLocation[$NewID]['Location'];            
            foreach($ArrListYear as $DataYear)
            {
                $TotalCountDt = 0;
                $TotalOther = 0;
                $TempArrayDt1 = array();
                $TempArrayDt2 = array();
                $TempArrayDt3 = array();
                $TempArrayNoOther = array();
                $TempArrayOther = array();
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    if($DataPerYear['YearShipment'] == $DataYear['YearShipment'] && $DataPerYear['From'] == $Location)
                    {
                        array_push($TempArrayDt1,array("Total"=>$DataPerYear['Total'],"Country"=>$DataPerYear['Country'],"From"=>$DataPerYear['From'],"YearShipment"=>$DataPerYear['YearShipment']));
                    }
                }
                if(count($TempArrayDt1) > 0)
                {
                    foreach ($TempArrayDt1 as $Dt1)
                    {
                        $TotalCountDt++;
                        if($Dt1['Country'] == "Other")
                        {
                            array_push($TempArrayOther,array("YearShipment" => $Dt1['YearShipment'],"Total" => $Dt1['Total'],"Country" => $Dt1['Country'],"From" => $Dt1['From']));
                            $TotalOther++;
                        }
                        else
                        {
                            array_push($TempArrayNoOther,array("YearShipment" => $Dt1['YearShipment'],"Total" => $Dt1['Total'],"Country" => $Dt1['Country'],"From" => $Dt1['From']));
                        }
                    }
                    
                    # sort array 
                    if(count($TempArrayNoOther) != 0)
                    {
                        array_multisort(array_column($TempArrayNoOther,'Total'), SORT_DESC, array_column($TempArrayNoOther,'Country'), SORT_DESC, $TempArrayNoOther);
                    }
                    if(count($TempArrayOther) != 0)
                    {
                        array_multisort(array_column($TempArrayOther,'Total'), SORT_DESC, array_column($TempArrayOther,'Country'), SORT_DESC, $TempArrayOther);
                    }
                    
                    if(count($TempArrayNoOther) != 0 && count($TempArrayOther) != 0)
                    {
                        if(count($TempArrayNoOther) == 1)    # jika total = 1
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location));                            
                                $ValRank++;
                            }
                        }
                        elseif(count($TempArrayNoOther) == 2)    # jika total = 2
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                # input array
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location));                                
                                $ValRank++;
                            }
                        }
                        else    # jika total >= 3
                        {
                            $ValTempTotal = 0;
                            $ValTempTotalCost = 0;
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                        }
                    }
                    elseif(count($TempArrayNoOther) != 0 && count($TempArrayOther) == 0)
                    {
                        if(count($TempArrayNoOther) == 1)    # jika total = 1
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                        }
                        elseif(count($TempArrayNoOther) == 2)   # jika total = 2
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                        }
                        else    # jika total >= 3
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                # input array
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                                $ValRank++;
                            }
                        }
                    }
                    else # data kosong
                    {
                        for($loopingRank = 1;$loopingRank <= 3;$loopingRank++)
                        {
                            array_push($TempArrayDt2,array("Rank" => $loopingRank,"Total" => "0","Country" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                        }
                    }
                    if(count($TempArrayDt2) == 1)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From']));
                        }
                        array_push($TempArrayDt3,array("Rank" => "2","Total" => "0","Country" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                        array_push($TempArrayDt3,array("Rank" => "3","Total" => "0","Country" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                    }
                    elseif (count($TempArrayDt2) == 2)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From']));
                        }
                        array_push($TempArrayDt3,array("Rank" => "3","Total" => "0","Country" => "N/A","YearShipment" =>  $DataYear['YearShipment'],"From" => $Location));
                    }
                    elseif (count($TempArrayDt2) == 3)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From']));
                        }
                    }
                    else
                    {
                        $NoLoopRank = 1;
                        $TTotalQty = 0;
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            if($NoLoopRank < 3)
                            {
                                array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From']));
                            }
                            else
                            {
                                $TTotalQty = (int)$TTotalQty + (int)$DataResult['Total'];
                            }
                            $NoLoopRank++;
                        }

                        if($NoLoopRank > 2)
                        {
                            array_push($TempArrayDt3,array("Rank" => "3","Total" => $TTotalQty,"Country" => "Others","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                        }
                    }
                    foreach($TempArrayDt3 as $DataResult)
                    {
                        array_push($ArrRestRankQtyAll,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From']));
                    }
                }
                else
                {
                    array_push($ArrRestRankQtyAll,array("Rank" => "1","Total" => "0","Country" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                    array_push($ArrRestRankQtyAll,array("Rank" => "2","Total" => "0","Country" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                    array_push($ArrRestRankQtyAll,array("Rank" => "3","Total" => "0","Country" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location));
                }
            }
        }
        
        $MaxTotalQtyAllTemp = 0;
        $MaxTotalQtyAll = 0;
        foreach($ArrRestRankQtyAll as $DtRankQtyAll)
        {
            if($DtRankQtyAll['Rank'] != "3")
            {
                $MaxTotalQtyAllTemp = $MaxTotalQtyAllTemp + (int)$DtRankQtyAll['Total'];
            }
            else
            {
                $MaxTotalQtyAllTemp = $MaxTotalQtyAllTemp + (int)$DtRankQtyAll['Total'];
                if($MaxTotalQtyAllTemp > $MaxTotalQtyAll)
                {
                    $MaxTotalQtyAll = $MaxTotalQtyAllTemp;
                }
                $MaxTotalQtyAllTemp = 0;
            }
        }
    ?>
        
    <div class="col-md-12">
        <div class="ColumnContent" id="columnchart_material1" style="width: 100%; height: 400px;"></div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="TableDataChart2" class="table table-responsive table-bordered table-hover">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Destination<br>Country</th>
                        <th class="text-center">Qty<br>Shipment</th>
                        <th class="text-center">Qty<br>Shipment (%)</th>
                    </tr>
                </thead>
                <tbody><?php 
                $No = 1;
                $TotalQtyShipment = 0;
                $ArrPercentage = array();
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    $TotalQtyShipment = $TotalQtyShipment + (int)$DataPerYear['Total'];
                }
                $TotalPercentageQty =  ($TotalQtyShipment / $TotalQtyShipment) * 100; 
                foreach($ArrDataPerYear as $DataPerYear)
                {
                        $PercentageQty = ((int)$DataPerYear['Total'] / (int)$TotalQtyShipment)*100;
                        $PercentageQty = sprintf('%.2f',floatval($PercentageQty));
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo $DataPerYear['YearShipment']; ?></td>
                        <td class="text-center"><?php echo $DataPerYear['From']; ?></td>
                        <td class="text-left"><?php echo $DataPerYear['Country']; ?></td>
                        <td class="text-center"><?php echo (int)$DataPerYear['Total']; ?></td>
                        <td class="text-right"><?php echo $PercentageQty; ?></td>
                    </tr>
                    <?php
                    $No++;
                    array_push($ArrPercentage,array("YearShipment" => $DataPerYear['YearShipment'],"From" => $DataPerYear['From'],"Country" => $DataPerYear['Country'],"Total" => (int)$DataPerYear['Total'],"Percentage" => $PercentageQty));
                }

                ?></tbody>
                <tfoot class="theadCustom">
                    <tr>
                        <td class="text-center" colspan="4"><strong>Grand Total</strong></td>
                        <td class="text-center"><?php echo "<strong>".$TotalQtyShipment."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".round($TotalPercentageQty)."</strong>"; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script type="text/javascript">
		google.charts.load('current', {
		  callback: function () {
			drawChart1();
			$(window).resize(drawChart1);
		  },
		  packages:['corechart','bar']
		});
        google.charts.setOnLoadCallback(drawChart1);
        function drawChart1() {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'Year');           
            <?php 
                foreach ($ArrLocation as $DataLocation) {
                    for($r2 = 1;$r2 <= count($ArrListYear);$r2++)
                    {
                        if($r2 < 3)
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Top ".$r2." Destination"; ?>');
                    <?php
                        }
                        else
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Others "; ?>');
                    <?php 
                        }
                    }
                }
            ?>    
            data1.addRows([
                <?php
                foreach ($ArrListYear as $ListYear)
                {
                    echo "[";
                    echo "'".$ListYear['YearShipment']."'";
                    for($i2 = 1;$i2 <= $TotalLocation;$i2++)
                    {
                        $NewID = $i2 - 1;
                        $Location = $ArrLocation[$NewID]['Location'];
                        for($r2 = 1;$r2 <= 3;$r2++)
                        {
                            foreach($ArrRestRankQtyAll as $DataRank)
                            {
                                if($DataRank['Rank'] == $r2 && $DataRank['From'] == $Location && $DataRank['YearShipment'] == $ListYear['YearShipment'])
                                {
                                    echo ",{v:".$DataRank['Total'].",f:'".$DataRank['Country']." : ".(int)$DataRank['Total']."'}";
                                    break;
                                }
                            }
                        }
                    }
                    echo "],";
                }
                ?>
            ]);

            var options1 = {
                    title: 'The Top 2 Destination Country (All Season)'
                    ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                    ,vAxis: { minValue: 0}
                    ,tooltip: { isHtml: true }
                    ,legend: { position: 'right', maxLines: 3 }
                    ,chartArea: {top:50, left:50, height:"80%", width:"58%"}  
                    ,isStacked: true
                    ,hAxis : {
                        titleTextStyle: {color: '#000000',bold: true}
                    }
                    ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Qty Shipment',titleTextStyle: {color: '#000000',bold: true}
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                    },
                    series: {
                        0: {
                            targetAxisIndex: 0,
                            color : '#3366CC'
                        },
                        1: {
                            targetAxisIndex: 0,
                            color : '#527ED3'
                        },
                        2: {
                            targetAxisIndex: 0,
                            color : '#7196D9'
                        },
                        3: {
                            targetAxisIndex: 1,
                            color : '#DC3912'
                        },
                        4: {
                            targetAxisIndex: 1,
                            color : '#EC9A87'
                        },
                        5: {
                            targetAxisIndex: 1,
                            color : '#f2b8a9'
                        },
                        6: {
                            targetAxisIndex: 2,
                            color : '#FF9900'
                        },
                        7: {
                            targetAxisIndex: 2,
                            color : '#ffb23f'
                        },
                        8: {
                            targetAxisIndex: 2,
                            color : '#fcc779'
                        },
                    },
                    width: '100%',
                    vAxis: {
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            min: 0,
                            max: <?php echo $MaxTotalQtyAll+1; ?>
                        }
                    }
            };
            var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
            chart1.draw(data1, google.charts.Bar.convertOptions(options1));
        };            
    </script>
    <style>
    .ColumnContent{width: 100%; height: 400px;}
    </style>
    <?php
    }
    else # tahun terpilih
    {
        $ArrDataDefault = array();
        $QGetData = GET_DATA_SHIPMENT_COUNTRY_BY_YEAR($InputYear,$linkMACHWebTrax);
        while($RGetData = sqlsrv_fetch_array($QGetData))
        {
            switch (trim($RGetData['FromSubject'])) {
                case 'PT. Promanufacture Indonesia':
                    $NewName = 'PSM';
                    break;
                case 'PT. Promanufacture Indonesia (Salatiga)':
                    $NewName = 'PSL';
                    break;
                default:
                    $NewName = 'FOR';
                    break;
            }
            $TempArray1 = array(
                "MonthNo" => (int)trim($RGetData['MonthNo']),
                "Country" => trim($RGetData['AccShippingCountry']),
                "From" => trim($NewName)
            );
            array_push($ArrDataDefault,$TempArray1);
        }
                     
        $QGetData2 = GET_DATA_SHIPMENT_FOR_COUNTRY_BY_YEAR($InputYear,$linkMACHWebTrax);
        while($RGetData2 = sqlsrv_fetch_array($QGetData2))
        {
            switch (trim($RGetData2['FromSubject'])) {
                case 'PT. Promanufacture Indonesia':
                    $NewName = 'PSM';
                    break;
                case 'PT. Promanufacture Indonesia (Salatiga)':
                    $NewName = 'PSL';
                    break;
                default:
                    $NewName = 'FOR';
                    break;
            }
            $TempArray1 = array(
                "MonthNo" => (int)trim($RGetData2['MonthNo']),
                "Country" => trim($RGetData2['AccShippingCountry']),
                "From" => trim($NewName)
            );
            array_push($ArrDataDefault,$TempArray1);
        }
        # Array location
        $ArrLocation = array();
        $TempLocation = "";
        array_push($ArrLocation,array("Location" => "PSM"));
        array_push($ArrLocation,array("Location" => "PSL"));
        array_push($ArrLocation,array("Location" => "FOR"));
        # total location
        $TotalLocation = count($ArrLocation);
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            # set array 
            $ArrDataPerLocationBranch[$i] = array();
            $ArrDataBranch[$i] = array();
        }
        $ArrTotalCountry = array();
        for ($y=1; $y < 13; $y++)
        {
            # set array temporary per month
            $ArrDataPerMonthAll[$y] = array();
            $ArrDataCountPerMonth[$y] = array();
        }
        $TotalLocation = count($ArrLocation);   
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $LoopLocation = $ArrLocation[$NewID]['Location'];
            for ($y=1; $y < 13; $y++)
            {
                foreach ($ArrDataDefault as $DataDefault)
                {
                    if($DataDefault['MonthNo'] == $y && $DataDefault['From'] == $LoopLocation)
                    {
                        array_push($ArrDataPerMonthAll[$y],array("Country"=>$DataDefault['Country'],"MonthNo"=>$DataDefault['MonthNo'],"From"=>$DataDefault['From']));
                    }
                }
            }
        }   
        for ($y=1; $y < 13; $y++)
        {
            ksort($ArrDataPerMonthAll[$y]);
            foreach($ArrDataPerMonthAll[$y] as $DataPerMonth)
            {
                $TempCountry = $DataPerMonth['Country'];
                $TempMonthNo = $DataPerMonth['MonthNo'];
                $TempFrom = $DataPerMonth['From'];
                $BolCheck1 = TRUE;
                foreach ($ArrDataCountPerMonth[$y] as $DataCount)
                {
                    if($DataCount['Country'] == $TempCountry && $DataCount['MonthNo'] == $y && $DataCount['From'] == $TempFrom)
                    {
                        $BolCheck1 = FALSE;
                        break;
                    }
                }
                if($BolCheck1 == TRUE)
                {
                    $CountCountry = 0;
                    foreach($ArrDataPerMonthAll[$y] as $DataPerMonth2)
                    {
                        if($DataPerMonth2['Country'] == $TempCountry && $DataPerMonth2['MonthNo'] == $y && $DataPerMonth2['From'] == $TempFrom)
                        {
                            $CountCountry = $CountCountry + 1;
                        }
                    }
                    $TempArray1 = array(
                        "Total" => (int)$CountCountry,
                        "Country" => $TempCountry,
                        "MonthNo" => $TempMonthNo,
                        "From" => $TempFrom
                    );
                    array_push($ArrDataCountPerMonth[$y],$TempArray1);
                }
            }              
        }
        # data chart
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $ValLocation = $ArrLocation[$NewID]['Location'];
            $ArrFinalChartTemp[$i] = array();
            $ArrFinalChart[$i] = array();
            for ($y=1; $y < 13; $y++)
            {
                $BolCheck2 = FALSE;
                $TTotal = "";
                $TCountry = "";
                foreach($ArrDataCountPerMonth[$y] as $DataResPerMonth)
                {
                    if($DataResPerMonth['MonthNo'] == $y && $DataResPerMonth['From'] == $ValLocation)
                    {
                        $TTotal = $DataResPerMonth['Total'];
                        $TCountry = $DataResPerMonth['Country'];
                        $BolCheck2 = TRUE;
                        array_push($ArrFinalChartTemp[$i],array("Total" => $TTotal,"Country" => $TCountry,"MonthNo" => $y,"From" => $ValLocation));                       
                    }
                }
                if($BolCheck2 == FALSE)
                {
                    array_push($ArrFinalChartTemp[$i],array("Total" => "0","Country" => "-","MonthNo" => $y,"From" => $ValLocation));
                }
            }
        }
        for($i = 1;$i <= $TotalLocation;$i++)
        {            
            $NewID = $i - 1;
            $ValLocation = $ArrLocation[$NewID]['Location'];
            for ($y=1; $y < 13; $y++)
            {
                $TTotal = 0;
                $TCountry = "";
                $NoLoop = 1;
                foreach($ArrFinalChartTemp[$i] as $DataFinalTemp)
                {
                    if($DataFinalTemp['From'] == $ValLocation && $DataFinalTemp['MonthNo'] == $y)
                    {
                        if($NoLoop == 1)
                        {
                            $TTotal = $DataFinalTemp['Total'];
                            $TCountry = $DataFinalTemp['Country'];
                            $NoLoop++;
                        }
                        else
                        {
                            if($DataFinalTemp['Total'] > $TTotal)
                            {
                                $TTotal = $DataFinalTemp['Total'];
                                $TCountry = $DataFinalTemp['Country'];
                            }
                            $NoLoop++;
                        }
                    }
                }
                array_push($ArrFinalChart[$i],array("Total"=> $TTotal,"Country"=> $TCountry,"MonthNo" => $y,"From" => $ValLocation));
            }
        }
        # rank qty
        $ArrRestRankQty = array();
        for($loopLocation = 1;$loopLocation <= $TotalLocation;$loopLocation++)
        {
            $NewID = $loopLocation - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            for ($loopMonth=1; $loopMonth < 13; $loopMonth++)
            {
                $TotalCountDt = 0;
                $TotalOther = 0;
                $TempArrayNoOther = array();
                $TempArrayOther = array();
                $ArrDataQtyPerMonthB[$loopMonth] = array();

                foreach($ArrDataCountPerMonth[$loopMonth] as $ID => $DataPerMonth) # get total Country && total row per month
                {
                    if($DataPerMonth['From'] == $Location)
                    {
                        $TotalCountDt++;
                        if($DataPerMonth['Country'] == "N/A")
                        {
                            array_push($TempArrayOther,array("Month" => $DataPerMonth['MonthNo'],"Total" => $DataPerMonth['Total'],"Country" => $DataPerMonth['Country'],"From" => $DataPerMonth['From']));
                            $TotalOther++;
                        }
                        else
                        {
                            array_push($TempArrayNoOther,array("Month" => $DataPerMonth['MonthNo'],"Total" => $DataPerMonth['Total'],"Country" => $DataPerMonth['Country'],"From" => $DataPerMonth['From']));
                        }
                    }                        
                }
                # sort array 
                if(count($TempArrayNoOther) != 0)
                {
                    array_multisort(array_column($TempArrayNoOther,'Total'), SORT_DESC, array_column($TempArrayNoOther,'Country'), SORT_DESC, $TempArrayNoOther);
                }
                if(count($TempArrayOther) != 0)
                {
                    array_multisort(array_column($TempArrayOther,'Total'), SORT_DESC, array_column($TempArrayOther,'Country'), SORT_DESC, $TempArrayOther);
                }
                array_multisort(array_column($ArrDataCountPerMonth[$loopMonth],'Total'), SORT_DESC, array_column($ArrDataCountPerMonth[$loopMonth],'Country'), SORT_DESC, $ArrDataCountPerMonth[$loopMonth]);
		        $ArrTempQtyPerMonth[$loopMonth] = array();
                if(count($TempArrayNoOther) != 0 && count($TempArrayOther) != 0)
                {
                    if(count($TempArrayNoOther) == 1)    # jika total = 1
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => "Other","MonthNo" => $loopMonth,"From" => $Location));                            
                            $ValRank++;
                        }
                    }
                    elseif(count($TempArrayNoOther) == 2)    # jika total = 2
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            # input array
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => "Other","MonthNo" => $loopMonth,"From" => $Location));                            
                            $ValRank++;
                        }
                    }
                    else    # jika total >= 3
                    {
                        $ValTempTotal = 0;
                        $ValTempTotalCost = 0;
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => "Other","MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        } 
                    }
                }
                elseif(count($TempArrayNoOther) != 0 && count($TempArrayOther) == 0)
                {
                    if(count($TempArrayNoOther) == 1)    # jika total = 1
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        }
                    }
                    elseif(count($TempArrayNoOther) == 2)   # jika total = 2
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        }
                    }
                    else    # jika total >= 3
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            # input array
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Country" => $DataArr['Country'],"MonthNo" => $loopMonth,"From" => $Location));
                            $ValRank++;
                        }
                    }
                }
                else # data kosong
                {
                    for($loopingRank = 1;$loopingRank <= 3;$loopingRank++)
                    {
                        array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $loopingRank,"Total" => "0","Country" => "N/A","MonthNo" => $loopMonth,"From" => $Location));
                    }
                }                
                if(count($ArrTempQtyPerMonth[$loopMonth]) == 1)
                {
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From']));
                    }
                    array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "2","Total" => "0","Country" => "N/A","MonthNo" => $loopMonth,"From" => $Location));
                    array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "3","Total" => "0","Country" => "N/A","MonthNo" => $loopMonth,"From" => $Location));
                }
                elseif (count($ArrTempQtyPerMonth[$loopMonth]) == 2)
                {
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From']));
                    }
                    array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "3","Total" => "0","Country" => "N/A","MonthNo" => $loopMonth,"From" => $Location));
                }
                elseif (count($ArrTempQtyPerMonth[$loopMonth]) == 3)
                {
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From']));
                    }
                }
                else
                {
                    $NoLoopRank = 1;
                    $TTotalQty = 0;
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        if($NoLoopRank < 3)
                        {
                            array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From']));
                        }
                        else
                        {
                            $TTotalQty = (int)$TTotalQty + (int)$DataResult['Total'];
                        }
                        $NoLoopRank++;
                    }

                    if($NoLoopRank > 2)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "3","Total" => $TTotalQty,"Country" => "Others","MonthNo" => $loopMonth,"From" => $Location));
                    }
                }
                foreach($ArrDataQtyPerMonthB[$loopMonth] as $DataResult)
                {
                    array_push($ArrRestRankQty,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Country" => $DataResult['Country'],"Month" => $DataResult['MonthNo'],"From" => $DataResult['From']));
                }
                # reset array
                $ArrDataQtyPerMonthB[$loopMonth] = array();
            }
        }
        $MaxTotalQtyPerYearTemp = 0;
        $MaxTotalQtyPerYear = 0;
        foreach ($ArrRestRankQty as $DtRestRankQty)
        {
            if($DtRestRankQty['Rank'] != '3')
            {
                $MaxTotalQtyPerYearTemp = $MaxTotalQtyPerYearTemp + (int)$DtRestRankQty['Total'];
            }
            else
            {
                $MaxTotalQtyPerYearTemp = $MaxTotalQtyPerYearTemp + (int)$DtRestRankQty['Total'];
                if($MaxTotalQtyPerYearTemp > $MaxTotalQtyPerYear)
                {
                    $MaxTotalQtyPerYear = $MaxTotalQtyPerYearTemp;
                }
                $MaxTotalQtyPerYearTemp = 0;
            }
        }
    ?>
        
    <div class="col-md-12">
        <div class="ColumnContent" id="columnchart_material1"></div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="TableDataChart2" class="table table-responsive table-bordered table-hover">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Month</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Destination<br>Country</th>
                        <th class="text-center">Qty<br>Shipment</th>
                        <th class="text-center">Qty<br>Shipment (%)</th>
                    </tr>
                </thead>
                <tbody><?php 
                $No = 1;
                $TotalQtyShipment = 0;
                $ArrPercentage = array();
                for($i = 1;$i<13;$i++)
                {
                    foreach($ArrDataCountPerMonth[$i] as $DataResult1)
                    {
                        $TotalQtyShipment = $TotalQtyShipment + (int)$DataResult1['Total'];
                    }
                }
                $TotalPercentageQty =  ($TotalQtyShipment / $TotalQtyShipment) * 100; 
                for($i = 1;$i<13;$i++)
                {
                    foreach($ArrDataCountPerMonth[$i] as $DataResult)
                    {
                        $PercentageQty = ((int)$DataResult['Total'] / (int)$TotalQtyShipment)*100;
                        $PercentageQty = sprintf('%.2f',floatval($PercentageQty));
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo date('M',mktime(0, 0, 0, $DataResult['MonthNo'], 10)).' '.$InputYear; ?></td>
                        <td class="text-center"><?php echo $DataResult['From']; ?></td>
                        <td class="text-left"><?php echo $DataResult['Country']; ?></td>
                        <td class="text-center"><?php echo (int)$DataResult['Total']; ?></td>
                        <td class="text-right"><?php echo $PercentageQty; ?></td>
                    </tr>
                    <?php
                        $No++;
                        array_push($ArrPercentage,array("MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"Country" => $DataResult['Country'],"Total" => (int)$DataResult['Total'],"Percentage" => $PercentageQty));
                    }
                }

                ?></tbody>
                <tfoot class="theadCustom">
                    <tr>
                        <td class="text-center" colspan="4"><strong>Grand Total</strong></td>
                        <td class="text-center"><?php echo "<strong>".$TotalQtyShipment."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".round($TotalPercentageQty)."</strong>"; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script type="text/javascript">
		google.charts.load('current', {
		  callback: function () {
			drawChart1();
			$(window).resize(drawChart1);
		  },
		  packages:['corechart','bar']
		});
        google.charts.setOnLoadCallback(drawChart1);
        function drawChart1() {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'Month');
            <?php 
                foreach ($ArrLocation as $DataLocation) {
                    for($r2 = 1;$r2 <= 3;$r2++)
                    {
                        if($r2 < 3)
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Top ".$r2." Destination"; ?>');
                    <?php
                        }
                        else
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Others "; ?>');
                    <?php                            
                        }
                    }
                }
            ?>
            data1.addRows([<?php
            for($i = 1;$i<13;$i++)
            {
                echo "[";
                echo "'".date('M',mktime(0, 0, 0, $i, 1, $InputYear))."'";
                for($i2 = 1;$i2 <= $TotalLocation;$i2++)
                {
                    $NewID = $i2 - 1;
                    $Location = $ArrLocation[$NewID]['Location'];
                    for($r2 = 1;$r2 <= 3;$r2++)
                    {
                        foreach($ArrRestRankQty as $DataRank)
                        {
                            if($DataRank['Rank'] == $r2 && $DataRank['From'] == $Location && $DataRank['Month'] == $i)
                            {
                                echo ",{v:".$DataRank['Total'].",f:'".$DataRank['Country']." : ".(int)$DataRank['Total']."'}";
                                break;
                            }
                        }
                    }
                }
                echo "],";
            }
            ?>]);
            var options1 = {
                    title: 'The Top 2 Destination Country (<?php echo $InputYear; ?>)'
                    ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                    ,vAxis: { minValue: 0}
                    ,tooltip: { isHtml: true }
                    ,legend: { position: 'right', maxLines: 3 }
                    ,chartArea: {top:50, left:50, height:"80%", width:"58%"}  
                    ,isStacked: true
                    ,hAxis : {
                        titleTextStyle: {color: '#000000',bold: true}
                    }
                    ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Qty Shipment',titleTextStyle: {color: '#000000',bold: true}
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                    },
                    series: {
                        0: {
                            targetAxisIndex: 0,
                            color : '#3366CC'
                        },
                        1: {
                            targetAxisIndex: 0,
                            color : '#527ED3'
                        },
                        2: {
                            targetAxisIndex: 0,
                            color : '#7196D9'
                        },
                        3: {
                            targetAxisIndex: 1,
                            color : '#DC3912'
                        },
                        4: {
                            targetAxisIndex: 1,
                            color : '#EC9A87'
                        },
                        5: {
                            targetAxisIndex: 1,
                            color : '#f2b8a9'
                        },
                        6: {
                            targetAxisIndex: 2,
                            color : '#FF9900'
                        },
                        7: {
                            targetAxisIndex: 2,
                            color : '#ffb23f'
                        },
                        8: {
                            targetAxisIndex: 2,
                            color : '#fcc779'
                        },
                    },
                    width: '100%',
                    vAxis: {
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            min: 0,
                            max: <?php echo $MaxTotalQtyPerYear+1; ?>
                        }
                    }
            };
            var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
            chart1.draw(data1, google.charts.Bar.convertOptions(options1));
        };            
    </script>
    <style>
    .ColumnContent{width: 100%; height: 400px;}
    </style>
    <?php
    }
}
else
{
    echo "";     
}
?>