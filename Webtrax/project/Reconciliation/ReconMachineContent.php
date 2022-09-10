
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
    $ValMachType = htmlspecialchars(trim($_POST['MachineType']), ENT_QUOTES, "UTF-8");
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    $ValHalf = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");
    
    switch ($ValFilterType) {
        case 'Daily':
            {
                ?>
                <div><h5><strong>Machine Tracking (<?php echo $ValDate;?>) : <?php echo $ValMachType;?></strong></h5></div>
                <br>
                <?php
                if($ValMachType=="ALL"){$QListMach = GET_DAILY_MACHINE_HOUR_ALL_DIV($ValDate,$linkMACHWebTrax);}
                else{$QListMach = GET_DAILY_MACHINE_HOUR_BY_DIV($ValDate,$ValMachType,$linkMACHWebTrax);}
                
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
                <div><h5><strong>Machine Tracking (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?> )
                <br> Week <?php echo $week;?> : <?php echo $ValMachType;?></strong></h5></div>
                <br>
                <?php
                if($ValMachType=="ALL"){$QListMach = GET_BY_DATE_MACHINE_HOUR_ALL_DIV($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);}
                else{$QListMach = GET_BY_DATE_MACHINE_HOUR_BY_DIV($ValDateAwal,$ValDateAkhir,$ValMachType,$linkMACHWebTrax);}
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
                <div><h5><strong>Machine Tracking (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?> ) : <?php echo $ValMachType;?></strong></h5></div>
                <br>
                <?php
                if($ValMachType=="ALL"){$QListMach = GET_BY_DATE_MACHINE_HOUR_ALL_DIV($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);}
                else{$QListMach = GET_BY_DATE_MACHINE_HOUR_BY_DIV($ValDateAwal,$ValDateAkhir,$ValMachType,$linkMACHWebTrax);}
            }
        break;
        case 'Half':
            {
                $ArrHalf = explode("-",$ValHalf);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                
                if($Half == "H1"){ $ValDateAwal="1/01/".$Year; $ValDateAkhir="6/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="7/01/".$Year; $ValDateAkhir="12/31/".$Year;}
                ?>
                <div><h5><strong>Machine Tracking (<?php echo $ValHalf;?>) : <?php echo $ValMachType;?></strong></h5></div>
                <br>
                <?php
                if($ValMachType=="ALL"){$QListMach = GET_BY_DATE_MACHINE_HOUR_ALL_DIV($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);}
                else{$QListMach = GET_BY_DATE_MACHINE_HOUR_BY_DIV($ValDateAwal,$ValDateAkhir,$ValMachType,$linkMACHWebTrax);}
            }
        break;
    }
    

?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TabelReconMach">
            <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width = "30">No</th>
                <th class="text-center trowCustom" width = "250">Machine Name</th>
                <th class="text-center trowCustom" width = "40">Machine Type</th>
                <th class="text-center trowCustom" width = "40">Location</th>
                <th class="text-center trowCustom" width = "80">Machine Hours<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "80">Real Time<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "80">Spindle Hours<br>(hh:mm)</th>
                <th class="text-center trowCustom" width = "60">Utilize (%)</th>
                <th class="text-center trowCustom" width = "25">#</th>
            </tr>
            
            </thead>
            <tbody>
            <?php
            $ValUtilize = 0;
            $No=1;
            while($RListMach = sqlsrv_fetch_array($QListMach))
            {
                $ValMachine = trim($RListMach['MachineName']);
                $StabilizeHour = trim($RListMach['Stabilizes']);
                $RealTime = trim($RListMach['Realtime']);
                $CycleHour = trim($RListMach['CycleHour']);
                $TipeMesin = trim($RListMach['TipeMesin']);
                $ValLoc = trim($RListMach['Location']);

                $ValSTB = round((float)$StabilizeHour, 0);
                $ValRealTime = round((float)$RealTime, 0);
                $ValCycle = round((float)$CycleHour, 0);
                $ValUtilize = @($ValCycle/$ValSTB*100);

                $ConvertSTB = ConvertMinutes2Hours($ValSTB);
                $ConvertRealTime = ConvertMinutes2Hours($ValRealTime);
                $ConvertCycle = ConvertMinutes2Hours($ValCycle);
                $ValUtilize = number_format((float)$ValUtilize,2,'.',',');

                $RowEnc = base64_encode($ValFilterType."*".$ValMachine."*".$TipeMesin."*".$ValDate."*".$ValHalf);
                $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#ModalDetailMach" title="Update"></span>'
                
            ?>
            <tr  class="RowMachRecon" data-cookies="<?php echo $RowEnc; ?>">
                <td class="text-center"><?php echo $No; ?></td>
                <td class="text-left"><?php echo $ValMachine; ?></td>
                <td class="text-center"><?php echo $TipeMesin; ?></td>
                <td class="text-center"><?php echo $ValLoc; ?></td>
                <td class="text-center"><?php echo $ConvertSTB; ?></td>
                <td class="text-center"><?php echo $ConvertRealTime; ?></td>
                <td class="text-center"><?php echo $ConvertCycle; ?></td>
                <td class="text-center"><?php echo $ValUtilize; ?></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>

            <?php
            $No++;
            }

            ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="ModalDetailMach" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Machine Tracking Details</strong></h5><span></span></div>
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
?>
<script>
$(document).ready(function () {

    $("#TabelReconMach").dataTable({
		"bInfo": true,
        "searching" : false,
	});
	$("#TabelReconMach").css("margin-bottom","10px");

});
</script>
<script>
$(document).ready(function () {
    $("#ModalDetailMach").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/reconciliation/ModalDetailMachRecon.php',
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