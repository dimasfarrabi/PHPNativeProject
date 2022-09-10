<?php
require_once("project/CostTracking/Modules/ModuleCostTracking.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");


?><script src="project/wipsims/lib/libwipoutput.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<style>
    .radio-inline-custom{margin-top:0px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=15">Production : Output</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">Quote Category</label>
                <select class="form-control" id="InputQuoteCategory"><?php 
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("PSL",$linkMACHWebTrax);
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
                <button class="btn btn-dark btn-labeled" id="BtnViewQuoteOutput">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
        <span id="TempQuoteCategory" class="InvisibleText"></span>
        <span id="TempDataFilter" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory"></div>
        <div class="row" id="ResultCategoryDetail"></div>
        <span id="TempQuote" class="InvisibleText"></span>
        <span id="TempDataTime" class="InvisibleText"></span>
    </div>
</div>
