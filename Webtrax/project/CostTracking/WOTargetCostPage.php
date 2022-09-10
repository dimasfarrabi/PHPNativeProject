<?php 
require_once("Modules/ModuleCostTracking.php"); 
require_once("src/Modules/ModuleLogin.php");
date_default_timezone_set("Asia/Jakarta");
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
}<script src="project/costtracking/lib/libwotarget.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=53">Cost Tracking : WO Target Cost</a></li>
        </ol>
    </div>
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputClosedTime">Season</label>
                <select class="form-control" id="InputClosedTime"><?php 
                $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                
                while($RListClosedTime = sqlsrv_fetch_array($QListClosedTime))
                {
                    $ClosedTime = $RListClosedTime['ClosedTime'];
                    ?>
                    <option><?php echo $ClosedTime; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="form-group">
                <label for="InputQuoteCategory">QuoteCategory</label>
                <select class="form-control" id="InputQuoteCategory">
                    <option>Quote</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnShowData">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-12">
        <div class="row" id="TargetCostContent">

        </div>
    </div>
</div>
<script>
$(document).ready(function () { 
    $("#BtnShowData").click(function(){ 
        var Half = $("#InputClosedTime").val();
        var Category = $("#InputQuoteCategory").val();
        var formdata = new FormData();
        formdata.append('Half', Half);
        formdata.append('Category', Category);
        $.ajax({
            url: 'project/costtracking/WOTargetCostContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnShowData").attr('disabled', true);
                $('#TargetCostContent').html("");
                $("#TargetCostContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#TargetCostContent').html("");
            },
            success: function (xaxa) {
                $("#ContentLoading").remove();
                $('#TargetCostContent').html("");
                $('#TargetCostContent').hide();
                $('#TargetCostContent').html(xaxa);
                $('#TargetCostContent').fadeIn('fast');
                $("#BtnShowData").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnShowData").attr('disabled', false);
            }
        });
    });
    $("#BtnShowData").trigger('click'); 
});
</script>