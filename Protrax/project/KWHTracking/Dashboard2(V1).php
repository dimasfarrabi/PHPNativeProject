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
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}

    ## data chart Last 24 Hours
    $DateNow = date("m/d/Y H:i:s");
    $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    $DateNowLabel = date("m/d/Y");
    $YesterdayLabel = date("m/d/Y",strtotime("-1 day"));

    $StartTime = date("m/d/Y H:00:00",strtotime($Yesterday));
    $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));

    // $StartTime = "02/01/2021 14:00:00";
    // $EndTime = "02/02/2021 14:00:00";
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
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
        $ArrTrackingDay1 = array();
        $ArrTrackingDay2 = array();
        $ArrTrackingDay3 = array();
        $TotalSlaveDay = 3;
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
            array_push($ArrDataAwalDay,$ArrTempRow);
        }
        // echo "<pre>";print_r($ArrDataAwalDay);echo "</pre>";
        # get data list slave
        $ArrDataSlaveDay = array();
        $VarCheckSlaveDay = "";
        foreach ($ArrDataAwalDay as $DataAwal)
        {
            $VarSlave = $DataAwal['Slave'];
            if($VarCheckSlaveDay != $VarSlave)
            {
                array_push($ArrDataSlaveDay,$VarSlave);
                $VarCheckSlaveDay = $VarSlave;
            }
        }
        $TotalSlaveDay = count($ArrDataSlaveDay);
        // echo "<pre>";print_r($ArrDataSlaveDay);echo "</pre>";
        // echo "Total Slave : ".$TotalSlaveDay;
        # data array tracking per slave
        for ($i=1; $i <= $TotalSlaveDay; $i++)
        {
            ${"ArrTrackingDay$i"} = array();
            foreach ($ArrDataAwalDay as $DataAwal1)
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
                    array_push(${"ArrTrackingDay$i"},$TempArrayDataSlave);
                }                          
            }
        }
        // for ($x=1; $x <= $TotalSlaveDay; $x++)
        // {
        //     echo "<pre>";print_r(${"ArrTrackingDay$x"});echo "</pre>";
        // }   
    }
    
    
    ## data chart Last 2 weeks
    $DateNow = date("m/d/Y H:i:s");
    $LastWeek = date("m/d/Y H:i:s",strtotime("-2 weeks"));
    # get date
    $StartTime = date("m/d/Y H:00:00",strtotime($LastWeek));
    $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
    # get data awal
    $ArrDataAwalWeeks = array();
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
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
        $VarCheckSlaveWeeks = "";
        foreach ($ArrDataAwalWeeks as $DataAwal)
        {
            $VarSlave = $DataAwal['Slave'];
            if($VarCheckSlaveWeeks != $VarSlave)
            {
                array_push($ArrDataSlaveWeeks,$VarSlave);
                $VarCheckSlaveWeeks = $VarSlave;
            }
        }
        $TotalSlaveWeeks = count($ArrDataSlaveWeeks);
        // echo "<pre>";print_r($ArrDataSlaveWeeks);echo "</pre>";
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
                                'NoSlave' => $DT1Slave,
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
                                'NoSlave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateWeeks,$TempTrackingPerDate);
                        }
                        else
                        {
                            // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                            $TempTrackingPerDate = array(
                                'NoSlave' => $DT1Slave,
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
                        array_push($ArrTrackingPerDate2Weeks,$TTempArray);
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
                    array_push($ArrTrackingPerDate2Weeks,$TTempArray);
                }



                $StartTime1 = strtotime('+1 day',$StartTime1);
            } while($StartTime1 <= $EndTime1);
        }
        // echo "<pre>";print_r($ArrTrackingPerDateWeeks);echo "</pre>";
        // echo "<pre>";print_r($ArrTrackingPerDate2Weeks);echo "</pre>";
        # pemecahan tracking per slave
        for ($i=1; $i <= $TotalSlaveWeeks; $i++)
        {
            ${"ArrTrackingResultWeeks$i"} = array();
            foreach ($ArrTrackingPerDate2Weeks as $ArrResult)
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
                    array_push(${"ArrTrackingResultWeeks$i"},$TArray);
                }
            }
        }
        // for ($i=1; $i <= $TotalSlaveWeeks; $i++)
        // {
        //     if($i == 1)
        //     {
        //         echo "<pre>";print_r(${"ArrTrackingResultWeeks$i"});echo "</pre>";
        //     }
        // }
        
        // echo "<pre>";print_r($TempArrayLoopDateWeeks);echo "</pre>";
        // exit();    
    }

    ## data chart Last 2 months
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
                                'NoSlave' => $DT1Slave,
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
                                'NoSlave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonth,$TempTrackingPerDate);
                        }
                        else
                        {
                            // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                            $TempTrackingPerDate = array(
                                'NoSlave' => $DT1Slave,
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
                        array_push($ArrTrackingPerDate2Month,$TTempArray);
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
                    array_push($ArrTrackingPerDate2Month,$TTempArray);
                }
                $StartTime1 = strtotime('+1 day',$StartTime1);
            } while($StartTime1 <= $EndTime1);
        }
        // echo "<pre>";print_r($ArrTrackingPerDateMonth);echo "</pre>";
        // echo "<pre>";print_r($ArrTrackingPerDate2Month);echo "</pre>";
        # pemecahan tracking per slave
        for ($i=1; $i <= $TotalSlaveMonth; $i++)
        {
            ${"ArrTrackingResultMonth$i"} = array();
            foreach ($ArrTrackingPerDate2Month as $ArrResult)
            {
                $ValSlave = $ArrResult['NoSlave'];
                $ValDateTracking = $ArrResult['DateTracking'];
                $ValotalUsage = $ArrResult['TotalUsage'];
                if($i == $ValSlave)
                {
                    $TArray = array(
                        "NoSlave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValotalUsage,
                        "Weeks" => date("W",strtotime($ValDateTracking))
                    );
                    array_push(${"ArrTrackingResultMonth$i"},$TArray);
                }
            }
        }
        // for ($i=1; $i <= $TotalSlaveMonth; $i++)
        // {
        //     if($i == 1)
        //     {
        //         echo "<pre>";print_r(${"ArrTrackingResultMonth$i"});echo "</pre>";
        //     }
        // }
        
        // echo "<pre>";print_r($TempArrayLoopDateMonth);echo "</pre>";
        // exit();
        # loop weeks
        $NoWeeks2Months = 1;
        $ArrWeeks2Months = array();
        $TempWeeks2Months = "";
        $CountArrayLoopWeekMonths = count($TempArrayLoopDateMonth);
        foreach ($TempArrayLoopDateMonth as $WeekMonths) {
            $Weeks2MonthsName = date("W",strtotime($WeekMonths['DateLoop']));
            $ValYearWeeks2MonthsName = date("Y",strtotime($WeekMonths['DateLoop']));
            // echo $WeekMonths['DateLoop'];
            // echo " - Weeks ".$Weeks2MonthsName." >> Total loop : ".$CountArrayLoopWeekMonths;
            // echo "<br>";
            if($NoWeeks2Months == 1)
            {
                $TempWeeks2MonthsArr = array(
                    "Weeks" => $Weeks2MonthsName,
                    "Years" => $ValYearWeeks2MonthsName
                );
                array_push($ArrWeeks2Months,$TempWeeks2MonthsArr);
                $TempWeeks2Months = $Weeks2MonthsName;
            }
            else
            {
                if($NoWeeks2Months != $CountArrayLoopWeekMonths)
                {
                    //data terakhir
                    //tmbh pengecekan antara sama atau beda dengan var temp
                    if($Weeks2MonthsName != $TempWeeks2Months)
                    {
                        $TempWeeks2MonthsArr = array(
                            "Weeks" => $Weeks2MonthsName,
                            "Years" => $ValYearWeeks2MonthsName
                        );
                        array_push($ArrWeeks2Months,$TempWeeks2MonthsArr);
                        $TempWeeks2Months = $Weeks2MonthsName;
                    }
                    else
                    {
                        $TempWeeks2Months = $Weeks2MonthsName;
                    }
                }   
                else
                {
                    //jika sama maka lewat dengan var temp
                    if($Weeks2MonthsName != $TempWeeks2Months)
                    {
                        $TempWeeks2MonthsArr = array(
                            "Weeks" => $Weeks2MonthsName,
                            "Years" => $ValYearWeeks2MonthsName
                        );
                        array_push($ArrWeeks2Months,$TempWeeks2MonthsArr);
                        $TempWeeks2Months = $Weeks2MonthsName;
                    }
                    else
                    {
                        $TempWeeks2Months = $Weeks2MonthsName;
                    }
                }
            }
            $NoWeeks2Months++;
        }
        // echo "<pre>";print_r($ArrWeeks2Months);echo "</pre>";
        // krsort($ArrWeeks2Months);
        # penjumlahan berdasarkan week
        for ($i=1; $i <= $TotalSlaveMonth; $i++)
        {
            ${"ArrTrackingResultMonthByWeeks$i"} = array();
            // if($i == 1)
            // {
                // echo "<pre>";print_r(${"ArrTrackingResultMonth$i"});echo "</pre>";
                $CountArrTrackingResultMonth = count(${"ArrTrackingResultMonth$i"});
                foreach($ArrWeeks2Months as $DataWeeks2Months)
                {
                    $ValDataWeeks2Months = $DataWeeks2Months['Weeks'];
                    $ValYearWeeks2MonthsName = $DataWeeks2Months['Years'];
                    // echo "<br>".$ValDataWeeks2Months;                    
                    $TempTotalByWeeks = 0;
                    $TempWeeks2MonthsB = "";
                    $TempNoLoopByWeeks2Months = 1;
                    foreach (${"ArrTrackingResultMonth$i"} as $TempTrackingResultMonthByWeeks)
                    {
                        $ValTempNoSlave = $TempTrackingResultMonthByWeeks['NoSlave'];
                        $ValTempTotalUsage = $TempTrackingResultMonthByWeeks['TotalUsage'];
                        $ValTempWeeks = $TempTrackingResultMonthByWeeks['Weeks'];
                    
                        if($ValTempWeeks == $ValDataWeeks2Months)
                        {
                            // echo "<br> $ValTempNoSlave >> $ValTempTotalUsage >> $ValTempWeeks";
                            if($TempNoLoopByWeeks2Months == 1)
                            {
                                $TempTotalByWeeks = $ValTempTotalUsage;
                            }
                            else
                            {
                                $TempTotalByWeeks = $TempTotalByWeeks + $ValTempTotalUsage;
                            }
                            $TempNoLoopByWeeks2Months++;
                        }
                    }
                    # simpan ke array
                    $ArrTemp2MonthsByWeek = array(
                        "NoSlave" => $i,
                        "TotalUsage" => $TempTotalByWeeks,
                        "Weeks" => $ValDataWeeks2Months,
                        "Years" => $ValYearWeeks2MonthsName
                    );
                    array_push(${"ArrTrackingResultMonthByWeeks$i"},$ArrTemp2MonthsByWeek);

                }
            // }
        }
        // for ($i=1; $i <= $TotalSlaveMonth; $i++)
        // {
        //     if($i == 1)
        //     {
        //         krsort(${"ArrTrackingResultMonthByWeeks$i"});
        //         echo "<pre>";print_r(${"ArrTrackingResultMonthByWeeks$i"});echo "</pre>";                
        //     }
        // }

    }
    

    ## Last 1 year
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
            
        // for ($x=1; $x <= $TotalSlaveYear; $x++)
        // {
        //     echo "<pre>";print_r(${"ArrTrackingResultYear$x"});echo "</pre>";
        // }
    }
    // # format berdasarkan bulan sekarang samapai 12 bulan ke belakang
    // $ArrDataAwalYear = array();
    // $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    // $LastYear = date("m/d/Y H:i:s",strtotime("-1 year"));
    // # get date
    // $StartTime = date("m/d/Y 00:00:00",strtotime($LastYear));
    // $EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
    // # get data awal
    // $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    // $ValDataRow = mssql_num_rows($QListKWHTracking);
    // if($ValDataRow == 0)
    // {
    //     // echo "Data 1 tahun lalu tidak ditemukan.";
    //     echo "";
    // }
    // else
    // {
    //     while ($RListKWHTracking = mssql_fetch_assoc($QListKWHTracking))
    //     {
    //         $TempNoSlave = $RListKWHTracking['Slave'];
    //         $TempKWH = $RListKWHTracking['KWH'];
    //         $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
    //         $TempUsage = $RListKWHTracking['Usage'];
    //         $ArrTempRow = array(
    //             "Slave" => $TempNoSlave,
    //             "KWH" => $TempKWH,
    //             "DataLog" => $TempDataLog,
    //             "Usage" => $TempUsage
    //         );
    //         array_push($ArrDataAwalYear,$ArrTempRow);
    //     }
    //     // echo "<pre>";print_r($ArrDataAwalYear);echo "</pre>";
    //     # get data list slave
    //     $ArrDataSlaveYear = array();
    //     $VarCheckSlaveYear = "";
    //     foreach ($ArrDataAwalYear as $DataAwal)
    //     {
    //         $VarSlave = $DataAwal['Slave'];
    //         if($VarCheckSlaveYear != $VarSlave)
    //         {
    //             array_push($ArrDataSlaveYear,$VarSlave);
    //             $VarCheckSlaveYear = $VarSlave;
    //         }
    //     }
    //     $TotalSlaveYear = count($ArrDataSlaveYear);
    //     // echo "<pre>";print_r($ArrDataSlaveYear);echo "</pre>";
    //     // echo "Total Slave : ".$TotalSlaveYear;
    //     # pemisahan data berdasarkan slave
    //     for ($i=1; $i <= $TotalSlaveYear; $i++)
    //     {
    //         ${"ArrTrackingYear$i"} = array();
    //         ${"ArrTrackingResultYear$i"} = array();
    //         # pemecahan berdasarkan slave
    //         # pemecahan berdasarkan slave
    //         foreach ($ArrDataAwalYear as $ArrData)
    //         {
    //             $ASlave = $ArrData['Slave'];
    //             $ADataLog = $ArrData['DataLog'];
    //             $ADataLog2 = explode(" ",$ADataLog);
    //             $ADateLog = $ADataLog2[0];
    //             $AMonthLog = date("m",strtotime($ADataLog2[0]));
    //             $AUsage = $ArrData['Usage'];
    //             $ATemp = array(
    //                 "Slave" => $ASlave,
    //                 "DataLog" => $ADataLog,
    //                 "DateLog" => $ADateLog,
    //                 "MonthLog" => $AMonthLog,
    //                 "Usage" => $AUsage
    //             );
    //             array_push(${"ArrTrackingYear$i"},$ATemp);
    //         }
    //         // echo "<pre>";print_r(${"ArrTrackingYear$i"});echo "</pre>";



    //     }
            
    //     // for ($x=1; $x <= $TotalSlaveYear; $x++)
    //     // {
    //     //     echo "<pre>";print_r(${"ArrTrackingResultYear$x"});echo "</pre>";
    //     // }

    //     /*

    //     //$StartTime = date("m/d/Y 00:00:00",strtotime($LastDate1Year));
    //     //$EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));

    //     buat daftar list bulan untk 1 tahun kebelakang
    //     penyesuaian dengan bulan 
        
        
    //     */
    //     ## looping list month
    //     for ($x=1; $x <= $TotalSlaveMonth; $x++)
    //     {
    //         # check data per hari
    //         $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
    //         $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
    //         $TempArrayLoopMonthOneYear = array();
    //         $NoLoop = 1;
    //         $TempMonth = "";
    //         $TempEndDate = date("m/d/Y",strtotime($EndTime));
    //         do
    //         {
    //             # start looping date
    //             $Date = date("m/d/Y",$StartTime1);
    //             $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
    //             $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
    //             echo "<br>".$Date;
    //             $MonthDate = date("m",$StartTime1);
    //             $YearDate = date("m",$StartTime1);
    //             // if($NoLoop == 1)
    //             // {
    //             //     $TempMonth = $MonthDate;
    //             // }
    //             // else
    //             // {
    //             //     //jika tgl terakhir
    //             //     //jika bkn tgl terahur
    //             //     if($Date == $TempEndDate)
    //             //     {
    //             //         if($TempMonth != $MonthDate)
    //             //         {

    //             //         }
    //             //         else
    //             //         {
                            
    //             //         }
    //             //     }
    //             //     else
    //             //     {
    //             //         if($TempMonth != $MonthDate)
    //             //         {

    //             //         }
    //             //         else
    //             //         {
                            
    //             //         }                        
    //             //     }                    
    //             // }



    //             # input date
    //             $TDate = array(
    //                 "DateLoop" => $Date
    //             );
    //             array_push($TempArrayLoopMonthOneYear,$TDate);
                


    //             $NoLoop++;
    //             $StartTime1 = strtotime('+1 day',$StartTime1);
    //         } while($StartTime1 <= $EndTime1);
    //         echo "<br>#######";
    //     }



    // }



    ## data total - 30 days
    $ArrResultMonthB = array();
    $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    $LastMonthB = date("m/d/Y H:i:s",strtotime("-30 days"));
    # get date
    $StartTime = date("m/d/Y 00:00:00",strtotime($LastMonthB));
    $EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
    # get data awal
    $ArrDataAwalMonthB = array();
    // $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_HOUR_ALL_SLAVE($StartTime,$EndTime,$linkHRISWebTrax);
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
        // echo "Data untuk 30 hari sebelumnya tidak ditemukan.";
        echo "";
    }
    else
    {
        //  while ($RListKWHTracking = mssql_fetch_assoc($QListKWHTracking))
        // {
        //     $TempNoSlave = $RListKWHTracking['Slave'];
        //     $TempKWH = $RListKWHTracking['KWH'];
        //     $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
        //     $TempUsage = $RListKWHTracking['Usage'];
        //     $ArrTempRow = array(
        //         "Slave" => $TempNoSlave,
        //         "KWH" => $TempKWH,
        //         "DataLog" => $TempDataLog,
        //         "Usage" => $TempUsage
        //     );
        //     array_push($ArrDataAwalMonthB,$ArrTempRow);
        // }
        // // echo "<pre>";print_r($ArrDataAwalMonthB);echo "</pre>";
        // # get data list slave
        // $ArrDataSlaveMonthB = array();
        // $VarCheckLogMonthB = "";
        // $NoLoopMonthB = 1;
        // foreach ($ArrDataAwalMonthB as $DataAwal)
        // {
        //     $VarSlave = $DataAwal['Slave'];
        //     $VarDataLog = $DataAwal['DataLog'];
        //     $ArrDataLog = explode("#",$VarDataLog);
        //     $ArrDateLog = explode(" ",$ArrDataLog[0]);
        //     $TimeLogMonthB = $ArrDataLog[0];
        //     if($NoLoopMonthB == 1)
        //     {
        //         $VarCheckLogMonthB = $TimeLogMonthB;
        //         array_push($ArrDataSlaveMonthB,$VarSlave);
        //     }
        //     else
        //     {
        //         if($VarCheckLogMonthB == $TimeLogMonthB)
        //         {
        //             array_push($ArrDataSlaveMonthB,$VarSlave);
        //         }
        //     }
        //     $NoLoopMonthB++;
        // }
        // $TotalSlaveMonthB = count($ArrDataSlaveMonthB);
        // // echo "<pre>";print_r($ArrDataSlaveMonthB);echo "</pre>";
        // // echo "Total Slave : ".$TotalSlaveMonthB;
        // # data array tracking per slave
        // $ArrTrackingPerDateMonthB = array();
        // for ($i=1; $i <= $TotalSlaveMonthB; $i++)
        // {
        //     ${"ArrTrackingMonthB$i"} = array();
        //     foreach ($ArrDataAwalMonthB as $DataAwal1)
        //     {
        //         $VarSlave1 = $DataAwal1['Slave'];
        //         $VarKWH1 = $DataAwal1['KWH'];
        //         $VarDataLog1 = $DataAwal1['DataLog'];
        //         $VarUsage1 = $DataAwal1['Usage'];
        //         if($i == $VarSlave1)
        //         {
        //             $TempArrayDataSlave = array(
        //                 "Slave" => $VarSlave1,
        //                 "KWH" => $VarKWH1,
        //                 "DataLog" => $VarDataLog1,
        //                 "Usage" => $VarUsage1
        //             );
        //             array_push(${"ArrTrackingMonthB$i"},$TempArrayDataSlave);
        //         }                          
        //     }
        //     # perhitungan setiap hari
        //     $TempDT = "";
        //     $TempDTTotalUsage = 0;
        //     $NoLoopingTempDT = 1;
        //     $CountRowTempDT = count(${"ArrTrackingMonthB$i"});
        //     foreach (${"ArrTrackingMonthB$i"} as $DT1)
        //     {
        //         $DT1Slave = $DT1['Slave'];
        //         $DT1KWH = $DT1['KWH'];
        //         $DT1DataLog = $DT1['DataLog'];
        //         $DT1Usage = $DT1['Usage'];
        //         $ArrDT1DataLog = explode(" ",$DT1DataLog);
        //         $DateDataLog = $ArrDT1DataLog[0];
                
        //         if($NoLoopingTempDT == 1)
        //         {
        //             $TempDT = $DateDataLog;
        //             $TempDTTotalUsage = $DT1Usage;
        //         }
        //         else
        //         {
        //             if($TempDT == $DateDataLog)
        //             {
        //                 # jika row terakhir
        //                 if($NoLoopingTempDT == $CountRowTempDT)
        //                 {
        //                     $TempDTTotalUsage = $TempDTTotalUsage + $DT1Usage;
        //                     // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
        //                     $TempTrackingPerDate = array(
        //                         'Slave' => $DT1Slave,
        //                         'DateTracking' => $TempDT,
        //                         'TotalUsage' => $TempDTTotalUsage
        //                     );
        //                     array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
        //                 }
        //                 else
        //                 {
        //                     $TempDTTotalUsage = $TempDTTotalUsage + $DT1Usage;
        //                 }
        //             }
        //             else
        //             {

        //                 # jika row terakhir
        //                 if($NoLoopingTempDT == $CountRowTempDT)
        //                 {
        //                     // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
        //                     $TempTrackingPerDate = array(
        //                         'Slave' => $DT1Slave,
        //                         'DateTracking' => $TempDT,
        //                         'TotalUsage' => $TempDTTotalUsage
        //                     );
        //                     array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
        //                 }
        //                 else
        //                 {
        //                     // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
        //                     $TempTrackingPerDate = array(
        //                         'Slave' => $DT1Slave,
        //                         'DateTracking' => $TempDT,
        //                         'TotalUsage' => $TempDTTotalUsage
        //                     );
        //                     array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
        //                     $TempDT = $DateDataLog;
        //                     $TempDTTotalUsage = 0;
        //                     $TempDTTotalUsage = $DT1Usage;
        //                 }
        //             }
        //         }
        //         $NoLoopingTempDT++;
        //     }
        // }
        // // echo "<pre>";print_r($ArrTrackingPerDateMonthB);echo "</pre>";
        // // echo "total : $TotalSlaveMonthB<br>";
        // # penambahan tgl kosong utk tracking per date
        // $ArrTrackingPerDate2MonthB = array();
        // for ($x=1; $x <= $TotalSlaveMonthB; $x++)
        // {
        //     # check data per hari
        //     $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
        //     $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
        //     $TempArrayLoopDateMonthB = array();
        //     do
        //     {
        //         # start looping date
        //         $Date = date("m/d/Y",$StartTime1);
        //         $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
        //         $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
        //         # input date
        //         $TDate = array(
        //             "DateLoop" => $Date
        //         );
        //         array_push($TempArrayLoopDateMonthB,$TDate);
        //         // echo "############<br>";
        //         // echo "Tgl : ".$Date."<br>";

                
        //         $ValCheckDate = FALSE;
        //         # pengecekan nilai usage per jam
        //         foreach ($ArrTrackingPerDateMonthB as $TPD)
        //         {
        //             $TNoSlave = $TPD['Slave'];
        //             $TDateTracking = $TPD['DateTracking'];
        //             $TTotalUsage = $TPD['TotalUsage'];
        //             if(($x == $TNoSlave) && ($Date == $TDateTracking))
        //             {
        //                 $TTempArray = array(
        //                     "Slave" => $TNoSlave,
        //                     "DateTracking" => $TDateTracking,
        //                     "TotalUsage" => $TTotalUsage
        //                 );
        //                 array_push($ArrTrackingPerDate2MonthB,$TTempArray);
        //                 $ValCheckDate = TRUE;
        //                 break;
        //             }
        //         }
        //         if($ValCheckDate == FALSE)
        //         {
        //             $TTempArray = array(
        //                 "Slave" => $x,
        //                 "DateTracking" => $Date,
        //                 "TotalUsage" => "0"
        //             );
        //             array_push($ArrTrackingPerDate2MonthB,$TTempArray);
        //         }
        //         $StartTime1 = strtotime('+1 day',$StartTime1);
        //     } while($StartTime1 <= $EndTime1);
        //         // echo "############<br>";
        // }
        // echo "<pre>";print_r($ArrTrackingPerDate2MonthB);echo "</pre>";
        // # count total usage per hour
        // $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
        // $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
        // $TempTotalUsage = 0;
        // do
        // {
        //     # start looping date
        //     $Date = date("m/d/Y",$StartTime1);
        //     $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
        //     $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
        //     # loop data
        //     foreach($ArrTrackingPerDate2MonthB as $Data2MonthB)
        //     {
        //         $TempDataSlave2MonthB = $Data2MonthB['Slave'];
        //         $TempDataTotalUsage2MonthB = number_format($Data2MonthB['TotalUsage'], 4, '.', '');
        //         $TempDataDate2MonthB =  $Data2MonthB['DateTracking'];
        //         if($TempDataDate2MonthB == $Date)
        //         {
        //             if($TempDataSlave2MonthB != "3")
        //             {
        //                 $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataTotalUsage2MonthB, 4, '.', '');
        //             }
        //             else
        //             {
        //                 $TempTotalUsage = number_format($TempTotalUsage, 4, '.', '') + number_format($TempDataTotalUsage2MonthB, 4, '.', '');
        //                 $TempTotalUsage = number_format($TempTotalUsage, 4, '.,', '');
        //                     # simpan ke array
        //                     $TempResultHour = array(
        //                         "TimeLoop" => $Date,
        //                         "ValLoop" => $TempTotalUsage
        //                     );
        //                     array_push($ArrResultMonthB,$TempResultHour);
        //                 $TempTotalUsage = 0;
        //             }
        //         }
        //     }
        //     $StartTime1 = strtotime('+1 day',$StartTime1);
        // } while($StartTime1 <= $EndTime1);        
        // // echo "<pre>";print_r($ArrResultMonthB);echo "</pre>";

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
            array_push($ArrDataAwalMonthB,$ArrTempRow);
        }
        // echo "<pre>";print_r($ArrDataAwalMonthB);echo "</pre>";
        # get data list slave
        $ArrDataSlaveMonthB = array();
        $VarCheckSlaveMonthB = "";
        foreach ($ArrDataAwalMonthB as $DataAwal)
        {
            $VarSlave = $DataAwal['Slave'];
            if($VarCheckSlaveMonthB != $VarSlave)
            {
                array_push($ArrDataSlaveMonthB,$VarSlave);
                $VarCheckSlaveMonthB = $VarSlave;
            }
        }
        $TotalSlaveMonthB = count($ArrDataSlaveMonthB);
        // echo "<pre>";print_r($ArrDataSlaveMonthB);echo "</pre>";
        // echo "Total Slave : ".$TotalSlaveMonthB;
        # data array tracking per slave
        $ArrTrackingPerDateMonthB = array();
        for ($i=1; $i <= $TotalSlaveMonthB; $i++)
        {
            ${"ArrTrackingMonthB$i"} = array();
            foreach ($ArrDataAwalMonthB as $DataAwal1)
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
                    array_push(${"ArrTrackingMonthB$i"},$TempArrayDataSlave);
                }                          
            }
            # perhitungan setiap hari
            $TempDT = "";
            $TempDTTotalUsage = 0;
            $NoLoopingTempDT = 1;
            $CountRowTempDT = count(${"ArrTrackingMonthB$i"});
            foreach (${"ArrTrackingMonthB$i"} as $DT1)
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
                            array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
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
                            array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
                        }
                        else
                        {
                            // echo "slave : $DT1Slave ".$TempDT." total : $TempDTTotalUsage<br>";
                            $TempTrackingPerDate = array(
                                'NoSlave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
                            $TempDT = $DateDataLog;
                            $TempDTTotalUsage = 0;
                            $TempDTTotalUsage = $DT1Usage;
                        }
                    }
                }
                $NoLoopingTempDT++;
            }
        }
        // echo "<pre>";print_r($ArrTrackingPerDateMonthB);echo "</pre>";
        # penambahan tgl kosong utk tracking per date
        $ArrTrackingPerDate2MonthB = array();
        for ($x=1; $x <= $TotalSlaveMonthB; $x++)
        {
            # check data per hari
            $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
            $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
            $TempArrayLoopDateMonthB = array();
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
                array_push($TempArrayLoopDateMonthB,$TDate);
                // echo "############<br>";
                // echo "Tgl : ".$Date."<br>";

                
                $ValCheckDate = FALSE;
                # pengecekan nilai usage per jam
                foreach ($ArrTrackingPerDateMonthB as $TPD)
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
                        array_push($ArrTrackingPerDate2MonthB,$TTempArray);
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
                    array_push($ArrTrackingPerDate2MonthB,$TTempArray);
                }



                $StartTime1 = strtotime('+1 day',$StartTime1);
            } while($StartTime1 <= $EndTime1);
        }
        // echo "<pre>";print_r($ArrTrackingPerDateMonthB);echo "</pre>";
        // echo "<pre>";print_r($ArrTrackingPerDate2MonthB);echo "</pre>";
        # pemecahan tracking per slave
        for ($i=1; $i <= $TotalSlaveMonthB; $i++)
        {
            ${"ArrTrackingResultMonthB$i"} = array();
            foreach ($ArrTrackingPerDate2MonthB as $ArrResult)
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
                    array_push(${"ArrTrackingResultMonthB$i"},$TArray);
                }
            }
        }
        // for ($i=1; $i <= $TotalSlaveMonthB; $i++)
        // {
        //     if($i == 1)
        //     {
        //         echo "<pre>";print_r(${"ArrTrackingResultMonthB$i"});echo "</pre>";
        //     }
        // }
        
        // echo "<pre>";print_r($TempArrayLoopDateMonthB);echo "</pre>";
        // exit();

    }




