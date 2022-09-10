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

## Last 2 weeks
$DateNow = date("m/d/Y",strtotime("-1 day"));
$LastWeek = date("m/d/Y",strtotime("-2 weeks"));
# get date
$StartTime = date("m/d/Y",strtotime($LastWeek));
$EndTime = date("m/d/Y",strtotime($DateNow));
// $StartTime = "12/23/2020";
// $EndTime = "01/07/2021";
// $StartTime = "04/20/2021";
// $EndTime = "05/04/2021";
# get data list slave
$QListSlave2Weeks = GET_LIST_SLAVE_BY_DATE_PSM($StartTime,$EndTime,$linkHRISWebTrax);
$NoListSlave2Weeks = 1;
$ArrDataSlave2Weeks = array();
while ($RListSlave2Weeks = mssql_fetch_assoc($QListSlave2Weeks))
{
    $ValListSlave2Weeks = trim($RListSlave2Weeks['Slave']);
    $ArrTempListSlave = array(
        "No" => $NoListSlave2Weeks,
        "Slave" => $ValListSlave2Weeks
    );
    array_push($ArrDataSlave2Weeks,$ArrTempListSlave);

    $NoListSlave2Weeks++;
}
$TotalSlave2Weeks = count($ArrDataSlave2Weeks);

