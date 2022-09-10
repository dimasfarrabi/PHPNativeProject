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


####################################################################
# get list slave
$ArrListDataSlave2 = array();
$QListSlave = GET_LIST_SLAVE_V2("FI",$linkHRISWebTrax);
$NoListSlave = 1;
while($RListSlave = mssql_fetch_assoc($QListSlave))
{
    $ValSlave = trim($RListSlave['Slave']);
    $ArrTempListSlave = array(
        "No" => $NoListSlave,
        "Slave" => $ValSlave
    );
    array_push($ArrListDataSlave2,$ArrTempListSlave);
    $NoListSlave++;
}
$TotalSlaveAll = count($ArrListDataSlave2);

##########################
## Last 2 weeks
$Yesterday = date("m/d/Y",strtotime("-1 day"));
$LastWeek = date("m/d/Y",strtotime("-2 weeks"));
# get date
$StartTime = date("m/d/Y",strtotime($LastWeek));
$EndTime = date("m/d/Y",strtotime($Yesterday));
// $StartTime = "04/23/2020";
// $EndTime = "05/07/2020";
# get list date
$StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
$EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
$TempArrayLoopDate2Weeks = array();
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
    array_push($TempArrayLoopDate2Weeks,$TDate);
    $StartTime1 = strtotime('+1 day',$StartTime1);
} while($StartTime1 <= $EndTime1);
# check row data
$QData2Weeks = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V3($StartTime,$EndTime,"FI",$linkHRISWebTrax);
if(mssql_num_rows($QData2Weeks) != "0") # kondisi data ditemukan
{
    $ArrDataAwal2Weeks = array();
    while($RData2Weeks = mssql_fetch_assoc($QData2Weeks))
    {
        $ValSlaveData2Weeks = trim($RData2Weeks['Slave']);
        $ValDatetimeData2Weeks = trim($RData2Weeks['DatetimeLog2']);
        $ValKWHData2Weeks = trim($RData2Weeks['KWH']);
        $ValUsageData2Weeks = trim($RData2Weeks['Usage']);
        $ValLocationData2Weeks = trim($RData2Weeks['Location']);
        $ArrTempRow = array(
            "Slave" => $ValSlaveData2Weeks,
            "KWH" => $ValKWHData2Weeks,
            "DataLog" => $ValDatetimeData2Weeks,
            "Usage" => $ValUsageData2Weeks
        );
        array_push($ArrDataAwal2Weeks,$ArrTempRow);
    }
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"ArrTrackingPerDate2Weeks2$i"} = array();
        foreach ($TempArrayLoopDate2Weeks as $LoopDate)
        {
            $ValLoopDate = $LoopDate['DateLoop'];            
            $BoolLoop2WeeksRes = FALSE;
            foreach ($ArrDataAwal2Weeks as $ArrDataAwal2Weeks2)
            {
                $ValArrDataAwal2Weeks2Slave = trim($ArrDataAwal2Weeks2['Slave']);
                $ValArrDataAwal2Weeks2KWH = trim($ArrDataAwal2Weeks2['KWH']);
                $ValArrDataAwal2Weeks2DataLog = trim($ArrDataAwal2Weeks2['DataLog']);
                $ValArrDataAwal2Weeks2Usage = trim($ArrDataAwal2Weeks2['Usage']);
                
                if($NameListSlave == $ValArrDataAwal2Weeks2Slave && $ValLoopDate == $ValArrDataAwal2Weeks2DataLog)
                {
                    $TTempArray = array(
                        "NoSlave" => $NoListSlave,
                        "Slave" => $NameListSlave,
                        "DateTracking" => $ValLoopDate,
                        "TotalUsage" => $ValArrDataAwal2Weeks2Usage
                    );
                    array_push(${"ArrTrackingPerDate2Weeks2$i"},$TTempArray);
                    $BoolLoop2WeeksRes = TRUE;
                }
            }
            if($BoolLoop2WeeksRes == FALSE)
            {
                $TTempArray = array(
                    "NoSlave" => $NoListSlave,
                    "Slave" => $NameListSlave,
                    "DateTracking" => $ValLoopDate,
                    "TotalUsage" => "0"
                );
                array_push(${"ArrTrackingPerDate2Weeks2$i"},$TTempArray);
            }
        }
        $i++;
    }
    # data array baru
    for($i = 1; $i <= $TotalSlaveAll; $i++)
    {
        ${"NewArrTrackingLast2Weeks$i"} = array();
        $NoLoop2Weeks = 1;
        $ValTempLoopData = "";
	    foreach (${"ArrTrackingPerDate2Weeks2$i"} as $ArrResult)
        {
            $ValNoSlave = $ArrResult['NoSlave'];
            $ValSlave = $ArrResult['Slave'];
            $ValDateTracking = $ArrResult['DateTracking'];
            $ValTotalUsage = $ArrResult['TotalUsage'];
            if($NoLoop2Weeks == 1)
            {
                $TempArray = array(
                    "NoSlave" => $ValNoSlave,
                    "Slave" => $ValSlave,
                    "DateTracking" => $ValDateTracking,
                    "TotalUsage" => $ValTotalUsage,
                    "NewUsage" => "0"
                );
                array_push(${"NewArrTrackingLast2Weeks$i"},$TempArray);
                $ValTempLoopData = $ValTotalUsage;
            }
            else
            {
                $ValTempLoopData = $ValTotalUsage - $ValTempLoopData;
                if(($ValTempLoopData == $ValTotalUsage))
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage,
                        "NewUsage" => "0"
                    );
                    array_push(${"NewArrTrackingLast2Weeks$i"},$TempArray);
                }
                else
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage,
                        "NewUsage" => $ValTempLoopData
                    );
                    array_push(${"NewArrTrackingLast2Weeks$i"},$TempArray);
                }
                $ValTempLoopData = $ValTotalUsage;
            }
            $NoLoop2Weeks++;

        }
    }
}
else # kondisi data kosong
{
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"NewArrTrackingLast2Weeks$i"} = array();
        foreach ($TempArrayLoopDate2Weeks as $LoopDate)
        {
            $ValLoopDate = $LoopDate['DateLoop'];
            $TTempArray = array(
                "NoSlave" => $NoListSlave,
                "Slave" => $NameListSlave,
                "DateTracking" => $ValLoopDate,
                "TotalUsage" => "0",
			    "NewUsage" => "0"
            );
            array_push(${"NewArrTrackingLast2Weeks$i"},$TTempArray);
        }
        $i++;
    }
}

