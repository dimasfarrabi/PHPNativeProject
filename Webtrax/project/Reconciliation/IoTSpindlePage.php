<?php
// require_once("../src/Modules/ModuleLogin.php");
// require_once("project/WIPSims/Modules/ModuleWIPSims.php"); 
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$today = date('m/d/Y');
$thisMonth = date('m/Y',strtotime("-1 days"));
?>
<style>.cards {padding: 10px; box-shadow: 0px 1px 3px #AEACAC;background:#FFFFFF;width: 100%;margin-bottom: 20px; }</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=47">Report : IoT Spindle Report</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="col-md-12 cards">
            <div class="col-md-12"><h6><strong>Filter Date</strong></h6></div>
            <div class="col-md-12">
                <form id="RadioFilter">
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Daily"><strong>Daily</strong></label>&nbsp;&nbsp;
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Weekly"><strong>Weekly</strong></label>&nbsp;&nbsp;
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
            </div>
            <div class="col-md-12">
                <button id="BtnSearch" type="button" class="btn btn-dark btn-labeled block" style="width: 100%;">Show Data</button>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="">
        <div id="ReportContent"></div>
        <div id="DetailContent"></div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#Date1').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true"
    });
    $("#BtnSearch").click(function(){
        var Fil2 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Fil3 = $('#Date1').val();
        var formdata = new FormData();
        formdata.append("FilterType", Fil2);
        formdata.append("Date", Fil3);
        $.ajax({
            url: 'project/Reconciliation/IoTSpindleContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#ReportContent').html("");
                $('#DetailContent').hide();
                $('#BtnSearch').attr('disabled', true);
                $("#ReportContent").before('<div class="col-sm-9" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ReportContent').html("");
            },
            success: function(xaxa){
                $('#load_img').hide();
                $('#ReportContent').hide();
                $('#ReportContent').html(xaxa);
                $('#ReportContent').fadeIn('fast');
                $('#BtnSearch').attr('disabled', false);
                $("#ContentLoadingTT").remove();
                $(".DataChild").click(function () {
                var BoolClick = "TRUE";
                if (BoolClick == "TRUE") {
                    $("#TableReport tr").removeClass('PointerListSelected');
                    $(this).closest('.DataChild').addClass("PointerListSelected");
                    var EncData = $(this).data('float');
                    SHOW_DETAIL(EncData);
                }
                else {
                    return false;
                }
            });
                
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    });
});
function SHOW_DETAIL(a)
{
    var formdata = new FormData();
    formdata.append('aFloat', a);
    $.ajax({
        url: 'project/Reconciliation/IoTSpindleChart.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#DetailContent").offset().top -10 }, "fast");
            $('#DetailContent').html("");
            $("#DetailContent").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#DetailContent').html("");
        },
        success: function (xaxa) {
            $('#DetailContent').html("");
            $('#DetailContent').hide();
            $('#DetailContent').html(xaxa);
            $('#DetailContent').fadeIn('fast');
            $("#ContentLoadingChart").remove();
            $("#TableDetail").dataTable({
            });
            DOWNLOAD_REPORT(a);
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#DetailContent').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
function DOWNLOAD_REPORT(enc)
{
    $("#BtnDownload").click(function(){
    window.location.href = 'project/Reconciliation/IoTSpindleCSV.php?afloat='+enc;
    });
}
</script>