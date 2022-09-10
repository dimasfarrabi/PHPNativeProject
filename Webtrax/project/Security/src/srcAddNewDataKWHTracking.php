<?php 
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleSecurity.php");
date_default_timezone_set("Asia/Jakarta");
$TimeNow = date("Y-m-d H:i:s");
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
    $ValDate = htmlspecialchars(trim($_POST['ValDate']), ENT_QUOTES, "UTF-8");
    $ValUsage = htmlspecialchars(trim($_POST['ValUsage']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(base64_decode($ValLocation));
    $ArrLocation = explode("#",$ValLocation);
    $Locations = $ArrLocation[1];
    $ValUser = trim(strtoupper(base64_decode($_SESSION['FullNameUser'])));
    # insert data
    ADD_DATA_KWH_TRACKING_SECURITY($Locations,$ValDate,$ValUsage,$ValUser,$TimeNow,$linkHRISWebTrax);
    INSERT_NEW_DATA_USAGE("Slave 1",$ValDate,$ValUsage,$Locations,$linkHRISWebTrax);
    # generate data
    $StartTime = strtotime(date('Y-m-d',strtotime($ValDate)));
    $EndTime = strtotime(date('Y-m-d', strtotime($ValDate)));
    # data slave
    $ArraySlave = array();
    $ArrayMaxMinTimeLog = array();
    $QDataSlave = GET_SLAVE_USAGE_BY_DATE($ValDate,$ValDate,$Locations,$linkHRISWebTrax);
    $CountSlave = mssql_num_rows($QDataSlave);
    while($RDataSlave = mssql_fetch_assoc($QDataSlave))
    {
        $SlaveTemp = $RDataSlave['Slave'];
        $ArrayTempSlave = array("Slave" => $SlaveTemp);
        array_push($ArraySlave,$ArrayTempSlave);
    }
    do 
    {
        # start looping date
        $Date = date("m/d/Y",$StartTime);
        $Date2 = date('m/d/Y',strtotime("+1 day",strtotime($Date)));
        $DateBefore = date('m/d/Y',strtotime("-1 day",strtotime($Date)));        
        # looping slave
        foreach($ArraySlave as $DataSlave)
        {
            $ValSlave = $DataSlave['Slave'];
            # data tracking sebelumnya
            $LastDate = "-";
            $LastTime = "-";
            $LastKWH = "-";
            $QLastDataKWH = GET_TOP_1_MAX_TIME_DATA_USAGE($DateBefore,$ValSlave,$Locations,$linkHRISWebTrax);
            $NumRowLast = mssql_num_rows($QLastDataKWH);
            if($NumRowLast != "0")
            {
                $RLastDataKWH = mssql_fetch_assoc($QLastDataKWH);
                $LastDate = date('m/d/Y',strtotime($RLastDataKWH['DateLog']));
                $LastTime = date('H:i:s',strtotime($RLastDataKWH['TimeLog']));
                $LastKWH = $RLastDataKWH['KWH'];
            }
            # array data tracking
            $ArrDataTracking = array();
            $QDataTracking = GET_DATA_USAGE_BY_DATE_AND_SLAVE($Date,$ValSlave,$Locations,$linkHRISWebTrax);
            while($RDataTracking = mssql_fetch_assoc($QDataTracking))
            {
                $NoSlaveData = $RDataTracking['Slave'];
                $KWHData = $RDataTracking['KWH'];
                $LogData = $RDataTracking['Log'];
                $DateLogData = $RDataTracking['DateLog'];
                $HourData = substr($RDataTracking['TimeLog'],0,2);
                $HourData = (int)$HourData.":00";
                
                switch ($Locations) {
                    case 'FI':
                        $Const = 60;
                        break;
                    case 'PSM':
                        $Const = 40;
                        break;
                    case 'PSL':
                        $Const = 800;
                        break;
                    default:
                        $Const = 0;
                        break;
                }

                $TempArray = array(
                    "Slave" => $NoSlaveData,
                    "KWH" => $KWHData,
                    "Log" => $LogData,
                    "CombineLog" => date('m/d/Y',strtotime($DateLogData))." ".$HourData,
                    "DateLog" => $DateLogData,
                    "TimeLog" => $HourData,
                    "DateBefore" => $LastDate,
                    "LastTimeBefore" => $LastTime,
                    "LastKWHBefore" => $LastKWH,
                    "DiffKWH" => round(round(($KWHData-$LastKWH),4)*$Const,4)
                );
                array_push($ArrDataTracking,$TempArray);
            }
            foreach ($ArrDataTracking as $ValDataKWHTracking)
            {
                $ValSlave = $ValDataKWHTracking['Slave'];
                $ValKWH = $ValDataKWHTracking['KWH'];
                $ValLog = $ValDataKWHTracking['Log'];
                $ValCombineLog = $ValDataKWHTracking['CombineLog'];
                $ValDateLog = $ValDataKWHTracking['DateLog'];
                $ValTimeLog = $ValDataKWHTracking['TimeLog'];
                $ValDateBefore = $ValDataKWHTracking['DateBefore'];
                $ValLastTimeBefore = $ValDataKWHTracking['LastTimeBefore'];
                $ValLastKWHBefore = $ValDataKWHTracking['LastKWHBefore'];
                $ValDiffKWH = $ValDataKWHTracking['DiffKWH']; 
                # simpan ke database
                INSERT_DATA_LOG($ValSlave,$ValDateLog,$ValKWH,$ValDiffKWH,$Locations,$linkHRISWebTrax);
            }
        }        
        $StartTime = strtotime('+1 day',$StartTime);
    }
    while ($StartTime <= $EndTime);
        
        
    echo '<div class="col-md-12"><span class="text-success"><strong><h5>Data successfully added!</h5></strong></span></div>';
}
else
{
    echo "";    
}
?>