##########################
## Last 30 days
$LastMonth = date("m/d/Y H:i:s",strtotime("-30 days"));
$Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
// $LastMonth = "06/07/2021";
// $Yesterday = "07/08/2021";
// # check data per hari
$StartTime1 = strtotime(date('Y-m-d',strtotime($LastMonth)));
$EndTime1 = strtotime(date('Y-m-d', strtotime($Yesterday)));
$TempArrayLoopDateOneMonth = array();
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
    array_push($TempArrayLoopDateOneMonth,$TDate);
    $StartTime1 = strtotime('+1 day',$StartTime1);
} while($StartTime1 <= $EndTime1);
# check row data
$QListKWHTrackingOneMonth = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V3($LastMonth,$Yesterday,"FI",$linkHRISWebTrax);
if(mssql_num_rows($QListKWHTrackingOneMonth) != "0") # kondisi data ditemukan
{
    $ArrDataAwalOneMonth = array();
    while ($RListKWHTrackingOneMonth = mssql_fetch_assoc($QListKWHTrackingOneMonth))
    {
        $ValSlaveDataOneMonth = trim($RListKWHTrackingOneMonth['Slave']);
        $ValDatetimeDataOneMonth = trim($RListKWHTrackingOneMonth['DatetimeLog2']);
        $ValKWHDataOneMonth = trim($RListKWHTrackingOneMonth['KWH']);
        $ValUsageDataOneMonth = trim($RListKWHTrackingOneMonth['Usage']);
        $ValLocationDataOneMonth = trim($RListKWHTrackingOneMonth['Location']);
        $ArrTempRow = array(
            "Slave" => $ValSlaveDataOneMonth,
            "KWH" => $ValKWHDataOneMonth,
            "DataLog" => $ValDatetimeDataOneMonth,
            "Usage" => $ValUsageDataOneMonth
        );
        array_push($ArrDataAwalOneMonth,$ArrTempRow);
    }
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"ArrTrackingPerDateOneMonth2$i"} = array();
        foreach ($TempArrayLoopDateOneMonth as $LoopDate)
        {
            $ValLoopDate = $LoopDate['DateLoop'];            
            $BoolLoopOneMonthRes = FALSE;
            foreach ($ArrDataAwalOneMonth as $ArrDataAwalOneMonth2)
            {
                $ValArrDataAwalOneMonth2Slave = trim($ArrDataAwalOneMonth2['Slave']);
                $ValArrDataAwalOneMonth2KWH = trim($ArrDataAwalOneMonth2['KWH']);
                $ValArrDataAwalOneMonth2DataLog = trim($ArrDataAwalOneMonth2['DataLog']);
                $ValArrDataAwalOneMonth2Usage = trim($ArrDataAwalOneMonth2['Usage']);
                
                if($NameListSlave == $ValArrDataAwalOneMonth2Slave && $ValLoopDate == $ValArrDataAwalOneMonth2DataLog)
                {
                    $TTempArray = array(
                        "NoSlave" => $NoListSlave,
                        "Slave" => $NameListSlave,
                        "DateTracking" => $ValLoopDate,
                        "TotalUsage" => $ValArrDataAwalOneMonth2Usage
                    );
                    array_push(${"ArrTrackingPerDateOneMonth2$i"},$TTempArray);
                    $BoolLoopOneMonthRes = TRUE;
                }
            }
            if($BoolLoopOneMonthRes == FALSE)
            {
                $TTempArray = array(
                    "NoSlave" => $NoListSlave,
                    "Slave" => $NameListSlave,
                    "DateTracking" => $ValLoopDate,
                    "TotalUsage" => "0"
                );
                array_push(${"ArrTrackingPerDateOneMonth2$i"},$TTempArray);
            }
        }
        $i++;
    }
    # data array baru
    for($i = 1; $i <= $TotalSlaveAll; $i++)
    {
        ${"NewArrTrackingLast30Days$i"} = array();
        $NoLoopOneMonth = 1;
        $ValTempLoopData = "";
        foreach (${"ArrTrackingPerDateOneMonth2$i"} as $ArrResult)
        {
            $ValNoSlave = $ArrResult['NoSlave'];
            $ValSlave = $ArrResult['Slave'];
            $ValDateTracking = $ArrResult['DateTracking'];
            $ValTotalUsage = $ArrResult['TotalUsage'];
            if($NoLoopOneMonth == 1)
            {
                $TempArray = array(
                    "NoSlave" => $ValNoSlave,
                    "Slave" => $ValSlave,
                    "DateTracking" => $ValDateTracking,
                    "TotalUsage" => $ValTotalUsage,
                    "NewUsage" => "0"
                );
                array_push(${"NewArrTrackingLast30Days$i"},$TempArray);
                $ValTempLoopData = $ValTotalUsage;
            }
            else
            {
                $ValTempLoopData = $ValTotalUsage - $ValTempLoopData;
                if(($ValTempLoopData == $ValTotalUsage))
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage,
                        "NewUsage" => "0"
                    );
                    array_push(${"NewArrTrackingLast30Days$i"},$TempArray);
                }
                else
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage,
                        "NewUsage" => $ValTempLoopData
                    );
                    array_push(${"NewArrTrackingLast30Days$i"},$TempArray);
                }
                $ValTempLoopData = $ValTotalUsage;
            }
            $NoLoopOneMonth++;
        }
    }
}
else # kondisi data kosong
{
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"NewArrTrackingLast30Days$i"} = array();
        foreach ($TempArrayLoopDateOneMonth as $LoopDate)
        {
            $ValLoopDate = $LoopDate['DateLoop'];
            $TTempArray = array(
                "NoSlave" => $NoListSlave,
                "Slave" => $NameListSlave,
                "DateTracking" => $ValLoopDate,
                "TotalUsage" => "0",
			    "NewUsage" => "0"
            );
            array_push(${"NewArrTrackingLast30Days$i"},$TTempArray);
        }
        $i++;
    }
}

