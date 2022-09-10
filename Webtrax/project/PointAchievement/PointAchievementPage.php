<?php 
require_once("project/CostTracking/Modules/ModuleCostTracking.php"); 
require_once("project/PointAchievement/Modules/ModulePointAchievement.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");


?>
<?php /*<script src="project/pointachievement/lib/libpointachievement.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script> */ ?>
<script src="project/pointachievement/lib/libpointachievement(NE).js?"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=19">Cost Tracking : Employee Time Tracking</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputClosedTime">Season</label>
                <select class="form-control" id="InputClosedTime">
                    <option>OPEN</option><?php 
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
                <button class="btn btn-dark btn-labeled" id="BtnViewProject">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListPM"></div>
        <div class="row" id="ListEmployee"></div>
        <div class="row" id="DetailTimeTrack"></div>
        <span id="TempFilter" class="InvisibleText"></span>
        <span id="TempPM" class="InvisibleText"></span>
        <span id="TempEmployee" class="InvisibleText"></span>
        <span id="TempFilter2" class="InvisibleText"></span>
        <span id="TempFilter3" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="EmpPoint"></div>
        <div class="row" id="ResultProject"></div>
        <div class="row" id="DetailProject"></div>
        <div class="row" id="ResultTimeTrack"></div>
        <div class="row" id="DetailTimeTrackEmployee"></div>
    </div>
</div>