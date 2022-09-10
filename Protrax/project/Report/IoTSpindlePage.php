<?php
require_once("Modules/ModuleMonitoringMachine.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
$GetMachine = GET_MACHINE_IOT_LISTED($linkMACHWebTrax);
$arr1 = array();
while($Data=sqlsrv_fetch_array($GetMachine))
{
    $NamaMesin = trim($Data['NamaMesin']);
    $KodeMesin = trim($Data['KodeMesin']);
    $DeviceID = trim($Data['IoT_DeviceID']);
    $arrTemp = array("MachineName" => $NamaMesin, "MachineCode" => $KodeMesin, "DeviceID" => $DeviceID);
    array_push($arr1,$arrTemp);
}
?>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report : IoT Machine Spindle Monitoring</li>
            </ol>
        </nav>
    </div>
</div>
<div class="col-md-16" id="IoTSpindleContent">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 text-center fw-bold"><h5>SALATIGA</h5></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="40">No</th>
                                <th class="text-center">MACHINE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no=1;
                            foreach($arr1 as $ValArr1)
                            {
                                $Machine = trim($ValArr1['MachineName']);
                                $Code = trim($ValArr1['MachineCode']);
                                $ID = trim($ValArr1['DeviceID']);
                                $InsideTable = '<div class="col-md-12">Machine Name : <strong>'.$Machine.'</strong><br>Machine Code : <strong>'.$Code.'</strong>
                                <br>IoT DeviceID : <strong>'.$ID.'</strong></div>';
                                $Datax = GET_MACHINE_STATUS($ID,$linkMACHWebTrax);
                                while($ResStatus=sqlsrv_fetch_array($Datax))
                                {
                                    $Status = trim($ResStatus['Status2']);
                                    $Battery = trim($ResStatus['DeviceBattery']);
                                }
                                $InsideTable2 = '<div class="col-md-12"><h6>Machine Status : <strong>['.$Status.']</strong></h6><br></div><i>Device Battery : '.$Battery.'%</i>';
                                if($Status == 'ON')
                                {
                                    $BackGround = ' class="alert alert-success"';
                                }
                                else
                                {
                                    $BackGround = '';
                                }
                            ?>
                            <tr <?php echo $BackGround; ?> >
                                <td class="text-center"><?php echo $no ;?></td>
                                <td>
                                    <div>
                                        <?php echo $InsideTable; ?>
                                        <?php echo $InsideTable2; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div>
<!-- <script>
$(document).ready(function() {
    $.ajax({
        url: 'project/Report/IoTSpindleContent.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        beforeSend: function () {
            $('#IoTSpindleContent').html("");
            $("#IoTSpindleContent").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#IoTSpindleContent').html("");
        },
        success: function(xaxa){
            $('#load_img').hide();
            $('#IoTSpindleContent').hide();
            $('#IoTSpindleContent').html(xaxa);
            $('#IoTSpindleContent').fadeIn('fast');
            $("#ContentLoadingTT").remove();

        },
        error: function() {
            alert('Request cannot proceed!');
        }
    });
});
</script> -->
<script>
$(document).ready(function () 
{
    var i = 0;
    function LoopSecond() {
        i++;
        if (i <= 15) {
            setTimeout(LoopSecond, 1000);
        }
        else
        {
            i = 1;
            LOAD_MONITOR();
            setTimeout(LoopSecond, 1000);
        }
    }
    LoopSecond();
});
function LOAD_MONITOR()
{
    var formdata = new FormData();
    formdata.append("Active", "10");
    $.ajax({
        url: 'project/Report/IoTSpindleContent.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        success: function (xaxa) {
            $("#IoTSpindleContent").html(xaxa);
			$("#IoTSpindleContent").fadeIn();
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#IoTSpindleContent").html("");
        }
    });
}
</script>