##########################
## Last 2 months
$DateNow = date("m/d/Y H:i:s");
$LastMonth = date("m/d/Y H:i:s",strtotime("-2 months"));
# get date
$StartTime = date("m/d/Y H:00:00",strtotime($LastMonth));
$EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
// $StartTime = "10/01/2021";
// $EndTime = "12/01/2021";
$StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
$EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
$TempArrayLoopDate2Month = array();
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
    array_push($TempArrayLoopDate2Month,$TDate);
    $StartTime1 = strtotime('+1 day',$StartTime1);
} while($StartTime1 <= $EndTime1);
# loop weeks
$NoWeeks2Months = 1;
$ArrWeeks2Months = array();
$TempWeeks2Months = "";
$CountArrayLoopWeek2Months = count($TempArrayLoopDate2Month);
foreach ($TempArrayLoopDate2Month as $WeekMonths)
{
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
        if($NoWeeks2Months != $CountArrayLoopWeek2Months)
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
# check row data
$ArrDataAwal2Month = array();
$QListKWHTracking2Month = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V3($StartTime,$EndTime,"FI",$linkHRISWebTrax);
if(mssql_num_rows($QListKWHTracking2Month) != "0") # kondisi data ditemukan
{
    while ($RListKWHTracking2Month = mssql_fetch_assoc($QListKWHTracking2Month))
    {
        $ValSlaveData2Month = trim($RListKWHTracking2Month['Slave']);
        $ValDatetimeData2Month = trim($RListKWHTracking2Month['DatetimeLog2']);
        $ValKWHData2Month = trim($RListKWHTracking2Month['KWH']);
        $ValUsageData2Month = trim($RListKWHTracking2Month['Usage']);
        $ValLocationData2Month = trim($RListKWHTracking2Month['Location']);

        $ArrTempRow = array(
            "Slave" => $ValSlaveData2Month,
            "KWH" => $ValKWHData2Month,
            "DataLog" => $ValDatetimeData2Month,
            "Usage" => $ValUsageData2Month
        );
        array_push($ArrDataAwal2Month,$ArrTempRow);
    }
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"ArrTrackingPerDateOneMonth2$i"} = array();
        foreach ($TempArrayLoopDate2Month as $LoopDate)
        {
            $ValLoopDate = $LoopDate['DateLoop'];            
            $BoolLoopOneMonthRes = FALSE;
            foreach ($ArrDataAwal2Month as $ArrDataAwal2Month2)
            {
                $ValArrDataAwal2Month2Slave = trim($ArrDataAwal2Month2['Slave']);
                $ValArrDataAwal2Month2KWH = trim($ArrDataAwal2Month2['KWH']);
                $ValArrDataAwal2Month2DataLog = trim($ArrDataAwal2Month2['DataLog']);
                $ValArrDataAwal2Month2Usage = trim($ArrDataAwal2Month2['Usage']);
                
                if($NameListSlave == $ValArrDataAwal2Month2Slave && $ValLoopDate == $ValArrDataAwal2Month2DataLog)
                {
                    $TTempArray = array(
                        "NoSlave" => $NoListSlave,
                        "Slave" => $NameListSlave,
                        "DateTracking" => $ValLoopDate,
                        "TotalUsage" => $ValArrDataAwal2Month2Usage,
				        "Weeks" => date("W",strtotime($ValLoopDate))
                    );
                    array_push(${"ArrTrackingPerDateOneMonth2$i"},$TTempArray);
                    $BoolLoopOneMonthRes = TRUE;
                }
            }
            if($BoolLoopOneMonthRes == FALSE)
            {
                $TTempArray = array(
                    "NoSlave" => $NoListSlave,
                    "Slave" => $NameListSlave,
                    "DateTracking" => $ValLoopDate,
                    "TotalUsage" => "0",
				    "Weeks" => date("W",strtotime($ValLoopDate))
                );
                array_push(${"ArrTrackingPerDateOneMonth2$i"},$TTempArray);
            }
        }
        $i++;
    }
    # penjumlahan per minggu
    for($i = 1; $i <= $TotalSlaveAll; $i++)
    {
        ${"ArrTrackingResultMonthByWeeks$i"} = array();
        foreach($ArrWeeks2Months as $DataWeeks2Months)
        {
            $ValDataWeeks2Months = $DataWeeks2Months['Weeks'];
            $ValYearWeeks2MonthsName = $DataWeeks2Months['Years'];      
            $TempTotalByWeeks = 0;
            $TempWeeks2MonthsB = "";
            $TempNoLoopByWeeks2Months = 1;
            foreach (${"ArrTrackingPerDateOneMonth2$i"} as $TempTrackingResultMonthByWeeks)
            {
                $ValTempNoSlave = $TempTrackingResultMonthByWeeks['NoSlave'];
                $ValTempTotalUsage = $TempTrackingResultMonthByWeeks['TotalUsage'];
                $ValTempWeeks = $TempTrackingResultMonthByWeeks['Weeks'];
                if($ValTempWeeks == $ValDataWeeks2Months)
                {
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
    # data array baru
    for($i = 1; $i <= $TotalSlaveAll; $i++)
    {
	    ${"NewArrTrackingLast2Months$i"} = array();
        $NoLoop2Months = 1;
        $ValTempLoopData = "";
    	foreach (${"ArrTrackingResultMonthByWeeks$i"} as $ArrResult)
        {
            $ValNoSlave = $ArrResult['NoSlave'];
            $ValTotalUsage = $ArrResult['TotalUsage'];
            $ValWeeks = $ArrResult['Weeks'];
            $ValYears = $ArrResult['Years'];
		    if($NoLoop2Months == 1)
            {
                $TempArray = array(
                    "NoSlave" => $ValNoSlave,
                    "TotalUsage" => $ValTotalUsage,
                    "Weeks" => $ValWeeks,
                    "Years" => $ValYears,
                    "NewUsage" => "0"
                );
                array_push(${"NewArrTrackingLast2Months$i"},$TempArray);
                $ValTempLoopData = $ValTotalUsage;
            }
            else
            {
                $ValTempLoopData = $ValTotalUsage - $ValTempLoopData;
                if(($ValTempLoopData == $ValTotalUsage))
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "TotalUsage" => $ValTotalUsage,
                        "Weeks" => $ValWeeks,
                        "Years" => $ValYears,
                        "NewUsage" => "0"
                    );
                    array_push(${"NewArrTrackingLast2Months$i"},$TempArray);
                }
                else
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "TotalUsage" => $ValTotalUsage,
                        "Weeks" => $ValWeeks,
                        "Years" => $ValYears,
                        "NewUsage" => $ValTempLoopData
                    );
                    array_push(${"NewArrTrackingLast2Months$i"},$TempArray);
                }
                $ValTempLoopData = $ValTotalUsage;
            }
		    $NoLoop2Months++;
        }
    }
}
else # kondisi data kosong
{
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"ArrTrackingPerDate2Month2$i"} = array();
        foreach ($TempArrayLoopDate2Month as $LoopDate)
        {
            $ValLoopDate = $LoopDate['DateLoop'];
            $TTempArray = array(
                "NoSlave" => $NoListSlave,
                "Slave" => $NameListSlave,
                "DateTracking" => $ValLoopDate,
                "TotalUsage" => "0",
                "Weeks" => date("W",strtotime($ValLoopDate))
            );
            array_push(${"ArrTrackingPerDate2Month2$i"},$TTempArray);
        }
        $i++;
    }
    # penjumlahan berdasarkan week
    for($i = 1; $i <= $TotalSlaveAll; $i++)
    {
        ${"NewArrTrackingLast2Months$i"} = array();
        foreach($ArrWeeks2Months as $DataWeeks2Months)
        {
            $ValDataWeeks2Months = $DataWeeks2Months['Weeks'];
			$ValYearWeeks2MonthsName = $DataWeeks2Months['Years'];      
			$TempTotalByWeeks = 0;
			$TempWeeks2MonthsB = "";
			$TempNoLoopByWeeks2Months = 1;
            foreach (${"ArrTrackingPerDate2Month2$i"} as $TempTrackingResultMonthByWeeks)
			{
				$ValTempNoSlave = $TempTrackingResultMonthByWeeks['NoSlave'];
				$ValTempTotalUsage = $TempTrackingResultMonthByWeeks['TotalUsage'];
				$ValTempWeeks = $TempTrackingResultMonthByWeeks['Weeks'];
			
				if($ValTempWeeks == $ValDataWeeks2Months)
				{
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
				"Years" => $ValYearWeeks2MonthsName,
			    "NewUsage" => "0"
			);
			array_push(${"NewArrTrackingLast2Months$i"},$ArrTemp2MonthsByWeek);
        }
    }
}


