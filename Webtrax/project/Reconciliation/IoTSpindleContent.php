<?php

require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}
function getDatesFromRange($start, $end) {
    // $array = array();
    // $interval = new DateInterval('P1D');
  
    // $realEnd = new DateTime($end);
    // $realEnd->add($interval);
  
    // $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    // foreach($period as $date) {                 
    //     $array[] = $date->format($format); 
    // }
    $Date1 = $start;
    $Date2 = $end;
    
    $array = array();
    
    $Variable1 = strtotime($Date1);
    $Variable2 = strtotime($Date2);
    
    for ($currentDate = $Variable1; $currentDate <= $Variable2; 
                                    $currentDate += (86400)) {
                                        
    $Store = date('m/d/Y', $currentDate);
    $array[] = $Store;
    }
    return $array;
}
?>
<style>
.Points{
    font-size:10px;
}
</style>
<div class="col-md-12">
<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    switch ($ValFilterType) {
        case 'Daily':
            {
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
                ?>
                <div>
                    <h5><strong>Machine Spindle Report  (<?php echo $ValDate;?>)</strong></h5>
                </div>
                <br>
                <?php
            }
        break;
        case 'Weekly':
            {
                $ArrWeek = explode("/",$ValDate);
                $Day = $ArrWeek[0];
                $Month = $ArrWeek[1];
                $Year = $ArrWeek[2];

                $date = new DateTime($ValDate);
                $week = $date->format("W");
                $week_array = getStartAndEndDate($week,$Year);
                $ArrRangeWeek2 = explode("#",$week_array);
                $ValDateAwal = $ArrRangeWeek2[0];
                $ValDateAkhir = $ArrRangeWeek2[1];
                ?>
                <div>
                    <h5><strong>Machine Spindle Report  (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>) WEEK : <?php echo $week; ?></strong></h5>
                </div>
                <br>
                <?php
            }
        break;
    }
?>
</div>
<?php
if($ValFilterType == 'Daily'){
    ?>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableReport">
                <thead class="theadCustom">
                    <tr>
                        <th>Machine Name</th>
                        <th>Device ID</th>
                        <th>Machine On Time (Hour)</th>
                        <th>Machine Off Time (Hour)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $no = 1;
                $data = GET_SUMARRY_ONOFF_HOUR($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $Machine = trim($res['Machine']);
                    $DeviceID = trim($res['DeviceID']);
                    $OnTime = trim($res['RunHour']);
                    $OffTime = trim($res['OffHour']);
                    $OnTime = number_format((float)$OnTime, 2, '.', ',');
                    $OffTime = number_format((float)$OffTime, 2, '.', ',');
                    $enc = base64_encode($DeviceID."*".$ValDateAwal."*".$ValDateAkhir);
                    ?>
                    <tr class="DataChild" data-float="<?php echo $enc; ?>">
                        <td class="text-left"><?php echo $Machine; ?></td>
                        <td class="text-center"><?php echo $DeviceID; ?></td>
                        <td class="text-right"><?php echo $OnTime; ?></td>
                        <td class="text-right"><?php echo $OffTime; ?></td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
    }
    else{
        
    $ArrDate = getDatesFromRange($ValDateAwal, $ValDateAkhir);
    ?>
    <div class="col-md-12">
        <div style="width:100%; overflow-x:scroll;">
            <table class="table table-bordered table-hover" id="TableReport">
                <thead class="theadCustom Points">
                    <tr>
                        <th rowspan="2"><span style="margin-left:50px; margin-right:50px;">MachineName</span></th>
                        <th rowspan="2">DeviceID</th>
                        <?php
                        foreach($ArrDate as $ValArrDate)
                        {
                        ?>
                            <th colspan="2"><?php echo $ValArrDate; ?></th>
                        <?php
                        }
                        ?>
                        <th colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                        <th>On Time(Hour)</th>
                        <th>Off Time(Hour)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Value = $TotalValueON =  0;
                    $TotalValueOFF = 0;
                    $arr = array("On","Off");
                    $DataM = GET_NAMA_MESIN($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                    while($ValM=sqlsrv_fetch_array($DataM))
                    {
                        $ValMachineName = trim($ValM['MachineName']);
                        $ValDevice = trim($ValM['DeviceID']);
                        $enc = base64_encode($ValDevice."*".$ValDateAwal."*".$ValDateAkhir);
                        ?>
                        <tr class="DataChild" data-float="<?php echo $enc; ?>">
                            <td><?php echo $ValMachineName; ?></td>
                            <td><?php echo $ValDevice; ?></td>
                            <?php
                            foreach($ArrDate as $ValDates)
                            {
                                foreach($arr as $OnOff)
                                {
                                    $Value = GET_SPINPDLE_ON_OFF($ValDevice,$ValDates,$ValDates,$OnOff,$linkMACHWebTrax);
                                    
                                    $Value = number_format((float)$Value, 2, '.', ',');
                            ?>
                            <td class="text-right"><?php echo $Value; ?></td>
                            <?php
                                }
                            }
                            $TotalValueON = GET_SPINPDLE_ON_OFF($ValDevice,$ValDateAwal,$ValDateAkhir,"ON",$linkMACHWebTrax);
                            $TotalValueOFF = GET_SPINPDLE_ON_OFF($ValDevice,$ValDateAwal,$ValDateAkhir,"OFF",$linkMACHWebTrax);
                            $TotalValueON = number_format((float)$TotalValueON, 2, '.', ',');
                            $TotalValueOFF = number_format((float)$TotalValueOFF, 2, '.', ',');
                            ?>
                            <td class="text-right"><strong><?php echo $TotalValueON; ?></strong></td>
                            <td class="text-right"><strong><?php echo $TotalValueOFF; ?></strong></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    }
}
?>
