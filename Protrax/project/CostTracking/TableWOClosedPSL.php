<?php 
require_once("Modules/ModuleCostTracking.php"); 
date_default_timezone_set("Asia/Jakarta");
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
}

if(isset($_SESSION['WOClosedPSL']))
{
    echo $_SESSION['WOClosedPSL'];
    unset($_SESSION['WOClosedPSL']);
}

?><script src="project/costtracking/lib/libcosttracking.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
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
                while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
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
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("",$linkMACHWebTrax);
                while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <?php
            // <div class="form-group">
            //     <label for="InputLocation">Location</label>
            //     <select class="form-control" id="InputLocation">
            //         <option value="PSL">PSL</option>
            //         <option value="PSM">PSM</option>
            //     </select>
            // </div>
            ?>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWOClosedPSL">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div><span id="TempQuote" class="InvisibleText"></span><span id="TempLocation" class="InvisibleText"></span>
    </div>
    <div class="col-md-9"><div class="row" id="ListReport"></div><span id="TempFilter" class="InvisibleText"></span><div class="row" id="ListOTSTop"></div></div>
</div>