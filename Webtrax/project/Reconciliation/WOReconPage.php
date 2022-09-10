<?php
// require_once("../src/Modules/ModuleLogin.php");
require_once("project/Reconciliation/Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");
?>
<style>.cards {padding: 10px; box-shadow: 0px 1px 3px #AEACAC;background:#FFFFFF;width: 100%;margin-bottom: 20px; }</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=48">Reconciliation : WO Mapping Report</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="col-md-12 cards">
            <div class="col-md-12"><h6><strong>Choose Category</strong></h6></div>
            <div class="col-md-12">
                <div class="form-group">
                    <select class="form-control" id="InputCategory">
                        <option>Quote</option>
                        <option>Unquote</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12"><h6><strong>Choose Season</strong></h6></div>
            <div class="col-md-12">
                <div class="form-group">
                    <select class="form-control" id="InputSeason">
                        <?php
                        $Data = GET_DATA_CLOSEDTIME($linkMACHWebTrax);
                        while($Datares=sqlsrv_fetch_array($Data))
                        {
                            $ValClosedTime = trim($Datares['ClosedTime']);
                            ?>
                            <option><?php echo $ValClosedTime; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12"><h6><strong>Choose Report</strong></h6></div>
            <div class="col-md-12">
                <div class="form-group">
                    <select class="form-control" id="InputType">
                        <option>Time Tracking</option>
                        <option>Machine Tracking</option>
                        <option>Material Tracking</option>
                    </select>
                </div>
            </div>
        </div>
        <button id="BtnSearch" type="button" class="btn btn-dark btn-labeled block" onclick="CariData()" style="width: 100%;">Search</button>
        
    </div>
    <div class="col-md-9" id="ReportContent">

    </div>
    
</div>
<script type="text/javascript">
    function CariData() {
        $('#BtnSearch').attr('disabled', true);
        $('#load_img').show();
        var Category = $('#InputCategory option:selected').val();
        var Season = $('#InputSeason option:selected').val();
        var Type = $('#InputType option:selected').val();
        var formdata = new FormData();
        formdata.append("Category", Category);
        formdata.append("Season", Season);
        formdata.append("Type", Type);
        $.ajax({
            url: 'project/Reconciliation/WOReconContent.php',
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
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    }
</script>