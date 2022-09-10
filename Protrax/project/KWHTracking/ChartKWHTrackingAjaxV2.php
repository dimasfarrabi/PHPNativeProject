<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php");
date_default_timezone_set("Asia/Jakarta");

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
    switch ($ValCategory){
        case '1': # kategori harian
            {
                $DateNow = date("m/d/Y H:i:s");
                $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
                $DateNowLabel = date("m/d/Y");
                $YesterdayLabel = date("m/d/Y",strtotime("-1 day"));

                $StartTime = date("m/d/Y H:00:00",strtotime($Yesterday));
                $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));

                $StartTime = "02/01/2021 14:00:00";
                $EndTime = "02/02/2021 14:00:00";

                # get data awal
                $ArrDataAwal = array();
                $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
                $ValDataRow = mssql_num_rows($QListKWHTracking);
                if($ValDataRow == 0)
                {
                    echo "Data untuk hari ini tidak ditemukan.";
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
                // echo "<pre>";print_r($ArrDataAwal);echo "</pre>";
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
                // echo "<pre>";print_r($ArrDataSlave);echo "</pre>";
                // echo "Total Slave : ".$TotalSlave;
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
                // for ($x=1; $x <= $TotalSlave; $x++)
                // {
                //     echo "<pre>";print_r(${"ArrTracking$x"});echo "</pre>";
                // }

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
                <?php /*rotation: -90,
                y: 50,*/ ?>
                enabled: false
            }
        },
        title: {
            text: 'Last 24 hours'
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
            if($x == 1){$SlaveName = "Lantai 2";}
            elseif($x == 2){$SlaveName = "Lantai 1";}
            elseif($x == 3){$SlaveName = "Lantai Dasar";}

            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $SlaveName; ?>',
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
                name: '<?php echo $SlaveName; ?>',
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
# sementara disembunyikan 
/* ?>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12"><h5>Tabel Data</h5></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Slave</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Usage</th>
                    </tr>
                </thead>
                <tbody><?php
                    $NoTable = 1;
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        foreach (${"ArrTracking$i"} as $ArrDTable)
                        {
                            $ValSlave = $ArrDTable['Slave'];
                            if($ValSlave == 1){$ValSlave = "Lantai 2";}
                            elseif($ValSlave == 2){$ValSlave = "Lantai 1";}
                            elseif($x == 3){$ValSlave = "Lantai Dasar";}

                            $ValData = $ArrDTable['DataLog'];
                            $ValData = explode("#",$ValData);
                            $ValDate = $ValData[0]; 
                            $ValUsage = $ArrDTable['Usage'];

                            ?>
                        <tr>
                            <td class="text-center"><?php echo $NoTable; ?></td>
                            <td class="text-center"><?php echo $ValSlave; ?></td>
                            <td class="text-center"><?php echo $ValDate; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
                        </tr>
                            <?php
                            $NoTable++;
                        }
                    }
                ?></tbody>
            </table>
        </div>
    </div>
</div><?php */ ?>
            <?php
                    # reset array
                    $ArrDataAwal = array();
                    $ArrDataSlave = array();
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        ${"ArrTracking$i"} = array();                        
                    }
            }
            break;
        case '2': # kategori mingguan
            {
                $DateNow = date("m/d/Y H:i:s");
                $LastWeek = date("m/d/Y H:i:s",strtotime("-2 weeks"));
                # get date
                $StartTime = date("m/d/Y H:00:00",strtotime($LastWeek));
                $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
                # get data awal
                $ArrDataAwal = array();
                $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
                $ValDataRow = mssql_num_rows($QListKWHTracking);
                if($ValDataRow == 0)
                {
                    echo "Data untuk mingguan tidak ditemukan.";
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
                // echo "<pre>";print_r($ArrDataAwal);echo "</pre>";
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
                // echo "<pre>";print_r($ArrDataSlave);echo "</pre>";
                // echo "Total Slave : ".$TotalSlave;
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
                                    // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
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
                                    // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                                    $TempTrackingPerDate = array(
                                        'NoSlave' => $DT1Slave,
                                        'DateTracking' => $TempDT,
                                        'TotalUsage' => $TempDTTotalUsage
                                    );
                                    array_push($ArrTrackingPerDate,$TempTrackingPerDate);
                                }
                                else
                                {
                                    // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
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
                // echo "<pre>";print_r($ArrTrackingPerDate);echo "</pre>";
                # penambahan tgl kosong utk tracking per date
                $ArrTrackingPerDate2 = array();
                for ($x=1; $x <= $TotalSlave; $x++)
                {
                    # check data per hari
                    $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
                    $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
                    $TempArrayLoopDate = array();
                    do
                    {
                        # start looping date
                        $Date = date("m/d/Y",$StartTime1);
                        $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
                        $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
                        # input date
                        $TDate = array(
                            "DateLoop" => $Date
                        );
                        array_push($TempArrayLoopDate,$TDate);
                        // echo "############<br>";
                        // echo "Tgl : ".$Date."<br>";

                        
                        $ValCheckDate = FALSE;
                        # pengecekan nilai usage per jam
                        foreach ($ArrTrackingPerDate as $TPD)
                        {
                            $TNoSlave = $TPD['NoSlave'];
                            $TDateTracking = $TPD['DateTracking'];
                            $TTotalUsage = $TPD['TotalUsage'];
                            if(($x == $TNoSlave) && ($Date == $TDateTracking))
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
                                "DateTracking" => $Date,
                                "TotalUsage" => "0"
                            );
                            array_push($ArrTrackingPerDate2,$TTempArray);
                        }



                        $StartTime1 = strtotime('+1 day',$StartTime1);
                    } while($StartTime1 <= $EndTime1);
                }
                // echo "<pre>";print_r($ArrTrackingPerDate);echo "</pre>";
                // echo "<pre>";print_r($ArrTrackingPerDate2);echo "</pre>";
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
                // for ($i=1; $i <= $TotalSlave; $i++)
                // {
                //     if($i == 1)
                //     {
                //         echo "<pre>";print_r(${"ArrTrackingResult$i"});echo "</pre>";
                //     }
                // }
                
                // echo "<pre>";print_r($TempArrayLoopDate);echo "</pre>";
                // exit();


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
                foreach ($TempArrayLoopDate as $Dates)
                {
                    $TheDate = $Dates['DateLoop'];
                    $TheDate = substr($TheDate,0,5);
                    if($NoListDateRange == 1)
                    {
                        echo "'".$TheDate."'"; 
                    }
                    else
                    {
                        echo ",'".$TheDate."'";
                    }
                    $NoListDateRange++;
                }

            ?>],
            labels: {
                <?php /*rotation: -90,
                y: 20,*/ ?>
                enabled: false
            }
        },
        title: {
            text: 'Last 2 weeks'
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
            if($x == 1){$SlaveName = "Lantai 2";}
            elseif($x == 2){$SlaveName = "Lantai 1";}
            elseif($x == 3){$SlaveName = "Lantai Dasar";}

            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $SlaveName; ?>',
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
                name: '<?php echo $SlaveName; ?>',
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
# sementara disembunyikan 
/*
?>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12"><h5>Tabel Data</h5></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Slave</th>
                        <th class="text-center">Date Log</th>
                        <th class="text-center">Usage</th>
                    </tr>
                </thead>
                <tbody><?php
                    $NoTable = 1;
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        foreach (${"ArrTrackingResult$i"} as $ArrDTable)
                        {
                            $ValSlave = $ArrDTable['NoSlave'];
                            if($ValSlave == 1){$ValSlave = "Lantai 2";}
                            elseif($ValSlave == 2){$ValSlave = "Lantai 1";}
                            elseif($x == 3){$ValSlave = "Lantai Dasar";}
                            $ValDate = $ArrDTable['DateTracking']; 
                            $ValUsage = $ArrDTable['TotalUsage'];

                            ?>
                        <tr>
                            <td class="text-center"><?php echo $NoTable; ?></td>
                            <td class="text-center"><?php echo $ValSlave; ?></td>
                            <td class="text-center"><?php echo $ValDate; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
                        </tr>
                            <?php
                            $NoTable++;
                        }
                    }
                ?></tbody>
            </table>
        </div>
    </div>
</div><?php */ ?>
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
                // echo "Kategori : $ValCategory ($StartTime >> $EndTime)<br> ";
                
            }
            break;
        case '3': # kategori bulanan
            {
                $DateNow = date("m/d/Y H:i:s");
                $LastMonth = date("m/d/Y H:i:s",strtotime("-2 months"));
                # get date
                $StartTime = date("m/d/Y H:00:00",strtotime($LastMonth));
                $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
                # get data awal
                $ArrDataAwal = array();
                $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
                $ValDataRow = mssql_num_rows($QListKWHTracking);
                if($ValDataRow == 0)
                {
                    echo "Data untuk bulanan tidak ditemukan.";
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
                // echo "<pre>";print_r($ArrDataAwal);echo "</pre>";
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
                // echo "<pre>";print_r($ArrDataSlave);echo "</pre>";
                // echo "Total Slave : ".$TotalSlave;
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
                                    // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
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
                                    // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                                    $TempTrackingPerDate = array(
                                        'NoSlave' => $DT1Slave,
                                        'DateTracking' => $TempDT,
                                        'TotalUsage' => $TempDTTotalUsage
                                    );
                                    array_push($ArrTrackingPerDate,$TempTrackingPerDate);
                                }
                                else
                                {
                                    // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
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
                // echo "<pre>";print_r($ArrTrackingPerDate);echo "</pre>";
                # penambahan tgl kosong utk tracking per date
                $ArrTrackingPerDate2 = array();
                for ($x=1; $x <= $TotalSlave; $x++)
                {
                    # check data per hari
                    $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
                    $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
                    $TempArrayLoopDate = array();
                    do
                    {
                        # start looping date
                        $Date = date("m/d/Y",$StartTime1);
                        $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
                        $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
                        # input date
                        $TDate = array(
                            "DateLoop" => $Date
                        );
                        array_push($TempArrayLoopDate,$TDate);
                        // echo "############<br>";
                        // echo "Tgl : ".$Date."<br>";

                        
                        $ValCheckDate = FALSE;
                        # pengecekan nilai usage per jam
                        foreach ($ArrTrackingPerDate as $TPD)
                        {
                            $TNoSlave = $TPD['NoSlave'];
                            $TDateTracking = $TPD['DateTracking'];
                            $TTotalUsage = $TPD['TotalUsage'];
                            if(($x == $TNoSlave) && ($Date == $TDateTracking))
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
                                "DateTracking" => $Date,
                                "TotalUsage" => "0"
                            );
                            array_push($ArrTrackingPerDate2,$TTempArray);
                        }



                        $StartTime1 = strtotime('+1 day',$StartTime1);
                    } while($StartTime1 <= $EndTime1);
                }
                // echo "<pre>";print_r($ArrTrackingPerDate);echo "</pre>";
                // echo "<pre>";print_r($ArrTrackingPerDate2);echo "</pre>";
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
                // for ($i=1; $i <= $TotalSlave; $i++)
                // {
                //     if($i == 1)
                //     {
                //         echo "<pre>";print_r(${"ArrTrackingResult$i"});echo "</pre>";
                //     }
                // }
                
                // echo "<pre>";print_r($TempArrayLoopDate);echo "</pre>";
                // exit();


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
                foreach ($TempArrayLoopDate as $Dates)
                {
                    $TheDate = $Dates['DateLoop'];
                    $TheDate = substr($TheDate,0,5);
                    if($NoListDateRange == 1)
                    {
                        echo "'".$TheDate."'"; 
                    }
                    else
                    {
                        echo ",'".$TheDate."'";
                    }
                    $NoListDateRange++;
                }

            ?>],
            labels: {
                <?php /*rotation: -90,
                y: 20,*/ ?>
                enabled: false
            }
        },
        title: {
            text: 'Last 2 months'
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
            if($x == 1){$SlaveName = "Lantai 2";}
            elseif($x == 2){$SlaveName = "Lantai 1";}
            elseif($x == 3){$SlaveName = "Lantai Dasar";}

            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $SlaveName; ?>',
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
                name: '<?php echo $SlaveName; ?>',
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
# sementara disembunyikan 
/*
?>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12"><h5>Tabel Data</h5></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Slave</th>
                        <th class="text-center">Date Log</th>
                        <th class="text-center">Usage</th>
                    </tr>
                </thead>
                <tbody><?php
                    $NoTable = 1;
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        foreach (${"ArrTrackingResult$i"} as $ArrDTable)
                        {
                            $ValSlave = $ArrDTable['Slave'];
                            if($ValSlave == 1){$ValSlave = "Lantai 2";}
                            elseif($ValSlave == 2){$ValSlave = "Lantai 1";}
                            elseif($x == 3){$ValSlave = "Lantai Dasar";}
                            $ValDate = $ArrDTable['DateTracking']; 
                            $ValUsage = $ArrDTable['TotalUsage'];

                            ?>
                        <tr>
                            <td class="text-center"><?php echo $NoTable; ?></td>
                            <td class="text-center"><?php echo $ValSlave; ?></td>
                            <td class="text-center"><?php echo $ValDate; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
                        </tr>
                            <?php
                            $NoTable++;
                        }
                    }
                ?></tbody>
            </table>
        </div>
    </div>
</div><?php */ ?>
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
                // echo "Kategori : $ValCategory ($DateNow >> $LastMonth)<br> ";
            }
            break;
        case '4': # kategori tahunan
            {
                $LastYear = date("Y",strtotime("-1 year"));
                # get data awal
                $ArrDataAwal = array();
                $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_YEAR($LastYear,$linkHRISWebTrax);
                $ValDataRow = mssql_num_rows($QListKWHTracking);
                if($ValDataRow == 0)
                {
                    echo "Data untuk tahun $LastYear tidak ditemukan.";
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
                // echo "<pre>";print_r($ArrDataAwal);echo "</pre>";
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
                // echo "<pre>";print_r($ArrDataSlave);echo "</pre>";
                // echo "Total Slave : ".$TotalSlave;
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
                    // echo "<pre>";print_r(${"ArrTracking$i"});echo "</pre>";
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
                                // if($i == 1)
                                // {
                                //     echo $ValArrTempDataLog." >> ".$ValArrTempUsage."<br>";
                                // }
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
                    // if($i == 1)
                    // {
                        // echo "<pre>";print_r(${"ArrTrackingResult$i"});echo "</pre>";
                        // echo "<pre>";print_r(${"ArrTracking$i"});echo "</pre>";
                    // }
                }
                    
                // for ($x=1; $x <= $TotalSlave; $x++)
                // {
                //     echo "<pre>";print_r(${"ArrTrackingResult$x"});echo "</pre>";
                // }
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
            categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'],
            labels: {
                /*rotation: 0,
                y: 15,*/
                enabled: false
            }
        },
        title: {
            text: 'Last 1 year'
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
            if($x == 1){$SlaveName = "Lantai 2";}
            elseif($x == 2){$SlaveName = "Lantai 1";}
            elseif($x == 3){$SlaveName = "Lantai Dasar";}
            if($x == 1)
            {
                ?>
                {
                name: '<?php echo $SlaveName; ?>',
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
                name: '<?php echo $SlaveName; ?>',
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
# sementara disembunyikan 
/*
?>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12"><h5>Tabel Data</h5></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Slave</th>
                        <th class="text-center">Month Log</th>
                        <th class="text-center">Usage</th>
                    </tr>
                </thead>
                <tbody><?php
                    $NoTable = 1;
                    for ($i=1; $i <= $TotalSlave; $i++)
                    {
                        foreach (${"ArrTrackingResult$i"} as $ArrDTable)
                        {
                            $ValSlave = $ArrDTable['Slave'];
                            if($ValSlave == 1){$ValSlave = "Lantai 2";}
                            elseif($ValSlave == 2){$ValSlave = "Lantai 1";}
                            elseif($x == 3){$ValSlave = "Lantai Dasar";}
                            $ValMonthLog = $ArrDTable['MonthLog'];
                            $ValUsage = $ArrDTable['TotalCount'];

                            ?>
                        <tr>
                            <td class="text-center"><?php echo $NoTable; ?></td>
                            <td class="text-center"><?php echo $ValSlave; ?></td>
                            <td class="text-center"><?php echo $ValMonthLog; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
                        </tr>
                            <?php
                            $NoTable++;
                        }
                    }
                ?></tbody>
            </table>
        </div>
    </div>
</div><?php */ ?>                 
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