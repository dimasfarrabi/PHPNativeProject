<?php
// require_once("../src/Modules/ModuleLogin.php");
require_once("project/Reconciliation/Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$today = date('m/d/Y');
$thisMonth = date('m/Y',strtotime("-1 days"));
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=45">Reconciliation : Material Tracking Reconciliation</a></li>
        </ol>
    </div>
</div>

<div class="col-md-12">
        <div class="col-md-3">
                <label for="InputQuoteCategory">Choose Category</label>
                    <select class="form-control" id="SelectCat">
                    <option>ALL</option>
                    <?php
                    $QListMach = GET_LIST_MATERIAL_CATEGORY($linkMACHWebTrax);
                    while($RListMach = sqlsrv_fetch_array($QListMach))
                    {
                        $ValCat = $RListMach['Category'];
                    ?>
                    <option><?php echo $ValCat; ?></option>
                    <?php
                    }
                    ?>
                    </select>
        </div>
        <div class="col-md-3">
                <label for="InputQuoteCategory">Choose Filter</label>
                <form id="RadioFilter">
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Daily"><strong>Daily</strong></label>&nbsp;&nbsp;
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Weekly"><strong>Weekly</strong></label>&nbsp;&nbsp;
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Monthly"><strong>Monthly</strong></label>&nbsp;&nbsp;
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter Half" value="Half"><strong>Half</strong></label>&nbsp;&nbsp;
                </form>
        </div>
        
        <div class="col-md-2">
            <div class="form-group">
                <label for="Date1">Choose Date</label>
                <div class="controls">
                    <div class="input-group"><input id="Date1" name="txtFilterTanggal" type="text" class="date-picker form-control" value="<?php echo $yesterday;?>">
                    <label for="Date1" class="input-group-addon btn"><span class="date-picker glyphicon glyphicon-calendar"></span></label>
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
            <button type="button" class="btn btn-dark btn-labeled" id="BtnSearch" onclick="cariDataMaterial()">Search</button>
            </div>
        </div>
        
            <div class="col-md-12"  id="MaterialContent">
                <div class="col-md-12">
                <br></br>
                <div class="row">

                </div>
            </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        
        $('#Date1').datepicker({
            format: "mm/dd/yyyy",
            autoclose: "true"
        });
    });
    function cariDataMaterial() {
        $('#BtnSearch').attr('disabled', true);
        $('#load_img').show();
        var Fil1 = $('#SelectCat option:selected').val();
        var Fil2 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        // var Fil2 = "Daily";
        var Fil3 = $('#Date1').val();
        var FilHalf = $("#Half").children("option:selected").val();
        var formdata = new FormData();
        formdata.append("Category", Fil1);
        formdata.append("FilterType", Fil2);
        formdata.append("Date", Fil3);
        formdata.append("ClosedTime", FilHalf);
        $.ajax({
            url: 'project/reconciliation/ReconMaterialContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#MaterialContent').html("");
                $("#MaterialContent").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#MaterialContent').html("");
            },
            success: function(xaxa){
                $('#load_img').hide();
                $('#MaterialContent').hide();
                $('#MaterialContent').html(xaxa);
                $('#MaterialContent').fadeIn('fast');
                $('#BtnSearch').attr('disabled', false);
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
    //    $('#Half').val("");
      if($(this).hasClass('Half')) {
      $("#Half").prop("disabled",false);
      }
   });
</script>