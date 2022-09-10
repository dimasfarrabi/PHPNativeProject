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
        # data array tracking per slave
        for ($i=1; $i <= $TotalSlaveDay; $i++)
        {
            ${"ArrTrackingDay$i"} = array();
            foreach($TempArrayLoopDateHours as $ArrLoopDateHours)
            {
                $ValDateTimeLoop = trim($ArrLoopDateHours['DateLoop']);
                $ValDateTimeLoop2 = substr($ValDateTimeLoop,0,16);
                # penambahan data kosong ke array
                $TTempArray = array(
                    "Slave" => $i,
                    "KWH" => "0",
                    "DataLog" => $ValDateTimeLoop2."#0#0",
                    "Usage" => "0"
                );
                array_push(${"ArrTrackingDay$i"},$TTempArray);
            }
        }
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
        # data array tracking per slave
        for ($i=1; $i <= $TotalSlaveDay; $i++)
        {
            ${"ArrTrackingDay$i"} = array();
            // echo "<br>============";
            foreach($TempArrayLoopDateHours as $ArrLoopDateHours)
            {
                $ValDateTimeLoop = trim($ArrLoopDateHours['DateLoop']);
                $ValDateTimeLoop2 = substr($ValDateTimeLoop,0,16);
                $ValCheckDate = FALSE;
                $ValTempSlaveDateHours = "";
                $ValTempDataLogDateHours = "";
                # pengecekan nilai usage per jam
                foreach ($ArrDataAwalDay as $DataAwal1)
                {
                    $VarSlave1 = $DataAwal1['Slave'];
                    $VarKWH1 = $DataAwal1['KWH'];
                    $VarDataLog1 = $DataAwal1['DataLog'];
                    $VarUsage1 = $DataAwal1['Usage'];
                    $ArrTempDataLog = explode("#",$VarDataLog1);
                    if($i == $VarSlave1)
                    {
                        $ValTempSlaveDateHours = $VarSlave1;
                        $ValTempDataLogDateHours = "#".$ArrTempDataLog[1]."#".$ArrTempDataLog[2];
                    }
                    if($i == $VarSlave1 && trim($ArrTempDataLog[0]) == trim($ValDateTimeLoop2))
                    {
                        $TempArrayDataSlave = array(
                            "Slave" => $VarSlave1,
                            "KWH" => $VarKWH1,
                            "DataLog" => $VarDataLog1,
                            "Usage" => $VarUsage1
                        );
                        array_push(${"ArrTrackingDay$i"},$TempArrayDataSlave);
                        $ValCheckDate = TRUE;
                    }                          
                }                
                if($ValCheckDate == FALSE)  # jika tidak ada data yg sama dgn jam terpilih
                {
                    # penambahan data kosong ke array
                    $TTempArray = array(
                        "Slave" => $ValTempSlaveDateHours,
                        "KWH" => "0",
                        "DataLog" => $ValDateTimeLoop2.$ValTempDataLogDateHours,
                        "Usage" => "0"
                    );
                    array_push(${"ArrTrackingDay$i"},$TTempArray);
                }
            }
        } 
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
                            $TempTrackingPerDate = array(
                                'NoSlave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateWeeks,$TempTrackingPerDate);
                        }
                        else
                        {
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
                            $TempTrackingPerDate = array(
                                'NoSlave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonth,$TempTrackingPerDate);
                        }
                        else
                        {
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
        # loop weeks
        $NoWeeks2Months = 1;
        $ArrWeeks2Months = array();
        $TempWeeks2Months = "";
        $CountArrayLoopWeekMonths = count($TempArrayLoopDateMonth);
        foreach ($TempArrayLoopDateMonth as $WeekMonths) {
            $Weeks2MonthsName = date("W",strtotime($WeekMonths['DateLoop']));
            $ValYearWeeks2MonthsName = date("Y",strtotime($WeekMonths['DateLoop']));
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
        # penjumlahan berdasarkan week
        for ($i=1; $i <= $TotalSlaveMonth; $i++)
        {
            ${"ArrTrackingResultMonthByWeeks$i"} = array();
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
        }
    }
    

    ## Last 1 year
    # format berdasarkan bulan sekarang samapai 12 bulan ke belakang
    $ArrDataAwalYear = array();
    $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    $LastYear = date("m/d/Y H:i:s",strtotime("-1 year"));
    # get date
    $StartTime = date("m/d/Y 00:00:00",strtotime($LastYear));
    $EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
    # get data awal
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
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
        # pemisahan data berdasarkan slave
        for ($i=1; $i <= $TotalSlaveYear; $i++)
        {
            ${"ArrTrackingYear$i"} = array();
            # pemecahan berdasarkan slave
            foreach ($ArrDataAwalYear as $ArrData)
            {
                $ASlave = $ArrData['Slave'];
                $ADataLog = $ArrData['DataLog'];
                $ADataLog2 = explode(" ",$ADataLog);
                $ADateLog = $ADataLog2[0];
                $AMonthLog = date("m",strtotime($ADataLog2[0]));
                $AUsage = $ArrData['Usage'];
                $AMonthYear = date("m-Y",strtotime($ADataLog2[0]));
                $ATemp = array(
                    "Slave" => $ASlave,
                    "DataLog" => $ADataLog,
                    "DateLog" => $ADateLog,
                    "MonthLog" => $AMonthLog,
                    "Usage" => $AUsage,
                    "MonthYear" => $AMonthYear
                );
                array_push(${"ArrTrackingYear$i"},$ATemp);
            }
        }
        # looping list month
        $TempArrayLoopMonthOneYear = array();
        function CheckInArray($Val,$Array)
        {
            foreach ($Array as $DataArray) {
                if($DataArray['MonthYear'] == trim($Val))
                {
                    return "1";
                }
            }
            return "0";
        }
        $NoLoop = 1;    
        $TempMonth = "";
        for ($x=1; $x <= $TotalSlaveYear; $x++)
        {
            # check data per hari
            $StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
            $EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
            $TempEndDate = date("m/d/Y",strtotime($EndTime));
            do
            {
                # start looping date
                $Date = date("m/d/Y",$StartTime1);
                $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
                $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));
                $MonthDate = date("m",$StartTime1);
                $YearDate = date("Y",$StartTime1);
                $MonthYear = $MonthDate."-".$YearDate;
                if($NoLoop == 1)
                {
                    $CountMatch = CheckInArray($MonthYear,$TempArrayLoopMonthOneYear);
                    if($CountMatch == "0")
                    {
                        # simpan ke array
                        $TMonthYear = array(
                            "MonthYear" => $MonthYear
                        );
                        array_push($TempArrayLoopMonthOneYear,$TMonthYear);
                        # simpan ke temporary
                        $TempMonth = $MonthYear;
                    }
                }
                else
                {
                    if($Date == $TempEndDate) ## jika tgl terakhir
                    {
                        if($TempMonth != $MonthYear)
                        {
                            # check sudah pernah disimpan atau belum                            
                            $CountMatch = CheckInArray($MonthYear,$TempArrayLoopMonthOneYear);
                            if($CountMatch == "0")
                            {
                                # simpan ke array
                                $TMonthYear = array(
                                    "MonthYear" => $MonthYear
                                );
                                array_push($TempArrayLoopMonthOneYear,$TMonthYear);
                                # simpan ke temporary
                                $TempMonth = $MonthYear; 
                            }
                        }
                        else
                        {
                            # check sudah pernah disimpan atau belum                            
                            $CountMatch = CheckInArray($MonthYear,$TempArrayLoopMonthOneYear);
                            if($CountMatch == "0")
                            {
                                # simpan ke array
                                $TMonthYear = array(
                                    "MonthYear" => $MonthYear
                                );
                                array_push($TempArrayLoopMonthOneYear,$TMonthYear);
                            }
                        }
                    }
                    else    ## selain tgl terakhir
                    {
                        if($TempMonth != $MonthYear)
                        {
                            # check sudah pernah disimpan atau belum
                            $CountMatch = CheckInArray($MonthYear,$TempArrayLoopMonthOneYear);
                            if($CountMatch == "0")
                            {
                                # simpan ke array
                                $TMonthYear = array(
                                    "MonthYear" => $MonthYear
                                );
                                array_push($TempArrayLoopMonthOneYear,$TMonthYear);
                                # simpan ke temporary
                                $TempMonth = $MonthYear;
                            }
                        }                     
                    }                    
                }
                $NoLoop++;
                $StartTime1 = strtotime('+1 day',$StartTime1);
            } while($StartTime1 <= $EndTime1);
        }
        # counting pre slave and month year
        for ($i=1; $i <= $TotalSlaveYear; $i++)
        {
            ${"ArrCountTrackingYear$i"} = array();
            foreach ($TempArrayLoopMonthOneYear as $ArrMonthYear)
            {
                $ValMonthYear = $ArrMonthYear['MonthYear'];
                $TempSlaveMonthYear = $i;
                $TempCountMonthYear = 0;
                $RowCountUsage = 0;
                foreach (${"ArrTrackingYear$i"} as $ArrTrackingYear)
                {
                    $RowCountUsage = $ArrTrackingYear['Usage'];                    
                    $RowMonthYear = $ArrTrackingYear['MonthYear'];                 
                    $RowSlaveMonthYear = $ArrTrackingYear['Slave'];
                    if($RowMonthYear == $ValMonthYear && $RowSlaveMonthYear == $i)
                    {
                        $TempCountMonthYear = $TempCountMonthYear + $RowCountUsage;
                    }
                }
                # simpan dalam array
                $ArrTemp = array(
                    "Slave" => $i,
                    "MonthYear" => $ValMonthYear,
                    "Usage" => $TempCountMonthYear
                );
                array_push(${"ArrCountTrackingYear$i"},$ArrTemp);

            }
        }
    }
    ## data total - 30 days
    $ArrResultMonthB = array();
    $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    $LastMonthB = date("m/d/Y H:i:s",strtotime("-30 days"));
    # get date
    $StartTime = date("m/d/Y 00:00:00",strtotime($LastMonthB));
    $EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
    # get data awal
    $ArrDataAwalMonthB = array();
    $QListKWHTracking = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V2($StartTime,$EndTime,$linkHRISWebTrax);
    $ValDataRow = mssql_num_rows($QListKWHTracking);
    if($ValDataRow == 0)
    {
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
            array_push($ArrDataAwalMonthB,$ArrTempRow);
        }
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
                            $TempTrackingPerDate = array(
                                'NoSlave' => $DT1Slave,
                                'DateTracking' => $TempDT,
                                'TotalUsage' => $TempDTTotalUsage
                            );
                            array_push($ArrTrackingPerDateMonthB,$TempTrackingPerDate);
                        }
                        else
                        {
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
    }
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=9">Electricy Usage : Site 3 (Salatiga New)</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <script type="text/javascript">           
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data1 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                <?php
                krsort($TempArrayLoopDateHours);
                foreach($TempArrayLoopDateHours as $LoopHours)
                {
                    $DateLoopHours = $LoopHours['DateLoop'];
                    $DateLoopHours = substr($DateLoopHours,0,16);
                    $ArrLoopHour = explode(" ",$DateLoopHours);
                    $DateLoopHours2 = substr($ArrLoopHour[0],0,5)." (".substr($ArrLoopHour[1],0,5).")";
                    $DateLoopHours3 = substr($ArrLoopHour[0],0,10)." ".substr($ArrLoopHour[1],0,5)."";
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
                                if($DateLoopHours3 == $ArrTimeLogDay[0])
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
                                if($DateLoopHours3 == $ArrTimeLogDay[0])
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
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data2 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
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
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data5 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                <?php
                krsort($TempArrayLoopDateMonthB);
                foreach($TempArrayLoopDateMonthB as $LoopMonthB)
                {
                    $DateLoopMonthB = $LoopMonthB['DateLoop'];
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
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data3 = google.visualization.arrayToDataTable([
                ['Date', 'Lantai 2', 'Lantai 1', 'Lantai Dasar']
                <?php
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
                krsort($TempArrayLoopMonthOneYear);
                foreach ($TempArrayLoopMonthOneYear as $ResArrayLoopMonthOneYear)
                {
                    $ArrValMonthYear = $ResArrayLoopMonthOneYear['MonthYear'];
                    $ArrValMonthYear2 = $ArrValMonthYear;
                    $ArrValMonthYear2 = explode("-",$ArrValMonthYear2);
                    $ValMonth = $ArrValMonthYear2[0];
                    $ValYear = $ArrValMonthYear2[1];
                    $ValMonthYearsName = date("M",strtotime($ValMonth."/01/".$ValYear))." ".$ValYear;
                    echo ",['".$ValMonthYearsName."'";
                    for ($x=1; $x <= $TotalSlaveMonth; $x++)
                    {
                        if($x != $TotalSlaveYear)
                        {
                            foreach (${"ArrCountTrackingYear$x"} as $ArrTT)
                            {
                                $ValUsageTTYear = $ArrTT['Usage'];
                                $ValMonthLogYear = $ArrTT['MonthYear'];
                                $ValSlaveMonthLogYear = $ArrTT['Slave'];
                                if($ArrValMonthYear == $ValMonthLogYear && $ValSlaveMonthLogYear == $x)
                                {
                                    echo ",".number_format((float)$ValUsageTTYear, 4, '.', '')."";
                                }
                            }
                        }
                        else
                        {
                            foreach (${"ArrCountTrackingYear$x"} as $ArrTT)
                            {
                                $ValUsageTTYear = $ArrTT['Usage'];
                                $ValMonthLogYear = $ArrTT['MonthYear'];
                                $ValSlaveMonthLogYear = $ArrTT['Slave'];
                                if($ArrValMonthYear == $ValMonthLogYear && $ValSlaveMonthLogYear == $x)
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

