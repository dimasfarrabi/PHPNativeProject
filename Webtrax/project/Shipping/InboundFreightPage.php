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
}<script src="project/Shipping/lib/LibShippingChartInbound.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=51">Shipping : Freight Weight Chart (Inbound)</a></li>
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
                <button class="btn btn-dark btn-labeled" id="BtnView">View Chart</button> 
            </div>           
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="row" id="ContentChartAll">

        </div>
        <div class="row" id="ContentChart">

        </div>
    </div>
</div>
<div class="modal fade" id="AllDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Inbound Shipment Details</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="AllContentDetails"></div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function(){
    VIEW_ALL();
    $("#BtnView").click(function(){
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
                url: 'project/Shipping/InboundFreightContent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'POST',
                beforeSend: function () {
                    $('#BtnView').attr('disabled', true);
                    $('#ContentChartAll').hide();
                    $('#ContentChart').html("");
                    $("#ContentChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $("#ContentLoading").remove();
                    $('#ContentChartAll').hide();
                    $('#ContentChart').html(xaxa);
                    $('#ContentChart').fadeIn('fast');
                    $('#BtnView').attr('disabled', false);
                    $("#TabelDataInbound").DataTable({
                        "pagingType": "full"
                    });
                    MODAL_DETAILS();
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#BtnView').attr('disabled', false);
                    $('#ContentChartAll').hide();
                }
            });
        }
    });
        
});
function VIEW_ALL()
{
    $.ajax({
        url: 'project/Shipping/InboundFreightContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#ContentChartAll').html("");
            $('#ContentChart').hide();
            $("#ContentChartAll").append('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading2").remove();
            $('#ContentChart').hide();
            $('#ContentChartAll').html(xaxa);
            $('#ContentChartAll').fadeIn('fast');
            $("#AllTableInbound").DataTable({
                "pagingType": "full"
            });
            MODAL_ALL_DETAILS();
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading2").remove();
            $('#ContentChart').hide();
        }
    });
}
function MODAL_DETAILS()
{
    $("#InboundDetail").on('show.bs.modal', function (event) {
    var act = $(event.relatedTarget);
    var DataCode = act.data('ecode');
    var formdata = new FormData();
    formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/IboundFreightDetail.php',
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
function MODAL_ALL_DETAILS()
{
    $("#AllDetail").on('show.bs.modal', function (event) {
    var act = $(event.relatedTarget);
    var DataCode = act.data('ecode');
    var formdata = new FormData();
    formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/IboundFreightDetailALL.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#AllContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#AllContentDetails').hide();
                $('#AllContentDetails').html(xaxa);
                $('#AllContentDetails').fadeIn('fast');
                $("#InboundTableDetail").DataTable({
                    "pagingType": "full"
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