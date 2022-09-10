<?php

require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php"); 
date_default_timezone_set("Asia/Jakarta");

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}
?>
<div class="col-md-12">
<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    // echo "$Location >> $Company >> $ValFilterType >> $ValDate";
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
<button id="BtnDownload" type="button" class="btn btn-sm btn-info btn-labeled block" style="float: right; margin-bottom:30px">Download</button>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableReport">
            <thead class="theadCustom">
                <tr>
                    <th width = "20">No</th>
                    <th>Date</th>
                    <th>Machine</th>
                    <th>Shift</th>
                    <th>Run Hour</th>
                    <th>Stabilize (Hour)</th>
                    <th>Idle Time (Hour)</th>
                    <th>Work Order</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no=1;
                $Data = GET_SPINDLE_REPORT_BYDATE($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                while($Datares=sqlsrv_fetch_array($Data))
                {
                    $Date = trim($Datares['DateTracked2']);
                    $Machine = trim($Datares['Machine']);
                    $Shift = trim($Datares['Shift']);
                    $RunHour = trim($Datares['RunHour']);
                    $Stabilize = trim($Datares['Stabilize']);
                    $IdleTime = trim($Datares['IdleTime']);
                    $WOP = trim($Datares['WOParent']);
                    $RunHour = number_format((float)$RunHour, 2, '.', ',');
                    $Stabilize = number_format((float)$Stabilize, 2, '.', ',');
                    $IdleTime = number_format((float)$IdleTime, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center" width="30"><?php echo $no; ?></td>
                    <td class="text-center" width="30"><?php echo $Date; ?></td>
                    <td class="text-left" width="30"><?php echo $Machine; ?></td>
                    <td class="text-left" width="30"><?php echo $Shift; ?></td>
                    <td class="text-right" width="30"><?php echo $RunHour; ?></td>
                    <td class="text-right" width="30"><?php echo $Stabilize; ?></td>
                    <td class="text-right" width="30"><?php echo $IdleTime; ?></td>
                    <td class="text-left" width="30"><?php echo $WOP; ?></td>
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
?>
