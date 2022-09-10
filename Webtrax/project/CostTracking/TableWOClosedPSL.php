<?php 
require_once("Modules/ModuleCostTracking.php"); 
require_once("src/Modules/ModuleLogin.php");
date_default_timezone_set("Asia/Jakarta");


if(isset($_SESSION['WOClosedPSL']))
{
    echo $_SESSION['WOClosedPSL'];
    unset($_SESSION['WOClosedPSL']);
}

?>
<?php /* <script src="project/costtracking/lib/libcosttracking.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script> */ ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=4">Cost Tracking : WO Closed (Periodical)</a></li>
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
                
                <?php 
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("",$linkMACHWebTrax);
                while($RListQuoteCategory = sqlsrv_fetch_array($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?>
                <option>All</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWOClosedPSL">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote">

        </div>
        <span id="TempQuote" class="InvisibleText">

        </span>
        <span id="TempLocation" class="InvisibleText">

        </span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ListReport">

        </div>
        <span id="TempFilter" class="InvisibleText">

        </span>
        <div class="row" id="ListOTSTop">

        </div>
        <div class="row" id="UnquoteReport">

        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("#BtnViewWOClosedPSL").click(function(){
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempLocation").text("");                                                // *) sementara utk ganti lokasi yg disembunyikan
        var ValTitle = "";                                                          // *) sementara utk ganti lokasi yg disembunyikan
        $("#TempFilter").text(ValTime + "*" + ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValTime', ValTime);
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        if(ValQuoteCategory != "All"){
        $.ajax({
            url: 'project/costtracking/listquoteclosed.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOClosedPSL").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#ListReport').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewWOClosedPSL").blur();
                $("#BtnViewWOClosedPSL").attr('disabled', false);
                SHOW_PIE_CHART_CLOSED()
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOClosedPSL").blur();
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOClosedPSL").attr('disabled', false);
            }
        
        });
    }
    else{}
    });


    $("#BtnViewWOOpenPSL").click(function () {
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempLocation").text("");                                                // *) sementara utk ganti lokasi yg disembunyikan
        var ValTitle = "";                                                          // *) sementara utk ganti lokasi yg disembunyikan
        $("#TempFilter").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        $.ajax({
            url: 'project/costtracking/listquoteopen.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOOpenPSL").attr('disabled', true);
                $('#ListQuoteOpen').html("");
                ListReportOpen
                $("#ListQuoteOpen").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuoteOpen').html("");
                $('#ListReportOpen').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#ListQuoteOpen').html("");
                $('#ListQuoteOpen').hide();
                $('#ListQuoteOpen').html(xaxa);
                $('#ListQuoteOpen').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewWOOpenPSL").blur();
                $("#BtnViewWOOpenPSL").attr('disabled', false);
                SHOW_PIE_CHART_OPEN();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOOpenPSL").blur();
                $('#ListQuoteOpen').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOOpenPSL").attr('disabled', false);
            }
        });
    });
});


function SHOW_PIE_CHART_CLOSED()
{
    var DataFilterSelected = $("#TempFilter").text();
    var formdata = new FormData();
    formdata.append('ValFilter', DataFilterSelected);
    $.ajax({
        url: 'project/costtracking/chartwoclosed.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ListReport').html("");
            $("#ListReport").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReport').html("");
        },
        success: function (xaxa) {
            $('#ListReport').html("");
            $('#ListReport').hide();
            $('#ListReport').html(xaxa);
            $('#ListReport').fadeIn('fast');
            $("#ContentLoadingChart").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ListReport').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
function SHOW_PIE_CHART_OPEN()
{
    var DataFilterSelected = $("#TempFilter").text();
    var formdata = new FormData();
    formdata.append('ValFilter', DataFilterSelected);
    $.ajax({
        url: 'project/costtracking/chartwoopen.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ListReportOpen').html("");
            $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReportOpen').html("");
        },
        success: function (xaxa) {
            $('#ListReportOpen').html("");
            $('#ListReportOpen').hide();
            $('#ListReportOpen').html(xaxa);
            $('#ListReportOpen').fadeIn('fast');
            $("#ContentLoadingChart").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ListReportOpen').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
</script>