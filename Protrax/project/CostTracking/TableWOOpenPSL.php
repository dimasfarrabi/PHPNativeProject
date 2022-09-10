<?php 
require_once("Modules/ModuleCostTracking.php"); 
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

if(isset($_SESSION['WOOpenPSL']))
{
    echo $_SESSION['WOOpenPSL'];
    unset($_SESSION['WOOpenPSL']);
}
?><script src="project/costtracking/lib/libcosttracking.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=5">Cost Tracking : WO Open</a></li>
        </ol>
    </div>
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">QuoteCategory</label>
                <select class="form-control" id="InputQuoteCategory"><?php 
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("PSL",$linkMACHWebTrax);
                while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?></select>
            </div><?php
            // <div class="form-group">
            //     <label for="InputLocation">Location</label>
            //     <select class="form-control" id="InputLocation">
            //         <option value="PSL">PSL</option>
            //         <option value="PSM">PSM</option>
            //     </select>
            // </div>
            ?>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWOOpenPSL">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuoteOpen"></div><span id="TempQuote" class="InvisibleText"></span><span id="TempLocation" class="InvisibleText"></span>
    </div>
    <div class="col-md-9"><div class="row" id="ListReportOpen"></div><span id="TempFilter" class="InvisibleText"></span><div class="row" id="ListOTSTop"></div></div>
</div>