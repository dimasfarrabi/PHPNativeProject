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
            <li class="active"><a href="home.php?link=31">Reconciliation : Time Tracking Reconciliation</a></li>
        </ol>
    </div>
</div>
<style>
    .Smalled{
        font-size:13px;
    }
</style>
<div class="col-md-12">

    <div class="col-md-3">
        <label for="InputQuoteCategory">Choose Division</label>
            <select class="form-control" id="SelectDiv">
                <option value="ALL ADMIN-100">ALL ADMIN</option>
                <option value="ALL ENGINEERS-100">ALL ENGINEERS</option>
                <?php
                $QListDiv = GET_LIST_DIVISION("FALSE",$linkMACHWebTrax);
                while($RListDiv = sqlsrv_fetch_array($QListDiv))
                {
                    $ValDiv = $RListDiv['DivisionName'];
                ?>
                <option class="Smalled" value="<?php echo $ValDiv; ?>-100">&emsp;<?php echo $ValDiv; ?></option>
                <?php
                }
                ?>
                <option value="ALL PRODUCTION-200">ALL PRODUCTION</option>
                <?php
                $QListDiv = GET_LIST_DIVISION("TRUE",$linkMACHWebTrax);
                while($RListDiv = sqlsrv_fetch_array($QListDiv))
                {
                    $ValDiv = $RListDiv['DivisionName'];
                ?>
                <option class="Smalled" value="<?php echo $ValDiv; ?>-200">&emsp;<?php echo $ValDiv; ?></option>
                <?php
                }
                ?>
                <option class="Smalled" value="PRODUCTION MANAGER-300">&emsp;PRODUCTION MANAGER</option>
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
                ?></select>
        </div>
    </div>
    <div class="col-md-2">
        <label class="InvisibleText">Choose Date</label>
        <div>
        <button type="button" class="btn btn-dark btn-labeled" id="BtnSearchTT" onclick="cariData()">Search</button>
        </div>
    </div>

    <div class="col-md-12"  id="TTDetail">
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
    function cariData() {
        $('#BtnSearchTT').attr('disabled', true);
        $('#load_img').show();
        var FilDate1 = $('#SelectDiv option:selected').val();
        var FilDate2 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var FilDate3 = $('#Daily').val();
        var FilHalf = $("#Half").children("option:selected").val();
        // var FilDate2 = $('#Weekly').val();
        // alert(FilHalf);
        var formdata = new FormData();
        formdata.append("DivName", FilDate1);
        formdata.append("FilterType", FilDate2);
        formdata.append("Date", FilDate3);
        formdata.append("ClosedTime", FilHalf);
        $.ajax({
            url: 'project/reconciliation/reconttcontent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#TTDetail').html("");
                $("#TTDetail").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#TTDetail').html("");
                // $('#InputHalf').hide();
            },
            success: function(xaxa){
                $('#load_img').hide();
                $('#TTDetail').hide();
                $('#TTDetail').html(xaxa);
                $('#TTDetail').fadeIn('fast');
                $('#BtnSearchTT').attr('disabled', false);
                // $('#InputHalf').hide();
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
    //    $('#Half').val("");
      if($(this).hasClass('Half')) {
      $("#Half").prop("disabled",false);
      }
   });
</script>