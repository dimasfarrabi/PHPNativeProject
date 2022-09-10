<?php 
require_once("Modules/ModuleCostTrackingChart.php"); 
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

?>
<script src="project/costtracking/lib/libwochart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=21">Cost Tracking : WO Open Chart</a></li>
        </ol>
    </div>
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">QuoteCategory</label>
                <select class="form-control" id="InputQuoteCategory">
                    <option value="<?php echo base64_encode(base64_encode("Category#Quote#OPEN")); ?>">Quote</option>
                    <option value="<?php echo base64_encode(base64_encode("Category#Unquote#OPEN")); ?>">Unquote</option>
                    <option value="<?php echo base64_encode(base64_encode("Category#All#OPEN")); ?>">All</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWOOpen">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
        <span id="TempQuote" class="InvisibleText"></span>
        <span id="TempContent" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultChart"></div>
    </div>
</div>