# get data awal
$ArrDataAwal2Weeks = array();
$QListKWHTracking2Weeks = GET_LIST_KWH_TRACKING_LOG_BY_DATE_PSM($StartTime,$EndTime,$linkHRISWebTrax);
$TotalRow2Weeks = mssql_num_rows($QListKWHTracking2Weeks);
if($TotalRow2Weeks != "0")
{
    while ($RListKWHTracking2Weeks = mssql_fetch_assoc($QListKWHTracking2Weeks))
    {
        $TempNoSlave = $RListKWHTracking2Weeks['Slave'];
        $TempKWH = $RListKWHTracking2Weeks['KWH'];
        $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking2Weeks['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
        $TempUsage = $RListKWHTracking2Weeks['Usage'];
        $ArrTempRow = array(
            "Slave" => $TempNoSlave,
            "KWH" => $TempKWH,
            "DataLog" => $TempDataLog,
            "Usage" => $TempUsage
        );
        array_push($ArrDataAwal2Weeks,$ArrTempRow);
    }
    // echo "<pre>";print_r($ArrDataAwal2Weeks);echo "</pre>";
    // // # get data list slave
    // // $ArrDataSlave2Weeks = array();
    // // $VarCheckSlave2Weeks = "";
    // // $NoListSlave = 1;
    // // foreach ($ArrDataAwal2Weeks as $DataAwal)
    // // {
    // //     $VarSlave = $DataAwal['Slave'];
    // //     if($VarCheckSlave2Weeks != $VarSlave)
    // //     {
    // //         $ArrTempListSlave = array(
    // //             "No" => $NoListSlave,
    // //             "Slave" => $VarSlave
    // //         );
    // //         array_push($ArrDataSlave2Weeks,$ArrTempListSlave);
    // //         $VarCheckSlave2Weeks = $VarSlave;
    // //         $NoListSlave++;
    // //     }
    // // }
    // // $TotalSlave2Weeks = count($ArrDataSlave2Weeks);
    // echo "<pre>";print_r($ArrDataSlave2Weeks);echo "</pre>";
    // echo "Total Slave : ".$TotalSlave2Weeks;
    # data array tracking per slave
    $ArrTrackingPerDate2Weeks = array();
    for ($i=1; $i <= $TotalSlave2Weeks; $i++)
    {
        foreach ($ArrDataSlave2Weeks as $ListDataSlave)
        {
            $NoSlave = $ListDataSlave['No'];
            $ValSlave = $ListDataSlave['Slave'];
            if($NoSlave == $i)
            {
                $TempSlave = $ValSlave;
                break;
            }
        }
        ${"ArrTracking2Weeks$i"} = array();
        foreach ($ArrDataAwal2Weeks as $DataAwal1)
        {
            $VarSlave1 = $DataAwal1['Slave'];
            $VarKWH1 = $DataAwal1['KWH'];
            $VarDataLog1 = $DataAwal1['DataLog'];
            $VarUsage1 = $DataAwal1['Usage'];
            if(trim($VarSlave1) == trim($TempSlave))
            {
                $TempArrayDataSlave = array(
                    "Slave" => $VarSlave1,
                    "KWH" => $VarKWH1,
                    "DataLog" => $VarDataLog1,
                    "Usage" => $VarUsage1
                );
                array_push(${"ArrTracking2Weeks$i"},$TempArrayDataSlave);
            }                          
        }
        // echo "<pre>";print_r(${"ArrTracking2Weeks$i"});echo "</pre>";
        
        # perhitungan setiap hari
        $TempDT = "";
        $TempDTTotalUsage = 0;
        foreach (${"ArrTracking2Weeks$i"} as $DT1)
        {
            $DT1Slave = $DT1['Slave'];
            $DT1KWH = $DT1['KWH'];
            $DT1DataLog = $DT1['DataLog'];
            $DT1Usage = $DT1['Usage'];
            $ArrDT1DataLog = explode(" ",$DT1DataLog);
            $DateDataLog = $ArrDT1DataLog[0];
            $ValNoSlave = "";

            foreach ($ArrDataSlave2Weeks as $DataSlave2Weeks)
            {
                $NoSlave = $DataSlave2Weeks['No'];
                $Slave = $DataSlave2Weeks['Slave'];
                if(trim($Slave) == trim($DT1Slave))
                {
                    $ValNoSlave = $NoSlave; 
                    $TempTrackingPerDate = array(
                        'NoSlave' => $ValNoSlave,
                        'Slave' => $DT1Slave,
                        'DateTracking' => $DateDataLog,
                        'TotalUsage' => $DT1Usage
                    );
                    array_push($ArrTrackingPerDate2Weeks,$TempTrackingPerDate);
                }
            }
        }   

        // if($i == 7)     //utk check
        // {
        //     // echo "<pre>";print_r($ArrTrackingPerDate2Weeks);echo "</pre>";
        //     echo "<pre>";print_r(${"ArrTracking2Weeks$i"});echo "</pre>";
        // } 
    }
            // echo "<pre>";print_r($ArrTrackingPerDate2Weeks);echo "</pre>";

    // echo "<pre>";print_r($ArrTrackingPerDate2Weeks);echo "</pre>";
    # penambahan tgl kosong utk tracking per date
    for ($i=1; $i <= $TotalSlave2Weeks; $i++)
    {
        ${"ArrTrackingPerDate2Weeks2$i"} = array();
        //looping pengecekan tgl berdasarkan $w dan no slave dari $ArrTrackingPerDate2Weeks

        # check data per hari
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
            // echo "############<br>";
            // echo "Tgl : ".$Date."<br>";
        
            $ValCheckDate = FALSE;
            $ValTempNoSlave2Weeks = "";
            $ValTempSlave2Weeks = "";
            # pengecekan nilai usage per tgl

            foreach($ArrTrackingPerDate2Weeks as $ArrData)
            {
                $ValNoSlave = $ArrData['NoSlave'];
                $ValSlave = $ArrData['Slave'];
                $ValDateTracking = $ArrData['DateTracking'];
                $ValTotalUsage = $ArrData['TotalUsage'];
                if($ValNoSlave == $i)
                {
                    $ValTempNoSlave2Weeks = $ValNoSlave;
                    $ValTempSlave2Weeks = $ValSlave;
                }
                if($ValNoSlave == $i && $ValDateTracking == $Date)   # jika noslave sama dengan no index looping && datetracking = loop date
                {
                    //input data ke array
                    $TTempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage
                    );
                    array_push(${"ArrTrackingPerDate2Weeks2$i"},$TTempArray);
                    $ValCheckDate = TRUE;
                    break;
                }
            }
            if($ValCheckDate == FALSE)  # jika tidak ada data yg sama dgn tgl
            {
                //penambahan data kosong ke array
                $TTempArray = array(
                    "NoSlave" => $ValTempNoSlave2Weeks,
                    "Slave" => $ValTempSlave2Weeks,
                    "DateTracking" => $Date,
                    "TotalUsage" => "0"
                );
                array_push(${"ArrTrackingPerDate2Weeks2$i"},$TTempArray);
            }
            // if($i == 7)     //utk check
            // {
            //     echo "<pre>";print_r(${"ArrTrackingPerDate2Weeks2$i"});echo "</pre>";
            // } 
            $StartTime1 = strtotime('+1 day',$StartTime1);
        } while($StartTime1 <= $EndTime1);

        // if($i == 1)     //utk check
        // {
        //     echo "<pre>";print_r(${"ArrTrackingPerDate2Weeks2$i"});echo "</pre>";
        // } 
    }

}
else
{
    echo "";
}


