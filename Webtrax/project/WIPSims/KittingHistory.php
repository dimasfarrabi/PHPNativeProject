<?php
require_once("project/WIPSims/Modules/ModuleWIPSims.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}<script src="project/wipsims/lib/LibKittingHistory.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/
?>

<style>
    .TextFont{font-size: 14px;}
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=54">Production : Kitting History Report</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label for="InputQuoteCategory" class="TextFont">Pilih Filter</label>
        <form id="RadioFilter" style="margin-top:20px;">
            <label class="radio-inline TextFont">
            <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Week"><strong>1 Minggu Terakhir</strong></label>
            <label class="radio-inline TextFont" style="margin-left:50px;">
            <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Month"><strong>1 Bulan Terakhir</strong></label>
            <label class="radio-inline TextFont" style="margin-left:50px;">
            <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="ThreeMonth"><strong>3 Bulan Terakhir</strong></label>
            <label class="radio-inline TextFont" style="margin-left:50px;">
            <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="SixMonth"><strong>6 Bulan Terakhir</strong></label>
        </form>
    </div>
    <div class="col-md-6">
        <label class="InvisibleText" class="TextFont">Search</label>
        <div style="margin-top:20px; margin-left:50px;">
            <button type="button" class="btn btn-md btn-dark" id="BtnSearch" style="width:30%">Search</button>
        </div>
    </div>
</div>
<div class="row" id="ContentKitting" style="margin-top:30px;">
    
</div>
<script>
$(document).ready(function() {
    $("#BtnSearch").click(function(){ 
        var Param = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        if(Param == undefined)
        {
            alert('Permintaan Tidak Dapat Diproses!');
            return false;
        }
        var formdata = new FormData();
        formdata.append('Param', Param);
        $.ajax({
            url: 'project/WIPSims/KittingHistoryContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#ContentKitting').html("");
                $("#ContentKitting").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentKitting').html("");
                $("#BtnSearch").prop('disabled', true);
            },
            success: function (xaxa) {
                $('#ContentKitting').html("");
                $('#ContentKitting').html(xaxa);
                $('#ContentKitting').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnSearch").prop('disabled', false);
                $("#ContentTable").dataTable({
                });
                $("#BtnDownload").click(function(){ 
                    window.location.href = 'project/WIPSims/KittingHistoryDownload.php?Params='+Param;
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnSearch").prop('disabled', false);
                $('#ContentKitting').html("");
                $("#ContentLoading").remove();
            }
        });
    });
});
</script>