##########################
## Last 1 year
$ArrDataAwalYear = array();
$Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
$LastYear = date("m/d/Y H:i:s",strtotime("-1 year"));
// $Yesterday = '08/01/2023';
// $LastYear = '07/01/2022';
# get date
$StartTime = date("m/d/Y 00:00:00",strtotime($LastYear));
$EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
# get list date
$StartTime1 = strtotime(date('Y-m-d',strtotime($StartTime)));
$EndTime1 = strtotime(date('Y-m-d', strtotime($EndTime)));
$TempArrayLoopDate2Month = array();
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
    array_push($TempArrayLoopDate2Month,$TDate);
    $StartTime1 = strtotime('+1 day',$StartTime1);
} while($StartTime1 <= $EndTime1);
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
for ($x=1; $x <= $TotalSlaveAll; $x++)
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
# check row data
$ArrDataAwalOneYear = array();
$QListKWHTrackingOneYear = GET_LIST_KWH_TRACKING_LOG_BY_DATE_V3($StartTime,$EndTime,"FI",$linkHRISWebTrax);
if(mssql_num_rows($QListKWHTrackingOneYear) != "0") # kondisi data ditemukan
{
    while ($RListKWHTrackingOneYear = mssql_fetch_assoc($QListKWHTrackingOneYear))
    {
        $TempNoSlave = $RListKWHTrackingOneYear['Slave'];
        $TempKWH = $RListKWHTrackingOneYear['KWH'];
        $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTrackingOneYear['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
        $TempUsage = $RListKWHTrackingOneYear['Usage'];
        $ArrTempRow = array(
            "Slave" => $TempNoSlave,
            "KWH" => $TempKWH,
            "DataLog" => $TempDataLog,
            "Usage" => $TempUsage
        );
        array_push($ArrDataAwalOneYear,$ArrTempRow);
    }
    # pemisahan data berdasarkan slave
    for ($i=1; $i <= $TotalSlaveAll; $i++)
    {
        ${"ArrTrackingYear$i"} = array();
        foreach ($ArrListDataSlave2 as $ArrDataSlave) {
            $ValNameSlave = $ArrDataSlave['Slave'];
            $ValNoSlave = $ArrDataSlave['No'];
            if($ValNoSlave == $i)
            {
                # pemecahan berdasarkan slave
                foreach ($ArrDataAwalOneYear as $ArrData)
                {
                    if($ValNameSlave == $ArrData['Slave'])
                    {
                        $ASlave = $ArrData['Slave'];
                        $ADataLog = $ArrData['DataLog'];
                        $ADataLog2 = explode(" ",$ADataLog);
                        $ADateLog = $ADataLog2[0];
                        $AMonthLog = date("m",strtotime($ADataLog2[0]));
                        $AUsage = $ArrData['Usage'];
                        $AMonthYear = date("m-Y",strtotime($ADataLog2[0]));
                        $ATemp = array(
                            "NoSlave" => $ValNoSlave,
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
            }
        }
    }
    # counting pre slave and month year
    for ($i=1; $i <= $TotalSlaveAll; $i++)
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
                $RowNoSlaveMonthYear = $ArrTrackingYear['NoSlave'];          
                $RowNameSlaveMonthYear = $ArrTrackingYear['Slave'];
                if($RowMonthYear == $ValMonthYear && $RowNoSlaveMonthYear == $i)
                {
                    $TempCountMonthYear = $TempCountMonthYear + $RowCountUsage;
                }
            }
            # simpan dalam array
            $ArrTemp = array(
                "NoSlave" => $i,
                "Slave" => $RowNameSlaveMonthYear,
                "MonthYear" => $ValMonthYear,
                "Usage" => $TempCountMonthYear
            );
            array_push(${"ArrCountTrackingYear$i"},$ArrTemp);
        }
    }
    # data array baru
    for($i = 1; $i <= $TotalSlaveAll; $i++)
    {
	    ${"NewArrTrackingLastOneYear$i"} = array();
        $NoLoopOneYear = 1;
        $ValTempLoopData = "";
	    foreach (${"ArrCountTrackingYear$i"} as $ArrResult)
        {
            $ValNoSlave = $ArrResult['NoSlave'];
            $ValSlave = $ArrResult['Slave'];
            $ValDateTracking = $ArrResult['MonthYear'];
            $ValTotalUsage = $ArrResult['Usage'];
            if($NoLoopOneYear == 1)
            {
                $TempArray = array(
                    "NoSlave" => $ValNoSlave,
                    "Slave" => $ValSlave,
                    "MonthYear" => $ValDateTracking,
                    "Usage" => $ValTotalUsage,
                    "NewUsage" => "0"
                );
                array_push(${"NewArrTrackingLastOneYear$i"},$TempArray);
                $ValTempLoopData = $ValTotalUsage;
            }
            else
            {
                $ValTempLoopData = $ValTotalUsage - $ValTempLoopData;
                if(($ValTempLoopData == $ValTotalUsage))
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "MonthYear" => $ValDateTracking,
                        "Usage" => $ValTotalUsage,
                        "NewUsage" => "0"
                    );
                    array_push(${"NewArrTrackingLastOneYear$i"},$TempArray);
                }
                else
                {
                    $TempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "MonthYear" => $ValDateTracking,
                        "Usage" => $ValTotalUsage,
                        "NewUsage" => $ValTempLoopData
                    );
                    array_push(${"NewArrTrackingLastOneYear$i"},$TempArray);
                }
                $ValTempLoopData = $ValTotalUsage;
            }
            $NoLoopOneYear++;
        }
    }
}
else # kondisi data kosong
{
    # loop slave
    $i = 1;
    foreach ($ArrListDataSlave2 as $ArrDataSlave)
    {
        $NoListSlave = $ArrDataSlave['No'];
        $NameListSlave = $ArrDataSlave['Slave'];
        ${"NewArrTrackingLastOneYear$i"} = array();
        foreach ($TempArrayLoopMonthOneYear as $ArrMonthYear)
        {
            $ValMonthYear = $ArrMonthYear['MonthYear'];
            $TempSlaveMonthYear = $i;
            $TempCountMonthYear = 0;
            $RowCountUsage = 0;
            $TTempArray = array(
                "NoSlave" => $NoListSlave,
                "Slave" => $NameListSlave,
                "MonthYear" => $ValMonthYear,
                "Usage" => "0",
			    "NewUsage" => "0"
            );
            array_push(${"NewArrTrackingLastOneYear$i"},$TTempArray);
        }
        $i++;
    }
}

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=17">Electricy Usage : Site 1 (Salatiga Old)</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12"><i class="text-danger">*) Coeficient for Semarang = 40; Salatiga Old = 60; Salatiga New = 800</i></div>
    <div class="col-md-6">
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart2Weeks() {
                var data2Weeks = google.visualization.arrayToDataTable([
                <?php 
                echo "['Date'";
                $Loops = 0;
                foreach($ArrListDataSlave2 as $DataSlave2Weeks){
                    $ValSlave = $DataSlave2Weeks['Slave'];
                    echo ",'Usage'";
                    $Loops++;
                }
                echo ",{type: 'string', role: 'tooltip'}]";
                krsort($TempArrayLoopDate2Weeks);
                foreach($TempArrayLoopDate2Weeks as $Date2Weeks)
                {
                    $ValDate2Weeks = $Date2Weeks['DateLoop'];
                    echo ",['".$ValDate2Weeks."'";
                    for ($i=1; $i <= $TotalSlaveAll; $i++)
                    {
                        // foreach (${"ArrTrackingPerDate2Weeks2$i"} as $DataSlave2Weeks2)
                        foreach (${"NewArrTrackingLast2Weeks$i"} as $DataSlave2Weeks2)
                        {
                            $ValNoSlave2Weeks = $DataSlave2Weeks2['NoSlave'];
                            $ValSlave2Weeks = $DataSlave2Weeks2['Slave'];
                            $ValDateTracking2Weeks = $DataSlave2Weeks2['DateTracking'];
                            $ValTotalUsage2Weeks = $DataSlave2Weeks2['TotalUsage'];
			                $ValNewTotalUsage2Weeks = $DataSlave2Weeks2['NewUsage'];

                            if($i == $ValNoSlave2Weeks)
                            {
                                if($ValDateTracking2Weeks == $ValDate2Weeks)
                                {
                                    if($ValTotalUsage2Weeks < 0 || $ValNewTotalUsage2Weeks == 0)
                                    {
                                        $ValTotalUsage2Weeks = 0;
                                    }
                                    $ValTotUsage2Weeks = number_format((float)$ValTotalUsage2Weeks, 4, '.', '');
                                    echo ",".number_format((float)$ValTotalUsage2Weeks, 4, '.', '')."";
                                }
                            }
                        }
                    }
                    $ValTotUsage2Weeks = number_format((float)$ValTotUsage2Weeks, 0, '.', ',');
                    echo ', "'.$ValTotUsage2Weeks.' KWh"]';
                }
                ?>
                ]);
                
                var options2Weeks = {
                    title: 'Last 2 weeks', 
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },                    
                    chartArea: {top:100,height:"80%",width:"75%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
                    ,focusTarget: 'category'
				};
                var chart2Weeks = new google.visualization.BarChart(document.getElementById('GraphWeeks'));
                chart2Weeks.draw(data2Weeks, options2Weeks);
            }
            google.charts.setOnLoadCallback(drawChart2Weeks);
        </script>
        <div id="GraphWeeks"></div>   
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart2() {
                var dataOneMonth = google.visualization.arrayToDataTable([
                <?php 
                echo "['Date'";
                $Loops = 0;
                foreach($ArrListDataSlave2 as $DataSlaveOneMonth){
                    $ValSlave = $DataSlaveOneMonth['Slave'];
                    echo ",'Usage'";
                    $Loops++;
                }
                echo ",{type: 'string', role: 'tooltip'}]";
                krsort($TempArrayLoopDateOneMonth);
                foreach($TempArrayLoopDateOneMonth as $DateOneMonth)
                {
                    $ValDateOneMonth = $DateOneMonth['DateLoop'];
                    echo ",['".$ValDateOneMonth."'";
                    for ($i=1; $i <= $TotalSlaveAll; $i++)
                    {
                        foreach (${"NewArrTrackingLast30Days$i"} as $DataSlaveOneMonth2)
                        {
                            $ValNoSlaveOneMonth = $DataSlaveOneMonth2['NoSlave'];
                            $ValSlaveOneMonth = $DataSlaveOneMonth2['Slave'];
                            $ValDateTrackingOneMonth = $DataSlaveOneMonth2['DateTracking'];
                            $ValTotalUsageOneMonth = $DataSlaveOneMonth2['TotalUsage'];
                            $ValNewTotalUsageOneMonth = $DataSlaveOneMonth2['NewUsage'];

                            if($i == $ValNoSlaveOneMonth && $ValDateTrackingOneMonth == $ValDateOneMonth)
                            {
                                if($ValDateTrackingOneMonth == $ValDateOneMonth)
                                {
                                    if($ValTotalUsageOneMonth < 0 || $ValNewTotalUsageOneMonth == 0)
                                    {
                                        $ValTotalUsageOneMonth = 0;
                                    }
                                    $ValTotUsageOneMonth = number_format((float)$ValTotalUsageOneMonth, 4, '.', '');
                                    echo ",".number_format((float)$ValTotalUsageOneMonth, 4, '.', '')."";
                                }
                            }
                        }
                    }
                    $ValTotUsageOneMonth = number_format((float)$ValTotUsageOneMonth, 0, '.', ',');
                    echo ', "'.$ValTotUsageOneMonth.' KWh"]';
                }
                ?>
                ]);
                
                var optionsOneMonth = {
                    title: 'Last 30 Days', 
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },                    
                    chartArea: {top:100,height:"80%",width:"75%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
                    ,focusTarget: 'category'
				};
                var chartOneMonth = new google.visualization.BarChart(document.getElementById('Graph30Day'));
                chartOneMonth.draw(dataOneMonth, optionsOneMonth);
            }
            google.charts.setOnLoadCallback(drawChart2);
        </script>
        <div id="Graph30Day"></div>
    </div>
    <div class="col-md-12">&nbsp;</div> 
    <div class="col-md-6">
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart3() {
                var data2Month = google.visualization.arrayToDataTable([
                <?php 
                echo "['Date'";
                $Loops = 0;
                foreach($ArrListDataSlave2 as $DataSlave2Month){
                    $ValSlave = $DataSlave2Month['Slave'];
                    echo ",'Usage'";
                    $Loops++;
                }
                echo ",{type: 'string', role: 'tooltip'}]";                       
                krsort($ArrWeeks2Months);
                foreach($ArrWeeks2Months as $DataLoopWeeks2Months)
                {
                    $ValWeeks2Months = $DataLoopWeeks2Months['Weeks'];
                    $ValYearsWeeks2Months = $DataLoopWeeks2Months['Years'];
                    $ValTextWeeks2Months = "Wk.".(int)$ValWeeks2Months." (".$ValYearsWeeks2Months.")";
                    echo ",['".$ValTextWeeks2Months."'";
                    for ($x=1; $x <= $TotalSlaveAll; $x++)
                    {
                        // foreach (${"ArrTrackingResultMonthByWeeks$x"} as $DataSlave2Month2)
                        foreach (${"NewArrTrackingLast2Months$x"} as $DataSlave2Month2)
                        {
                            $ValNoSlave2Month = $DataSlave2Month2['NoSlave'];
                            $ValTotalUsage2Month = $DataSlave2Month2['TotalUsage'];
                            $ValDateTracking2Month = $DataSlave2Month2['Weeks'];
                            $ValSlave2Month = $DataSlave2Month2['Years'];
			                $ValNewTotalUsage2Month = $DataSlave2Month2['NewUsage'];
                            if($x == $ValNoSlave2Month)
                            {
                                if($ValDateTracking2Month == $ValWeeks2Months)
                                {
                                    if($ValSlave2Month == $ValYearsWeeks2Months)
                                    {
                                        if($ValTotalUsage2Month < 0 || $ValNewTotalUsage2Month == 0)
                                        {
                                            $ValTotalUsage2Month = 0;
                                        }
                                        $ValTotUsage2Month = number_format((float)$ValTotalUsage2Month, 4, '.', '');
                                        echo ",".number_format((float)$ValTotalUsage2Month, 4, '.', '')."";
                                    }
                                }
                            }
                        }
                    }
                    $ValTotUsage2Month = number_format((float)$ValTotUsage2Month, 0, '.', ',');
                    echo ', "'.$ValTotUsage2Month.' KWh"]';
                }
                ?>
                ]);                
                var options2Month = {
                    title: 'Last 2 months', 
                    groupWidth: "60%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },                    
                    chartArea: {top:100,height:"80%",width:"70%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
                    ,focusTarget: 'category'
				};
                var chart2Month = new google.visualization.BarChart(document.getElementById('GraphMonths'));
                chart2Month.draw(data2Month, options2Month);
            }
            google.charts.setOnLoadCallback(drawChart3);
        </script>
        <div id="GraphMonths"></div> 
    </div>
    <div class="col-md-6">
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart4() {
                var dataYear = google.visualization.arrayToDataTable([
                <?php 
                echo "['Date'";
                $Loops = 0;
                foreach($ArrListDataSlave2 as $DataSlaveOneYear){
                    $ValSlave = $DataSlaveOneYear['Slave'];
                    echo ",'Usage'";
                    $Loops++;
                }
                echo ",{type: 'string', role: 'tooltip'}]";
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
                    for ($i=1; $i <= $TotalSlaveAll; $i++)
                    {
                        // foreach(${"ArrCountTrackingYear$i"} as $DataLoopOneYear)
                        foreach(${"NewArrTrackingLastOneYear$i"} as $DataLoopOneYear)
                        {
                            $ValDataTrackingNoSlave = $DataLoopOneYear['NoSlave'];
                            $ValDataTrackingSlave = $DataLoopOneYear['Slave'];
                            $ValDataTrackingMonthYear = $DataLoopOneYear['MonthYear'];
                            $ValDataTrackingUsage = $DataLoopOneYear['Usage'];
			                $ValDataTrackingNewUsage = $DataLoopOneYear['NewUsage'];
                            if($ValDataTrackingNoSlave == $i && $ValDataTrackingMonthYear == $ArrValMonthYear)
                            {
                                if($ValDataTrackingUsage < 0 || $ValDataTrackingNewUsage == 0)
                                {
                                    $ValDataTrackingUsage = 0;
                                }
                                $ValTotDataTrackingUsage = number_format((float)$ValDataTrackingUsage, 4, '.', '');
                                echo ",".number_format((float)$ValDataTrackingUsage, 4, '.', '')."";
                            }
                        }  
                    }
                    $ValTotDataTrackingUsage = number_format((float)$ValTotDataTrackingUsage, 0, '.', ',');
                    echo ', "'.$ValTotDataTrackingUsage.' KWh"]';
                }
                ?>
                ]);                
                var optionsYear = {
                    title: 'Last 1 year', 
                    groupWidth: "30%",
                    hAxis: {
                        title: 'Usage'
                    },
                    height: 900,
                    legend: { position: 'top', maxLines: 3 },                    
                    chartArea: {top:100,height:"80%",width:"70%"},
                    bar: { groupWidth: '50%' },
                    isStacked:true
                    ,focusTarget: 'category'
				};
                var chartYear = new google.visualization.BarChart(document.getElementById('GraphYear'));
                chartYear.draw(dataYear, optionsYear);
            }
            google.charts.setOnLoadCallback(drawChart4);
        </script>
        <div id="GraphYear"></div>
    </div>
</div> 

