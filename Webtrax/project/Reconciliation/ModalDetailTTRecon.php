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
        $minus = "-";
    }
    else
    {
        $Min = $Minutes;
        $minus = "";
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
    $tHours = $minus."".$iHours .":". $Minutes;
    return $tHours;
}
function isWeekend($date) {
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $ValFilterType = $ArrCodeDec[0];
    $ValNIK = $ArrCodeDec[1];
    $ValDate = $ArrCodeDec[2];
    $EmployeeFullName = $ArrCodeDec[3];
    $ValLoc = $ArrCodeDec[4];
    $ValClosedTime = $ArrCodeDec[5];
    $ValDiv = $ArrCodeDec[6];
    $NameSet = $ArrCodeDec[7];
    $EmpWorkHour = $ArrCodeDec[8];
    $Kode = $ArrCodeDec[9];
    $arrNIK = explode(".",$ValNIK);
    $NIKSort = $arrNIK[1];
    switch ($ValFilterType) {
        case 'Daily':
            {
                
                ?>
                <div><h5><strong><?php echo $EmployeeFullName." - ".$ValDate;?></strong></h5></div>
                <br>
                <?php
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
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
                <div><h5><strong><?php echo $EmployeeFullName?> - (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)
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
                ?>
                <div><h5><strong><?php echo $EmployeeFullName?> - (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Half':
            {
                
                $ArrHalf = explode("-",$ValClosedTime);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                // echo $ValClosedTime;
                if($Half == "H1"){ $ValDateAwal="01/01/".$Year; $ValDateAkhir="06/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="07/01/".$Year; $ValDateAkhir="12/31/".$Year;}
                ?>
                <div><h5><strong><?php echo $EmployeeFullName?> - (<?php echo $ValClosedTime;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        }
        // if($EmployeeFullName == 'NURSETYO CAHYONO' || $EmployeeFullName == 'YODHA PRADANA'|| $EmployeeFullName == 'TAOFANO BAGUS IRIANTO')
        // {$FullName = $EmployeeFullName." - PSM";} else {$FullName = $EmployeeFullName;}
        echo $ValNIK;
        $QListTT= GET_EMP_DETAIL_ATT_BYDATE($ValDateAwal,$ValDateAkhir,$NIKSort,$EmployeeFullName,$linkMACHWebTrax);
        $QListPTO= GET_EMP_DETAIL_PTO_BYDATE($ValDateAwal,$ValDateAkhir,$ValNIK,$linkMACHWebTrax);
        $QListWFH= GET_EMP_DETAIL_WFH_BYDATE($ValDateAwal,$ValDateAkhir,$ValNIK,$linkMACHWebTrax);
        $QListSTB= GET_EMP_DETAIL_STB_BYDATE($ValDiv,$ValDateAwal,$ValDateAkhir,$NameSet,$linkMACHWebTrax);

        $ArrSTB = array();
        if($Kode == '200'){
            $Holiday = GET_NATIONAL_HOLIDAY($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
            while($HolidayRes = sqlsrv_fetch_array($Holiday))
            {
                $ValHolidayDate = trim($HolidayRes['HolidayDate']);
                $HolidayDesc = trim($HolidayRes['Description']);
                $IsWeekend = isWeekend($ValHolidayDate);
                if($IsWeekend != 1)
                {
                    $TemporaryArray = array(
                        "Tanggal" => $ValHolidayDate,
                        "Activity" => "Company Holiday",
                        "Stabilize" => ($EmpWorkHour*60)
                    );
                    array_push($ArrSTB,$TemporaryArray);
                } else {}
            }
        }
        while($RListSTB = sqlsrv_fetch_array($QListSTB))
        {
            $Tanggal2 = trim($RListSTB['DATEPART']);
            $Activity = trim($RListSTB['Activity']);
            $Stabilize = trim($RListSTB['Stabilezes']);
            $TemporaryArray = array(
                "Tanggal" => $Tanggal2,
                "Activity" => $Activity,
                "Stabilize" => $Stabilize
            );
            array_push($ArrSTB,$TemporaryArray);
        }
        $ArrAtt = array();
        while($RListTT = sqlsrv_fetch_array($QListTT))
        {
            $Date = trim($RListTT['DATEPART']);
            $TimeIn = trim($RListTT['TimeIn']);
            $StartBreak = trim($RListTT['TimeStartBreak']);
            $EndBreak = trim($RListTT['TimeEndBreak']);
            $TimeOut = trim($RListTT['TimeOut']);
            $AttHours = trim($RListTT['AttTime']);
            $TemporaryArray = array(
                "Date" => $Date,
                "TimeIn" => $TimeIn,
                "StartBreak" => $StartBreak,
                "EndBreak" => $EndBreak,
                "TimeOut" => $TimeOut,
                "AttHours" => $AttHours,
                "Notes" => ""
            );
            array_push($ArrAtt,$TemporaryArray);
        }
        while($RListPTO = sqlsrv_fetch_array($QListPTO))
        {
            $NIK = trim($RListPTO['NIK']);
            $TanggalCuti = trim($RListPTO['DayOff']);
            $Approval = trim($RListPTO['DayOff_Approval']);
            $TipeCuti = trim($RListPTO['TipeCuti']);
            $Hrs = trim($RListPTO['PTOhour']);
            if( $TipeCuti == '1'){ $Ket = "Full Day PTO";} else { $Ket = "Half Day PTO";}
            $TemporaryArray = array(
                "Date" => $TanggalCuti,
                "TimeIn" => "-",
                "StartBreak" => "-",
                "EndBreak" => "-",
                "TimeOut" => "-",
                "AttHours" => $Hrs,
                "Notes" => $Ket
            );
            array_push($ArrAtt,$TemporaryArray);
        }
        while($RListWFH = sqlsrv_fetch_array($QListWFH))
        {
            $NIK = trim($RListWFH['NIK']);
            $TanggalWFH = trim($RListWFH['Dates']);
            $Start = trim($RListWFH['TimeIn']);
            $End = trim($RListWFH['TimeOut']);
            $wfhTime = trim($RListWFH['wfh']);
            $TemporaryArray = array(
                "Date" => $TanggalWFH,
                "TimeIn" => $Start,
                "StartBreak" => "-",
                "EndBreak" => "-",
                "TimeOut" => $End,
                "AttHours" => $wfhTime,
                "Notes" => "WFH"
            );
            array_push($ArrAtt,$TemporaryArray);
        }
        $Holiday = GET_NATIONAL_HOLIDAY($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
        while($HolidayRes = sqlsrv_fetch_array($Holiday))
        {
            $ValHolidayDate = trim($HolidayRes['HolidayDate']);
            $HolidayDesc = trim($HolidayRes['Description']);
            $IsWeekend = isWeekend($ValHolidayDate);
            if($IsWeekend != 1)
            {
                // $TemporaryArray = array(
                //     "Tanggal" => $ValHolidayDate,
                //     "Activity" => $HolidayDesc,
                //     "Stabilize" => ($EmpWorkHour*60)
                // );
                // array_push($ArrSTB,$TemporaryArray);
                $TemporaryArray = array(
                    "Date" => $ValHolidayDate,
                    "TimeIn" => "-",
                    "StartBreak" => "-",
                    "EndBreak" => "-",
                    "TimeOut" => "-",
                    "AttHours" => ($EmpWorkHour*60),
                    "Notes" => "Company Holiday"
                );
                array_push($ArrAtt,$TemporaryArray);
            } else {}
        }
        sort($ArrAtt);
        sort($ArrSTB);
?>
<style>
    .tableFixHead {
        overflow-y: auto;
        height: 280px;
      }
      .tableFixHead thead th {
        position: sticky;
        top: 0;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      th,
      td {
        padding: 8px 16px;
        border: 1px solid #ccc;
      }
      th {
        background: #eee;
      }
</style>
    <div><h5><strong>Detail Attendance</strong></h5></div>
    <div class="table-responsive tableFixHead">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Start Break</th>
                    <th>End Break</th>
                    <th>Time Out</th>
                    <th>Attendance</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalAtt = 0;
            $NO=1;
            $ConvertATT = "";
            foreach($ArrAtt as $ValArrAtt)
            {
                $Tanggal = trim($ValArrAtt['Date']);
                $jamMasuk = trim($ValArrAtt['TimeIn']);
                $Istirahat = trim($ValArrAtt['StartBreak']);
                $Sls_istirahat = trim($ValArrAtt['EndBreak']);
                $Pulang = trim($ValArrAtt['TimeOut']);
                $WorkHour = trim($ValArrAtt['AttHours']);
                $Notes = trim($ValArrAtt['Notes']);
                $ConvertATT = ConvertMinutes2Hours($WorkHour);
                // if($WorkHour != 'PTO'){$ConvertATT = ConvertMinutes2Hours($WorkHour);}
                // else{$ConvertATT = "PTO";}
                
            ?>
            <tr>
                <td class="text-center"><?php echo $NO; ?></td>
                <td class="text-center"><?php echo $Tanggal; ?></td>
                <td class="text-center"><?php echo $jamMasuk; ?></td>
                <td class="text-center"><?php echo $Istirahat; ?></td>
                <td class="text-center"><?php echo $Sls_istirahat; ?></td>
                <td class="text-center"><?php echo $Pulang; ?></td>
                <td class="text-center"><?php echo $ConvertATT; ?></td>
                <td class="text-center"><?php echo $Notes; ?></td>
            </tr>
            <?php
            $NO++;
            $TotalAtt = @($TotalAtt + $WorkHour);
            
            }
            $ConvertTotalATT = ConvertMinutes2Hours($TotalAtt);
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td class="text-center" colspan="6">Total Attendance</td>
                <td class="text-center"><?php echo $ConvertTotalATT; ?></td>
            <tr>
            </tfoot>
        </table>
    </div>

    <div><h5><strong>Detail Time Tracking</strong></h5></div>
    <div class="table-responsive tableFixHead">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>Stabilize</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalSTB = $ValTotalSTB = 0;
            $Num=1;
            foreach($ArrSTB as $ValArrSTB)
            {
                $Tanggal2 = trim($ValArrSTB['Tanggal']);
                $Activity = trim($ValArrSTB['Activity']);
                $Stabilize = trim($ValArrSTB['Stabilize']);
                $Stabilizes = round((float)$Stabilize, 0);
                $ConvertSTB = ConvertMinutes2Hours($Stabilizes);
            ?>
            <tr>
                <td class="text-center"><?php echo $Num; ?></td>
                <td class="text-center"><?php echo $Tanggal2; ?></td>
                <td class="text-left"><?php echo $Activity; ?></td>
                <td class="text-center"><?php echo $ConvertSTB; ?></td>
            </tr>
            <?php
            $Num++;
            $TotalSTB = $TotalSTB + $Stabilize;
            }
            $TotalSTB = round((float)$TotalSTB, 0);
            $ConvertTotalSTB = ConvertMinutes2Hours($TotalSTB);
            
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td class="text-center" colspan="3">Total Stabilize</td>
                <td class="text-center"><?php echo $ConvertTotalSTB; ?></td>
            <tr>
            </tfoot>
        </table>
    </div>
<?php
}
?>