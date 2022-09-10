<?php
// require_once("../src/Modules/ModuleLogin.php");
require_once("project/Reconciliation/Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$today = date('m/d/Y');
$thisMonth = date('m/Y',strtotime("-1 days"));
?>
<style>.cards {padding: 10px; box-shadow: 0px 1px 3px #AEACAC;background:#FFFFFF;width: 100%;margin-bottom: 20px; }</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=45">Reconciliation : Small Warehouse Reconciliation</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="col-md-12 cards">
            <div class="col-md-12"><h6><strong>Choose Warehouse Location</strong></h6></div>
            <div class="col-md-12">
                <select class="form-control" id="SelectCat">
                    <!-- <option>ALL</option> -->
                    <?php
                    $QListMach = GET_WAREHOUSE_LOCATION($linkMACHWebTrax);
                    while($RListMach = sqlsrv_fetch_array($QListMach))
                    {
                        $Division = $RListMach['Division'];
                        $LocationCode = "PSL";
                    ?>
                    <option><?php echo "$Division - $LocationCode"; ?></option>
                    <?php
                    }
                    
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-12 cards">
            <div class="col-md-12"><h6><strong>Filter Date*</strong></h6></div>
            <div class="col-md-12">
                <form id="RadioFilter">
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Daily"><strong>Daily</strong></label>&nbsp;&nbsp;
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Weekly"><strong>Weekly</strong></label>&nbsp;&nbsp;
                    <label class="radio-inline">
                    <!-- <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Monthly"><strong>Monthly</strong></label>&nbsp;&nbsp; -->
                </form>
            </div>
            <div class="col-md-12">
                <div class="form-group" style="margin-top: 20px;">
                    <div class="controls">
                        <div class="input-group"><input id="Date1" name="txtFilterTanggal" type="text" class="date-picker form-control" value="<?php echo $yesterday;?>">
                        <label for="Date1" class="input-group-addon btn"><span class="date-picker glyphicon glyphicon-calendar"></span></label>
                        </div>
                    </div>
                </div>
                <i>*) Last Transact Date</i>
            </div>
        </div>
        <button type="button" class="btn btn-dark btn-labeled block" onclick="cariDataMaterial()" style="width: 100%;">Search</button>
    </div>
    <div class="col-md-9" id="ReconMatContent">

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
        var Fil3 = $('#Date1').val();
        var formdata = new FormData();
        formdata.append("Category", Fil1);
        formdata.append("FilterType", Fil2);
        formdata.append("Date", Fil3);
        $.ajax({
            url: 'project/reconciliation/ReconMaterialContentV2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#ReconMatContent').html("");
                $("#ReconMatContent").before('<div class="col-sm-9" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ReconMatContent').html("");
            },
            success: function(xaxa){
                $('#load_img').hide();
                $('#ReconMatContent').hide();
                $('#ReconMatContent').html(xaxa);
                $('#ReconMatContent').fadeIn('fast');
                $('#BtnSearch').attr('disabled', false);
                $("#ContentLoadingTT").remove();
                $("#TableSmallWH").dataTable({
                });
                SHOW_DETAIL();
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    }
function SHOW_DETAIL()
{
    $("#ModalDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/reconciliation/DetailMaterialV2.php',
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
}
</script>