## Last 30 days
$Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
$LastMonth = date("m/d/Y H:i:s",strtotime("-30 days"));
// $Yesterday = "01/07/2021";
// $LastMonth = "12/07/2020";
# get data list slave
$QListSlaveOneMonth = GET_LIST_SLAVE_BY_DATE_PSM($LastMonth,$Yesterday,$linkHRISWebTrax);
$NoListSlaveOneMonth = 1;
$ArrDataSlaveOneMonth = array();
while ($RListSlaveOneMonth = mssql_fetch_assoc($QListSlaveOneMonth))
{
    $ValListSlaveOneMonth = trim($RListSlaveOneMonth['Slave']);
    $ArrTempListSlave = array(
        "No" => $NoListSlaveOneMonth,
        "Slave" => $ValListSlaveOneMonth
    );
    array_push($ArrDataSlaveOneMonth,$ArrTempListSlave);

    $NoListSlaveOneMonth++;
}
$TotalSlaveOneMonth = count($ArrDataSlaveOneMonth);
# get data awal
$ArrDataAwalOneMonth = array();
$QListKWHTrackingOneMonth = GET_LIST_KWH_TRACKING_LOG_BY_DATE_PSM($LastMonth,$Yesterday,$linkHRISWebTrax);
$TotalRowOneMonth = mssql_num_rows($QListKWHTrackingOneMonth);
if($TotalRowOneMonth != "0")
{
    while ($RListKWHTrackingOneMonth = mssql_fetch_assoc($QListKWHTrackingOneMonth))
    {
        $TempNoSlave = $RListKWHTrackingOneMonth['Slave'];
        $TempKWH = $RListKWHTrackingOneMonth['KWH'];
        $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTrackingOneMonth['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
        $TempUsage = $RListKWHTrackingOneMonth['Usage'];
        $ArrTempRow = array(
            "Slave" => $TempNoSlave,
            "KWH" => $TempKWH,
            "DataLog" => $TempDataLog,
            "Usage" => $TempUsage
        );
        array_push($ArrDataAwalOneMonth,$ArrTempRow);
    }
    // echo "<pre>";print_r($ArrDataAwalOneMonth);echo "</pre>";
    // # get data list slave
    // $ArrDataSlaveOneMonth = array();
    // $VarCheckSlaveOneMonth = "";
    // $NoListSlave = 1;
    // foreach ($ArrDataAwalOneMonth as $DataAwal)
    // {
    //     $VarSlave = $DataAwal['Slave'];
    //     if($VarCheckSlaveOneMonth != $VarSlave)
    //     {
    //         $ArrTempListSlave = array(
    //             "No" => $NoListSlave,
    //             "Slave" => $VarSlave
    //         );
    //         array_push($ArrDataSlaveOneMonth,$ArrTempListSlave);
    //         $VarCheckSlaveOneMonth = $VarSlave;
    //         $NoListSlave++;
    //     }
    // }
    // $TotalSlaveOneMonth = count($ArrDataSlaveOneMonth);
    // echo "<pre>";print_r($ArrDataSlaveOneMonth);echo "</pre>";
    // echo "Total Slave : ".$TotalSlaveOneMonth;
    // exit();
    # data array tracking per slave
    $ArrTrackingPerDateOneMonth = array();
    for ($i=1; $i <= $TotalSlaveOneMonth; $i++)
    {
        foreach ($ArrDataSlaveOneMonth as $ListDataSlave)
        {
            $NoSlave = $ListDataSlave['No'];
            $ValSlave = $ListDataSlave['Slave'];
            if($NoSlave == $i)
            {
                $TempSlave = $ValSlave;
                break;
            }
        }
        ${"ArrTrackingOneMonth$i"} = array();
        foreach ($ArrDataAwalOneMonth as $DataAwal1)
        {
            $VarSlave1 = $DataAwal1['Slave'];
            $VarKWH1 = $DataAwal1['KWH'];
            $VarDataLog1 = $DataAwal1['DataLog'];
            $VarUsage1 = $DataAwal1['Usage'];
            if($VarSlave1 == $TempSlave)
            {
                $TempArrayDataSlave = array(
                    "Slave" => $VarSlave1,
                    "KWH" => $VarKWH1,
                    "DataLog" => $VarDataLog1,
                    "Usage" => $VarUsage1
                );
                array_push(${"ArrTrackingOneMonth$i"},$TempArrayDataSlave);
            }                          
        }
        // echo "<pre>";print_r(${"ArrTrackingOneMonth$i"});echo "</pre>";
        # perhitungan setiap hari
        $TempDT = "";
        $TempDTTotalUsage = 0;
        foreach (${"ArrTrackingOneMonth$i"} as $DT1)
        {
            $DT1Slave = $DT1['Slave'];
            $DT1KWH = $DT1['KWH'];
            $DT1DataLog = $DT1['DataLog'];
            $DT1Usage = $DT1['Usage'];
            $ArrDT1DataLog = explode(" ",$DT1DataLog);
            $DateDataLog = $ArrDT1DataLog[0];
            $ValNoSlave = "";

            foreach ($ArrDataSlaveOneMonth as $DataSlaveOneMonth)
            {
                $NoSlave = $DataSlaveOneMonth['No'];
                $Slave = $DataSlaveOneMonth['Slave'];
                // if($Slave == $DT1Slave)
                if(trim($Slave) == trim($DT1Slave))
                {
                    $ValNoSlave = $NoSlave; 
                    $TempTrackingPerDate = array(
                        'NoSlave' => $ValNoSlave,
                        'Slave' => $DT1Slave,
                        'DateTracking' => $DateDataLog,
                        'TotalUsage' => $DT1Usage
                    );
                    array_push($ArrTrackingPerDateOneMonth,$TempTrackingPerDate);
                }
            }
        }   

        // if($i == 1)     //utk check
        // {
        //     echo "<pre>";print_r($ArrTrackingPerDateOneMonth);echo "</pre>";
        // } 
    }
    // echo "<pre>";print_r($ArrTrackingPerDateOneMonth);echo "</pre>";
    # penambahan tgl kosong utk tracking per date
    for ($i=1; $i <= $TotalSlaveOneMonth; $i++)
    {
        ${"ArrTrackingPerDateOneMonth2$i"} = array();
        //looping pengecekan tgl berdasarkan $i dan no slave dari $ArrTrackingPerDate2Weeks

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
            // echo "############<br>";
            // echo "Tgl : ".$Date."<br>";
        
            $ValCheckDate = FALSE;
            # pengecekan nilai usage per tgl
            foreach($ArrTrackingPerDateOneMonth as $ArrData)
            {
                $ValNoSlave = $ArrData['NoSlave'];
                $ValSlave = $ArrData['Slave'];
                $ValDateTracking = $ArrData['DateTracking'];
                $ValTotalUsage = $ArrData['TotalUsage'];
                if($ValNoSlave == $i)
                {
                    $ValTempNoSlaveOneMonth = $ValNoSlave;
                    $ValTempSlaveOneMonth = $ValSlave;
                }
                // if($i == 1)
                // {
                //     echo "<br>".$ValNoSlave == $i." && ".$ValDateTracking." == ".$Date;
                // }
                if($ValNoSlave == $i && $ValDateTracking == $Date)   # jika noslave sama dengan no index looping && datetracking = loop date
                {
                    //input data ke array
                    $TTempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage
                    );
                    array_push(${"ArrTrackingPerDateOneMonth2$i"},$TTempArray);
                    $ValCheckDate = TRUE;
                    break;
                }
            }
            if($ValCheckDate == FALSE)  # jika tidak ada data yg sama dgn tgl
            {
                //penambahan data kosong ke array
                $TTempArray = array(
                    "NoSlave" => $ValTempNoSlaveOneMonth,
                    "Slave" => $ValTempSlaveOneMonth,
                    "DateTracking" => $Date,
                    "TotalUsage" => "0"
                );
                array_push(${"ArrTrackingPerDateOneMonth2$i"},$TTempArray);
            }
            $StartTime1 = strtotime('+1 day',$StartTime1);
        } while($StartTime1 <= $EndTime1);
        // if($i == 2)     //utk check
        // {
        //     echo "<pre>";print_r(${"ArrTrackingPerDateOneMonth2$i"});echo "</pre>";
        // } 
    }
}
else
{
    echo "";
}


