<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleSyncRecon.php"); 
date_default_timezone_set("Asia/Jakarta");
$ValDateAwal = $ValDateAkhir = 0;

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ReportType = htmlspecialchars(trim($_POST['ReportType']), ENT_QUOTES, "UTF-8");
    $FilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");
    // $ValClosedTime2 = htmlspecialchars(trim($_POST['ClosedTime2']), ENT_QUOTES, "UTF-8");

    // echo $ReportType."<br>".$FilterType."<br>".$ValDate."<br>".$ValClosedTime."<br>".$ValClosedTime2;

    switch ($FilterType) {
        case 'Daily':
            {
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
                ?>
                <br>
                <div><h5><strong>PSM to PSL Synchronize (<?php echo $ValDate;?>)</strong></h5></div>
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
                <br>
                <div><h5><strong>PSM to PSL Synchronize (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)
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
                <br>
                <div><h5><strong>PSM to PSL Synchronize (<?php echo $Month."/".$Year;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'ClosedTime':
            {
                $ArrHalf = explode("-",$ValClosedTime);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                
                if($Half == "H1"){ $ValDateAwal="1/01/".$Year; $ValDateAkhir="6/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="7/01/".$Year; $ValDateAkhir="12/31/".$Year;}
                ?>
                <br>
                <div><h5><strong>PSM to PSL Synchronize (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?>)</strong></h5></div>
                <br>
                <?php
            }
        break;
        default:
        {
            
            ?>
            <br>
            <div><h5><strong>PSM to PSL Synchronize</strong></h5></div>
            <br>
            <?php
        }
        break;
    }
    switch ($ReportType) {
        case 'Time Tracking':
            {
                // echo $ValDateAwal,$ValDateAkhir;
                $QList = GET_PSL_TIME_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $QList2 = GET_PSM_TIME_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$PSMConn);
                $x = "Division";
            }
        break;
        case 'Machine Tracking':
            {
                // echo $ValDateAwal,$ValDateAkhir;
                $QList = GET_PSL_MACHINE_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $QList2 = GET_PSM_MACHINE_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$PSMConn);
                $x = "Machine Name";
            }
        break;
        case 'Material Tracking':
            {
                // echo $ValDateAwal,$ValDateAkhir;
                $QList = GET_PSL_MATERIAL_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $QList2 = GET_PSM_MATERIAL_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$PSMConn);
                $x = "Category";
            }
        break;
        case 'WO Mapping':
            {
                // echo $ValClosedTime2;
                $QList = GET_PSL_WO_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $QList2 = GET_PSM_WO_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$PSMConn);
                $x = "Expense Allocation";
            }
        break;
        case 'Barcode Status':
            {
                // echo $ValDateAwal,$ValDateAkhir;
                $QList = GET_PSL_BARCODE_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
                $QList2 = GET_PSM_BARCODE_TRACKING_ROW($ValDateAwal,$ValDateAkhir,$PSMConn);
                $x = "Barcode";
                
            }
        break;
    }
    $Arr1 = array();
    $Arr2 = array();
    while($RList = sqlsrv_fetch_array($QList))
    {
        $ValParam = trim($RList['Params']);
        $ValNumRow = trim($RList['PSMonPSL']);
        $ArrayPSL = array("Params" => $ValParam, "RowPSL" => $ValNumRow);
        array_push($Arr1,$ArrayPSL);
    }
    while($RList2 = sqlsrv_fetch_array($QList2))
    {
        $ValParam2 = trim($RList2['Params']);
        $ValNumRowPSM = trim($RList2['PSMRow']);
        $ArrayPSM = array("Params" => $ValParam2, "RowPSM" => $ValNumRowPSM);
        array_push($Arr2,$ArrayPSM);
    }

?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center trowCustom" width = "40">No</th>
                <th class="text-center trowCustom"><?php  echo $x;?></th>
                <th class="text-center trowCustom">Number of PSL Rows</th>
                <th class="text-center trowCustom">Number of PSM Rows</th>
                <th class="text-center trowCustom">Difference</th>
                <th class="text-center trowCustom">#</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no=1;
        $ValNumRowPSM = $TotalPSM = $TotalPSL = 0;
        foreach($Arr2 as $ValArr2)
        {
            $ValParam2 = $ValArr2['Params'];
            $ValNumRowPSM = $ValArr2['RowPSM'];
            foreach($Arr1 as $ValArr)
            { 
                $ValParam = $ValArr['Params'];
                $ValNumRow = $ValArr['RowPSL'];
                $ValDiff = ($ValNumRowPSM - $ValNumRow);
                if($ValParam == $ValParam2)
                {
                    $RowEnc = base64_encode($ReportType."*".$ValParam."*".$ValDateAwal."*".$ValDateAkhir);
                    $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#SyncDetail" title="Detail"></span>'
        ?>
            <tr>
                <td class="text-center"><?php echo $no; ?></td>
                <td class="text-left"><?php echo $ValParam2; ?></td>
                <td class="text-center"><?php echo $ValNumRow; ?></td>
                <td class="text-center"><?php echo $ValNumRowPSM; ?></td>
                <td class="text-center"><?php echo $ValDiff; ?></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>
        <?php
        $no++;
        $TotalPSL = $TotalPSL + $ValNumRow;
        $TotalPSM = $TotalPSM + $ValNumRowPSM;
                }
            }
        }
        $TotalDiff = ($TotalPSM - $TotalPSL)
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center theadCustom" colspan="2">TOTAL</th>
                <td class="text-center"><?php echo $TotalPSL; ?></td>
                <td class="text-center"><?php echo $TotalPSM; ?></td>
                <td class="text-center"><?php echo $TotalDiff; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal fade" id="SyncDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Details</strong></h5><span></span></div>
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
    </div>    
<?php
}
?>
<script>
$(document).ready(function () {
    $("#SyncDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("Code", DataCode);
        $.ajax({
            url: 'project/reconciliation/SyncModalDetail.php',
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