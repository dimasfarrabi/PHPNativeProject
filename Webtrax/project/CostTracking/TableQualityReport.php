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
<!-- <script src="project/costtracking/lib/libcosttracking.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=28">Quality Report (Periodical)</a></li>
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
                <select class="form-control" id="InputQuoteCategory"><?php 
                $QListQuoteCategory = GET_QUOTE_CATEGORY("",$linkMACHWebTrax);
                while($RListQuoteCategory = sqlsrv_fetch_array($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewQualityReport">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div><span id="TempQuote" class="InvisibleText"></span><span id="TempLocation" class="InvisibleText"></span>
    </div>
    <div class="col-md-9"><div class="row" id="ListReport"></div><span id="TempFilter" class="InvisibleText"></span><div class="row" id="ListOTSTop"></div></div>
</div>
<script>
    $(document).ready(function () {
    $("#BtnViewQualityReport").click(function(){
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        var ValTitle = "";
        $("#TempFilter").text(ValTime + "*" + ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValTime', ValTime);
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        $.ajax({
            url: 'project/costtracking/listquoteclosed2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewQualityReport").blur();
                $("#BtnViewQualityReport").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewQualityReport").blur();
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnViewQualityReport").attr('disabled', false);
            }
        });
    });
    });
</script>