## data chart Last 2 months
$DateNow = date("m/d/Y H:i:s");
$LastMonth = date("m/d/Y H:i:s",strtotime("-2 months"));
# get date
$StartTime = date("m/d/Y H:00:00",strtotime($LastMonth));
$EndTime = date("m/d/Y H:00:00",strtotime($DateNow));
// $StartTime = "12/01/2020";
// $EndTime = "02/01/2021";
# get data awal
$ArrDataAwal2Month = array();
$QListKWHTracking2Month = GET_LIST_KWH_TRACKING_LOG_BY_DATE_PSM($StartTime,$EndTime,$linkHRISWebTrax);
$TotalRow2Month = mssql_num_rows($QListKWHTracking2Month);
if($TotalRow2Month != "0")
{
    while ($RListKWHTracking2Month = mssql_fetch_assoc($QListKWHTracking2Month))
    {
        $TempNoSlave = $RListKWHTracking2Month['Slave'];
        $TempKWH = $RListKWHTracking2Month['KWH'];
        $TempDataLog = date("m/d/Y H:i",strtotime($RListKWHTracking2Month['DatetimeLog']))."#".$TempKWH."#".$TempNoSlave;
        $TempUsage = $RListKWHTracking2Month['Usage'];
        $ArrTempRow = array(
            "Slave" => $TempNoSlave,
            "KWH" => $TempKWH,
            "DataLog" => $TempDataLog,
            "Usage" => $TempUsage
        );
        array_push($ArrDataAwal2Month,$ArrTempRow);
    }
    // echo "<pre>";print_r($ArrDataAwal2Month);echo "</pre>";
    # get data list slave
    $ArrDataSlave2Month = array();
    $VarCheckSlave2Month = "";
    $NoListSlave = 1;
    foreach ($ArrDataAwal2Month as $DataAwal)
    {
        $VarSlave = $DataAwal['Slave'];
        if($VarCheckSlave2Month != $VarSlave)
        {
            $ArrTempListSlave = array(
                "No" => $NoListSlave,
                "Slave" => $VarSlave
            );
            array_push($ArrDataSlave2Month,$ArrTempListSlave);
            $VarCheckSlave2Month = $VarSlave;
            $NoListSlave++;
        }
    }
    $TotalSlave2Month = count($ArrDataSlave2Month);
    // echo "<pre>";print_r($ArrDataSlave2Month);echo "</pre>";
    // echo "Total Slave : ".$TotalSlave2Month;
    # data array tracking per slave
    $ArrTrackingPerDate2Month = array();
    for ($i=1; $i <= $TotalSlave2Month; $i++)
    {
        foreach ($ArrDataSlave2Month as $ListDataSlave)
        {
            $NoSlave = $ListDataSlave['No'];
            $ValSlave = $ListDataSlave['Slave'];
            if($NoSlave == $i)
            {
                $TempSlave = $ValSlave;
            }
        }
        ${"ArrTracking2Month$i"} = array();
        foreach ($ArrDataAwal2Month as $DataAwal1)
        {
            $VarSlave1 = $DataAwal1['Slave'];
            $VarKWH1 = $DataAwal1['KWH'];
            $VarDataLog1 = $DataAwal1['DataLog'];
            $VarUsage1 = $DataAwal1['Usage'];
            if($VarSlave1 == $TempSlave)
            {
                $TempArrayDataSlave = array(
                    "Slave" => $VarSlave1,
                    "KWH" => $VarKWH1,
                    "DataLog" => $VarDataLog1,
                    "Usage" => $VarUsage1
                );
                array_push(${"ArrTracking2Month$i"},$TempArrayDataSlave);
            }                          
        }
        // echo "<pre>";print_r(${"ArrTracking2Month$i"});echo "</pre>";
        # perhitungan setiap hari
        $TempDT = "";
        $TempDTTotalUsage = 0;
        foreach (${"ArrTracking2Month$i"} as $DT1)
        {
            $DT1Slave = $DT1['Slave'];
            $DT1KWH = $DT1['KWH'];
            $DT1DataLog = $DT1['DataLog'];
            $DT1Usage = $DT1['Usage'];
            $ArrDT1DataLog = explode(" ",$DT1DataLog);
            $DateDataLog = $ArrDT1DataLog[0];
            $ValNoSlave = "";

            foreach ($ArrDataSlave2Month as $DataSlave2Month)
            {
                $NoSlave = $DataSlave2Month['No'];
                $Slave = $DataSlave2Month['Slave'];
                if($Slave == $DT1Slave)
                {
                    $ValNoSlave = $NoSlave; 
                    $TempTrackingPerDate = array(
                        'NoSlave' => $ValNoSlave,
                        'Slave' => $DT1Slave,
                        'DateTracking' => $DateDataLog,
                        'TotalUsage' => $DT1Usage
                    );
                    array_push($ArrTrackingPerDate2Month,$TempTrackingPerDate);
                }
            }
        }   
        // if($i == 4)     //utk check
        // {
        //     echo "<pre>";print_r($ArrTrackingPerDate2Month);echo "</pre>";
        // } 
    }
    # penambahan tgl kosong utk tracking per date   
    for ($i=1; $i <= $TotalSlave2Month; $i++)
    {
        ${"ArrTrackingPerDate2Month2$i"} = array();
        # check data per hari
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
            // echo "############<br>";
            // echo "Tgl : ".$Date."<br>";
        
            $ValCheckDate = FALSE;
            # pengecekan nilai usage per tgl

            foreach($ArrTrackingPerDate2Month as $ArrData)
            {
                $ValNoSlave = $ArrData['NoSlave'];
                $ValSlave = $ArrData['Slave'];
                $ValDateTracking = $ArrData['DateTracking'];
                $ValTotalUsage = $ArrData['TotalUsage'];
                if($ValNoSlave == $i && $ValDateTracking == $Date)   # jika noslave sama dengan no index looping && datetracking = loop date
                {
                    //input data ke array
                    $TTempArray = array(
                        "NoSlave" => $ValNoSlave,
                        "Slave" => $ValSlave,
                        "DateTracking" => $ValDateTracking,
                        "TotalUsage" => $ValTotalUsage,
                        "Weeks" => date("W",strtotime($ValDateTracking))
                    );
                    array_push(${"ArrTrackingPerDate2Month2$i"},$TTempArray);
                    $ValCheckDate = TRUE;
                    break;
                }
            }
            if($ValCheckDate == FALSE)  # jika tidak ada data yg sama dgn tgl
            {
                //penambahan data kosong ke array
                $TTempArray = array(
                    "NoSlave" => $ValNoSlave,
                    "Slave" => $ValSlave,
                    "DateTracking" => $Date,
                    "TotalUsage" => "0",
                    "Weeks" => date("W",strtotime($Date))
                );
                array_push(${"ArrTrackingPerDate2Month2$i"},$TTempArray);
            }
            $StartTime1 = strtotime('+1 day',$StartTime1);
        } while($StartTime1 <= $EndTime1);
        // if($i == 4)     //utk check
        // {
        //     echo "<pre>";print_r(${"ArrTrackingPerDate2Month2$i"});echo "</pre>";
        // } 
    }
    # loop weeks
    $NoWeeks2Months = 1;
    $ArrWeeks2Months = array();
    $TempWeeks2Months = "";
    $CountArrayLoopWeek2Months = count($TempArrayLoopDate2Month);
    foreach ($TempArrayLoopDate2Month as $WeekMonths) {
        $Weeks2MonthsName = date("W",strtotime($WeekMonths['DateLoop']));
        $ValYearWeeks2MonthsName = date("Y",strtotime($WeekMonths['DateLoop']));
        // echo $WeekMonths['DateLoop'];
        // echo " - Weeks ".$Weeks2MonthsName." >> Total loop : ".$CountArrayLoopWeek2Months;
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
            if($NoWeeks2Months != $CountArrayLoopWeek2Months)
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
    for ($i=1; $i <= $TotalSlave2Month; $i++)
    {
        ${"ArrTrackingResultMonthByWeeks$i"} = array();
        // if($i == 1)
        // {
            // echo "<pre>";print_r(${"ArrTrackingPerDate2Month2$i"});echo "</pre>";
            $CountArrTrackingResultMonth = count(${"ArrTrackingPerDate2Month2$i"});
            foreach($ArrWeeks2Months as $DataWeeks2Months)
            {
                $ValDataWeeks2Months = $DataWeeks2Months['Weeks'];
                $ValYearWeeks2MonthsName = $DataWeeks2Months['Years'];
                // echo "<br>".$ValDataWeeks2Months;                    
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
                    "Years" => $ValYearWeeks2MonthsName
                );
                array_push(${"ArrTrackingResultMonthByWeeks$i"},$ArrTemp2MonthsByWeek);

            }
        // }
    }
    // for ($i=1; $i <= $TotalSlave2Month; $i++)
    // {
    //     // if($i == 1)
    //     // {
    //         // krsort(${"ArrTrackingResultMonthByWeeks$i"});
    //         // echo "<pre>";print_r(${"ArrTrackingResultMonthByWeeks$i"});echo "</pre>";                
    //     // }
    // }

}
else
{
    echo "";
}


