<?php
// require_once("../src/Modules/ModuleLogin.php");
require_once("project/WIPSims/Modules/ModuleWIPSims.php"); 
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
            <li class="active"><a href="home.php?link=46">Production : Machine Spindle Report</a></li>
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
        </div>
        <button id="BtnSearch" type="button" class="btn btn-dark btn-labeled block" onclick="CariData()" style="width: 100%;">Search</button>
        
    </div>
    <div class="col-md-9" id="ReportContent">

    </div>
    
</div>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#Date1').datepicker({
            format: "mm/dd/yyyy",
            autoclose: "true"
        });
    });
    function CariData() {
        $('#BtnSearch').attr('disabled', true);
        $('#load_img').show();
        var Fil2 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Fil3 = $('#Date1').val();
        var formdata = new FormData();
        formdata.append("FilterType", Fil2);
        formdata.append("Date", Fil3);
        $.ajax({
            url: 'project/WIPSims/SpindleReportContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#ReportContent').html("");
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
                $("#TableReport").dataTable({
                });
                DOWNLOAD_REPORT(Fil2,Fil3);
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    }
    function DOWNLOAD_REPORT(Fil2,Fil3)
    {
        $("#BtnDownload").click(function(){
        window.location.href = 'project/WIPSims/SpindleReportCSV.php?Cat='+Fil2+'&&Date='+Fil3;
        });
    }
</script>