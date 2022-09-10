<?php
// require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$thisMonth = date('m/Y',strtotime("-1 days"));

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFilterType = htmlspecialchars(trim($_POST['ValFilterType']), ENT_QUOTES, "UTF-8");
    $ValMachType = htmlspecialchars(trim($_POST['ValMachine']), ENT_QUOTES, "UTF-8");
    // echo $ValFilterType; 
    // echo $ValMachType;


    if($ValFilterType == "Daily")
    {
?>
        <div class="form-group">
            <label for="txtDaily">Choose Date</label>
            <div class="controls">
                <div class="input-group"><input id="Daily" name="txtFilterTanggal" type="text" class="datepicker form-control" value="<?php echo $yesterday;?>">
                <label for="Daily" class="input-group-addon btn"><span class="datepicker glyphicon glyphicon-calendar"></span></label>
                </div>
            </div>
        </div>
<?php
    }
    elseif($ValFilterType == "Weekly")
    {
?>
        <div class="form-group">
            <label for="txtFrom">Choose Date</label>
            <div class="controls">
                <div class="input-group"><input id="Weekly" name="txtFilterTanggal1" type="text" class="datepicker form-control" value="<?php echo $yesterday;?>">
                <label for="Weekly" class="input-group-addon btn"><span class="datepicker glyphicon glyphicon-calendar"></span></label>
                </div>
                <!-- <input type="text" name="datetimes" /> -->
            </div>
        </div>
<?php
    }
    elseif($ValFilterType == "Monthly")
    {
?>
        <div class="form-group">
            <label for="txtFrom">Choose Date</label>
            <div class="controls">
                <div class="input-group"><input id="Monthly" name="txtFilterTanggal1" type="text" class="datepicker form-control" value="<?php echo $thisMonth;?>">
                <label for="Monthly" class="input-group-addon btn"><span class="datepicker glyphicon glyphicon-calendar"></span></label>
                </div>
            </div>
        </div>
<?php
    }
    else
    {
?>
        <div class="form-group">
                <label for="InputHalf">Season</label>
                <select class="form-control" id="InputHalf">
                <option>OPEN</option><?php 
                $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN($linkMACHWebTrax);
                while($RListClosedTime = sqlsrv_fetch_array($QListClosedTime))
                {
                    $ClosedTime = $RListClosedTime['ClosedTime'];
                    ?>
                    <option><?php echo $ClosedTime; ?></option>
                    <?php
                }                
                ?></select>
        </div>

<?php
    }
}
?>
<div>
    <button type="button" class="btn btn-dark btn-labeled" id="BtnSearchMach" onclick="cariDataMesin()">Search</button>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#Daily').datepicker({
            format: "mm/dd/yyyy",
            autoclose: "true"
        });
        $("#Weekly").datepicker({
            // weekNumber : "true",
            format: "mm/dd/yyyy",
            autoclose: "true"
        });
        // $("#ToDate").datepicker({
        //     // weekNumber : "true",
        //     format: "yyyy-mm-dd",
        //     autoclose: "true"
        // });
        $('#Monthly').datepicker({
            format: "mm/yyyy",
            autoclose: "true"
        });
    });
    function cariDataMesin() {
        $('#BtnSearchTT').attr('disabled', true);
        $('#load_img').show();
        var FilDate = $('#Daily').val();
        var FilHalf = $("#InputHalf").children("option:selected").val();
        // var FilDate2 = $('#InputHalf').txt().trim();
        var FilDate3 = $('#Monthly').val();
        var FilDate2 = $('#Weekly').val();
        var formdata = new FormData();
        formdata.append("DailyDate", FilDate);
        formdata.append("HalfClosed", FilHalf);
        formdata.append("MonthFilter", FilDate3);
        formdata.append("WeekFilter", FilDate2);
        formdata.append("MachineName", '<?php echo $ValMachType; ?>');
        formdata.append("FilterType", '<?php echo $ValFilterType; ?>');
        $.ajax({
            url: 'project/reconciliation/reconmachinecontent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#MachDetail').html("");
                $("#MachDetail").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#MachDetail').html("");
            },
            success: function(xaxa){
                $('#load_img').hide();
                $('#MachDetail').hide();
                $('#MachDetail').html(xaxa);
                $('#MachDetail').fadeIn('fast');
                $('#BtnSearchTT').attr('disabled', false);
                // $('#TipeFilter').hide();
                $("#ContentLoadingTT").remove();
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    }
</script>