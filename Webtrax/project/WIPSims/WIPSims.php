<?php
require_once("project/WIPSims/Modules/ModuleWIPSims.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");


?><script src="project/wipsims/lib/libwipsims.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=14">Production : WIP</a></li>
        </ol>
    </div>
</div>

<div class="row"><?php /*
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">Quote Category</label>
                <select class="form-control" id="InputQuoteCategory"><?php 
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY($linkMACHWebTrax);
                while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewQuote">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>*/ ?>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
        <span id="TempQuote" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory"></div><div class="row" id="ListDetailPart"></div>
        <span id="TempFilter" class="InvisibleText"></span>
    </div>
</div>
