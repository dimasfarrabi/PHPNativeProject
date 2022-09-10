<?php 
require_once("Modules/ModuleShippingChart.php"); 
date_default_timezone_set("Asia/Jakarta");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <html>
    <head></head>
    <body><script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script></body>
    </html>
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
}<script src="project/Shipping/lib/LibCourierChart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=52">Shipping : Courier Chart</a></li>
        </ol>
    </div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputSeason">Season</label>
                <select class="form-control" id="InputSeason">
                    <option>ALL</option>
                    <?php 
                    $QListYear = GET_LIST_FILTER_SHIPPING($linkMACHWebTrax);
                    while($RListYear = sqlsrv_fetch_array($QListYear))
                    {
                        $YearShipment = $RListYear['YearShipment'];
                        ?>
                        <option><?php echo $YearShipment; ?></option>
                        <?php
                    }          
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnCourierChart">View Chart</button> 
            </div>           
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div id="ChartContent">

        </div>
        <div id="ChartContentAll">

        </div>
        <div id="DetailContent">
            <div class="row">
                <div class="col-md-4" id="ForTop1"></div>
                <div class="col-md-4" id="ForTop2"></div>
                <div class="col-md-4" id="ForTop3"></div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    VIEW_ALL();
    $("#BtnCourierChart").click(function(){
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        if(InputYear == 'ALL')
        {
            VIEW_ALL();
        }
        else
        {
            formdata.append("InputYear", InputYear);
            $.ajax({
                url: 'project/Shipping/CourierChartContentV2.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'POST',
                beforeSend: function () {
                    $('#BtnCourierChart').attr('disabled', true);
                    $('#ChartContentAll').hide();
                    $('#ChartContent').html("");
                    $("#ChartContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $("#ContentLoading").remove();
                    $('#ChartContentAll').hide();
                    $('#ChartContent').html(xaxa);
                    $('#ChartContent').fadeIn('fast');
                    $('#BtnCourierChart').attr('disabled', false);
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#BtnCourierChart').attr('disabled', false);
                    $('#ChartContentAll').hide();
                }
            });
        }
    });
});
function VIEW_ALL()
{
    $.ajax({
        url: 'project/Shipping/CourierChartContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#ChartContentAll').html("");
            $('#ChartContent').hide();
            $("#ChartContentAll").append('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading2").remove();
            $('#ChartContent').hide();
            $('#ChartContentAll').html(xaxa);
            $('#ChartContentAll').fadeIn('fast');
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading2").remove();
            $('#ChartContent').hide();
        }
    });
}
</script>