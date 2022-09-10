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
}<script src="project/Shipping/lib/LibShippingChart(NE).js"></script>
*/
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=50">Shipping : Destination Country Chart</a></li>
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
                <button class="btn btn-dark btn-labeled" id="BtnDestinationChart">View Chart</button> 
            </div>           
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="row" id="ResultChart">

        </div>
        <div class="row" id="AllDestinationChart">

        </div>
        <div class="row" id="DetailDestination">
    
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    SHOW_ALL_DESTINATION();
    $("#BtnDestinationChart").click(function () {
        var InputYear = $("#InputSeason option:selected").val().trim();
        if(InputYear == "ALL")
        {
            SHOW_ALL_DESTINATION();
        }
        else
        {
            var formdata = new FormData();
            formdata.append("InputYear", InputYear);
            $.ajax({
                url: 'project/Shipping/DestinationCountryChartContentV2.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'POST',
                beforeSend: function () {
                    $('#AllDestinationChart').hide();
                    $('#DetailDestination').hide();
                    $('#BtnDestinationChart').attr('disabled', true);
                    $('#ResultChart').html("");
                    $("#ResultChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $('#AllDestinationChart').hide();
                    $('#ResultChart').html(xaxa);
                    $('#ResultChart').fadeIn('fast');
                    $('#BtnDestinationChart').attr('disabled', false);
                    $("#TableDataChart2").DataTable({
                        "pagingType": "full"
                    });
                    SHOW_DETAIL();
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#BtnDestinationChart').attr('disabled', false);
                }
            });
        }
    });
});
function SHOW_ALL_DESTINATION()
{
    $.ajax({
        url: 'project/Shipping/DestinationCountryChartContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#ResultChart').hide();
            $('#DetailDestination').hide();
            $('#AllDestinationChart').html("");
            $("#AllDestinationChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $('#ResultChart').hide();
            $('#DetailDestination').hide();
            $('#AllDestinationChart').hide();
            $('#AllDestinationChart').html(xaxa);
            $('#AllDestinationChart').fadeIn('fast');
            $("#ContentLoading").remove();
            SHOW_DETAIL2()
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading").remove();
        }
    });
}
function SHOW_DETAIL()
{
    var Bol = "TRUE";
    if (Bol == "TRUE") {
        $(".PointerList").click(function () {
            if (Bol == "TRUE") {
                $("#TableSumDestination tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var Data = $(this).data('id');
                var formdata = new FormData();
                formdata.append('EncData', Data);
                $.ajax({
                    url: 'project/Shipping/DestinationCountryDetail.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        Bol = "FALSE";
                        $("html, body").animate({ scrollTop: $("#DetailDestination").offset().top -10 }, "slow");
                        $('#AllDestinationChart').hide();
                        $('#DetailDestination').html("");
                        $("#ContentLoading").remove();
                        $("#DetailDestination").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#DetailDestination').html("");
                    },
                    success: function (xaxa) {
                        $('#AllDestinationChart').hide();
                        $('#DetailDestination').html("");
                        $('#DetailDestination').hide();
                        $('#DetailDestination').html(xaxa);
                        $('#DetailDestination').fadeIn('fast');
                        $("#ContentLoading").remove();
                        Bol = "TRUE";
                        SHOW_MODAL();
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#DetailDestination').html("");
                        $("#ContentLoading").remove();
                        Bol = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
}
function SHOW_DETAIL2()
{
    var Bol = "TRUE";
    if (Bol == "TRUE") {
        $(".PointerList").click(function () {
            if (Bol == "TRUE") {
                $("#TableSumDestination2 tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var Data = $(this).data('id');
                var formdata = new FormData();
                formdata.append('EncData', Data);
                $.ajax({
                    url: 'project/Shipping/DestinationCountryDetailAll.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        Bol = "FALSE";
                        $("html, body").animate({ scrollTop: $("#DetailDestination").offset().top -10 }, "slow");
                        $('#ResultChart').hide();
                        $('#DetailDestination').html("");
                        $("#ContentLoading").remove();
                        $("#DetailDestination").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#DetailDestination').html("");
                    },
                    success: function (xaxa) {
                        $('#DetailDestination').html("");
                        $('#DetailDestination').hide();
                        $('#DetailDestination').html(xaxa);
                        $('#DetailDestination').fadeIn('fast');
                        $("#ContentLoading").remove();
                        Bol = "TRUE";
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#DetailDestination').html("");
                        $("#ContentLoading").remove();
                        Bol = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
}
function SHOW_MODAL()
{
    $("#ModalShipment").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/DestinationShippingModal.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentModal').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#ModalShippingTable").DataTable({
                });
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}

</script>