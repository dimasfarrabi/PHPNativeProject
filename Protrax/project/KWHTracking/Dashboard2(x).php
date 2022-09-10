<?php
require_once("project/KWHTracking/Modules/ModuleKWHTracking.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
    #### data chart Last 24 Hours
    $DateNow = date("m/d/Y H:i:s");
    $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    $DateNowLabel = date("m/d/Y");
    $YesterdayLabel = date("m/d/Y",strtotime("-1 day"));

    $StartTime = date("m/d/Y H:00:00",strtotime($Yesterday));
    $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));

    // $StartTime = "03/03/2021 14:00:00";
    // $EndTime = "03/04/2021 14:00:00";

    # check data per hari
    $StartTime1 = strtotime($StartTime);
    $EndTime1 = strtotime($EndTime);
    $TempArrayLoopDateHours = array();
    do
    {
        # start looping date
        $Date = date("m/d/Y H:i:00",$StartTime1);
        $Date2 = date('m/d/Y H:i:00',strtotime("+1 hour",strtotime($Date)));
        $DateBefore = date('m/d/Y H:00:00',strtotime("-1 hour",strtotime($Date)));
        # input date
        $TDate = array(
            "DateLoop" => $Date
        );
        array_push($TempArrayLoopDateHours,$TDate);
        // echo $Date."<br>";

        $StartTime1 = strtotime('+1 hour',$StartTime1);
    } while($StartTime1 <= $EndTime1);

    # get data awal
    $ArrDataAwalDay = array();
    $ArrResultHours = array();
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_HOUR_ALL_SLAVE($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
        $ArrTrackingDay = array();
        # looping data
        // echo "<pre>";print_r($TempArrayLoopDateHours);echo "</pre>";
        foreach ($TempArrayLoopDateHours as $TempArrResultHours)
        {
            $TimeHour = date("m/d/Y H:i",strtotime($TempArrResultHours['DateLoop']));
            $TempResultHour = array(
                "TimeLoop" => $TimeHour,
                "ValLoop" => "0"
            );
            array_push($ArrResultHours,$TempResultHour);
            // echo $TimeHour."<br>";
        }
        // echo "<pre>";print_r($ArrResultHours);echo "</pre>";
    }
    else
    {
        // echo "<pre>";print_r($TempArrayLoopDateHours);echo "</pre>";
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
            array_push($ArrDataAwalDay,$ArrTempRow);
        }
        // echo "<pre>";print_r($ArrDataAwalDay);echo "</pre>";
        # count total usage per hour
        foreach ($TempArrayLoopDateHours as $ArrLoopDateHours)
        {
            $DateLoopDay = date("m/d/Y H:i",strtotime($ArrLoopDateHours['DateLoop']));
            $TempTotalUsage = 0;
            foreach($ArrDataAwalDay as $ArrAwalDay)
            {
                $ArrDataLogDay = explode("#",$ArrAwalDay['DataLog']);
                $TempDataLogDay = $ArrDataLogDay[0];
                $TempDataUsageDay = number_format($ArrAwalDay['Usage'], 4, '.', '');
                $TempDataSlaveDay = $ArrAwalDay['Slave'];
                // echo $TempDataLogDay." ?? ".$TempDataUsageDay." ?? ".$TempDataSlaveDay."<br>";
                if($TempDataLogDay == $DateLoopDay)
                {
                    if($TempDataSlaveDay != "3")
                    {
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataUsageDay, 4, '.', '');                    
                    }
                    else
                    {
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataUsageDay, 4, '.', '');
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '');
                        # simpan ke array
                        $TempResultHour = array(
                            "TimeLoop" => $DateLoopDay,
                            "ValLoop" => $TempTotalUsage
                        );
                        array_push($ArrResultHours,$TempResultHour);
                        $TempTotalUsage = 0;
                    }
                }
            }
        }
        // echo "<pre>";print_r($ArrResultHours);echo "</pre>";
    }

    #### data chart Last 2 weeks
    $ArrResultWeeks = array();
    $DateNow = date("m/d/Y H:i:s");
    $LastWeek = date("m/d/Y H:i:s",strtotime("-2 weeks"));
    # get date
    $StartTime = date("m/d/Y H:00:00",strtotime($LastWeek));
    $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
    # get data awal
    $ArrDataAwalWeeks = array();
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_HOUR_ALL_SLAVE($StartTime,$EndTime,$linkHRISWebTrax);
    // $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
        // echo "Data untuk mingguan tidak ditemukan.";
        echo "";
    }
    else
    {
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
            array_push($ArrDataAwalWeeks,$ArrTempRow);
        }
        // echo "<pre>";print_r($ArrDataAwalWeeks);echo "</pre>";
        # get data list slave
        $ArrDataSlaveWeeks = array();
        $VarCheckLogWeeks = "";
        $NoLoopWeeks = 1;
        foreach ($ArrDataAwalWeeks as $DataAwal)
        {
            $VarSlave = $DataAwal['Slave'];
            $VarDataLog = $DataAwal['DataLog'];
            $ArrDataLog = explode("#",$VarDataLog);
            $ArrDateLog = explode(" ",$ArrDataLog[0]);
            $TimeLogWeeks = $ArrDataLog[0];
            if($NoLoopWeeks == 1)
            {
                $VarCheckLogWeeks = $TimeLogWeeks;
                array_push($ArrDataSlaveWeeks,$VarSlave);
            }
            else
            {
                if($VarCheckLogWeeks == $TimeLogWeeks)
                {
                    array_push($ArrDataSlaveWeeks,$VarSlave);
                }
            }
            $NoLoopWeeks++;
        }
        $TotalSlaveWeeks = count($ArrDataSlaveWeeks);
        // echo "<pre>";print_r($ArrDataSlaveWeeks);echo "</pre>";
        // exit();
        // echo "Total Slave : ".$TotalSlaveWeeks;
        # data array tracking per slave
        $ArrTrackingPerDateWeeks = array();
        for ($i=1; $i <= $TotalSlaveWeeks; $i++)
        {
            ${"ArrTrackingWeeks$i"} = array();
            foreach ($ArrDataAwalWeeks as $DataAwal1)
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
                    array_push(${"ArrTrackingWeeks$i"},$TempArrayDataSlave);
                }                          
            }
            # perhitungan setiap hari
            $TempDT = "";
            $TempDTTotalUsage = 0;
            $NoLoopingTempDT = 1;
            $CountRowTempDT = count(${"ArrTrackingWeeks$i"});
            foreach (${"ArrTrackingWeeks$i"} as $DT1)
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
                                'Slave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateWeeks,$TempTrackingPerDate);
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
                                'Slave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateWeeks,$TempTrackingPerDate);
                        }
                        else
                        {
                            // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                            $TempTrackingPerDate = array(
                                'Slave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateWeeks,$TempTrackingPerDate);
                            $TempDT = $DateDataLog;
                            $TempDTTotalUsage = 0;
                            $TempDTTotalUsage = $DT1Usage;
                        }
                    }
                }
                $NoLoopingTempDT++;
            }
        }
        // echo "<pre>";print_r($ArrTrackingPerDateWeeks);echo "</pre>";
        // echo "total : $TotalSlaveWeeks<br>";
        # penambahan tgl kosong utk tracking per date
        $ArrTrackingPerDate2Weeks = array();
        for ($x=1; $x <= $TotalSlaveWeeks; $x++)
        {
            # check data per hari
            $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
            $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
            $TempArrayLoopDateWeeks = array();
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
                array_push($TempArrayLoopDateWeeks,$TDate);
                // echo "############<br>";
                // echo "Tgl : ".$Date."<br>";

                
                $ValCheckDate = FALSE;
                # pengecekan nilai usage per jam
                foreach ($ArrTrackingPerDateWeeks as $TPD)
                {
                    $TNoSlave = $TPD['Slave'];
                    $TDateTracking = $TPD['DateTracking'];
                    $TTotalUsage = $TPD['TotalUsage'];
                    if(($x == $TNoSlave) && ($Date == $TDateTracking))
                    {
                        $TTempArray = array(
                            "Slave" => $TNoSlave,
                            "DateTracking" => $TDateTracking,
                            "TotalUsage" => $TTotalUsage
                        );
                        array_push($ArrTrackingPerDate2Weeks,$TTempArray);
                        $ValCheckDate = TRUE;
                        break;
                    }
                }
                if($ValCheckDate == FALSE)
                {
                    $TTempArray = array(
                        "Slave" => $x,
                        "DateTracking" => $Date,
                        "TotalUsage" => "0"
                    );
                    array_push($ArrTrackingPerDate2Weeks,$TTempArray);
                }
                $StartTime1 = strtotime('+1 day',$StartTime1);
            } while($StartTime1 <= $EndTime1);
                // echo "############<br>";
        }
        // echo "<pre>";print_r($ArrTrackingPerDate2Weeks);echo "</pre>";
        // exit();
        # count total usage per hour
        $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
        $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
        $TempTotalUsage = 0;
        do
        {
            # start looping date
            $Date = date("m/d/Y",$StartTime1);
            $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
            $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
            # loop data
            foreach($ArrTrackingPerDate2Weeks as $Data2Weeks)
            {
                $TempDataSlave2Weeks = $Data2Weeks['Slave'];
                $TempDataTotalUsage2Weeks = number_format($Data2Weeks['TotalUsage'], 4, '.', '');
                $TempDataDate2Weeks =  $Data2Weeks['DateTracking'];
                if($TempDataDate2Weeks == $Date)
                {
                    if($TempDataSlave2Weeks != "3")
                    {
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataTotalUsage2Weeks, 4, '.', '');
                    }
                    else
                    {
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataTotalUsage2Weeks, 4, '.', '');
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.,', '');
                            # simpan ke array
                            $TempResultHour = array(
                                "TimeLoop" => $Date,
                                "ValLoop" => $TempTotalUsage
                            );
                            array_push($ArrResultWeeks,$TempResultHour);
                        $TempTotalUsage = 0;
                    }
                }
            }
            $StartTime1 = strtotime('+1 day',$StartTime1);
        } while($StartTime1 <= $EndTime1);        
        // echo "<pre>";print_r($ArrResultWeeks);echo "</pre>";  
    }
    #### data chart Last 2 months
    $ArrResultMonths = array();
    $DateNow = date("m/d/Y H:i:s");
    $LastMonth = date("m/d/Y H:i:s",strtotime("-2 months"));
    # get date
    $StartTime = date("m/d/Y H:00:00",strtotime($LastMonth));
    $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
    # get data awal
    $ArrDataAwalMonth = array();
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
        // echo "Data untuk bulanan tidak ditemukan.";
        echo "";
    }
    else
    {
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
            array_push($ArrDataAwalMonth,$ArrTempRow);
        }
        // echo "<pre>";print_r($ArrDataAwalMonth);echo "</pre>";
        # get data list slave
        $ArrDataSlaveMonth = array();
        $VarCheckSlaveMonth = "";
        foreach ($ArrDataAwalMonth as $DataAwal)
        {
            $VarSlave = $DataAwal['Slave'];
            if($VarCheckSlaveMonth != $VarSlave)
            {
                array_push($ArrDataSlaveMonth,$VarSlave);
                $VarCheckSlaveMonth = $VarSlave;
            }
        }
        $TotalSlaveMonth = count($ArrDataSlaveMonth);
        // echo "<pre>";print_r($ArrDataSlaveMonth);echo "</pre>";
        // echo "Total Slave : ".$TotalSlaveMonth;
        # data array tracking per slave
        $ArrTrackingPerDateMonth = array();
        for ($i=1; $i <= $TotalSlaveMonth; $i++)
        {
            ${"ArrTrackingMonth$i"} = array();
            foreach ($ArrDataAwalMonth as $DataAwal1)
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
                    array_push(${"ArrTrackingMonth$i"},$TempArrayDataSlave);
                }                          
            }
            # perhitungan setiap hari
            $TempDT = "";
            $TempDTTotalUsage = 0;
            $NoLoopingTempDT = 1;
            $CountRowTempDT = count(${"ArrTrackingMonth$i"});
            foreach (${"ArrTrackingMonth$i"} as $DT1)
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
                                'Slave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonth,$TempTrackingPerDate);
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
                                'Slave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonth,$TempTrackingPerDate);
                        }
                        else
                        {
                            // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                            $TempTrackingPerDate = array(
                                'Slave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonth,$TempTrackingPerDate);
                            $TempDT = $DateDataLog;
                            $TempDTTotalUsage = 0;
                            $TempDTTotalUsage = $DT1Usage;
                        }
                    }
                }
                $NoLoopingTempDT++;
            }
        }
        // echo "<pre>";print_r($ArrTrackingPerDateMonth);echo "</pre>";
        # penambahan tgl kosong utk tracking per date
        $ArrTrackingPerDate2Month = array();
        for ($x=1; $x <= $TotalSlaveMonth; $x++)
        {
            # check data per hari
            $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
            $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
            $TempArrayLoopDateMonth = array();
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
                array_push($TempArrayLoopDateMonth,$TDate);
                // echo "############<br>";
                // echo "Tgl : ".$Date."<br>";

                
                $ValCheckDate = FALSE;
                # pengecekan nilai usage per jam
                foreach ($ArrTrackingPerDateMonth as $TPD)
                {
                    $TNoSlave = $TPD['Slave'];
                    $TDateTracking = $TPD['DateTracking'];
                    $TTotalUsage = $TPD['TotalUsage'];
                    if(($x == $TNoSlave) && ($Date == $TDateTracking))
                    {
                        $TTempArray = array(
                            "Slave" => $TNoSlave,
                            "DateTracking" => $TDateTracking,
                            "TotalUsage" => $TTotalUsage
                        );
                        array_push($ArrTrackingPerDate2Month,$TTempArray);
                        $ValCheckDate = TRUE;
                        break;
                    }
                }
                if($ValCheckDate == FALSE)
                {
                    $TTempArray = array(
                        "Slave" => $x,
                        "DateTracking" => $Date,
                        "TotalUsage" => "0"
                    );
                    array_push($ArrTrackingPerDate2Month,$TTempArray);
                }
                $StartTime1 = strtotime('+1 day',$StartTime1);
            } while($StartTime1 <= $EndTime1);
        }
        // echo "<pre>";print_r($ArrTrackingPerDateMonth);echo "</pre>";
        # check data per hari
        $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
        $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
        $TempArrayLoopDateMonth = array();
        do
        {
            # start looping date
            $Date = date("m/d/Y",$StartTime1);
            $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
            $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
            # input date
            // $TDate = array(
            //     "DateLoop" => $Date
            // );
            // array_push($TempArrayLoopDateMonth,$TDate);
            // echo "############<br>";
            // echo "Tgl : ".$Date."<br>";
            foreach ($ArrTrackingPerDateMonth as $DataMonths)
            {
                $TempDataSlave2Months = $DataMonths['Slave'];
                $TempDataTotalUsage2Months = number_format($DataMonths['TotalUsage'], 4, '.', '');
                $TempDataDate2Months =  $DataMonths['DateTracking'];
                if($TempDataDate2Months == $Date)
                {
                    if($TempDataSlave2Months != "3")
                    {
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataTotalUsage2Months, 4, '.', '');
                    }
                    else
                    {
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataTotalUsage2Months, 4, '.', '');
                        $TempTotalUsage = number_format($TempTotalUsage, 4, '.,', '');
                        # simpan ke array
                        $TempResultHour = array(
                            "TimeLoop" => $Date,
                            "ValLoop" => $TempTotalUsage
                        );
                        array_push($ArrResultMonths,$TempResultHour);
                        $TempTotalUsage = 0;
                    }
                }
            }
            $StartTime1 = strtotime('+1 day',$StartTime1);
        } while($StartTime1 <= $EndTime1);
        // echo "<pre>";print_r($ArrResultMonths);echo "</pre>";
    }
    #### data chart Last 1 year
    $ArrResultYear = array();
    $LastYear = date("Y",strtotime("-1 year"));
    # get data awal
    $ArrDataAwalYear = array();
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_YEAR($LastYear,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
        // echo "Data untuk tahun $LastYear tidak ditemukan.";
        echo "";
    }
    else
    {
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
            array_push($ArrDataAwalYear,$ArrTempRow);
        }
        // echo "<pre>";print_r($ArrDataAwalYear);echo "</pre>";
        # get data list slave
        $ArrDataSlaveYear = array();
        $VarCheckSlaveYear = "";
        foreach ($ArrDataAwalYear as $DataAwal)
        {
            $VarSlave = $DataAwal['Slave'];
            if($VarCheckSlaveYear != $VarSlave)
            {
                array_push($ArrDataSlaveYear,$VarSlave);
                $VarCheckSlaveYear = $VarSlave;
            }
        }
        $TotalSlaveYear = count($ArrDataSlaveYear);
        // echo "<pre>";print_r($ArrDataSlaveYear);echo "</pre>";
        // echo "Total Slave : ".$TotalSlaveYear;
        # pemisahan data berdasarkan slave
        for ($i=1; $i <= $TotalSlaveYear; $i++)
        {
            ${"ArrTrackingYear$i"} = array();
            ${"ArrTrackingResultYear$i"} = array();
            # pemecahan berdasarkan slave
            foreach ($ArrDataAwalYear as $ArrData)
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
                array_push(${"ArrTrackingYear$i"},$ATemp);
            }
            // echo "<pre>";print_r(${"ArrTrackingYear$i"});echo "</pre>";
            # pemecahan berdasarkan bulan
            for($y=1;$y<=12;$y++)
            {
                $TotalCount = 0;
                $BolCheck = FALSE;
                $ValTempMonth = "";
                foreach (${"ArrTrackingYear$i"} as $ArrTemp)
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
                    array_push(${"ArrTrackingResultYear$i"},$ATempVal);
                }
                else
                {
                    # masukkan ke array per bulan  
                    $ATempVal = array(
                        "Slave" => $i,
                        "MonthLog" => $y,
                        "TotalCount" => $TotalCount
                    );
                    array_push(${"ArrTrackingResultYear$i"},$ATempVal);
                }
            }
            // if($i == 1)
            // {
                // echo "<pre>";print_r(${"ArrTrackingResultYear$i"});echo "</pre>";
                // echo "<pre>";print_r(${"ArrTrackingYear$i"});echo "</pre>";
            // }
        }
        $ArrDataYear = array();
        for ($x=1; $x <= $TotalSlaveYear; $x++)
        {
            // echo "<pre>";print_r(${"ArrTrackingResultYear$x"});echo "</pre>";
            foreach(${"ArrTrackingResultYear$x"} as $TempTrackingResult)
            {
                $ResSlave = $TempTrackingResult['Slave'];
                $ResMonthLog = $TempTrackingResult['MonthLog']; 
                $ResTotalCount = $TempTrackingResult['TotalCount'];
                $TempArray = array(
                    "Slave" => $ResSlave,
                    "MonthLog" => $ResMonthLog,
                    "TotalCount" => $ResTotalCount,
                );
                array_push($ArrDataYear,$TempArray);

            }
        }
        // echo "<pre>";print_r($ArrDataYear);echo "</pre>";
        for($x=1;$x<13;$x++)
        {
            $TempCountYear = 0;
            foreach($ArrDataYear as $ArrResDataYear)
            {
                $ValSlaveYear = $ArrResDataYear['Slave'];
                $ValMonthLogYear = $ArrResDataYear['MonthLog'];
                $ValTotalCountYear = $ArrResDataYear['TotalCount'];
                if($ValMonthLogYear == $x)
                {
                    // $TempCountYear = $TempCountYear + $ValTotalCountYear;
                    $TempCountYear = number_format($TempCountYear, 4, '.', '') + number_format($ValTotalCountYear, 4, '.', '');
                }
            }
            # simpan ke array
            if($x<10){$ValMonth = "0".$x;}else{$ValMonth = $x;}
            $TempResultHour = array(
                "TimeLoop" => $ValMonth,
                "ValLoop" => $TempCountYear
            );
            array_push($ArrResultYear,$TempResultHour);
        }
        // echo "<pre>";print_r($ArrResultYear);echo "</pre>";
    }

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=9">KWH Tracking : Dashboard 2</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <script type="text/javascript">
            <?php // google.charts.load("current", {packages:["bar"]}); ?>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data1 = google.visualization.arrayToDataTable([
                    ["Element", "Usage", { role: "style" } ]
                    <?php 
                    foreach ($ArrResultHours as $ListDate) {
                        $ValDatePerDay = $ListDate['TimeLoop'];
                        $ValUsagePerDay = $ListDate['ValLoop'];                        
                        echo ',["'.date("m/d H:i",strtotime($ValDatePerDay)).'", '.number_format($ValUsagePerDay, 4, '.', '').', "color: #0087c6"]';
                    }
                    ?>
                ]);
                var view1 = new google.visualization.DataView(data1);
                view1.setColumns([0, 1,
                                { calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation" },
                                2]);
                var options1 = {
                title: "Last 24 Hours",
                <?php // width: 600, ?>
                height: 500,
                bar: {groupWidth: "60%"},
                chartArea: {top:20,'width': '70%', 'height': '80%'},
                legend: { position: "bottom" },
                };
                var chart1 = new google.visualization.BarChart(document.getElementById("GraphDay"));
                chart1.draw(view1, options1);
            }
        </script>
        <div id="GraphDay"></div>
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            <?php // google.charts.load("current", {packages:["bar"]}); ?>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data2 = google.visualization.arrayToDataTable([
                    ["Element", "Usage", { role: "style" } ]
                    <?php 
                    foreach ($ArrResultWeeks as $ListDate) {
                        $ValDatePerWeek = $ListDate['TimeLoop'];
                        $ValUsagePerWeek = $ListDate['ValLoop'];                        
                        echo ',["'.date("m/d/Y",strtotime($ValDatePerWeek)).'", '.number_format($ValUsagePerWeek, 4, '.', '').', "color: #0087c6"]';
                    }
                    ?>
                ]);
                var view2 = new google.visualization.DataView(data2);
                view2.setColumns([0, 1,
                                { calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation" },
                                2]);
                var options2 = {
                title: "Last 2 weeks",
                <?php // width: 600, ?>
                height: 500,
                bar: {groupWidth: "60%"},
                chartArea: {top:20,'width': '70%', 'height': '80%'},
                legend: { position: "bottom" },
                };
                var chart2 = new google.visualization.BarChart(document.getElementById("GraphWeeks"));
                chart2.draw(view2, options2);
            }
        </script>
        <div id="GraphWeeks"></div>   
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            <?php // google.charts.load("current", {packages:["bar"]}); ?>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data3 = google.visualization.arrayToDataTable([
                    ["Element", "Usage", { role: "style" } ]
                    <?php 
                    foreach ($ArrResultMonths as $ListDate) {
                        $ValDatePerMonths = $ListDate['TimeLoop'];
                        $ValUsagePerMonths = $ListDate['ValLoop'];                        
                        echo ',["'.date("m/d/Y",strtotime($ValDatePerMonths)).'", '.number_format($ValUsagePerMonths, 4, '.', '').', "color: #0087c6"]';
                    }
                    ?>
                ]);
                var view3 = new google.visualization.DataView(data3);
                view3.setColumns([0, 1,
                                { calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation" },
                                2]);
                var options3 = {
                title: "Last 2 months",
                <?php // width: 600, ?>
                height: 1300,
                bar: {groupWidth: "60%"},
                chartArea: {top:20,'width': '70%', 'height': '80%'},
                legend: { position: "bottom" },
                };
                var chart3 = new google.visualization.BarChart(document.getElementById("GraphMonths"));
                chart3.draw(view3, options3);
            }
        </script>
        <div id="GraphMonths"></div>   
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            <?php // google.charts.load("current", {packages:["bar"]}); ?>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data4 = google.visualization.arrayToDataTable([
                    ["Element", "Usage", { role: "style" } ]
                    <?php 
                    foreach ($ArrResultYear as $ListDate) {
                        $ValDatePerYear = $ListDate['TimeLoop'];
                        $ValUsagePerYear = $ListDate['ValLoop'];
                        $ValDatePerYear = date("M",strtotime($ValDatePerYear."/01/".date("Y"))); 
                        echo ',["'.$ValDatePerYear.'", '.number_format($ValUsagePerYear, 4, '.', '').', "color: #0087c6"]';
                    }
                    ?>
                ]);
                var view4 = new google.visualization.DataView(data4);
                view4.setColumns([0, 1,
                                { calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation" },
                                2]);
                var options4 = {
                title: "Last 1 year",
                <?php // width: 600, ?>
                height: 700,
                bar: {groupWidth: "60%"},
                chartArea: {top:20,'width': '70%', 'height': '80%'},
                legend: { position: "bottom" },
                };
                var chart4 = new google.visualization.BarChart(document.getElementById("GraphYear"));
                chart4.draw(view4, options4);
            }
        </script>
        <div id="GraphYear"></div>
    </div>


</div>