## Last 1 year
$ArrDataAwalYear = array();
$Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
$LastYear = date("m/d/Y H:i:s",strtotime("-1 year"));
# get date
$StartTime = date("m/d/Y 00:00:00",strtotime($LastYear));
$EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
# get data awal
$ArrDataAwalOneYear = array();
$QListKWHTrackingOneYear = GET_LIST_KWH_TRACKING_LOG_BY_DATE_PSM($StartTime,$EndTime,$linkHRISWebTrax);
$TotalRowOneYear = mssql_num_rows($QListKWHTrackingOneYear);
if($TotalRowOneYear != "0")
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
    // echo "<pre>";print_r($ArrDataAwalOneYear);echo "</pre>";
    # get data list slave
    $ArrDataSlaveOneYear = array();
    $VarCheckSlaveOneYear = "";
    $NoListSlave = 1;
    foreach ($ArrDataAwalOneYear as $DataAwal)
    {
        $VarSlave = $DataAwal['Slave'];
        if($VarCheckSlaveOneYear != $VarSlave)
        {
            $ArrTempListSlave = array(
                "No" => $NoListSlave,
                "Slave" => $VarSlave
            );
            array_push($ArrDataSlaveOneYear,$ArrTempListSlave);
            $VarCheckSlaveOneYear = $VarSlave;
            $NoListSlave++;
        }
    }
    $TotalSlaveOneYear = count($ArrDataSlaveOneYear);
    // echo "<pre>";print_r($ArrDataSlaveOneYear);echo "</pre>";
    // echo "Total Slave : ".$TotalSlaveOneYear;
    # pemisahan data berdasarkan slave
    for ($i=1; $i <= $TotalSlaveOneYear; $i++)
    {
        ${"ArrTrackingYear$i"} = array();
        foreach ($ArrDataSlaveOneYear as $ArrDataSlave) {
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
        // if($i == 2)
        // {
        //     echo "<pre>";print_r(${"ArrTrackingYear$i"});echo "</pre>";
        // }
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
    for ($x=1; $x <= $TotalSlaveOneYear; $x++)
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
    // echo "<pre>";print_r($TempArrayLoopMonthOneYear);echo "</pre>";
    # counting pre slave and month year
    for ($i=1; $i <= $TotalSlaveOneYear; $i++)
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
                if($RowMonthYear == $ValMonthYear && $RowNoSlaveMonthYear == $i)
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
        // if($i == 2)
        // {
        //     echo "<pre>";print_r(${"ArrCountTrackingYear$i"});echo "</pre>";
        // }
    }
}
else
{
    echo "";
}

