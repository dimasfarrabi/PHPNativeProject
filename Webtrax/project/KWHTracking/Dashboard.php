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

    ## data chart Last 24 Hours
    $DateNow = date("m/d/Y H:i:s");
    $Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
    $DateNowLabel = date("m/d/Y");
    $YesterdayLabel = date("m/d/Y",strtotime("-1 day"));

    $StartTime = date("m/d/Y H:00:00",strtotime($Yesterday));
    $EndTime = date("m/d/Y H:00:00",strtotime($DateNow));

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
                        "TotalUsage" => $ValotalUsage
                    );
                    array_push(${"ArrTrackingResultMonth$i"},$TArray);
                }
            }
        }
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
        // echo "Data untuk 30 hari sebelumnya tidak ditemukan.";
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
        // for ($i=1; $i <= $TotalSlaveMonthB; $i++)
        // {
            // if($i == 1)
            // {
                // echo "<pre>";print_r(${"ArrTrackingResultMonthB$i"});echo "</pre>";
            // }
        // }
    }

?>
<?php //<script src="./../microplate/js/jquery.min.js" type="text/javascript"></script> ?>
<script src="./../microplate/js/highstock.js" type="text/javascript"></script>
<script src="./../microplate/js/highcharts.js" type="text/javascript"></script>
<script src="./../microplate/js/exporting.js"></script>
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
                            foreach ($ArrTrackingDay1 as $ArrTrack)
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
                    for ($x=1; $x <= $TotalSlaveDay; $x++)
                    {
                        if($x == 1){$SlaveNameDay = "Lantai 2";}
                        elseif($x == 2){$SlaveNameDay = "Lantai 1";}
                        elseif($x == 3){$SlaveNameDay = "Lantai Dasar";}
                        if($x == 1)
                        {
                            ?>
                            {
                            name: '<?php echo $SlaveNameDay; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingDay$x"} as $ArrTT)
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
                            name: '<?php echo $SlaveNameDay; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingDay$x"} as $ArrTT)
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
    </div>
    <div class="col-md-6">
        <script type="text/javascript">  
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'containerChart2',
                        defaultSeriesType: 'line'
                    },
                    xAxis: {
                        title: {
                            text: ''
                        },
                        categories: [<?php
                            $NoListDateRange = 1;
                            foreach ($TempArrayLoopDateWeeks as $Dates)
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
                    for ($x=1; $x <= $TotalSlaveWeeks; $x++)
                    {
                        if($x == 1){$SlaveNameWeeks = "Lantai 2";}
                        elseif($x == 2){$SlaveNameWeeks = "Lantai 1";}
                        elseif($x == 3){$SlaveNameWeeks = "Lantai Dasar";}
                        if($x == 1)
                        {
                            ?>
                            {
                            name: '<?php echo $SlaveNameWeeks; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultWeeks$x"} as $ArrTT)
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
                            name: '<?php echo $SlaveNameWeeks; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultWeeks$x"} as $ArrTT)
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
            <div id="containerChart2" style="height: 500px;"></div>
    </div>
    <div class="col-md-12">&nbsp;</div>
	<div class="col-md-6">
        <script type="text/javascript">  
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'containerChart5',
                        defaultSeriesType: 'line'
                    },
                    xAxis: {
                        title: {
                            text: ''
                        },
                        categories: [<?php
                            $NoListDateRange = 1;
                            foreach ($TempArrayLoopDateMonthB as $Dates)
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
                            enabled: false
                        }
                    },
                    title: {
                        text: 'Last 30 Days'
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
                    for ($x=1; $x <= $TotalSlaveMonthB; $x++)
                    {
                        if($x == 1){$SlaveNameMonthB = "Lantai 2";}
                        elseif($x == 2){$SlaveNameMonthB = "Lantai 1";}
                        elseif($x == 3){$SlaveNameMonthB = "Lantai Dasar";}
                        if($x == 1)
                        {
                            ?>
                            {
                            name: '<?php echo $SlaveNameMonthB; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultMonthB$x"} as $ArrTT)
                            {
                                $ValUsageTTMonthB = $ArrTT['TotalUsage'];
                                $ValUsageTTMonthB = number_format((float)$ValUsageTTMonthB, 4, '.', '');
                                if($No == 1)
                                {
                                    echo "".$ValUsageTTMonthB."";
                                }
                                else
                                {
                                    echo ",".$ValUsageTTMonthB."";
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
                            name: '<?php echo $SlaveNameMonthB; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultMonthB$x"} as $ArrTT)
                            {
                                $ValUsageTTMonthB = $ArrTT['TotalUsage'];
                                $ValUsageTTMonthB = number_format((float)$ValUsageTTMonthB, 4, '.', '');
                                if($No == 1)
                                {
                                    echo "".$ValUsageTTMonthB."";
                                }
                                else
                                {
                                    echo ",".$ValUsageTTMonthB."";
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
        <div id="containerChart5" style="height: 500px;"></div>
    </div>
	<div class="col-md-6">
        <script type="text/javascript">  
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'containerChart3',
                        defaultSeriesType: 'line'
                    },
                    xAxis: {
                        title: {
                            text: ''
                        },
                        categories: [<?php
                            $NoListDateRange = 1;
                            foreach ($TempArrayLoopDateMonth as $Dates)
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
                    for ($x=1; $x <= $TotalSlaveMonth; $x++)
                    {
                        if($x == 1){$SlaveNameMonth = "Lantai 2";}
                        elseif($x == 2){$SlaveNameMonth = "Lantai 1";}
                        elseif($x == 3){$SlaveNameMonth = "Lantai Dasar";}
                        if($x == 1)
                        {
                            ?>
                            {
                            name: '<?php echo $SlaveNameMonth; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultMonth$x"} as $ArrTT)
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
                            name: '<?php echo $SlaveNameMonth; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultMonth$x"} as $ArrTT)
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
        <div id="containerChart3" style="height: 500px;"></div>
    </div>    
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-6">
        <script type="text/javascript">  
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'containerChart4',
                        defaultSeriesType: 'line'
                    },
                    xAxis: {
                        title: {
                            text: ''
                        },
                        categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Des'],
                        labels: {
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
                        },
                        line:{
                            dataLabels: {
                                enabled: false,
                            }
                        }
                    },
                    series: [
                    <?php
                    for ($x=1; $x <= $TotalSlaveYear; $x++)
                    {
                        if($x == 1){$SlaveNameYear = "Lantai 2";}
                        elseif($x == 2){$SlaveNameYear = "Lantai 1";}
                        elseif($x == 3){$SlaveNameYear = "Lantai Dasar";}
                        if($x == 1)
                        {
                            ?>
                            {
                            name: '<?php echo $SlaveNameYear; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultYear$x"} as $ArrTT)
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
                            name: '<?php echo $SlaveNameYear; ?>',
                            data: [<?php
                            $No = 1; 
                            foreach (${"ArrTrackingResultYear$x"} as $ArrTT)
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
        <div id="containerChart4" style="height: 500px;"></div>
	</div>
</div> 
<script type="text/javascript">
    Highcharts.theme = { colors: ['#4572A7'] };
    var highchartsOptions = Highcharts.getOptions(); 
</script>  


