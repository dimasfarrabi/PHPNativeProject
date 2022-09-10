<?php 
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");

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
    $ValDateStart = htmlspecialchars(trim($_POST['ValDateStart']), ENT_QUOTES, "UTF-8");
    $ValDateEnd = htmlspecialchars(trim($_POST['ValDateEnd']), ENT_QUOTES, "UTF-8");    
    # set duration
    $StartTime = strtotime(date('Y-m-d',strtotime($ValDateStart)));
    $EndTime = strtotime(date('Y-m-d', strtotime($ValDateEnd)));
    # data slave
    $ArraySlave = array();
    $ArrayMaxMinTimeLog = array();
    $QDataSlave = GET_SLAVE_USAGE_BY_DATE($ValDateStart,$ValDateEnd,"FI",$linkHRISWebTrax);
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
        # reset data hari terpilih
        DELETE_DATA_USAGE_LOG_BY_DATE($Date,"FI",$linkHRISWebTrax);
        
        # looping slave
        foreach($ArraySlave as $DataSlave)
        {
            $ValSlave = $DataSlave['Slave'];
            # data tracking sebelumnya
            $LastDate = "-";
            $LastTime = "-";
            $LastKWH = "-";
            $QLastDataKWH = GET_TOP_1_MAX_TIME_DATA_USAGE($DateBefore,$ValSlave,"FI",$linkHRISWebTrax);
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
            $QDataTracking = GET_DATA_USAGE_BY_DATE_AND_SLAVE($Date,$ValSlave,"FI",$linkHRISWebTrax);
            while($RDataTracking = mssql_fetch_assoc($QDataTracking))
            {
                $NoSlaveData = $RDataTracking['Slave'];
                $KWHData = $RDataTracking['KWH'];
                $LogData = $RDataTracking['Log'];
                $DateLogData = $RDataTracking['DateLog'];
                $HourData = substr($RDataTracking['TimeLog'],0,2);
                $HourData = (int)$HourData.":00";
                
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
                    "DiffKWH" => round(round(($KWHData-$LastKWH),4)*60,4)
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
                INSERT_DATA_LOG($ValSlave,$ValDateLog,$ValKWH,$ValDiffKWH,"FI",$linkHRISWebTrax);
            }
        }        
        $StartTime = strtotime('+1 day',$StartTime);
    }
    while ($StartTime <= $EndTime);
    ?>
<div class="col-sm-12">
    <h5>Proses generate dari <?php echo $ValDateStart; ?> ke <?php echo $ValDateEnd; ?>  berhasil.</h5>
</div>
    <?php
}
else
{
    echo "";    
}
?>