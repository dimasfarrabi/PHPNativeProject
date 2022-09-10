<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php");

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
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    # percabangan kategori
    switch ($ValCategory) {
        case '1': # kategori harian
            {
                $ValDateStart = htmlspecialchars(trim($_POST['ValDateStart']), ENT_QUOTES, "UTF-8");
                $ValDateEnd = htmlspecialchars(trim($_POST['ValDateEnd']), ENT_QUOTES, "UTF-8");
                # percabangan hari
                if($ValDateStart == $ValDateEnd) # sama hari
                {
                    # get data awal
                    $ArrDataAwal = array();
                    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE($ValDateStart,$ValDateEnd,$linkHRISWebTrax);
                    $ValDataRow = mssql_num_rows($QListKWHTracking);
                    if($ValDataRow == 0)
                    {
                        echo "Data untuk tanggal $ValDateStart - $ValDateEnd tidak ditemukan.";
                        exit();
                    }
                    while ($RListKWHTracking = mssql_fetch_assoc($QListKWHTracking))
                    {
                        $TempNoSlave = $RListKWHTracking['Slave'];
                        $TempKWH = $RListKWHTracking['KWH'];
                        $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
                        $TempUsage = $RListKWHTracking['Usage'];
                        $ArrTempRow = array(
                            "Slave" => $TempNoSlave,
                            "KWH" => $TempKWH,
                            "DataLog" => $TempDataLog,
                            "Usage" => $TempUsage
                        );
                        array_push($ArrDataAwal,$ArrTempRow);
                    }
                    # get data list slave
                    $ArrDataSlave = array();
                    $VarCheckSlave = "";
                    foreach ($ArrDataAwal as $DataAwal)
                    {
                        $VarSlave = $DataAwal['Slave'];
                        if($VarCheckSlave != $VarSlave)
                        {
                            array_push($ArrDataSlave,$VarSlave);
                            $VarCheckSlave = $VarSlave;
                        }
                    }
                    $TotalSlave = count($ArrDataSlave);
                    # data array tracking per slave
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        ${"ArrTracking$i"} = array();
                        foreach ($ArrDataAwal as $DataAwal1)
                        {
                            $VarSlave1 = $DataAwal1['Slave'];
                            $VarKWH1 = $DataAwal1['KWH'];
                            $VarDataLog1 = $DataAwal1['DataLog'];
                            $VarUsage1 = $DataAwal1['Usage'];
                            if($i == $VarSlave1)
                            {
                                $TempArrayDataSlave = array(
                                    "Slave" => $VarSlave1,
                                    "KWH" => $VarKWH1,
                                    "DataLog" => $VarDataLog1,
                                    "Usage" => $VarUsage1
                                );
                                array_push(${"ArrTracking$i"},$TempArrayDataSlave);
                            }                          
                        }
                    }
                    
            ?> 
<script src="./../microplate/js/jquery.min.js" type="text/javascript"></script>
<script src="./../microplate/js/highstock.js" type="text/javascript"></script>
<script src="./../microplate/js/highcharts.js" type="text/javascript"></script>
<script src="./../microplate/js/exporting.js"></script>
<script type="text/javascript">  
var chart;
$(document).ready(function() {
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'containerChart1',
            defaultSeriesType: 'line'
        },
        xAxis: {
            title: {
                text: ''
            },
            categories: [<?php
                $NoListDateRange = 1;
                foreach ($ArrTracking1 as $ArrTrack)
                {
                    $ArrDataLog = $ArrTrack['DataLog'];
                    $ArrDataLog1 = explode("#",$ArrDataLog);
                    $DateLog = $ArrDataLog1[0];
                    $DateLog = substr($DateLog,0,5)." (".substr($DateLog,11,5).")";
                    if($NoListDateRange == 1)
                    {
                        echo "'".$DateLog."'"; 
                    }
                    else
                    {
                        echo ",'".$DateLog."'";
                    }
                    $NoListDateRange++;
                }
            ?>],
            labels: {
                rotation: -90,
                y: 50,
            }
        },
        title: {
            text: 'KWH Tracking (<?php echo $ValDateStart; ?> - <?php echo $ValDateEnd; ?>)'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Usage'
            }
        },
        legend: {
            layout: 'vertical',
            backgroundColor: '#FFFFFF',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 70,
            floating: true,
            shadow: true
        },
        tooltip: {
            formatter: function() {
                return 'Date : '+ this.x +', Usage : '+ this.y +'';
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        <?php
        for ($x=1; $x <= $TotalSlave; $x++)
        {

            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTracking$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['Usage'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
            else
            {
                ?>
                ,{
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTracking$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['Usage'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
        }
        ?>]
    });        
});    
</script>
<div id="containerChart1" style="height: 500px;"></div>
<script type="text/javascript">
Highcharts.theme = { colors: ['#4572A7'] };
var highchartsOptions = Highcharts.getOptions(); 
</script>

            <?php
                    # reset array
                    $ArrDataAwal = array();
                    $ArrDataSlave = array();
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        ${"ArrTracking$i"} = array();                        
                    }
                }
                else # beda hari
                {
                    # get data awal
                    $ArrDataAwal = array();
                    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE($ValDateStart,$ValDateEnd,$linkHRISWebTrax);
                    $ValDataRow = mssql_num_rows($QListKWHTracking);
                    if($ValDataRow == 0)
                    {
                        echo "Data untuk tanggal $ValDateStart - $ValDateEnd tidak ditemukan.";
                        exit();
                    }
                    while ($RListKWHTracking = mssql_fetch_assoc($QListKWHTracking))
                    {
                        $TempNoSlave = $RListKWHTracking['Slave'];
                        $TempKWH = $RListKWHTracking['KWH'];
                        $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
                        $TempUsage = $RListKWHTracking['Usage'];
                        $ArrTempRow = array(
                            "Slave" => $TempNoSlave,
                            "KWH" => $TempKWH,
                            "DataLog" => $TempDataLog,
                            "Usage" => $TempUsage
                        );
                        array_push($ArrDataAwal,$ArrTempRow);
                    }
                    # get data list slave
                    $ArrDataSlave = array();
                    $VarCheckSlave = "";
                    foreach ($ArrDataAwal as $DataAwal)
                    {
                        $VarSlave = $DataAwal['Slave'];
                        if($VarCheckSlave != $VarSlave)
                        {
                            array_push($ArrDataSlave,$VarSlave);
                            $VarCheckSlave = $VarSlave;
                        }
                    }
                    $TotalSlave = count($ArrDataSlave);
                    # data array tracking per slave
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        ${"ArrTracking$i"} = array();
                        foreach ($ArrDataAwal as $DataAwal1)
                        {
                            $VarSlave1 = $DataAwal1['Slave'];
                            $VarKWH1 = $DataAwal1['KWH'];
                            $VarDataLog1 = $DataAwal1['DataLog'];
                            $VarUsage1 = $DataAwal1['Usage'];
                            if($i == $VarSlave1)
                            {
                                $TempArrayDataSlave = array(
                                    "Slave" => $VarSlave1,
                                    "KWH" => $VarKWH1,
                                    "DataLog" => $VarDataLog1,
                                    "Usage" => $VarUsage1
                                );
                                array_push(${"ArrTracking$i"},$TempArrayDataSlave);
                            }                          
                        }
                    }
            ?> 
<script src="./../microplate/js/jquery.min.js" type="text/javascript"></script>
<script src="./../microplate/js/highstock.js" type="text/javascript"></script>
<script src="./../microplate/js/highcharts.js" type="text/javascript"></script>
<script src="./../microplate/js/exporting.js"></script>
<script type="text/javascript">  
var chart;
$(document).ready(function() {
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'containerChart1',
            defaultSeriesType: 'line'
        },
        xAxis: {
            title: {
                text: ''
            },
            categories: [<?php
                $NoListDateRange = 1;
                foreach ($ArrTracking1 as $ArrTrack)
                {
                    $ArrDataLog = $ArrTrack['DataLog'];
                    $ArrDataLog1 = explode("#",$ArrDataLog);
                    $DateLog = $ArrDataLog1[0];
                    $DateLog = substr($DateLog,0,5)." (".substr($DateLog,11,5).")";
                    if($NoListDateRange == 1)
                    {
                        echo "'".$DateLog."'"; 
                    }
                    else
                    {
                        echo ",'".$DateLog."'";
                    }
                    $NoListDateRange++;
                }
            ?>],
            labels: {
                rotation: -90,
                y: 50,
            }
        },
        title: {
            text: 'KWH Tracking (<?php echo $ValDateStart; ?> - <?php echo $ValDateEnd; ?>)'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Usage'
            }
        },
        legend: {
            layout: 'vertical',
            backgroundColor: '#FFFFFF',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 70,
            floating: true,
            shadow: true
        },
        tooltip: {
            formatter: function() {
                return 'Date : '+ this.x +', Usage : '+ this.y +'';
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        <?php
        for ($x=1; $x <= $TotalSlave; $x++)
        {
            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTracking$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['Usage'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
            else
            {
                ?>
                ,{
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTracking$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['Usage'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
        }?>]
    });        
});    
</script>
<div id="containerChart1" style="height: 500px;"></div>
<script type="text/javascript">
Highcharts.theme = { colors: ['#4572A7'] };
var highchartsOptions = Highcharts.getOptions(); 
</script>
            <?php
                    # reset array
                    $ArrDataAwal = array();
                    $ArrDataSlave = array();
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        ${"ArrTracking$i"} = array();                        
                    }
                }
            }
            break;
        case '2': # kategori bulanan
            {
                $TempInput = htmlspecialchars(trim($_POST['ValMonth']), ENT_QUOTES, "UTF-8");
                $ArrMonth = explode("#",$TempInput);
                $ValMonth = $ArrMonth[1];
                $ValYear = $ArrMonth[0];
                $MonthNameID = date("F",mktime(0, 0, 0, $ArrMonth[1]));
                $ValOpt = substr($MonthNameID,0,3)." ".$ValYear;
                if($ValMonth<10){$ValMonth = "0".$ValMonth;}
                # get total hari
                $FirstDate = "$ValMonth/01/$ValYear";
                $TotalDate = date("t",strtotime($FirstDate));
                # get data awal
                $ArrDataAwal = array();
                $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_MONTH($ValMonth,$ValYear,$linkHRISWebTrax);
                $ValDataRow = mssql_num_rows($QListKWHTracking);
                if($ValDataRow == 0)
                {
                    echo "Data untuk bulan $ValOpt tidak ditemukan.";
                    exit();
                }
                while ($RListKWHTracking = mssql_fetch_assoc($QListKWHTracking))
                {
                    $TempNoSlave = $RListKWHTracking['Slave'];
                    $TempKWH = $RListKWHTracking['KWH'];
                    $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
                    $TempUsage = $RListKWHTracking['Usage'];
                    $ArrTempRow = array(
                        "Slave" => $TempNoSlave,
                        "KWH" => $TempKWH,
                        "DataLog" => $TempDataLog,
                        "Usage" => $TempUsage
                    );
                    array_push($ArrDataAwal,$ArrTempRow);
                }
                # get data list slave
                $ArrDataSlave = array();
                $VarCheckSlave = "";
                foreach ($ArrDataAwal as $DataAwal)
                {
                    $VarSlave = $DataAwal['Slave'];
                    if($VarCheckSlave != $VarSlave)
                    {
                        array_push($ArrDataSlave,$VarSlave);
                        $VarCheckSlave = $VarSlave;
                    }
                }
                $TotalSlave = count($ArrDataSlave);
                # data array tracking per slave
                $ArrTrackingPerDate = array();
                for ($i=1; $i <= $TotalSlave; $i++)
                {
                    ${"ArrTracking$i"} = array();
                    foreach ($ArrDataAwal as $DataAwal1)
                    {
                        $VarSlave1 = $DataAwal1['Slave'];
                        $VarKWH1 = $DataAwal1['KWH'];
                        $VarDataLog1 = $DataAwal1['DataLog'];
                        $VarUsage1 = $DataAwal1['Usage'];
                        if($i == $VarSlave1)
                        {
                            $TempArrayDataSlave = array(
                                "Slave" => $VarSlave1,
                                "KWH" => $VarKWH1,
                                "DataLog" => $VarDataLog1,
                                "Usage" => $VarUsage1
                            );
                            array_push(${"ArrTracking$i"},$TempArrayDataSlave);
                        }                          
                    }
                    # perhitungan setiap hari
                    $TempDT = "";
                    $TempDTTotalUsage = 0;
                    $NoLoopingTempDT = 1;
                    $CountRowTempDT = count(${"ArrTracking$i"});
                    foreach (${"ArrTracking$i"} as $DT1)
                    {
                        $DT1Slave = $DT1['Slave'];
                        $DT1KWH = $DT1['KWH'];
                        $DT1DataLog = $DT1['DataLog'];
                        $DT1Usage = $DT1['Usage'];
                        $ArrDT1DataLog = explode(" ",$DT1DataLog);
                        $DateDataLog = $ArrDT1DataLog[0];
                        
                        if($NoLoopingTempDT == 1)
                        {
                            $TempDT = $DateDataLog;
                            $TempDTTotalUsage = $DT1Usage;
                        }
                        else
                        {
                            if($TempDT == $DateDataLog)
                            {
                                # jika row terakhir
                                if($NoLoopingTempDT == $CountRowTempDT)
                                {
                                    $TempDTTotalUsage = $TempDTTotalUsage + $DT1Usage;
                                    $TempTrackingPerDate = array(
                                        'NoSlave' => $DT1Slave,
                                        'DateTracking' => $TempDT,
                                        'TotalUsage' => $TempDTTotalUsage
                                    );
                                    array_push($ArrTrackingPerDate,$TempTrackingPerDate);
                                }
                                else
                                {
                                    $TempDTTotalUsage = $TempDTTotalUsage + $DT1Usage;
                                }
                            }
                            else
                            {

                                # jika row terakhir
                                if($NoLoopingTempDT == $CountRowTempDT)
                                {
                                    $TempTrackingPerDate = array(
                                        'NoSlave' => $DT1Slave,
                                        'DateTracking' => $TempDT,
                                        'TotalUsage' => $TempDTTotalUsage
                                    );
                                    array_push($ArrTrackingPerDate,$TempTrackingPerDate);
                                }
                                else
                                {
                                    $TempTrackingPerDate = array(
                                        'NoSlave' => $DT1Slave,
                                        'DateTracking' => $TempDT,
                                        'TotalUsage' => $TempDTTotalUsage
                                    );
                                    array_push($ArrTrackingPerDate,$TempTrackingPerDate);
                                    $TempDT = $DateDataLog;
                                    $TempDTTotalUsage = 0;
                                    $TempDTTotalUsage = $DT1Usage;
                                }
                            }
                        }
                        $NoLoopingTempDT++;
                    }
                }
                # penambahan tgl kosong utk tracking per date
                $ArrTrackingPerDate2 = array();
                for ($x=1; $x <= $TotalSlave; $x++)
                {
                    for($y=1; $y <= $TotalDate; $y++)   # looping date
                    {
                        $TempDateCheck = date("m/d/Y",strtotime("$ValMonth/$y/$ValYear"));
                        $ValCheckDate = FALSE;
                        # pengecekan nilai usage per jam
                        foreach ($ArrTrackingPerDate as $TPD)
                        {
                            $TNoSlave = $TPD['NoSlave'];
                            $TDateTracking = $TPD['DateTracking'];
                            $TTotalUsage = $TPD['TotalUsage'];
                            if(($x == $TNoSlave) && ($TempDateCheck == $TDateTracking))
                            {
                                $TTempArray = array(
                                    "NoSlave" => $TNoSlave,
                                    "DateTracking" => $TDateTracking,
                                    "TotalUsage" => $TTotalUsage
                                );
                                array_push($ArrTrackingPerDate2,$TTempArray);
                                $ValCheckDate = TRUE;
                                break;
                            }
                        }
                        if($ValCheckDate == FALSE)
                        {
                            $TTempArray = array(
                                "NoSlave" => $x,
                                "DateTracking" => $TempDateCheck,
                                "TotalUsage" => "0"
                            );
                            array_push($ArrTrackingPerDate2,$TTempArray);
                        }
                    }
                }
                # pemecahan tracking per slave
                for ($i=1; $i <= $TotalSlave; $i++)
                {
                    ${"ArrTrackingResult$i"} = array();
                    foreach ($ArrTrackingPerDate2 as $ArrResult)
                    {
                        $ValSlave = $ArrResult['NoSlave'];
                        $ValDateTracking = $ArrResult['DateTracking'];
                        $ValotalUsage = $ArrResult['TotalUsage'];
                        if($i == $ValSlave)
                        {
                            $TArray = array(
                                "NoSlave" => $ValSlave,
                                "DateTracking" => $ValDateTracking,
                                "TotalUsage" => $ValotalUsage
                            );
                            array_push(${"ArrTrackingResult$i"},$TArray);
                        }
                    }
                }
                ?> 
<script src="./../microplate/js/jquery.min.js" type="text/javascript"></script>
<script src="./../microplate/js/highstock.js" type="text/javascript"></script>
<script src="./../microplate/js/highcharts.js" type="text/javascript"></script>
<script src="./../microplate/js/exporting.js"></script>
<script type="text/javascript">  
var chart;
$(document).ready(function() {
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'containerChart1',
            defaultSeriesType: 'line'
        },
        xAxis: {
            title: {
                text: 'Date'
            },
            categories: [<?php
                $NoListDateRange = 1;
                for($y=1; $y <= $TotalDate; $y++)
                {
                    $TempDateCheck = date("m/d/Y",strtotime("$ValMonth/$y/$ValYear"));
                    $TempDateCheck = substr($TempDateCheck,3,2);
                    if($NoListDateRange == 1)
                    {
                        echo "'".$TempDateCheck."'"; 
                    }
                    else
                    {
                        echo ",'".$TempDateCheck."'";
                    }
                    $NoListDateRange++;
                }
            ?>],
            labels: {
                rotation: 0,
                y: 15,
            }
        },
        title: {
            text: 'KWH Tracking (<?php echo $ValOpt; ?>)'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Usage'
            }
        },
        legend: {
            layout: 'vertical',
            backgroundColor: '#FFFFFF',
            align: 'right',
            verticalAlign: 'top',
            x: 10,
            y: 70,
            floating: true,
            shadow: true
        },
        tooltip: {
            formatter: function() {
                return 'Date : <?php echo $ValMonth; ?>/'+ this.x +'/<?php echo $ValYear; ?>, Usage : '+ this.y +'';
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        <?php
        for ($x=1; $x <= $TotalSlave; $x++)
        {
            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTrackingResult$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['TotalUsage'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
            else
            {
                ?>
                ,{
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTrackingResult$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['TotalUsage'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
        }?>]
    });        
});    
</script>
<div id="containerChart1" style="height: 500px;"></div>
<script type="text/javascript">
Highcharts.theme = { colors: ['#4572A7'] };
var highchartsOptions = Highcharts.getOptions(); 
</script>
            <?php
                # reset array
                $ArrDataAwal = array();
                $ArrDataSlave = array();
                for ($i=1; $i <= $TotalSlave; $i++)
                {
                    ${"ArrTracking$i"} = array();
                    ${"ArrTrackingResult$i"} = array();
                }
                $ArrTrackingPerDate = array();
                $ArrTrackingPerDate2 = array();                
            }
            break;
        case '3': # kategori tahunan
            {
                $ValYear = htmlspecialchars(trim($_POST['ValYear']), ENT_QUOTES, "UTF-8");
                # get data awal
                $ArrDataAwal = array();
                $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_YEAR($ValYear,$linkHRISWebTrax);
                $ValDataRow = mssql_num_rows($QListKWHTracking);
                if($ValDataRow == 0)
                {
                    echo "Data untuk tahun $ValYear tidak ditemukan.";
                    exit();
                }
                while ($RListKWHTracking = mssql_fetch_assoc($QListKWHTracking))
                {
                    $TempNoSlave = $RListKWHTracking['Slave'];
                    $TempKWH = $RListKWHTracking['KWH'];
                    $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
                    $TempUsage = $RListKWHTracking['Usage'];
                    $ArrTempRow = array(
                        "Slave" => $TempNoSlave,
                        "KWH" => $TempKWH,
                        "DataLog" => $TempDataLog,
                        "Usage" => $TempUsage
                    );
                    array_push($ArrDataAwal,$ArrTempRow);
                }
                # get data list slave
                $ArrDataSlave = array();
                $VarCheckSlave = "";
                foreach ($ArrDataAwal as $DataAwal)
                {
                    $VarSlave = $DataAwal['Slave'];
                    if($VarCheckSlave != $VarSlave)
                    {
                        array_push($ArrDataSlave,$VarSlave);
                        $VarCheckSlave = $VarSlave;
                    }
                }
                $TotalSlave = count($ArrDataSlave);
                # pemisahan data berdasarkan slave
                for ($i=1; $i <= $TotalSlave; $i++)
                {
                    ${"ArrTracking$i"} = array();
                    ${"ArrTrackingResult$i"} = array();
                    # pemecahan berdasarkan slave
                    foreach ($ArrDataAwal as $ArrData)
                    {
                        $ASlave = $ArrData['Slave'];
                        $ADataLog = $ArrData['DataLog'];
                        $ADataLog2 = explode(" ",$ADataLog);
                        $ADateLog = $ADataLog2[0];
                        $AMonthLog = date("m",strtotime($ADataLog2[0]));
                        $AUsage = $ArrData['Usage'];
                        $ATemp = array(
                            "Slave" => $ASlave,
                            "DataLog" => $ADataLog,
                            "DateLog" => $ADateLog,
                            "MonthLog" => $AMonthLog,
                            "Usage" => $AUsage
                        );
                        array_push(${"ArrTracking$i"},$ATemp);
                    }
                    # pemecahan berdasarkan bulan
                    for($y=1;$y<=12;$y++)
                    {
                        $TotalCount = 0;
                        $BolCheck = FALSE;
                        $ValTempMonth = "";
                        foreach (${"ArrTracking$i"} as $ArrTemp)
                        {
                            $ValArrTempSlave = $ArrTemp['Slave'];
                            $ValArrTempDataLog = $ArrTemp['DataLog'];
                            $ValArrTempDateLog = $ArrTemp['DateLog'];
                            $ValArrTempMonthLog = $ArrTemp['MonthLog'];
                            $ValArrTempUsage = $ArrTemp['Usage'];

                            if($y == $ValArrTempMonthLog && $i == $ValArrTempSlave)
                            {
                                # penambahan nilai usage
                                $TotalCount = $TotalCount + $ValArrTempUsage;
                                $BolCheck = TRUE;
                            }
                        }
                        if($BolCheck == FALSE)  # jika bulan tanpa usage
                        {
                            # masukkan ke array per bulan
                            $ATempVal = array(
                                "Slave" => $i,
                                "MonthLog" => $y,
                                "TotalCount" => $TotalCount
                            );
                            array_push(${"ArrTrackingResult$i"},$ATempVal);
                        }
                        else
                        {
                            # masukkan ke array per bulan  
                            $ATempVal = array(
                                "Slave" => $i,
                                "MonthLog" => $y,
                                "TotalCount" => $TotalCount
                            );
                            array_push(${"ArrTrackingResult$i"},$ATempVal);
                        }
                    }
                }
            ?>

<script src="./../microplate/js/jquery.min.js" type="text/javascript"></script>
<script src="./../microplate/js/highstock.js" type="text/javascript"></script>
<script src="./../microplate/js/highcharts.js" type="text/javascript"></script>
<script src="./../microplate/js/exporting.js"></script>
<script type="text/javascript">  
var chart;
$(document).ready(function() {
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'containerChart1',
            defaultSeriesType: 'line'
        },
        xAxis: {
            title: {
                text: 'Month'
            },
            categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'],
            labels: {
                rotation: 0,
                y: 15,
            }
        },
        title: {
            text: 'KWH Tracking (<?php echo $ValYear; ?>)'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Usage'
            }
        },
        legend: {
            layout: 'vertical',
            backgroundColor: '#FFFFFF',
            align: 'right',
            verticalAlign: 'top',
            x: 10,
            y: 70,
            floating: true,
            shadow: true
        },
        tooltip: {
            formatter: function() {
                return 'Month : '+ this.x +', Usage : '+ this.y +'';
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        <?php
        for ($x=1; $x <= $TotalSlave; $x++)
        {
            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTrackingResult$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['TotalCount'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
            else
            {
                ?>
                ,{
                name: '<?php echo $x; ?>',
                data: [<?php
                $No = 1; 
                foreach (${"ArrTrackingResult$x"} as $ArrTT)
                {
                    $ValUsageTT = $ArrTT['TotalCount'];
                    if($No == 1)
                    {
                        echo "".$ValUsageTT."";
                    }
                    else
                    {
                        echo ",".$ValUsageTT."";
                    }
                    $No++;
                }
                ?>]
                }
                <?php
            }
        }?>]
    });        
});    
</script>
<div id="containerChart1" style="height: 500px;"></div>
<script type="text/javascript">
Highcharts.theme = { colors: ['#4572A7'] };
var highchartsOptions = Highcharts.getOptions(); 
</script>                    
            <?php
                # reset array
                $ArrDataAwal = array();
                $ArrDataSlave = array();
                for ($i=1; $i <= $TotalSlave; $i++)
                {
                    ${"ArrTracking$i"} = array();
                    ${"ArrTrackingResult$i"} = array();
                }
            }
            break;
        default: # kategori tidak terdaftar
            echo "";
            break;
    }
}
else
{
    echo "";    
}
?>