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
}
*/

?><script src="project/Shipping/lib/LibFreightChartV2.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=49">Shipping : Freight Weight Chart</a></li>
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
        <div class="row" id="FreightAll">

        </div>
        <div class="row" id="FreightChart">

        </div>
    </div>
</div>
<div class="modal fade" id="DetailAll" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Shipment Details</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="DetailContent"></div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>