<?php
$Yesterday = date("m/d/Y H:i:s",strtotime("-1 day"));
$LastYear = date("m/d/Y H:i:s",strtotime("-1 year"));
# get date
$StartTime = date("m/d/Y 00:00:00",strtotime($LastYear));
$EndTime = date("m/d/Y 23:59:59",strtotime($Yesterday));
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
}
*/


?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=44">KPI : Cutting Material</a></li>
        </ol>
    </div>
</div>
<style>
    .cards {
    padding: 20px;
    background:#FFFFFf;
    width: 100%; 
    margin-bottom: 20px; 
    box-shadow: 0px 1px 3px #808080
    }
</style>

<div class="row" id="CutterKPIContent">
    
</div>
    <div class="modal fade" id="DetailCutter" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="width:78%">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Material Tracking Details</strong></h5><span></span></div>
                        <div class="col-xs-6 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row" id="ContentDetails">
                    </div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function () {
    $.ajax({
        url: 'project/KPI/CutterKPIContent.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#CutterKPIContent').html("");
            $("#ContentLoading").remove();
            $("#CutterKPIContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#CutterKPIContent').html("");
        },
        success: function (xaxa) {
            $('#CutterKPIContent').html("");
            $('#CutterKPIContent').hide();
            $('#CutterKPIContent').html(xaxa);
            $('#CutterKPIContent').fadeIn('fast');
            $("#ContentLoading").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#ContentLoading").remove();
            }
    });
    $("#DetailCutter").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/KPI/ModalDetailCutter.php',
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
                $('#TableDetailCutter').DataTable( {
                });
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
});
</script>