?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=3">KWH Tracking : Dashboard</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <script type="text/javascript">
            // google.charts.load('current', {'packages':['bar']});
            // google.charts.setOnLoadCallback(drawChart);
            // function drawChart() {
            //     var data1  = google.visualization.arrayToDataTable([
            //     ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
            //     <?php
            //     foreach($TempArrayLoopDateHours as $LoopHours)
            //     {
            //         $DateLoopHours = $LoopHours['DateLoop'];
            //         $DateLoopHours = substr($DateLoopHours,0,16);
            //         $ArrLoopHour = explode(" ",$DateLoopHours);
            //         $DateLoopHours2 = substr($ArrLoopHour[0],0,5)." (".substr($ArrLoopHour[1],0,5).")";
            //         $DateLoopHours3 = substr($ArrLoopHour[0],0,10)." ".substr($ArrLoopHour[1],0,5)."";
            //         // echo ",['".$DateLoopHours2."',123,121,13]";
            //         echo ",['".$DateLoopHours2."'";
            //         for ($x=1; $x <= $TotalSlaveDay; $x++)
            //         {
            //             if($x != $TotalSlaveDay)
            //             {
            //                 foreach (${"ArrTrackingDay$x"} as $ArrTT)
            //                 {
            //                     $ValUsageTTDay = $ArrTT['Usage'];
            //                     $ValTimeLogDay = $ArrTT['DataLog'];
            //                     $ArrTimeLogDay = explode("#",$ValTimeLogDay);
            //                     // echo ",".$ArrTimeLogDay[0];
            //                     if($DateLoopHours3 == $ArrTimeLogDay[0])
            //                     // if($DateLoopHours3 == $ValTimeLogDay)
            //                     {
            //                         echo ",".number_format((float)$ValUsageTTDay, 4, '.', '')."";
            //                     }
            //                 }
            //             }
            //             else
            //             {
            //                 foreach (${"ArrTrackingDay$x"} as $ArrTT)
            //                 {
            //                     $ValUsageTTDay = $ArrTT['Usage'];
            //                     $ValTimeLogDay = $ArrTT['DataLog'];
            //                     $ArrTimeLogDay = explode("#",$ValTimeLogDay);
            //                     // echo ",".$ArrTimeLogDay[0];
            //                     if($DateLoopHours3 == $ArrTimeLogDay[0])
            //                     // if($DateLoopHours3 == $ValTimeLogDay)
            //                     {
            //                         echo ",".number_format((float)$ValUsageTTDay, 4, '.', '')."]";
            //                     }
            //                 }
            //             }                        
            //         }
            //     }
            //     ?>
            //     ]);
            //     var formatNumber = new google.visualization.NumberFormat({
            //         pattern: '0.0000'
            //     });
            //     formatNumber.format(data1, 1);
            //     formatNumber.format(data1, 2);
            //     formatNumber.format(data1, 3);
            //     var options1 = {
            //         chart: {
            //             title: 'Last 24 Hours'
            //         },
            //         bars: 'horizontal',
            //         height: 900,
            //         groupWidth: "60%",
            //         hAxis: {
            //             title: 'Usage'
            //         }
            //     };
            //     var chart1 = new google.charts.Bar(document.getElementById('GraphDay'));
            //     chart1.draw(data1 , google.charts.Bar.convertOptions(options1));
            // }
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                // Define the chart to be drawn.
                var data1 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                // ,['2012', 900, 390, 155],
                // ['2013', 1000, 400, 555],
                // ['2014', 1170, 440, 200],
                // ['2015', 1250, 480, 500],
                // ['2016', 1530, 540, 523]
                <?php
                krsort($TempArrayLoopDateHours);
                foreach($TempArrayLoopDateHours as $LoopHours)
                {
                    $DateLoopHours = $LoopHours['DateLoop'];
                    $DateLoopHours = substr($DateLoopHours,0,16);
                    $ArrLoopHour = explode(" ",$DateLoopHours);
                    $DateLoopHours2 = substr($ArrLoopHour[0],0,5)." (".substr($ArrLoopHour[1],0,5).")";
                    $DateLoopHours3 = substr($ArrLoopHour[0],0,10)." ".substr($ArrLoopHour[1],0,5)."";
                    // echo ",['".$DateLoopHours2."',123,121,13]";
                    echo ",['".$DateLoopHours2."'";
                    for ($x=1; $x <= $TotalSlaveDay; $x++)
                    {
                        if($x != $TotalSlaveDay)
                        {
                            foreach (${"ArrTrackingDay$x"} as $ArrTT)
                            {
                                $ValUsageTTDay = $ArrTT['Usage'];
                                $ValTimeLogDay = $ArrTT['DataLog'];
                                $ArrTimeLogDay = explode("#",$ValTimeLogDay);
                                // echo ",".$ArrTimeLogDay[0];
                                if($DateLoopHours3 == $ArrTimeLogDay[0])
                                // if($DateLoopHours3 == $ValTimeLogDay)
                                {
                                    echo ",".number_format((float)$ValUsageTTDay, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrTrackingDay$x"} as $ArrTT)
                            {
                                $ValUsageTTDay = $ArrTT['Usage'];
                                $ValTimeLogDay = $ArrTT['DataLog'];
                                $ArrTimeLogDay = explode("#",$ValTimeLogDay);
                                // echo ",".$ArrTimeLogDay[0];
                                if($DateLoopHours3 == $ArrTimeLogDay[0])
                                // if($DateLoopHours3 == $ValTimeLogDay)
                                {
                                    echo ",".number_format((float)$ValUsageTTDay, 4, '.', '')."]";
                                }
                            }
                        }                        
                    }
                }
                ?>
                ]);
                var formatNumber = new google.visualization.NumberFormat({
                    pattern: '0.0000'
                });
                formatNumber.format(data1, 1);
                formatNumber.format(data1, 2);
                formatNumber.format(data1, 3);

                var options1 = {
                    title: 'Last 24 Hours', 
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },
                    chartArea: {top:50,height:"90%",width:"60%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
				};
                var chart1 = new google.visualization.BarChart(document.getElementById('GraphDay'));
                chart1.draw(data1, options1);
            }
            google.charts.setOnLoadCallback(drawChart);
        </script>
        <div id="GraphDay"></div>
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            // google.charts.load('current', {'packages':['bar']});
            // google.charts.setOnLoadCallback(drawChart);
            // function drawChart() {
            //     var data2  = google.visualization.arrayToDataTable([
            //     ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
            //     <?php
            //     foreach($TempArrayLoopDateWeeks as $LoopWeeks)
            //     {
            //         $DateLoopMonth = $LoopWeeks['DateLoop'];
            //         $DateLoopMonth2 = substr($DateLoopMonth,0,5);
            //         echo ",['".$DateLoopMonth2."'";
            //         for ($x=1; $x <= $TotalSlaveWeeks; $x++)
            //         {
            //             if($x != $TotalSlaveWeeks)
            //             {
            //                 foreach (${"ArrTrackingResultWeeks$x"} as $ArrTT)
            //                 {
            //                     $ValUsageTTMonth = $ArrTT['TotalUsage'];
            //                     $ValTimeLogMonth = $ArrTT['DateTracking'];
            //                     if($DateLoopMonth == $ValTimeLogMonth)
            //                     {
            //                         echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."";
            //                     }
            //                 }
            //             }
            //             else
            //             {
            //                 foreach (${"ArrTrackingResultWeeks$x"} as $ArrTT)
            //                 {
            //                     $ValUsageTTMonth = $ArrTT['TotalUsage'];
            //                     $ValTimeLogMonth = $ArrTT['DateTracking'];
            //                     if($DateLoopMonth == $ValTimeLogMonth)
            //                     {
            //                         echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."]";
            //                     }
            //                 }
            //             }                        
            //         }
            //     }
            //     ?>
            //     ]);
            //     var formatNumber = new google.visualization.NumberFormat({
            //         pattern: '0.0000'
            //     });
            //     formatNumber.format(data2, 1);
            //     formatNumber.format(data2, 2);
            //     formatNumber.format(data2, 3);
            //     var options2 = {
            //         chart: {
            //             title: 'Last 2 weeks'
            //         },
            //         bars: 'horizontal',
            //         height: 900,
            //         groupWidth: "60%",
            //         hAxis: {
            //             title: 'Usage'
            //         }
            //     };
            //     var chart2 = new google.charts.Bar(document.getElementById('GraphWeeks'));
            //     chart2.draw(data2 , google.charts.Bar.convertOptions(options2));
            // }
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                // Define the chart to be drawn.
                var data2 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                // ,['2012', 900, 390, 155],
                // ['2013', 1000, 400, 555],
                // ['2014', 1170, 440, 200],
                // ['2015', 1250, 480, 500],
                // ['2016', 1530, 540, 523]
                <?php
                krsort($TempArrayLoopDateWeeks);
                foreach($TempArrayLoopDateWeeks as $LoopWeeks)
                {
                    $DateLoopMonth = $LoopWeeks['DateLoop'];
                    $DateLoopMonth2 = substr($DateLoopMonth,0,5);
                    echo ",['".$DateLoopMonth2."'";
                    for ($x=1; $x <= $TotalSlaveWeeks; $x++)
                    {
                        if($x != $TotalSlaveWeeks)
                        {
                            foreach (${"ArrTrackingResultWeeks$x"} as $ArrTT)
                            {
                                $ValUsageTTMonth = $ArrTT['TotalUsage'];
                                $ValTimeLogMonth = $ArrTT['DateTracking'];
                                if($DateLoopMonth == $ValTimeLogMonth)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrTrackingResultWeeks$x"} as $ArrTT)
                            {
                                $ValUsageTTMonth = $ArrTT['TotalUsage'];
                                $ValTimeLogMonth = $ArrTT['DateTracking'];
                                if($DateLoopMonth == $ValTimeLogMonth)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."]";
                                }
                            }
                        }                        
                    }
                }
                ?>
                ]);
                var formatNumber = new google.visualization.NumberFormat({
                    pattern: '0.0000'
                });
                formatNumber.format(data2, 1);
                formatNumber.format(data2, 2);
                formatNumber.format(data2, 3);

                var options2 = {
                    title: 'Last 2 weeks', 
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },                    
                    chartArea: {top:50,height:"90%",width:"70%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
				};
                var chart2 = new google.visualization.BarChart(document.getElementById('GraphWeeks'));
                chart2.draw(data2, options2);
            }
            google.charts.setOnLoadCallback(drawChart);
        </script>
        <div id="GraphWeeks"></div>   
    </div>
    <div class="col-md-12">&nbsp;</div> 
    <div class="col-md-6">
        <script type="text/javascript">
            // google.charts.load('current', {'packages':['bar']});
            // google.charts.setOnLoadCallback(drawChart);
            // function drawChart() {
            //     var data5  = google.visualization.arrayToDataTable([
            //     ['Date', 'Semua Lantai']
            //     <?php
            //     foreach ($ArrResultMonthB as $ResultMonthB)
            //     {
            //         $ValTimeLoop = $ResultMonthB['TimeLoop'];
            //         $ValLoopMonthB = $ResultMonthB['ValLoop'];                        
            //         echo ',["'.date("m/d/Y",strtotime($ValTimeLoop)).'", '.number_format($ValLoopMonthB, 4, '.', '').']';
            //     }
            //     ?>
            //     ]);
            //     var formatNumber = new google.visualization.NumberFormat({
            //         pattern: '0.0000'
            //     });
            //     formatNumber.format(data5, 1);
            //     var options5 = {
            //         chart: {
            //             title: 'Last 30 Days'
            //         },
            //         bars: 'horizontal',
            //         height: 900,
            //         groupWidth: "60%",
            //         hAxis: {
            //             title: 'Usage'
            //         }
            //     };
            //     var chart5 = new google.charts.Bar(document.getElementById('Graph30Day'));
            //     chart5.draw(data5 , google.charts.Bar.convertOptions(options5));
            // }
            
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data5 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                // ,['2012', 900, 390, 155],
                // ['2013', 1000, 400, 555],
                // ['2014', 1170, 440, 200],
                // ['2015', 1250, 480, 500],
                // ['2016', 1530, 540, 523]
                <?php
                krsort($TempArrayLoopDateMonthB);
                foreach($TempArrayLoopDateMonthB as $LoopMonthB)
                {
                    $DateLoopMonthB = $LoopMonthB['DateLoop'];
                    // $DateLoopMonthB2 = substr($DateLoopMonth,0,5);
                    echo ",['".$DateLoopMonthB."'";
                    for ($x=1; $x <= $TotalSlaveMonthB; $x++)
                    {
                        if($x != $TotalSlaveMonthB)
                        {
                            foreach (${"ArrTrackingResultMonthB$x"} as $ArrTT)
                            {
                                $ValUsageTTMonthB = $ArrTT['TotalUsage'];
                                $ValTimeLogMonthB = $ArrTT['DateTracking'];
                                if($DateLoopMonthB == $ValTimeLogMonthB)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonthB, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrTrackingResultMonthB$x"} as $ArrTT)
                            {
                                $ValUsageTTMonthB = $ArrTT['TotalUsage'];
                                $ValTimeLogMonthB = $ArrTT['DateTracking'];
                                if($DateLoopMonthB == $ValTimeLogMonthB)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonthB, 4, '.', '')."]";
                                }
                            }
                        }                        
                    }
                }
                ?>
                ]);
                var formatNumber = new google.visualization.NumberFormat({
                    pattern: '0.0000'
                });
                formatNumber.format(data5, 1);
                formatNumber.format(data5, 2);
                formatNumber.format(data5, 3);

                var options5 = {
                    title: 'Last 30 Days', 
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },
                    chartArea: {top:50,height:"90%",width:"70%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
                };
                var chart5 = new google.visualization.BarChart(document.getElementById('Graph30Day'));
                chart5.draw(data5, options5);
            }
            google.charts.setOnLoadCallback(drawChart);
        </script>
        <div id="Graph30Day"></div>
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            <?php /*
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data3  = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                <?php
                foreach($TempArrayLoopDateMonth as $LoopMonth)
                {
                    $DateLoopMonth = $LoopMonth['DateLoop'];
                    $DateLoopMonth2 = substr($DateLoopMonth,0,5);
                    echo ",['".$DateLoopMonth2."'";
                    for ($x=1; $x <= $TotalSlaveMonth; $x++)
                    {
                        if($x != $TotalSlaveMonth)
                        {
                            foreach (${"ArrTrackingResultMonth$x"} as $ArrTT)
                            {
                                $ValUsageTTMonth = $ArrTT['TotalUsage'];
                                $ValTimeLogMonth = $ArrTT['DateTracking'];
                                if($DateLoopMonth == $ValTimeLogMonth)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrTrackingResultMonth$x"} as $ArrTT)
                            {
                                $ValUsageTTMonth = $ArrTT['TotalUsage'];
                                $ValTimeLogMonth = $ArrTT['DateTracking'];
                                if($DateLoopMonth == $ValTimeLogMonth)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."]";
                                }
                            }
                        }                        
                    }
                }
                ?>
                ]);
                var formatNumber = new google.visualization.NumberFormat({
                    pattern: '0.0000'
                });
                formatNumber.format(data3, 1);
                formatNumber.format(data3, 2);
                formatNumber.format(data3, 3);
                var options3 = {
                    chart: {
                        title: 'Last 2 months'
                    },
                    bars: 'horizontal',
                    height: 2000,
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    }
                };
                var chart3 = new google.charts.Bar(document.getElementById('GraphMonths'));
                chart3.draw(data3 , google.charts.Bar.convertOptions(options3));
            }
            */?>
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                // Define the chart to be drawn.
                var data3 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                // ,['2012', 900, 390, 155],
                // ['2013', 1000, 400, 555],
                // ['2014', 1170, 440, 200],
                // ['2015', 1250, 480, 500],
                // ['2016', 1530, 540, 523]
                <?php
                // foreach($TempArrayLoopDateMonth as $LoopMonth)
                // {
                //     $DateLoopMonth = $LoopMonth['DateLoop'];
                //     $DateLoopMonth2 = substr($DateLoopMonth,0,5);
                //     echo ",['".$DateLoopMonth2."'";
                //     for ($x=1; $x <= $TotalSlaveMonth; $x++)
                //     {
                //         if($x != $TotalSlaveMonth)
                //         {
                //             foreach (${"ArrTrackingResultMonth$x"} as $ArrTT)
                //             {
                //                 $ValUsageTTMonth = $ArrTT['TotalUsage'];
                //                 $ValTimeLogMonth = $ArrTT['DateTracking'];
                //                 if($DateLoopMonth == $ValTimeLogMonth)
                //                 {
                //                     echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."";
                //                 }
                //             }
                //         }
                //         else
                //         {
                //             foreach (${"ArrTrackingResultMonth$x"} as $ArrTT)
                //             {
                //                 $ValUsageTTMonth = $ArrTT['TotalUsage'];
                //                 $ValTimeLogMonth = $ArrTT['DateTracking'];
                //                 if($DateLoopMonth == $ValTimeLogMonth)
                //                 {
                //                     echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."]";
                //                 }
                //             }
                //         }                        
                //     }
                // }

                

                krsort($ArrWeeks2Months);
                foreach($ArrWeeks2Months as $DataLoopWeeks2Months)
                {
                    $ValWeeks2Months = $DataLoopWeeks2Months['Weeks'];
                    $ValYearsWeeks2Months = $DataLoopWeeks2Months['Years'];
                    $ValTextWeeks2Months = "Wk. ".(int)$ValWeeks2Months." (".$ValYearsWeeks2Months.")";
                    echo ",['".$ValTextWeeks2Months."'";
                    for ($x=1; $x <= $TotalSlaveMonth; $x++)
                    {
                        if($x != $TotalSlaveMonth)
                        {
                            foreach (${"ArrTrackingResultMonthByWeeks$x"} as $ArrTT)
                            {
                                $ValUsageTTMonth = $ArrTT['TotalUsage'];
                                $ValTimeLogMonth = $ArrTT['Weeks'];
                                if($ValWeeks2Months == $ValTimeLogMonth)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrTrackingResultMonthByWeeks$x"} as $ArrTT)
                            {
                                $ValUsageTTMonth = $ArrTT['TotalUsage'];
                                $ValTimeLogMonth = $ArrTT['Weeks'];
                                if($ValWeeks2Months == $ValTimeLogMonth)
                                {
                                    echo ",".number_format((float)$ValUsageTTMonth, 4, '.', '')."]";
                                }
                            }
                        }
                    }
                }



                ?>
                ]);
                var formatNumber = new google.visualization.NumberFormat({
                    pattern: '0.0000'
                });
                formatNumber.format(data3, 1);
                formatNumber.format(data3, 2);
                formatNumber.format(data3, 3);
                var options3 = {
                    title: 'Last 2 months', 
                    // groupWidth: "60%",
                    // hAxis: {
                    //     title: 'Usage'
                    // },
                    // vAxis: {
                    //     textStyle: {
                    //         fontSize: 12,
                    //         marginTop: '10'
                    //     }
                    // },
                    // height: 1000,
                    // legend: { position: 'top', maxLines: 3 },
                    // chartArea: {top:50,height:"90%",width:"80%"},
                    // bar: { groupWidth: '75%' },
                    groupWidth: "30%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },
                    chartArea: {top:50,height:"90%",width:"70%"},
                    bar: { groupWidth: '50%' },
                    isStacked:true};
                var chart3 = new google.visualization.BarChart(document.getElementById('GraphMonths'));
                chart3.draw(data3, options3);
            }
            google.charts.setOnLoadCallback(drawChart);
        </script>
        <div id="GraphMonths"></div> 
    </div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-6">
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data4 = google.visualization.arrayToDataTable([
                ['Month', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                <?php
                for($year=12;$year > 0;$year--)
                {
                    if($year<10){$year = "0".$year;}
                    $ValMonthYearsName = date("M",strtotime($year."/01/".date("Y"))); 
                    echo ",['".$ValMonthYearsName."'";
                    for ($x=1; $x <= $TotalSlaveYear; $x++)
                    {
                        if($x != $TotalSlaveYear)
                        {
                            foreach (${"ArrTrackingResultYear$x"} as $ArrTT)
                            {
                                $ValUsageTTYear = $ArrTT['TotalCount'];
                                $ValMOnthLogYear = $ArrTT['MonthLog'];
                                if($year == $ValMOnthLogYear)
                                {
                                    echo ",".number_format((float)$ValUsageTTYear, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrTrackingResultYear$x"} as $ArrTT)
                            {
                                $ValUsageTTYear = $ArrTT['TotalCount'];
                                $ValMOnthLogYear = $ArrTT['MonthLog'];
                                if($year == $ValMOnthLogYear)
                                {
                                    echo ",".number_format((float)$ValUsageTTYear, 4, '.', '')."]";
                                }
                            }
                        }                        
                    }
                }
                ?>
                ]);
                var formatNumber = new google.visualization.NumberFormat({
                    pattern: '0.0000'
                });
                formatNumber.format(data4, 1);
                formatNumber.format(data4, 2);
                formatNumber.format(data4, 3);

                var options4 = {
                    title: 'Last 1 year', 
                    groupWidth: "30%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },
                    chartArea: {top:50,height:"90%",width:"80%"},
                    bar: { groupWidth: '50%' },
                    isStacked:true
				};
                var chart = new google.visualization.BarChart(document.getElementById('GraphYear'));
                chart.draw(data4, options4);
            }
            google.charts.setOnLoadCallback(drawChart);


        </script>
        <div id="GraphYear"></div>
    </div>


</div> 

