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
function isWeekend($date) {
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
}

$ValDateAwal = $ValDateAkhir = 0;
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ArrDiv = htmlspecialchars(trim($_POST['DivName']), ENT_QUOTES, "UTF-8");
    $ValArr = explode("-",$ArrDiv);
    $ValDiv = $ValArr[0];
    $ValKode = $ValArr[1];
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");
    // echo "$ValDiv <br>";
    
    $ArrayTT = array();
    switch ($ValFilterType) {
        case 'Daily':
            {
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
                
                ?>
                <div><h5><strong>Time Tracking (<?php echo $ValDate;?>)</strong></h5></div>
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
                <div><h5><strong>Time Tracking (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)
                <br>Week : <?php echo $week;?></strong></h5></div>
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
                <div><h5><strong>Time Tracking (<?php echo $Month."/".$Year;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Half':
            {
                $ArrHalf = explode("-",$ValClosedTime);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                
                if($Half == "H1"){ $ValDateAwal="1/01/".$Year; $ValDateAkhir="6/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="7/01/".$Year; $ValDateAkhir="12/31/".$Year;}
               
                ?>
                <div><h5><strong>Time Tracking (<?php echo $ValClosedTime;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
    }
    // echo $ValDateAwal."||".$ValDateAkhir."||".$ValDiv."<br>";
    $ArrHoliday = array();
    $Holiday = GET_NATIONAL_HOLIDAY($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
    while($HolidayRes = sqlsrv_fetch_array($Holiday))
    {
        $ValHolidayDate = trim($HolidayRes['HolidayDate']);
        $IsWeekend = isWeekend($ValHolidayDate);
        if($IsWeekend != 1)
        {
        array_push($ArrHoliday,$ValHolidayDate);
        } else {}
    }
    // print_r($ArrHoliday);
    // echo count($ArrHoliday);
    $QListTT = GET_TT_PER_DIVISION_BY_DATE($ValDateAwal,$ValDateAkhir,$ValDiv,$linkMACHWebTrax);
    while($RListTT = sqlsrv_fetch_array($QListTT)){
        $ValNIK = trim($RListTT['NIK']);
        $ValEmployee = trim($RListTT['FullName']);
        $ValDetailPosition = trim($RListTT['DetailPosition']);
        $ValCompanyID = trim($RListTT['CompanyCode']);
        $ValDivID = trim($RListTT['DivisionName']);
        $ValAtt = trim($RListTT['AttTime']);
        $ValStabilizes = trim($RListTT['Stabilizes']);
        $ValPTOHour = trim($RListTT['PTOHour']);
        $ValOtherHour = trim($RListTT['Ijin_Out']);
        $Extra = trim($RListTT['Extra']);
        $WorkHour = trim($RListTT['TotalWorkHourPerDay']);
        if($ValEmployee == 'NURSETYO CAHYONO' || $ValEmployee == 'YODHA PRADANA'|| $ValEmployee == 'TAOFANO BAGUS IRIANTO')
        {$FullName = $ValEmployee." - PSM";} else {$FullName = $ValEmployee;}
        $TemporaryArray = array(
        "NIK" => $ValNIK,
        "FullName" => $ValEmployee,
        "Position" => $ValDetailPosition,
        "Location" => $ValCompanyID,
        "Division" => $ValDivID,
        "Attendance" =>  $ValAtt,
        "Stabilizes" => $ValStabilizes,
        "PTOHour" => $ValPTOHour,
        "Ijin_Out" => $ValOtherHour,
        "NameSet" => $FullName,
        "XtraHour" => $Extra,
        "EmpWorkHour" => $WorkHour
        );
        array_push($ArrayTT,$TemporaryArray);
    }
    // print_r($ArrayTT[0]);

?>

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="TabelReconTT">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width = "20">No</th>
                <th class="text-center trowCustom" width = "175">Employee Name</th>
                <th class="text-center trowCustom">Position</th>
                <th class="text-center trowCustom" width = "90">Division</th>
                <th class="text-center trowCustom" width = "60">Location</th>
                <th class="text-center trowCustom" width = "80">Time Tracking*<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "80">Total Work<br>Hour (hh:mm)</th>
                <th class="text-center trowCustom" width = "80">Attendance<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "80">PTO<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "80">Business Leave<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "80">SIK WFH<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "25">#</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $NO=1;
        foreach($ArrayTT as $ValArrayTT)
        {
            $NIK = trim($ValArrayTT['NIK']);
            $ValName = trim($ValArrayTT['FullName']);
            $ValPosition = trim($ValArrayTT['Position']);
            $ValLoc = trim($ValArrayTT['Location']);
            $ValDivision = trim($ValArrayTT['Division']);
            $ValATT = trim($ValArrayTT['Attendance']);
            $ValSTB = trim($ValArrayTT['Stabilizes']);
            $ValPTO = trim($ValArrayTT['PTOHour']);
            $ValOthers = trim($ValArrayTT['Ijin_Out']);
            $ValNameSet = trim($ValArrayTT['NameSet']);
            $XtraHours = trim($ValArrayTT['XtraHour']);
            $EmpWorkHour = trim($ValArrayTT['EmpWorkHour'])*60;
            $ValSTBs = round((float)$ValSTB, 0);
            $ValATTs = round((float)$ValATT, 0);

            $ValHoliday = @(count($ArrHoliday)*$EmpWorkHour);
            $ValATTs = @($ValATTs + $ValHoliday);
            $ValATTs = round((float)$ValATTs, 0);
            if($ValKode == '200')
            {
                $ValHoliday = @(count($ArrHoliday)*$EmpWorkHour);
                $ValSTBs = @($ValSTBs + $ValHoliday);
                $ValSTBs = round((float)$ValSTBs, 0);
            }
            // $EmpWorkHour = round((float)$EmpWorkHour, 0);
            // $ValHoliday = @(count($ArrHoliday)*$EmpWorkHour);
            // $ValSTBFinal = @($ValSTB - $ValHoliday);
            $TotalWork = @($ValATTs + $ValPTO + $ValOthers + $XtraHours);
            $ConvertATT = ConvertMinutes2Hours($ValATTs);
            $ConvertSTB = ConvertMinutes2Hours($ValSTBs);
            $ConvertPTO = ConvertMinutes2Hours($ValPTO);
            $ConvertOthers = ConvertMinutes2Hours($ValOthers);
            $ConvertTotal = ConvertMinutes2Hours($TotalWork);
            $ConvertExtra = ConvertMinutes2Hours($XtraHours);

            $RowEnc = base64_encode($ValFilterType."*".$NIK."*".$ValDate."*".$ValName."*".$ValLoc."*".$ValClosedTime."*".$ValDiv."*".$ValNameSet."*".trim($ValArrayTT['EmpWorkHour'])."*".$ValKode);
            $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#ModalDetail" title="Update"></span>'
        ?>
            <tr class="RowTTRecon" data-cookies="<?php echo $RowEnc; ?>">
            <!-- <tr> -->
                <td class="text-center"><?php echo $NO; ?></td>
                <td class="text-left"><?php echo $ValName; ?></td>
                <td class="text-left"><?php echo $ValPosition; ?></td>
                <td class="text-left"><?php echo $ValDivision; ?></td>
                <td class="text-center"><?php echo $ValLoc; ?></td>
                <td class="text-center"><?php echo $ConvertSTB; ?></td>
                <td class="text-center"><?php echo $ConvertTotal; ?></td>
                <td class="text-center"><?php echo $ConvertATT; ?></td>
                <td class="text-center"><?php echo $ConvertPTO; ?></td>
                <td class="text-center"><?php echo $ConvertOthers; ?></td>
                <td class="text-center"><?php echo $ConvertExtra; ?></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>
        <?php
        $NO++;
        }

        ?>
        </tbody>
    </table><i>*)Stabilized Hour</i>
</div>

<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Employee Time Tracking Details</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="ContentDetails"></div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
else{ echo ""; }

?>

<script>
$(document).ready(function () {
    $("#TabelReconTT").dataTable({
		"bInfo": false
	});
	$("#TabelReconTT").css("margin-bottom","10px");
    $("#ModalDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        // var DataTemp = act.data('dcode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        // formdata.append("ValTemp", DataTemp);
        $.ajax({
            url: 'project/reconciliation/ModalDetailTTRecon.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentDetails').hide();
                $('#ContentDetails').html(xaxa);
                $('#ContentDetails').fadeIn('fast');
                
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
});
</script>


