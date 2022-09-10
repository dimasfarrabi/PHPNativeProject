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
    $ArrData = array();
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
        $ArrDataDefault = array();
        foreach($ArrListYear as $ListYear)
        {
            $QGetData = GET_DATA_SHIPMENT_BY_YEAR($ListYear['YearShipment'],$linkMACHWebTrax);
            while($RGetData = sqlsrv_fetch_array($QGetData))
            {
                $ValCountry1 = trim($RGetData['AccShippingCountry']);
                $ValCountry2 = trim($RGetData['Country2']);
                if($ValCountry1 != "")
                {
                    $ValCountry = $ValCountry1;
                }
                else
                {
                    if($ValCountry2 != "")
                    {
                        $ValCountry = $ValCountry2;
                    }
                    else
                    {
                        $ValCountry = "Other";
                    }
                }
                
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
                    "DateShipped" => trim($RGetData['Date']),
                    "MonthName" => trim($RGetData['MonthName']),
                    "MonthNo" =>  (int)date("m", strtotime(trim($RGetData['MonthName']))),
                    "OwnerUsername" => trim($RGetData['OwnerUsername']),
                    "From" => trim($NewName),
                    "TrackingID" => trim($RGetData['TrackingID']),
                    "TotalWeight" => trim($RGetData['TotalWeight']),
                    "Freight" => trim($RGetData['Freight']),
                    "Courier" => trim($RGetData['Courier']),
                    "Country" => trim($ValCountry)
                );
                array_push($ArrDataDefault,$TempArray1);
            }
            
            # get data FOR
            $QGetData2 = GET_DATA_SHIPMENT_FOR_BY_YEAR($ListYear['YearShipment'],$linkMACHWebTrax);
            while($RGetData2 = sqlsrv_fetch_array($QGetData2))
            {
                $ValCountry1 = trim($RGetData2['AccShippingCountry']);
                $ValCountry2 = trim($RGetData2['Country2']);
                if($ValCountry1 != "")
                {
                    $ValCountry = $ValCountry1;
                }
                else
                {
                    if($ValCountry2 != "")
                    {
                        $ValCountry = $ValCountry2;
                    }
                    else
                    {
                        $ValCountry = "Other";
                    }
                }

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
                    "DateShipped" => trim($RGetData2['Date']),
                    "MonthName" => trim($RGetData2['MonthName']),
                    "MonthNo" =>  (int)date("m", strtotime(trim($RGetData2['MonthName']))),
                    "OwnerUsername" => trim($RGetData2['OwnerUsername']),
                    "From" => trim($NewName),
                    "TrackingID" => trim($RGetData2['TrackingID']),
                    "TotalWeight" => trim($RGetData2['TotalWeight']),
                    "Freight" => trim($RGetData2['Freight']),
                    "Courier" => trim($RGetData2['Courier']),
                    "Country" => trim($ValCountry)
                );
                array_push($ArrDataDefault,$TempArray1);
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
        $ArrDataResult = array();
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            # set array freight
            $ArrDataPerLocationBranch[$i] = array();
            $ArrDataBranch[$i] = array();
        }

        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $LoopLocation = $ArrLocation[$NewID]['Location'];
            $TempTotalFreight = 0;
            $TempTotalWeight = 0;
            $TempLoop = "";
            # pemisahan per lokasi
            foreach($ArrDataDefault as $DataDefault)
            {
                if($DataDefault['From'] == $LoopLocation)
                {
                    $TempArray2 = array(
                        "DateShipped" => trim($DataDefault['DateShipped']),
                        "YearShipped" => date('Y',strtotime(trim($DataDefault['DateShipped']))),
                        "MonthName" => trim($DataDefault['MonthName']),
                        "MonthNo" =>  trim($DataDefault['MonthNo']),
                        "OwnerUsername" => trim($DataDefault['OwnerUsername']),
                        "From" => trim($DataDefault['From']),
                        "TrackingID" => trim($DataDefault['TrackingID']),
                        "TotalWeight" => trim($DataDefault['TotalWeight']),
                        "Freight" => trim($DataDefault['Freight']),
                        "Courier" => trim($DataDefault['Courier']),
                        "Country" => trim($DataDefault['Country'])
                    );
                    array_push($ArrDataPerLocationBranch[$i],$TempArray2);
                }
            }
        }
        $CountID = 0;
        foreach ($ArrListYear as $ListYear)
        {
            for($i = 1;$i <= $TotalLocation;$i++)
            {
                # set array all
                $ArrDataPerBranchAll[$CountID] = array();
                $CountID++;
            }
        }
        $CountID = 0;
        foreach ($ArrListYear as $ListYear)
        {
            $YearSelected = trim($ListYear['YearShipment']);
            for($i = 1;$i <= $TotalLocation;$i++)
            {
                $NewID = $i - 1;
                $Location = $ArrLocation[$NewID]['Location'];
                $TempTotalFreight = 0;
                $TempTotalWeight = 0;
                $TempTotalQty = 0;
                foreach($ArrDataPerLocationBranch[$i] as $DataBranch)
                {
                    if($DataBranch['YearShipped'] == $YearSelected && $DataBranch['From'] == $Location)
                    {
                        $TempTotalFreight = (float)$TempTotalFreight + (float)$DataBranch['Freight'];
                        $TempTotalWeight = (float)$TempTotalWeight + (float)$DataBranch['TotalWeight'];
                        $TempTotalQty = $TempTotalQty + 1;
                    }
                }
                # simpan ke array baru
                $NewArray = array(
                    "Year" => $YearSelected,
                    "Location" => $Location,
                    "TotalFreight" => $TempTotalFreight,
                    "TotalWeight" => $TempTotalWeight,
                    "TotalQty" => $TempTotalQty
                );
                array_push($ArrDataPerBranchAll[$CountID],$NewArray);
                $CountID++;
            }
        }
        ?>        
        <div class="col-md-4">
            <div class="ColumnContent" id="columnchart_material1"></div>
        </div>
        <div class="col-md-4">
            <div class="ColumnContent" id="columnchart_material2"></div>
        </div>
        <div class="col-md-4">
            <div class="ColumnContent" id="columnchart_material3"></div>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="TableDataChart1" class="table table-responsive table-bordered table-hover">
                    <thead class="theadCustom">
                        <tr>
                            <th class="text-center">Year</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Total of Freight Cost ($)</th>
                            <th class="text-center">Qty Shipment</th>
                            <th class="text-center">Total Weight</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                    $No = 1;
                    $TotalFreightCost = 0;
                    $TotalQty = 0;
                    $TotalWeight = 0;
                    $IdxID = 0;
                    foreach ($ArrListYear as $ListYear)
                    {
                        $YearSelected = trim($ListYear['YearShipment']);
                        for($i = 1;$i <= $TotalLocation;$i++)
                        {
                            $NewID = $i - 1;
                            $Location = $ArrLocation[$NewID]['Location'];
                            foreach($ArrDataPerBranchAll[$IdxID] as $DataResult)
                            {
                                $RowEnc = base64_encode($DataResult['Location']."*".$DataResult['Year']."*".$InputYear);
                                $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#ModalDetail" title="Update"></span>';
                                ?>
                                <tr>
                                    <td class="text-left"><?php echo $DataResult['Year']; ?></td>
                                    <td class="text-center"><?php echo $DataResult['Location']; ?></td>
                                    <td class="text-right"><?php echo number_format(sprintf('%.2f',floatval($DataResult['TotalFreight'])),2,'.',','); ?></td>
                                    <td class="text-right"><?php echo (int)$DataResult['TotalQty']; ?></td>
                                    <td class="text-right"><?php echo number_format(sprintf('%.2f',floatval($DataResult['TotalWeight'])),2,'.',','); ?></td>
                                    <td class="text-center"><?php echo $ValOptForm; ?></td>
                                </tr>
                                <?php
                                $No++;
                                $TotalFreightCost = $TotalFreightCost + (float)$DataResult['TotalFreight'];
                                $TotalQty = $TotalQty + (int)$DataResult['TotalQty'];
                                $TotalWeight = $TotalWeight + (float)$DataResult['TotalWeight'];
                            }
                            $IdxID++;
                        }
                    }

                    ?>
                    </tbody>
                    <tfoot class="theadCustom" style="padding:none;">
                        <tr>
                            <td class="text-center" colspan="2"><strong>Grand Total</strong></td>
                            <td class="text-right"><?php echo "<strong>".number_format(sprintf('%.2f',floatval($TotalFreightCost)),2,'.',',')."</strong>"; ?></td>
                            <td class="text-center"><?php echo "<strong>".$TotalQty."</strong>"; ?></td>
                            <td class="text-right"><?php echo "<strong>".number_format(sprintf('%.2f',floatval($TotalWeight)),2,'.',',')."</strong>"; ?></td>
                            <td class="text-right"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <script type="text/javascript">
			google.charts.load('current', {
			  callback: function () {
				drawChart1();
				drawChart2();
				drawChart3();
				$(window).resize(drawChart1);
				$(window).resize(drawChart2);
				$(window).resize(drawChart3);
			  },
			  packages:['corechart','bar']
			});
            google.charts.setOnLoadCallback(drawChart1);
            function drawChart1() {
                var data1 = new google.visualization.DataTable();
                data1.addColumn('string', 'Year');
                <?php 
                foreach($ArrLocation as $DataLocation)
                {
                ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']; ?>');
                <?php
                }
                ?>
                data1.addRows([<?php
                    $IdxID = 0;
                    $NoLoopRes = 1;
                    foreach ($ArrListYear as $ListYear)
                    {
                        $YearSelected = trim($ListYear['YearShipment']);
                        if($NoLoopRes == 1)
                        {
                            echo "['".$YearSelected."'";
                            $NoLoopRes++;
                        }
                        else
                        {
                            echo ",['".$YearSelected."'";
                            $NoLoopRes++;
                        }
                        
                        for($i = 1;$i <= $TotalLocation;$i++)
                        {
                            $NewID = $i - 1;
                            $Location = $ArrLocation[$NewID]['Location'];
                            foreach($ArrDataPerBranchAll[$IdxID] as $DataResult)
                            {
                                echo ",{v:".$DataResult['TotalFreight'].",f:'Total Cost : $".number_format(sprintf('%.2f',floatval($DataResult['TotalFreight'])),2,'.',',')."'}";
                            }
                            $IdxID++;
                        }
                        echo "]";
                    }
                ?>
                ]);
                var options1 = {
                        title: 'Total of Freight Cost (All Season)'
                        ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                        ,vAxis: { minValue: 0,format:'short'}
                        ,tooltip: { isHtml: true }
                        ,legend: { position: 'top', maxLines: 3 } 
						,chartArea: {top:50,left:50,height:"80%",width:"100%"}            
                        ,isStacked: true
                        ,hAxis : {
                            titleTextStyle: {color: '#000000',bold: true}
                        }
                        ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Cost ($)',titleTextStyle: {color: '#000000',bold: true}
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
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                        },
                        series: {
                            0: {color:'#3366CC'},
                            1: {color:'#DC3912'},
                            2: {color:'#FF9900'}
                        },
						width: '100%',
                };
                var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
                chart1.draw(data1, google.charts.Bar.convertOptions(options1));
            };
            google.charts.setOnLoadCallback(drawChart2);
            function drawChart2() {
                    var data2 = new google.visualization.DataTable();
                    data2.addColumn('string', 'Year');
                    <?php 
                    foreach($ArrLocation as $DataLocation)
                    {
                    ?>
                    data2.addColumn('number', '<?php echo $DataLocation['Location']; ?>');
                    <?php
                    }
                    ?>
                    data2.addRows([<?php
                    $IdxID = 0;
                    $NoLoopRes = 1;
                    foreach ($ArrListYear as $ListYear)
                    {
                        $YearSelected = trim($ListYear['YearShipment']);
                        if($NoLoopRes == 1)
                        {
                            echo "['".$YearSelected."'";
                            $NoLoopRes++;
                        }
                        else
                        {
                            echo ",['".$YearSelected."'";
                            $NoLoopRes++;
                        }
                        
                        for($i = 1;$i <= $TotalLocation;$i++)
                        {
                            $NewID = $i - 1;
                            $Location = $ArrLocation[$NewID]['Location'];
                            foreach($ArrDataPerBranchAll[$IdxID] as $DataResult)
                            {
                                echo ",{v:".(int)$DataResult['TotalQty'].",f:'Total Qty Shipment : ".(int)($DataResult['TotalQty'])."'}";
                            }
                            $IdxID++;
                        }
                        echo "]";
                    }
                ?>
                ]);
                var options2 = {
                        title: 'Qty Shipment (All Season)'
                        ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                        ,vAxis: { minValue: 0,format:'short'}
                        ,tooltip: { isHtml: true }
                        ,legend: { position: 'top', maxLines: 3 } 
						,chartArea: {top:50,left:50,height:"80%",width:"100%"}             
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
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                        },
                        series: {
                            0: {color:'#3366CC'},
                            1: {color:'#DC3912'},
                            2: {color:'#FF9900'}
                        },
						width: '100%',
                };
                var chart2 = new google.charts.Bar(document.getElementById('columnchart_material2'));
                chart2.draw(data2, google.charts.Bar.convertOptions(options2));
            }
            google.charts.setOnLoadCallback(drawChart3);
            function drawChart3() {
                    var data3 = new google.visualization.DataTable();
                    data3.addColumn('string', 'Year');
                    <?php 
                    foreach($ArrLocation as $DataLocation)
                    {
                    ?>
                    data3.addColumn('number', '<?php echo $DataLocation['Location']; ?>');
                    <?php
                    }
                    ?>
                    data3.addRows([<?php
                        $IdxID = 0;
                        $NoLoopRes = 1;
                        foreach ($ArrListYear as $ListYear)
                        {
                            $YearSelected = trim($ListYear['YearShipment']);
                            if($NoLoopRes == 1)
                            {
                                echo "['".$YearSelected."'";
                                $NoLoopRes++;
                            }
                            else
                            {
                                echo ",['".$YearSelected."'";
                                $NoLoopRes++;
                            }
                            
                            for($i = 1;$i <= $TotalLocation;$i++)
                            {
                                $NewID = $i - 1;
                                $Location = $ArrLocation[$NewID]['Location'];
                                foreach($ArrDataPerBranchAll[$IdxID] as $DataResult)
                                {
                                    echo ",{v:".$DataResult['TotalWeight'].",f:'Total Weight : ".number_format(sprintf('%.2f',floatval($DataResult['TotalWeight'])),2,'.',',')."'}";
                                }
                                $IdxID++;
                            }
                            echo "]";
                        }
                    ?>
                    ]);
                var options3 = {
                        title: 'Total Weight (All Season)'
                        ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                        ,vAxis: { minValue: 0,format:'short'}
                        ,tooltip: { isHtml: true }
                        ,legend: { position: 'top', maxLines: 3 }
						,chartArea: {top:50,left:50,height:"80%",width:"100%"}                 
                        ,isStacked: true
                        ,hAxis : {
                            titleTextStyle: {color: '#000000',bold: true}
                        }
                        ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Weight',titleTextStyle: {color: '#000000',bold: true}
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
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                        },
                        series: {
                            0: {color:'#3366CC'},
                            1: {color:'#DC3912'},
                            2: {color:'#FF9900'}
                        },
						width: '100%',
                };
                var chart3 = new google.charts.Bar(document.getElementById('columnchart_material3'));
                chart3.draw(data3, google.charts.Bar.convertOptions(options3));
            }
        </script>
        <style>
        .ColumnContent{width:100%; height: 400px;}
        </style>
        <?php
    }
    else # tahun terpilih
    {
        $ArrDataDefault = array();
        $QGetData = GET_DATA_SHIPMENT_BY_YEAR($InputYear,$linkMACHWebTrax);
        while($RGetData = sqlsrv_fetch_array($QGetData))
        {
            $ValCountry1 = trim($RGetData['AccShippingCountry']);
            $ValCountry2 = trim($RGetData['Country2']);
            if($ValCountry1 != "")
            {
                $ValCountry = $ValCountry1;
            }
            else
            {
                if($ValCountry2 != "")
                {
                    $ValCountry = $ValCountry2;
                }
                else
                {
                    $ValCountry = "Other";
                }
            }

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
                "DateShipped" => trim($RGetData['Date']),
                "MonthName" => trim($RGetData['MonthName']),
                "MonthNo" =>  (int)date("m", strtotime(trim($RGetData['MonthName']))),
                "OwnerUsername" => trim($RGetData['OwnerUsername']),
                "From" => trim($NewName),
                "TrackingID" => trim($RGetData['TrackingID']),
                "TotalWeight" => trim($RGetData['TotalWeight']),
                "Freight" => trim($RGetData['Freight']),
                "Courier" => trim($RGetData['Courier']),
                "Country" => trim($ValCountry)
            );
            array_push($ArrDataDefault,$TempArray1);
        }
        # get data FOR
        $QGetData2 = GET_DATA_SHIPMENT_FOR_BY_YEAR($InputYear,$linkMACHWebTrax);
        while($RGetData2 = sqlsrv_fetch_array($QGetData2))
        {
            $ValCountry1 = trim($RGetData2['AccShippingCountry']);
            $ValCountry2 = trim($RGetData2['Country2']);
            if($ValCountry1 != "")
            {
                $ValCountry = $ValCountry1;
            }
            else
            {
                if($ValCountry2 != "")
                {
                    $ValCountry = $ValCountry2;
                }
                else
                {
                    $ValCountry = "Other";
                }
            }
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
                "DateShipped" => trim($RGetData2['Date']),
                "MonthName" => trim($RGetData2['MonthName']),
                "MonthNo" =>  (int)date("m", strtotime(trim($RGetData2['MonthName']))),
                "OwnerUsername" => trim($RGetData2['OwnerUsername']),
                "From" => trim($NewName),
                "TrackingID" => trim($RGetData2['TrackingID']),
                "TotalWeight" => trim($RGetData2['TotalWeight']),
                "Freight" => trim($RGetData2['Freight']),
                "Courier" => trim($RGetData2['Courier']),
                "Country" => trim($ValCountry)
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
        $ArrDataResult = array();
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            # set array freight
            $ArrDataPerLocationBranch[$i] = array();
            $ArrDataBranch[$i] = array();
        }
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $LoopLocation = $ArrLocation[$NewID]['Location'];
            $TempTotalFreight = 0;
            $TempTotalWeight = 0;
            $TempLoop = "";
            # pemisahan per lokasi
            foreach($ArrDataDefault as $DataDefault)
            {
                if($DataDefault['From'] == $LoopLocation)
                {
                    $TempArray2 = array(
                        "DateShipped" => trim($DataDefault['DateShipped']),
                        "MonthName" => trim($DataDefault['MonthName']),
                        "MonthNo" =>  trim($DataDefault['MonthNo']),
                        "OwnerUsername" => trim($DataDefault['OwnerUsername']),
                        "From" => trim($DataDefault['From']),
                        "TrackingID" => trim($DataDefault['TrackingID']),
                        "TotalWeight" => trim($DataDefault['TotalWeight']),
                        "Freight" => trim($DataDefault['Freight']),
                        "Courier" => trim($DataDefault['Courier']),
                        "Country" => trim($DataDefault['Country'])
                    );
                    array_push($ArrDataPerLocationBranch[$i],$TempArray2);
                }
            }
            # penjumlahan per bulan per lokasi
            for ($y=1; $y < 13; $y++)
            { 
                $SumWeight = 0;
                $SumFreight = 0;
                $SumQtyShipment = 0;
                foreach($ArrDataPerLocationBranch[$i] as $DataGroup)
                {
                    if($DataGroup['MonthNo'] == $y)
                    {
                        $SumWeight = $SumWeight + (float)$DataGroup['TotalWeight'];
                        $SumFreight = $SumFreight + (float)$DataGroup['Freight'];
                        $SumQtyShipment = $SumQtyShipment + 1;
                    }
                }
                $TempArray3 = array(
                    "MonthNo" => $y,
                    "From" => $LoopLocation,
                    "TotalWeight" => $SumWeight,
                    "TotalFreight" => $SumFreight,   
                    "TotalQtyShipment" => $SumQtyShipment,
                );
                array_push($ArrDataBranch[$i],$TempArray3);
                array_push($ArrDataResult,$TempArray3);
            }
        }
        ?>
        <div class="col-md-4">
            <div class="ColumnContent" id="columnchart_material1"></div>
        </div>
        <div class="col-md-4">
            <div class="ColumnContent" id="columnchart_material2"></div>
        </div>
        <div class="col-md-4">
            <div class="ColumnContent" id="columnchart_material3"></div>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="TableDataChart1" class="table table-responsive table-bordered table-hover">
                    <thead class="theadCustom">
                        <tr>
                            <th class="text-center">Month</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Total of Freight Cost ($)</th>
                            <th class="text-center">Qty Shipment</th>
                            <th class="text-center">Total Weight</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                    $No = 1;
                    $TotalFreightCost = 0;
                    $TotalQty = 0;
                    $TotalWeight = 0;
                    foreach ($ArrDataResult as $DataResult)
                    {
                        $RowEnc = base64_encode($DataResult['From']."*".$DataResult['MonthNo'].'-'.$InputYear."*".$InputYear);
                        $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#ModalDetail" title="Details"></span>';
                        ?>
                        <tr>
                            <td class="text-center"><?php echo date('M',mktime(0, 0, 0, $DataResult['MonthNo'], 10)).' '.$InputYear; ?></td>
                            <td class="text-center"><?php echo $DataResult['From']; ?></td>
                            <td class="text-right"><?php echo number_format(sprintf('%.2f',floatval($DataResult['TotalFreight'])),2,'.',','); ?></td>
                            <td class="text-center"><?php echo (int)$DataResult['TotalQtyShipment']; ?></td>
                            <td class="text-right"><?php echo number_format(sprintf('%.2f',floatval($DataResult['TotalWeight'])),2,'.',','); ?></td>
                            <td class="text-center"><?php echo $ValOptForm; ?></td>
                        </tr>
                        <?php
                        $No++;
                        $TotalFreightCost = $TotalFreightCost + $DataResult['TotalFreight'];
                        $TotalQty = $TotalQty + (int)$DataResult['TotalQtyShipment'];
                        $TotalWeight = $TotalWeight + $DataResult['TotalWeight'];
                    }
                    $TotalFreightCost = number_format(sprintf('%.2f',floatval($TotalFreightCost)),2,'.',',');
                    $TotalWeight = number_format(sprintf('%.2f',floatval($TotalWeight)),2,'.',',');
                    ?>
                    </tbody>
                    <tfoot class="theadCustom">
                        <tr>
                            <td class="text-center" colspan="2"><strong>Grand Total</strong></td>
                            <td class="text-right"><?php echo "<strong>".$TotalFreightCost."</strong>"; ?></td>
                            <td class="text-center"><?php echo "<strong>".$TotalQty."</strong>"; ?></td>
                            <td class="text-right"><?php echo "<strong>".$TotalWeight."</strong>"; ?></td>
                            <td class="text-right"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <style>table.dataTable tfoot th, table.dataTable tfoot td{ padding-right : 10px !important;}</style>
        <script type="text/javascript">
			google.charts.load('current', {
			  callback: function () {
				drawChart1();
				drawChart2();
				drawChart3();
				$(window).resize(drawChart1);
				$(window).resize(drawChart2);
				$(window).resize(drawChart3);
			  },
			  packages:['corechart','bar']
			});
            google.charts.setOnLoadCallback(drawChart1);
            function drawChart1() {
                var data1 = new google.visualization.DataTable();
                data1.addColumn('string', 'Month');
                <?php 
                foreach ($ArrLocation as $DataLocation) {
                    
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']; ?>');
                    <?php
                }
                ?>
                data1.addRows([<?php
                    if(count($DataResult) > 0)
                    {
                        $NoLoopRes = 1;
                        for ($z=1; $z < 13; $z++)
                        {    
                            if($NoLoopRes == 1)
                            {
                                echo "[";
                                echo "'".date('M',mktime(0, 0, 0, $z, 10))."'";
                                foreach($ArrDataResult as $DataResult)
                                {    
                                    if($DataResult['MonthNo'] == $z)
                                    {
                                        echo ",{v:".sprintf('%.2f',floatval($DataResult['TotalFreight'])).",f:'Total Cost : $".number_format(sprintf('%.2f',floatval($DataResult['TotalFreight'])),2,'.',',')."'}";
                                    }
                                }
                                echo "]";
                                $NoLoopRes++;
                            }
                            else
                            {
                                echo ",[";
                                echo "'".date('M',mktime(0, 0, 0, $z, 10))."'";
                                foreach($ArrDataResult as $DataResult)
                                {     
                                    if($DataResult['MonthNo'] == $z)
                                    {
                                        echo ",{v:".sprintf('%.2f',floatval($DataResult['TotalFreight'])).",f:'Total Cost : $".number_format(sprintf('%.2f',floatval($DataResult['TotalFreight'])),2,'.',',')."'}";
                                    }
                                }                                
                                echo "]";
                                $NoLoopRes++;
                            }
                        }
                    }
                ?>
                ]);

                var options1 = {
                        title: 'Total of Freight Cost (<?php echo $InputYear; ?>)'
                        ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                        ,vAxis: { minValue: 0,format:'short'}
                        ,tooltip: { isHtml: true }
                        ,legend: { position: 'top', maxLines: 3 }
						,chartArea: {top:50,left:50,height:"80%",width:"100%"}             
                        ,isStacked: true
                        ,hAxis : {
                            titleTextStyle: {color: '#000000',bold: true}
                        }
                        ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Cost ($)',titleTextStyle: {color: '#000000',bold: true}    
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            }
                            ,
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                        },
                        series: {
                            0: {color:'#3366CC'},
                            1: {color:'#DC3912'},
                            2: {color:'#FF9900'}
                        },
						width: '100%',
                };
                var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
                chart1.draw(data1, google.charts.Bar.convertOptions(options1));
            };
            google.charts.setOnLoadCallback(drawChart2);
            function drawChart2() {
                    var data2 = new google.visualization.DataTable();
                    data2.addColumn('string', 'Month');
                    <?php 
                    foreach ($ArrLocation as $DataLocation) {
                        ?>
                    data2.addColumn('number', '<?php echo $DataLocation['Location']; ?>');
                        <?php
                    }
                    ?>
                    data2.addRows([<?php
                        if(count($DataResult) > 0)
                        {
                            $NoLoopRes = 1;
                            for ($z=1; $z < 13; $z++)
                            {
                                if($NoLoopRes == 1)
                                {
                                    echo "[";
                                    echo "'".date('M',mktime(0, 0, 0, $z, 10))."'";
                                    foreach($ArrDataResult as $DataResult)
                                    {
                                        if($DataResult['MonthNo'] == $z)
                                        {
                                            echo ",{v:".(int)$DataResult['TotalQtyShipment'].",f:'Total Qty Shipment : ".(int)$DataResult['TotalQtyShipment']."'}";
                                        }
                                    }
                                    echo "]";
                                    $NoLoopRes++;
                                }
                                else
                                {
                                    echo ",[";
                                    echo "'".date('M',mktime(0, 0, 0, $z, 10))."'";
                                    foreach($ArrDataResult as $DataResult)
                                    {
                                        if($DataResult['MonthNo'] == $z)
                                        {
                                            echo ",{v:".(int)$DataResult['TotalQtyShipment'].",f:'Total Qty Shipment : ".(int)$DataResult['TotalQtyShipment']."'}";
                                        }
                                    }                                
                                    echo "]";
                                    $NoLoopRes++;
                                }
                            }
                        }
                    ?>
                    ]);
                var options2 = {
                        title: 'Qty Shipment (<?php echo $InputYear; ?>)'
                        ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                        ,vAxis: { minValue: 0,format:'short'}
                        ,tooltip: { isHtml: true }
                        ,legend: { position: 'top', maxLines: 3 } 
						,chartArea: {top:50,left:50,height:"80%",width:"100%"}             
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
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                        },
                        series: {
                            0: {color:'#3366CC'},
                            1: {color:'#DC3912'},
                            2: {color:'#FF9900'}
                        },
						width: '100%',
                };
                var chart2 = new google.charts.Bar(document.getElementById('columnchart_material2'));
                chart2.draw(data2, google.charts.Bar.convertOptions(options2));
            }
            google.charts.setOnLoadCallback(drawChart3);
            function drawChart3() {
                    var data3 = new google.visualization.DataTable();
                    data3.addColumn('string', 'Month');
                    <?php 
                    foreach ($ArrLocation as $DataLocation) {
                        ?>
                    data3.addColumn('number', '<?php echo $DataLocation['Location']; ?>');
                        <?php
                    }
                    ?>
                    data3.addRows([<?php
                        if(count($DataResult) > 0)
                        {
                            $NoLoopRes = 1;
                            for ($z=1; $z < 13; $z++)
                            {
                                if($NoLoopRes == 1)
                                {
                                    echo "[";
                                    echo "'".date('M',mktime(0, 0, 0, $z, 10))."'";
                                    foreach($ArrDataResult as $DataResult)
                                    {
                                        if($DataResult['MonthNo'] == $z)
                                        {
                                            echo ",{v:".sprintf('%.2f',floatval($DataResult['TotalWeight'])).",f:'Total Weight : ".number_format(sprintf('%.2f',floatval($DataResult['TotalWeight'])),2,'.',',')."'}";
                                        }
                                    }
                                    echo "]";
                                    $NoLoopRes++;
                                }
                                else
                                {
                                    echo ",[";
                                    echo "'".date('M',mktime(0, 0, 0, $z, 10))."'";
                                    foreach($ArrDataResult as $DataResult)
                                    {
                                        if($DataResult['MonthNo'] == $z)
                                        {
                                            echo ",{v:".sprintf('%.2f',floatval($DataResult['TotalWeight'])).",f:'Total Weight : ".number_format(sprintf('%.2f',floatval($DataResult['TotalWeight'])),2,'.',',')."'}";
                                        }
                                    }                                
                                    echo "]";
                                    $NoLoopRes++;
                                }
                            }
                        }
                    ?>
                    ]);
                var options3 = {
                        title: 'Total Weight (<?php echo $InputYear; ?>)'
                        ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                        ,vAxis: { minValue: 0,format:'short'}
                        ,tooltip: { isHtml: true }
                        ,legend: { position: 'top', maxLines: 3 }
						,chartArea: {top:50,left:50,height:"80%",width:"100%"}                 
                        ,isStacked: true
                        ,hAxis : {
                            titleTextStyle: {color: '#000000',bold: true}
                        }
                        ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Weight',titleTextStyle: {color: '#000000',bold: true}
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
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                        },
                        series: {
                            0: {color:'#3366CC'},
                            1: {color:'#DC3912'},
                            2: {color:'#FF9900'}
                        },
						width: '100%',
                };
                var chart3 = new google.charts.Bar(document.getElementById('columnchart_material3'));
                chart3.draw(data3, google.charts.Bar.convertOptions(options3));
            }
        </script>
        <style>
        .ColumnContent{width:100%; height: 400px;}
        </style>
        <?php
    }
    ?>
    <div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Freight Weight Details</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="ContentDetails"></div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
else
{
    echo "";     
}
?>
<script>
$(document).ready(function(){
    $("#ModalDetail").on('show.bs.modal', function (event) {
    var act = $(event.relatedTarget);
    var DataCode = act.data('ecode');
    // var DataTemp = act.data('dcode');
    var formdata = new FormData();
    formdata.append("ValCode", DataCode);
    // formdata.append("ValTemp", DataTemp);
        $.ajax({
            url: 'project/Shipping/FreightWeightModal.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentDetails').hide();
                $('#ContentDetails').html(xaxa);
                $('#ContentDetails').fadeIn('fast');
                
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
});
</script>