//utk check hasil array
for ($i=1; $i <= $TotalSlave2Weeks; $i++)
{
    
    // echo "<pre>";print_r(${"ArrTrackingPerDate2Weeks2$i"});echo "</pre>";
    // echo "<pre>";print_r(${"ArrTrackingPerDateOneMonth2$i"});echo "</pre>";
    // echo "<br>=====";
}

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=12">Electricy Usage : Site 2 (Semarang)</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart']});  
            function drawChart() {
                var data2Weeks = google.visualization.arrayToDataTable([
                <?php 
                echo "['Date'";
                $Loops = 0;
                foreach($ArrDataSlave2Weeks as $DataSlave2Weeks){
                    $ValSlave = $DataSlave2Weeks['Slave'];
                    echo ",'".$ValSlave."'";
                    $Loops++;
                }
                echo "]";
                krsort($TempArrayLoopDate2Weeks);
                foreach($TempArrayLoopDate2Weeks as $Date2Weeks)
                {
                    $ValDate2Weeks = $Date2Weeks['DateLoop'];
                    echo ",['".$ValDate2Weeks."'";
                    for ($i=1; $i <= $TotalSlave2Weeks; $i++)
                    {
                        foreach (${"ArrTrackingPerDate2Weeks2$i"} as $DataSlave2Weeks2)
                        {
                            $ValNoSlave2Weeks = $DataSlave2Weeks2['NoSlave'];
                            $ValSlave2Weeks = $DataSlave2Weeks2['Slave'];
                            $ValDateTracking2Weeks = $DataSlave2Weeks2['DateTracking'];
                            $ValTotalUsage2Weeks = $DataSlave2Weeks2['TotalUsage'];

                            if($i == $ValNoSlave2Weeks)
                            {
                                if($ValDateTracking2Weeks == $ValDate2Weeks)
                                {
                                    if($ValTotalUsage2Weeks < 0)
                                    {
                                        $ValTotalUsage2Weeks = 0;
                                    }
                                    echo ",".number_format((float)$ValTotalUsage2Weeks, 4, '.', '')."";
                                }
                            }
                        }
                    }
                    echo "]";
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
				};
                var chart2Weeks = new google.visualization.BarChart(document.getElementById('GraphWeeks'));
                chart2Weeks.draw(data2Weeks, options2Weeks);
            }
            google.charts.setOnLoadCallback(drawChart);
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
                foreach($ArrDataSlaveOneMonth as $DataSlaveOneMonth){
                    $ValSlave = $DataSlaveOneMonth['Slave'];
                    echo ",'".$ValSlave."'";
                    $Loops++;
                }
                echo "]";
                krsort($TempArrayLoopDateOneMonth);
                foreach($TempArrayLoopDateOneMonth as $DateOneMonth)
                {
                    $ValDateOneMonth = $DateOneMonth['DateLoop'];
                    echo ",['".$ValDateOneMonth."'";
                    for ($i=1; $i <= $TotalSlaveOneMonth; $i++)
                    {
                        foreach (${"ArrTrackingPerDateOneMonth2$i"} as $DataSlaveOneMonth2)
                        {
                            $ValNoSlaveOneMonth = $DataSlaveOneMonth2['NoSlave'];
                            $ValSlaveOneMonth = $DataSlaveOneMonth2['Slave'];
                            $ValDateTrackingOneMonth = $DataSlaveOneMonth2['DateTracking'];
                            $ValTotalUsageOneMonth = $DataSlaveOneMonth2['TotalUsage'];

                            if($i == $ValNoSlaveOneMonth)
                            {
                                if($ValDateTrackingOneMonth == $ValDateOneMonth)
                                {
                                    if($ValTotalUsageOneMonth < 0)
                                    {
                                        $ValTotalUsageOneMonth = 0;
                                    }
                                    echo ",".number_format((float)$ValTotalUsageOneMonth, 4, '.', '')."";
                                }
                            }
                        }
                    }
                    echo "]";
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
                foreach($ArrDataSlave2Month as $DataSlave2Month){
                    $ValSlave = $DataSlave2Month['Slave'];
                    echo ",'".$ValSlave."'";
                    $Loops++;
                }
                echo "]";                              
                krsort($ArrWeeks2Months);
                foreach($ArrWeeks2Months as $DataLoopWeeks2Months)
                {
                    $ValWeeks2Months = $DataLoopWeeks2Months['Weeks'];
                    $ValYearsWeeks2Months = $DataLoopWeeks2Months['Years'];
                    $ValTextWeeks2Months = "Wk. ".(int)$ValWeeks2Months." (".$ValYearsWeeks2Months.")";
                    echo ",['".$ValTextWeeks2Months."'";
                    for ($x=1; $x <= $TotalSlave2Month; $x++)
                    {
                        foreach (${"ArrTrackingResultMonthByWeeks$x"} as $DataSlave2Month2)
                        {
                            $ValNoSlave2Month = $DataSlave2Month2['NoSlave'];
                            $ValTotalUsage2Month = $DataSlave2Month2['TotalUsage'];
                            $ValDateTracking2Month = $DataSlave2Month2['Weeks'];
                            $ValSlave2Month = $DataSlave2Month2['Years'];
                            if($x == $ValNoSlave2Month)
                            {
                                if($ValDateTracking2Month == $ValWeeks2Months)
                                {
                                    if($ValSlave2Month == $ValYearsWeeks2Months)
                                    {
                                        if($ValTotalUsage2Month < 0)
                                        {
                                            $ValTotalUsage2Month = 0;
                                        }
                                        echo ",".number_format((float)$ValTotalUsage2Month, 4, '.', '')."";
                                    }
                                }
                            }
                        }
                    }
                    echo "]";
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
                    chartArea: {top:100,height:"80%",width:"75%"},
                    bar: { groupWidth: '75%' },
                    isStacked:true
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
                foreach($ArrDataSlaveOneYear as $DataSlaveOneYear){
                    $ValSlave = $DataSlaveOneYear['Slave'];
                    echo ",'".$ValSlave."'";
                    $Loops++;
                }
                echo "]";
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
                    for ($i=1; $i <= $TotalSlaveOneYear; $i++)
                    {
                        foreach(${"ArrCountTrackingYear$i"} as $DataLoopOneYear)
                        {
                            $ValDataTrackingNoSlave = $DataLoopOneYear['Slave'];
                            $ValDataTrackingMonthYear = $DataLoopOneYear['MonthYear'];
                            $ValDataTrackingUsage = $DataLoopOneYear['Usage'];
                            if($ValDataTrackingNoSlave == $i && $ValDataTrackingMonthYear == $ArrValMonthYear)
                            {
                                if($ValDataTrackingUsage < 0)
                                {
                                    $ValDataTrackingUsage = 0;
                                }
                                echo ",".number_format((float)$ValDataTrackingUsage, 4, '.', '')."";
                            }
                        }  
                    }
                    echo "]";
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
                    chartArea: {top:100,height:"80%",width:"80%"},
                    bar: { groupWidth: '50%' },
                    isStacked:true
				};
                var chartYear = new google.visualization.BarChart(document.getElementById('GraphYear'));
                chartYear.draw(dataYear, optionsYear);
            }
            google.charts.setOnLoadCallback(drawChart4);
        </script>
        <div id="GraphYear"></div>
    </div> 

</div>
