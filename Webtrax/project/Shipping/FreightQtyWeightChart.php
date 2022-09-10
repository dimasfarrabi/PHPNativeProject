<?php 
require_once("Modules/ModuleShippingChart.php"); 
date_default_timezone_set("Asia/Jakarta");
// if(!session_is_registered("UIDWebTrax"))
/*
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
}

<script src="project/Shipping/lib/LibShippingChart(NE).js"></script>*/
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=49">Shipping : Freight Weight Chart</a></li>
        </ol>
    </div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputSeason">Season</label>
                <select class="form-control" id="InputSeason"><option>All</option><?php 
                $QListYear = GET_LIST_FILTER_SHIPPING($linkMACHWebTrax);
                while($RListYear = sqlsrv_fetch_array($QListYear))
                {
                    $YearShipment = $RListYear['YearShipment'];
                    ?>
                    <option><?php echo $YearShipment; ?></option>
                    <?php
                } 
                ?></select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWeightChart">View Chart</button> 
            </div>           
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12"><div class="row" id="ResultChart"></div></div>
</div>
<script>
$(document).ready(function(){
    $("#BtnViewWeightChart").click(function(){
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        formdata.append("InputYear", InputYear);
        $.ajax({
            url: 'project/Shipping/FreightQtyWeightChartContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#BtnViewWeightChart').attr('disabled', true);
                $('#ResultChart').html("");
                $("#ResultChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentLoading").remove();
                $('#ResultChart').html(xaxa);
                $('#ResultChart').fadeIn('fast');
                $('#BtnViewWeightChart').attr('disabled', false);
            },
            error: function () {
                alert('Request cannot proceed!');
                $('#BtnViewWeightChart').attr('disabled', false);
            }
        });
    });
});
</script>