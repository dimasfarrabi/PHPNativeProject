<?php
// require_once("../src/Modules/ModuleLogin.php");
require_once("project/Reconciliation/Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$thisMonth = date('m/Y',strtotime("-1 days"));
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=32">Reconciliation : Machine Hour Reconciliation</a></li>
        </ol>
    </div>
</div>
<div class="col-md-12">
    <div class="col-md-3">
            <label for="InputQuoteCategory">Choose Machine Type</label>
                <select class="form-control" id="SelectMachine">
                <option>ALL</option>
                <option>CNC</option>
                <option>NON CNC</option>
                <?php
                // $QListMach = GET_LIST_MACHINE_TYPE($linkMACHWebTrax);
                // while($RListMach = sqlsrv_fetch_array($QListMach))
                // {
                //     $ValMachine = $RListMach['Machine'];
                ?>
                <!-- <option><?php echo $ValMachine; ?></option> -->
                <?php
                // }
                ?>
                </select>
    </div>
    <div class="col-md-3">
            <label for="InputQuoteCategory">Choose Filter</label>
            <form id="RadioFilter">
                <label class="radio-inline">
                <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Daily"><strong>Daily</strong></label>
                <label class="radio-inline">
                <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Weekly"><strong>Weekly</strong></label>
                <label class="radio-inline">
                <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Monthly"><strong>Monthly</strong></label>
                <label class="radio-inline">
                <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter Half" value="Half"><strong>Half</strong></label>
            </form>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            <label for="Daily">Choose Date</label>
            <div class="controls">
                <div class="input-group"><input id="Daily" name="txtFilterTanggal" type="text" class="date-picker form-control" value="<?php echo $yesterday;?>">
                <label for="Daily" class="input-group-addon btn"><span class="date-picker glyphicon glyphicon-calendar"></span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
                <label for="InputHalf">Season</label>
                <select class="form-control" id="Half" disabled="disabled">
                    <?php 
                    $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN($linkMACHWebTrax);
                    while($RListClosedTime = sqlsrv_fetch_array($QListClosedTime))
                    {
                    $ClosedTime = $RListClosedTime['ClosedTime'];
                    ?>
                    <option><?php echo $ClosedTime; ?></option>
                    <?php
                    }                
                    ?>
                </select>
        </div>
    </div>
    <div class="col-md-2">
        <label class="InvisibleText">Choose Date</label>
        <div>
        <button type="button" class="btn btn-dark btn-labeled" id="BtnSearchTT" onclick="cariDataMesin()">Search</button>
        </div>
    </div>
    <div class="col-md-12"  id="MachDetail">
        <div class="col-md-12">
        <br></br>
        <div class="row">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        
        $('#Daily').datepicker({
            format: "mm/dd/yyyy",
            autoclose: "true"
        });
    });
    function cariDataMesin() {
        $('#BtnSearchTT').attr('disabled', true);
        $('#load_img').show();
        var FilDate1 = $('#SelectMachine option:selected').val();
        var FilDate2 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var FilDate3 = $('#Daily').val();
        var FilHalf = $("#Half").children("option:selected").val();
        // var FilDate2 = $('#Weekly').val();
        // alert(FilHalf);
        var formdata = new FormData();
        formdata.append("MachineType", FilDate1);
        formdata.append("FilterType", FilDate2);
        formdata.append("Date", FilDate3);
        formdata.append("ClosedTime", FilHalf);
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
<script type="text/javascript">
   $("input[name='RadioFilter']").click(function() { 
      $("#Half").prop("disabled",true);
       $('#Half').val("");
      if($(this).hasClass('Half')) {
      $("#Half").prop("disabled",false);
      }
   });
</script>