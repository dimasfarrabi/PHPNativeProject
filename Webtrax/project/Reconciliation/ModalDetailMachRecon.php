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
function ConvertMinutes2Hours($Minutes)
{
    if ($Minutes < 0)
    {
        $Min = Abs($Minutes);
    }
    else
    {
        $Min = $Minutes;
    }
    $iHours = Floor(@($Min / 60));
    $Minutes = @($Min - ($iHours * 60)) / 100;
    $tHours = $iHours + $Minutes;
    if ($Minutes < 0)
    {
        $tHours = $tHours * (-1);
    }
    $aHours = explode(".", $tHours);
    $iHours = $aHours[0];
    if (empty($aHours[1]))
    {
        $aHours[1] = "00";
    }
    $Minutes = $aHours[1];
    if (strlen($Minutes) < 2)
    {
        $Minutes = $Minutes ."0";
    }
    $tHours = $iHours .":". $Minutes;
    return $tHours;
}
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $FilterType = $ArrCodeDec[0];
    $MachineName = $ArrCodeDec[1];
    $TipeMesin = $ArrCodeDec[2];
    $ValDate = $ArrCodeDec[3];
    $ValClosedTime = $ArrCodeDec[4];
    // echo $FilterType."<br>".$MachineName."<br>".$TipeMesin."<br>".$ValDate."<br>";

    switch ($FilterType) {
        case 'Daily':
            {
                
                $QListSTB = GET_DETAIL_STB_MESIN_DAILY($ValDate,$MachineName,$linkMACHWebTrax);
                $QList = GET_DETAIL_CYCLE_MESIN_DAILY($ValDate,$MachineName,$linkMACHWebTrax);
                ?>
                <div><h5><strong><?php echo $MachineName." - ".$ValDate;?></strong></h5></div>
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

                $QListSTB = GET_DETAIL_STB_MESIN_BYDATE($ValDateAwal,$ValDateAkhir,$MachineName,$linkMACHWebTrax);
                $QList = GET_DETAIL_CYCLE_MESIN_BYDATE($ValDateAwal,$ValDateAkhir,$MachineName,$linkMACHWebTrax);
                ?>
                <div><h5><strong><?php echo $MachineName?> - (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)
                Week <?php echo $week;?></strong></h5></div>
                <br>
                <?php
                
            }
        break;
        case 'Monthly':
            {
                
                $ArrMonth = explode("/",$ValDate);
                $Month = $ArrMonth[0];
                $Year = $ArrMonth[2];
                $ValDateAwal = $Month ."/01/". $Year;
                $ValDateAkhir = date("m/t/Y", strtotime($ValDateAwal));
                $QListSTB = GET_DETAIL_STB_MESIN_BYDATE($ValDateAwal,$ValDateAkhir,$MachineName,$linkMACHWebTrax);
                $QList = GET_DETAIL_CYCLE_MESIN_BYDATE($ValDateAwal,$ValDateAkhir,$MachineName,$linkMACHWebTrax);
                ?>
                <div><h5><strong><?php echo $MachineName?> - (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)</strong></h5></div>
                <br>
                <?php
                
            }
        break;
        case 'Half':
            {
                
                $ArrHalf = explode("-",$ValClosedTime);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                
                if($Half == "H1"){ $ValDateAwal="1/1/".$Year; $ValDateAkhir="6/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="7/1/".$Year; $ValDateAkhir="12/31/".$Year;}
                $QListSTB = GET_DETAIL_STB_MESIN_BYDATE($ValDateAwal,$ValDateAkhir,$MachineName,$linkMACHWebTrax);
                $QList = GET_DETAIL_CYCLE_MESIN_BYDATE($ValDateAwal,$ValDateAkhir,$MachineName,$linkMACHWebTrax);
                ?>
                <div><h5><strong><?php echo $MachineName?> - (<?php echo $ValClosedTime;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        }

?>
    <div><h5><strong>Detail Stabilize & Real Time</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width = "20">No</th>
                    <th class="text-center trowCustom">Date</th>
                    <th class="text-center trowCustom">Operator</th>
                    <th class="text-center trowCustom">Part Number</th>
                    <th class="text-center trowCustom">Product Name</th>
                    <th class="text-center trowCustom">Expense Allocation</th>
                    <th class="text-center trowCustom">Stabilize<br>(hh:mm)</th>
                    <th class="text-center trowCustom">Real Time<br>(hh:mm)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalSTB = $TotalRealtime = 0;
            $NO=1;
            while($RListSTB = sqlsrv_fetch_array($QListSTB))
            {
                $Tanggal = trim($RListSTB['DateCreated']);
                $OpName = trim($RListSTB['Operator']);
                $PartNum = trim($RListSTB['PartNo']);
                $Product = trim($RListSTB['ProductName']);
                $Expense = trim($RListSTB['ExpenseAllocation']);
                $Stabilizes = trim($RListSTB['Stabilizes']);
                $RealTime = trim($RListSTB['Realtime']);

                $ValSTB = round((float)$Stabilizes, 0);
                $ValRealTime = round((float)$RealTime, 0);
                $TotalSTB = $TotalSTB + $ValSTB;
                $TotalRealtime = $TotalRealtime + $RealTime;

                $ConvertSTB = ConvertMinutes2Hours($ValSTB);
                $ConvertRealTime = ConvertMinutes2Hours($ValRealTime);
                
            ?>
                <tr>
                    <td class="text-center"><?php echo $NO; ?></td>
                    <td class="text-center"><?php echo $Tanggal; ?></td>
                    <td class="text-left"><?php echo $OpName; ?></td>
                    <td class="text-center"><?php echo $PartNum; ?></td>
                    <td class="text-left"><?php echo $Product; ?></td>
                    <td class="text-left"><?php echo $Expense; ?></td>
                    <td class="text-center"><?php echo $ConvertSTB; ?></td>
                    <td class="text-center"><?php echo $ConvertRealTime; ?></td>
                </tr>
            <?php
            
            $NO++;
            }
            $ValTotalSTB = round((float)$TotalSTB, 0);
            $ValTotalRealTime = round((float)$TotalRealtime, 0);
            $ConvertTotalSTB = ConvertMinutes2Hours($ValTotalSTB);
            $ConvertTotalRT = ConvertMinutes2Hours($ValTotalRealTime);
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center trowCustom" Colspan="6"><strong>TOTAL</strong></td>
                    <td class="text-center"><?php echo $ConvertTotalSTB; ?></td>
                    <td class="text-center"><?php echo $ConvertTotalRT; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div><h5><strong>Detail Spindle/Cycle Time</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width = "20">No</th>
                    <th class="text-center trowCustom">Date</th>
                    <th class="text-center trowCustom">Operator</th>
                    <th class="text-center trowCustom">Shift</th>
                    <th class="text-center trowCustom">Project Name</th>
                    <th class="text-center trowCustom">Start Time<br>(hh:mm:ss)</th>
                    <th class="text-center trowCustom">End Time<br>(hh:mm:ss)</th>
                    <th class="text-center trowCustom">Run Hour<br>(hh:mm)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalRunHour = 0;
            $num = 1;
            while($RList = sqlsrv_fetch_array($QList))
            {
                $DateCreated = trim($RList['DateTracked']);
                $Operator = trim($RList['OperatorName']);
                $Shift = trim($RList['Shift']);
                $StartTime = trim($RList['StartTime']);
                $EndTime = trim($RList['EndTime']);
                $RunHour = trim($RList['RunHour']);
                $Project = trim($RList['ProjectName']);
                $TotalRunHour = $TotalRunHour + $RunHour;
                $ValRunHour = round((float)$RunHour, 0);
                $ConvertRunHour = ConvertMinutes2Hours($ValRunHour);
            ?>
                <tr>
                    <td class="text-center"><?php echo $num; ?></td>
                    <td class="text-center"><?php echo $DateCreated; ?></td>
                    <td class="text-left"><?php echo $Operator; ?></td>
                    <td class="text-center"><?php echo $Shift; ?></td>
                    <td class="text-left"><?php echo $Project; ?></td>
                    <td class="text-center"><?php echo $StartTime; ?></td>
                    <td class="text-center"><?php echo $EndTime; ?></td>
                    <td class="text-center"><?php echo $ConvertRunHour; ?></td>
                </tr>
            <?php
            $num++;
            }
            $ValTotalRunHour = round((float)$TotalRunHour, 0);
            $ConvertTotalRH = ConvertMinutes2Hours($ValTotalRunHour);
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center trowCustom" Colspan="7"><strong>TOTAL</strong></td>
                    <td class="text-center"><?php echo $ConvertTotalRH; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
}
else{ echo "";}
?>